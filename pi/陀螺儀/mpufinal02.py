#!/usr/bin/python

import os
import smbus
import math
from time import sleep

# Power management registers
power_mgmt_1 = 0x6b
power_mgmt_2 = 0x6c

def read_byte(adr):
    return bus.read_byte_data(address, adr)

def read_word(adr):
    high = bus.read_byte_data(address, adr)
    low = bus.read_byte_data(address, adr+1)
    val = (high << 8) + low
    return val

def read_word_2c(adr):
    val = read_word(adr)
    if (val >= 0x8000):
        return -((65535 - val) + 1)
    else:
        return val
def dist(a,b):
    return math.sqrt((a*a)+(b*b))

def get_y_rotation(x,y,z):
    radians = math.atan2(x, dist(y,z))
    return -math.degrees(radians)

def get_x_rotation(x,y,z):
    radians = math.atan2(y, dist(x,z))
    return math.degrees(radians)
    
def get_time():
    from time import strftime
    M = int(strftime('%M'))
    S = int(strftime('%S'))
    return M * 60 + S

bus = smbus.SMBus(1) # or bus = smbus.SMBus(1) for Revision 2 boards
address = 0x68       # This is the address value read via the i2cdetect command

# Now wake the 6050 up as it starts in sleep mode
bus.write_byte_data(address, power_mgmt_1, 0)

t = 0

gyro_xout_before = 0
gyro_yout_before = 0
gyro_zout_before = 0

accel_xout_before = 0               
accel_yout_before = 0
accel_zout_before = 0

print "Time\tgyro\taccel"


configread = open('/home/pi/config.txt', 'r')
mpuindex = 1;
ftpgyro = ftpaccel = smsgyro = smsaccel = bluegyro = blueaccel = 0.0
while True:
    configline = configread.readline()
    if mpuindex == 8:
        mpusecond = int(configline)
        break;
    mpujudge = configline.split(' ')
    if mpuindex == 2:
        ftpgyro = float(mpujudge[0])
        ftpaccel = float(mpujudge[1])
    if mpuindex == 4:
        smsgyro = float(mpujudge[0])
        smsaccel = float(mpujudge[1])
    if mpuindex == 6:
        bluegyro = float(mpujudge[0])
        blueaccel = float(mpujudge[1])
    mpuindex += 1
configread.close()

start = get_time()
end = start + mpusecond

while True:

    from time import strftime
    now = strftime('%Y%m%d%H%M%S')
    
    nowstart = get_time()
    if nowstart >= end:
        freturn = open('/home/pi/cooking/arduPi/mpureturn.txt', 'w')
        freturn.write('continue')
        freturn.close()
        break
    
    gyro_xout = read_word_2c(0x43)
    gyro_yout = read_word_2c(0x45)
    gyro_zout = read_word_2c(0x47)

    gyro_xout_scaled = (gyro_xout - (-241.937)) / 16.4
    gyro_yout_scaled = (gyro_yout - (-241.937)) / 16.4
    gyro_zout_scaled = (gyro_zout - (-241.937)) / 16.4

    gyro_xout_deduct = gyro_xout_scaled - gyro_xout_before
    gyro_yout_deduct = gyro_yout_scaled - gyro_yout_before
    gyro_zout_deduct = gyro_zout_scaled - gyro_zout_before

    gyro_xout_before = gyro_xout_scaled
    gyro_yout_before = gyro_yout_scaled
    gyro_zout_before = gyro_zout_scaled

    gyro = math.sqrt(math.pow(gyro_xout_deduct,2.0) + math.pow(gyro_yout_deduct,2.0) + math.pow(gyro_zout_deduct,2.0))

    accel_xout = read_word_2c(0x3b)
    accel_yout = read_word_2c(0x3d)
    accel_zout = read_word_2c(0x3f)

    accel_xout_scaled = (accel_xout - (-180.3768)) / 2048.0
    accel_yout_scaled = (accel_yout - (-180.3768)) / 2048.0
    accel_zout_scaled = (accel_zout - (-180.3768)) / 2048.0

    accel_xout_deduct = accel_xout_scaled - accel_xout_before
    accel_yout_deduct = accel_yout_scaled - accel_yout_before
    accel_zout_deduct = accel_zout_scaled - accel_zout_before

    accel_xout_before = accel_xout_scaled
    accel_yout_before = accel_yout_scaled
    accel_zout_before = accel_zout_scaled

    accel = math.sqrt(math.pow(accel_xout_deduct,2.0) + math.pow(accel_yout_deduct,2.0) + math.pow(accel_zout_deduct,2.0))
    
    if gyro >= ftpgyro or accel >= ftpaccel: #FTP
        f = open('/home/pi/mpudata.txt', 'a+')
        f.write(now + '\t' + str(gyro) + '\t' + str(accel))
        f.write('\r\n')
        f.close()
        print now,"\t",gyro,"\t",accel
        
    if gyro >= smsgyro or accel >= smsaccel: # SMS
        freturn = open('/home/pi/cooking/arduPi/mpureturn.txt', 'w')
        fsms = open('/home/pi/sms.txt', 'w')
        freturn.write('sms')
        fsms.write(now)
        fsms.close()
        freturn.close()
        break
        
    elif gyro >= bluegyro or accel >= blueaccel: # bluetooth
        freturn = open('/home/pi/cooking/arduPi/mpureturn.txt', 'w')
        fblue = open('/home/pi/bluetooth.txt', 'w')
        freturn.write('bluetooth')
        fblue.write(now)
        fblue.close()
        freturn.close()
        break

    
    t += 0.2

    sleep(0.2)

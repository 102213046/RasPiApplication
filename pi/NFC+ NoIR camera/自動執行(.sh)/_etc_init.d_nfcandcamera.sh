#! /bin/sh 
#/etc/init.d/nfcandcamera.sh

### BEGIN INIT INFO
# Provides: nfcandcamera.sh 
# Required-Start: $local_fs 
# Required-Stop: $local_fs 
# Default-Start:2 3 4 5 
# Default-Stop: 0 1 6 
# Short-Description: script to start the camera 
# Description: A script to start the camera automatically when rpi boots up
### END INIT INFO
sudo nfc-mfclassic r b /home/pi/cooking/arduiPi/orignfctag.txt & 
sleep 10; 
cd /home/pi 
sudo ./nfccallerdata & 
sleep 10; 
sh /home/pi/camera.sh &

import os

for x in range(5):
    os.system('tts ""Are you there?""')

info = os.popen('hcitool info 5A:5A:5A:AB:33:28 ')
noconsciousness = 0
index = -1

while True:
    information = info.readline()
    
    if information == '': break
    
    index = information.find('HBS-800')
    
    if index != -1:
        noconsciousness = 1
        break
    
if noconsciousness == 1:
	print "No consciousness. Call the police..\n"
else:
	print "I'm awake no phone calls.\n"

info.close()
file=/home/pi/nfccaller.txt
SAVEDIR=/home/pi/cam/
exec < $file
read line;
while [ true ]; do
filename=$line-$(date +"%Y%m%d_%H%M-%S").jpg
/opt/vc/bin/raspistill -o $SAVEDIR/$filename
sleep 1;
done;

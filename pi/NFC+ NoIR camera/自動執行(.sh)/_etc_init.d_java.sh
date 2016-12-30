#! /bin/sh
#/etc/init.d/java.sh

### BEGIN INIT INFO
# Provides: java.sh
# Required-Start: $local_fs
# Required-Stop: $local_fs
# Default-Start: 2 3 4 5
# Default-Stop: 0 1 6
# Short-Description: starts combine.java
# Description: starts combine.java automatically when the rpi boots
### END INIT INFO
cd /home/pi/java
java Combine &


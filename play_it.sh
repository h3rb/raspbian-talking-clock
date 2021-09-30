#!/bin/bash

FILE=/var/www/html/messaging/now.txt

i=0

while [ $i -ne 25 ]
do

if test -f "$FILE"; then
   value=$(</var/www/html/messaging/now.txt)
value=${value//$'\n'/} # Remove all newlines.
value=${value%$'\n'}   # Remove a trailing newline.
   rm $FILE
   sudo php -f /home/pi/run.php $value
fi

sleep 2
i=$[$i+1]

done

#sudo pkill -9 -f play_it.sh

#!/bin/bash

FILE=/var/www/html/messaging/now.txt

i=0

while [ $i -ne 10 ]
do

if test -f "$FILE"; then
   rm $FILE
   sudo php -f /home/pi/run.php
   # Sudo is required to read fortunes files.
fi

sleep 4
i=$[$i+1]

done

sudo pkill -9 -f play_it.sh

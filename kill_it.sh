#!/bin/bash

FILE=/var/www/html/messaging/kill.txt

i=0

while [ $i -ne 25 ]
do

if test -f "$FILE"; then
   rm $FILE
   sudo pkill -9 -f "php -f run.php"
   sudo pkill -9 -f espeak
fi

sleep 2
i=$[$i+1]

done

#sudo pkill -9 -f kill_it.sh

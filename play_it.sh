#!/bin/bash

FILE=/var/www/html/messaging/now.txt

i=0

while [ $i -ne 10 ]
do

if test -f "$FILE"; then
   rm $FILE
   php -f /home/pi/run.php
fi

sleep 4

done

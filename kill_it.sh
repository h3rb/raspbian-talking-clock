#!/bin/bash

FILE=/var/www/html/messaging/kill.txt

i=0

while [ $i -ne 10 ]
do

if test -f "$FILE"; then
   rm $FILE
   sudo pkill -9 -f run.php
   sudo pkill -9 -f espeak
fi

sleep 4

done

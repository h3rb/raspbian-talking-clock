# raspbian-talking-clock
A talking clock using espeak, wttr.in, curl, SimpleXML and php.  Written for speaker-phat but doesn't require one.

## Installation

Move files to /home/pi

```
sudo apt install php curl php-xml
chown pi:pi /var/www/html
mkdir /var/www/html/messaging
chmod a+rwx /var/www/html/messaging
chmod 0777 /home/pi/kill_it.sh
chmod 0777 /home/pi/play_it.sh
```

Place contents of folder "html" in /var/www/html

```
crontab -e
```

Crontab entries:
```
# Activate on reboot after 30 seconds... (give system time to get wifi going)
@reboot sleep 30 && php -f ~/run.php

# Setup periodic talking hourly, half hour
0 * * * * php -f ~/run.php
#15 * * * * php -f ~/run.php brief
30 * * * * php -f ~/run.php brief
#45 * * * * php -f ~/run.php brief

# Activate repeating scripts to check for web interface messaging
* * * * * sudo /home/pi/kill_it.sh
* * * * * sudo /home/pi/play_it.sh
```

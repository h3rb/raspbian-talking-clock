# raspbian-talking-clock
A talking clock using espeak, wttr.in, curl, SimpleXML and php.  Written for speaker-phat but doesn't require one.

## Installation

1. Setup an audio output (ie speakerphat, or use a speaker with a bigger raspi, etc)

2. Setup your PiZeroW to have Wifi, or another network interface to internet.

3. Move files to /home/pi

4. Execute these lines to install a webserver and setup the scripts provided in this repo:
```
sudo apt install php php-xml
chown pi:pi /var/www/html
mkdir /var/www/html/messaging
chmod a+rwx /var/www/html/messaging
chmod 0777 /home/pi/kill_it.sh
chmod 0777 /home/pi/play_it.sh
```

5. Place contents of folder "html" in /var/www/html

6. Install these crontab entries:

Using:
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

## Documentation

Visit the web interface by going to your raspi in a web browser.  You should see some buttons.  Press them.

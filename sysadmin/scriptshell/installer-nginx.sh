#!/bin/bash

# 
sudo apt-get remove nginx* # Removes all but config files
sudo apt-get purge nginx*  # Removes everything

# remove apache completly
sudo service apache2 stop
sudo apt-get remove --purge apache2 apache2-utils
sudo rm -rf /etc/apache2

# add dependencies
sudo add-apt-repository ppa:nginx/stable
sudo apt-get update
sudo apt-get upgrade --show-upgraded
sudo apt-get install libpcre3-dev build-essential libssl-dev

# install nginx
sudo apt-get install nginx

# start the server
sudo /etc/init.d/nginx start

# Create an Init Script to Manage nginx
#sudo wget https://raw.github.com/JasonGiedymin/nginx-init-ubuntu/master/nginx -O /etc/init.d/nginx
#sudo chmod +x /etc/init.d/nginx
#sudo /usr/sbin/update-rc.d -f nginx defaults 

#
sudo apt-get install php5 php5-fpm php-apc php5-mcrypt php5-mysqlnd php5-sqlite php5-intl php5-cli php5-curl php5-mongo

#
sudo /etc/init.d/php5-fpm restart
sudo /etc/init.d/nginx start

sudo chown -R www-data:www-data /var/www
sudo chmod -R 777 /var/www

# installation phpmyadmin
sudo apt-get install phpmyadmin
sudo chmod 755 /etc/phpmyadmin/config.inc.php


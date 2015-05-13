#!/bin/bash
. `dirname $0`/env.sh

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
sudo apt-get clean

# install nginx
sudo apt-get install nginx

# mysql install
. `dirname $0`/installer-mysql.sh

# php install
. `dirname $0`/installer-php.sh
sudo apt-get install php5-fpm

# Create an Init Script to Manage nginx
#sudo wget https://raw.github.com/JasonGiedymin/nginx-init-ubuntu/master/nginx -O /etc/init.d/nginx
#sudo chmod +x /etc/init.d/nginx
#sudo /usr/sbin/update-rc.d -f nginx defaults 

# permission
sudo chown -R www-data:www-data /var/www

# Au cas où ce script-ci ait été exécuté par root,
# on donne les droits à notre utilisateur
sudo chown -R ${INSTALL_USERNAME}:${INSTALL_USERGROUP} ${INSTALL_USERWWW}

# installation phpmyadmin
sudo apt-get install phpmyadmin
sudo chmod 755 /etc/phpmyadmin/config.inc.php

# restart servers
sudo /etc/init.d/php5-fpm restart
sudo /etc/init.d/nginx start

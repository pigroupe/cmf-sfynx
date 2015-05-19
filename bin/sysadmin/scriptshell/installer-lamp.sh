#!/bin/sh
. `dirname $0`/env.sh

# remove nginx
sudo apt-get remove nginx* # Removes all but config files
sudo apt-get purge nginx*  # Removes everything

#sudo apt-get install lamp-server^

# Installation d'Apache
sudo apt-get update
sudo apt-get -y install apache2-mpm-prefork apache2 apache2-utils 
sudo apt-get clean

# Add tools
sudo apt-get -y install autoconf build-essential manpages-dev 
sudo apt-get -y install apache2-prefork-dev 

# Pour compilation de PHP et/ou d'extensions
sudo apt-get -y install libxml2-dev libcurl4-gnutls-dev libicu-dev libmcrypt-dev libpng12-dev libxslt1-dev libmysqlclient15-dev libbz2-dev libmhash-dev libltdl-dev libgearman-dev libevent-dev libmagickwand-dev

# Activation de modules fréquemment requis
sudo a2enmod expires
sudo a2enmod headers
sudo a2enmod proxy
sudo a2enmod proxy_http
sudo a2enmod rewrite
sudo a2enmod userdir

# mysql install
. `dirname $0`/installer-mysql.sh

# php install
. `dirname $0`/installer-php.sh

# permission
sudo chown -R www-data:www-data /var/www

# Au cas où ce script-ci ait été exécuté par root,
# on donne les droits à notre utilisateur
sudo chown -R ${INSTALL_USERNAME}:${INSTALL_USERGROUP} ${INSTALL_USERWWW}

# installation phpmyadmin
sudo apt-get -y install phpmyadmin
sudo chmod 755 /etc/phpmyadmin/config.inc.php

sudo sed -i "/'password'/d" /etc/phpmyadmin/config.inc.php
echo "\$cfg['Servers'][\$i]['password'] = 'pacman';" | sudo tee --append /etc/phpmyadmin/config.inc.php

# restart apache
sudo /etc/init.d/apache2 restart

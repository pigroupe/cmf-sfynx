#!/bin/sh

# remove nginx
sudo apt-get remove nginx* # Removes all but config files
sudo apt-get purge nginx*  # Removes everything

# install lamp
sudo apt-get update
sudo apt-get install lamp-server^
sudo apt-get install apache2-utils 
sudo apt-get install php-pear php5-dev php-apc php5-apcu php5-gd php5-memcache php5-memcached php5-imagick php5-curl php5-sqlite php5-xdebug php5-intl php5-imap php5-mcrypt php5-ming php5-ps php5-pspell php5-recode php5-snmp php5-tidy php5-xmlrpc php5-xsl php5-cli php5-idn php5-openssl php-soap
sudo apt-get install apache2 php5 mysql-server libapache2-mod-php5 php5-mysql
sudo pecl install timezonedb

# installation phpmyadmin
sudo apt-get install phpmyadmin
sudo chmod 755 /etc/phpmyadmin/config.inc.php
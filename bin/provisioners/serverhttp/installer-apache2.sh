#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

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

echo "Install OpenSSL"
apt-get -y install openssl > /dev/null

# Pour compilation de PHP et/ou d'extensions
sudo apt-get -y install libxml2-dev libcurl4-gnutls-dev libicu-dev libmcrypt-dev libpng12-dev libxslt1-dev libmysqlclient15-dev libbz2-dev libmhash-dev libltdl-dev libgearman-dev libevent-dev libmagickwand-dev

# Activation de modules fréquemment requis
sudo a2enmod expires
sudo a2enmod headers
sudo a2enmod proxy
sudo a2enmod proxy_http
sudo a2enmod rewrite
sudo a2enmod userdir

# register apache2 in the boot 
sudo update-rc.d apache2 defaults 

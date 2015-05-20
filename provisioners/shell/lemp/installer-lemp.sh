#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

echo "*** NGINX ***"

# NGINX
$DIR/provisioners/shell/lemp/installer-nginx.sh $DIR

# MYSQL
$DIR/provisioners/shell/lemp/installer-mysql.sh $DIR

# PHP
$DIR/provisioners/shell/lemp/installer-php.sh $DIR

# PHPMYADMIN
$DIR/provisioners/shell/lemp/installer-phpmyadmin.sh $DIR

# permission
mkdir -p ${INSTALL_USERWWW}
chown -R www-data:www-data ${INSTALL_USERWWW}

# Au cas où ce script-ci ait été exécuté par root,
# on donne les droits à notre utilisateur
chown -R ${INSTALL_USERNAME}:${INSTALL_USERGROUP} ${INSTALL_USERWWW}

# Add www-data to www-data group
usermod -a -G www-data www-data

echo "Restart mysql for the config to take effect"
service mysql restart

echo "Restart php5 fpm for the config to take effect"
service php5-fpm restart

echo "Restart Nginx for the config to take effect"
service nginx restart

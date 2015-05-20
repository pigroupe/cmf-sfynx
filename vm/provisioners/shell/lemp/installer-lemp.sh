#!/bin/bash
DIR=$1
source $DIR/vm/provisioners/shell/env.sh

# NGINX
$DIR/vm/provisioners/shell/lemp/installer-nginx.sh $DIR

# MYSQL
$DIR/vm/provisioners/shell/lemp/installer-mysql.sh $DIR

# PHP
$DIR/vm/provisioners/shell/lemp/installer-php.sh $DIR

# PHPMYADMIN
$DIR/vm/provisioners/shell/lemp/installer-phpmyadmin.sh $DIR

# permission
mkdir -p ${INSTALL_USERWWW}
chown -R www-data:www-data ${INSTALL_USERWWW}

# Au cas où ce script-ci ait été exécuté par root,
# on donne les droits à notre utilisateur
chown -R ${INSTALL_USERNAME}:${INSTALL_USERGROUP} ${INSTALL_USERWWW}

# Add www-data to vagrant group
usermod -a -G vagrant www-data

echo "Restart mysql for the config to take effect"
service mysql restart

echo "Restart php5 fpm for the config to take effect"
service php5-fpm restart

echo "Restart Nginx for the config to take effect"
service nginx restart

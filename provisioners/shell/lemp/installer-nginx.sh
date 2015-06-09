#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

# NGINX
echo "*** NGINX ***"
sudo service apache2 stop
apt-get remove apache2
apt-get purge apache2

#
echo "Add repository for nginx"
add-apt-repository ppa:nginx/stable
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null

# NGINX
echo "Installing Nginx"
apt-get -y install nginx

echo "**** On déclare le socket Unix de PHP-FPM pour que Nginx puisse passer les requêtes PHP via fast_cgi ****"
if [ ! -f /etc/nginx/conf.d/php5-fpm.conf ];
then
sh -c "cat > /etc/nginx/conf.d/php5-fpm.conf" <<EOT
upstream php5-fpm-sock {  
    server unix:/var/run/php5-fpm.sock;  
}
EOT
fi

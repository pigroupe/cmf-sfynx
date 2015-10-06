#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

# NGINX
echo "*** NGINX ***"
sudo service apache2 stop
sudo apt-get remove apache2
sudo apt-get purge apache2

#
echo "Add repository for nginx"
sudo add-apt-repository ppa:nginx/stable
sudo apt-get -y update > /dev/null
sudo apt-get -y dist-upgrade > /dev/null

# NGINX
echo "Installing Nginx"
sudo apt-get -y install nginx

echo "**** On déclare le socket Unix de PHP-FPM pour que Nginx puisse passer les requêtes PHP via fast_cgi ****"
if [ ! -f /etc/nginx/conf.d/php5-fpm.conf ];
then
sh -c "cat > /etc/nginx/conf.d/php5-fpm.conf" <<EOT
upstream php5-fpm-sock {  
    server unix:/var/run/php5-fpm.sock;  
}
EOT
fi

# register nginx in the boot 
sudo update-rc.d nginx defaults 

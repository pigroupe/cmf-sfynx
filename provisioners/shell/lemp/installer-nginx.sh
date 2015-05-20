#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

# NGINX
echo "*** NGINX ***"

#
echo "Add repository for nginx"
add-apt-repository ppa:nginx/stable
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null

# NGINX
echo "Installing Nginx"
apt-get -y install nginx
#!/bin/bash


# install 7 wheezy backports
echo "deb http://http.debian.net/debian wheezy-backports main" > /etc/apt/sources.list
sudo apt-get update
sudo apt-get install -t wheezy-backports linux-image-amd64
apt-get install linux-headers-$(uname -r|sed 's,[^-]*-[^-]*-,,')
apt-get -t wheezy-backports install virtualbox
/etc/init.d/vboxadd setup

# install apt-transport-https package
echo "deb http://ftp.de.debian.org/debian wheezy main"  >> /etc/apt/sources.list
sudo apt-get upgrade
sudo apt-get install apt-transport-https

# install docker
curl -sSL https://get.docker.com/ | sh

# test
sudo docker run hello-world
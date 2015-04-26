#!/bin/bash

# install 7 wheezy backports
echo "deb http://http.debian.net/debian wheezy-backports main" > /etc/apt/sources.list
apt-get update
apt-get install -t wheezy-backports linux-image-amd64
apt-get install linux-headers-$(uname -r|sed 's,[^-]*-[^-]*-,,')
apt-get -t wheezy-backports install virtualbox
/etc/init.d/vboxadd setup

# install apt-transport-https package
echo "deb http://ftp.de.debian.org/debian wheezy main"  >> /etc/apt/sources.list
apt-get upgrade
apt-get install apt-transport-https

# install docker
curl -sSL https://get.docker.com/ | sh

# Install docker compose
curl -L https://github.com/docker/compose/releases/download/1.1.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# install adapter nfs
sudo apt-get install nfs-common nfs-kernel-server

# test
docker run hello-world
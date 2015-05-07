#!/bin/bash

# ubuntu install
apt-get update
apt-get install wget
curl -sSL https://get.docker.com/ubuntu/ | sh

# Install docker compose
curl -L https://github.com/docker/compose/releases/download/1.1.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# install adapter nfs
sudo apt-get install nfs-common nfs-kernel-server

# test
docker run hello-world
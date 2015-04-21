#!/bin/bash

# ubuntu install
sudo apt-get update
sudo apt-get install wget
wget -qO- https://get.docker.com/ | sh

# test
sudo docker run hello-world
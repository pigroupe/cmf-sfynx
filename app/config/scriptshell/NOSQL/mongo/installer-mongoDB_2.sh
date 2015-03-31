#!/bin/bash

# MongoDB
echo "#### installing mongodb"
sudo apt-key adv -keyserver hkp://keyserver.ubuntu.com:80 -recv 7F0CEB10
echo 'deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen' | sudo tee /etc/apt/sources.list.d/mongodb.list
sudo apt-get update && sudo apt-get install mongodb-org -y
sudo service mongod start
cd /opt/statsd
 
## Mongo Statsd backend - mongo-statsd-backend
## the version on npm has issues; use a patched version on github instead:
sudo git clone https://github.com/rposbo/mongo-statsd-backend.git
cd mongo-statsd-backend
sudo npm install

#!/bin/bash
 
# Prerequisites
echo "#### Starting"
echo "#### apt-get updating and installing prereqs"
sudo apt-get update
sudo apt-get install screen libexpat1-dev libicu-dev git build-essential curl -y
 
# Node
echo "#### Installing node"
. ~/.bashrc
export "PATH=$HOME/local/bin:$PATH"
mkdir $HOME/local
mkdir $HOME/node-latest-install
 
pushd $HOME/node-latest-install
 curl http://nodejs.org/dist/node-latest.tar.gz | tar xz -strip-components=1
 ./configure -prefix=~/local
 make install
popd
 
## the path isn't always correct, so set up a symlink
sudo ln -s /usr/bin/nodejs /usr/bin/node
 
## nodemon
echo "#### npming nodemon"
sudo apt-get install npm -y
sudo npm install -g nodemon
 
# StatsD
echo "#### installing statsd"
pushd /opt
sudo git clone https://github.com/etsy/statsd.git
 cat >> /tmp/localConfig.js << EOF
{
 port: 8125
, dumpMessages: true
, debug: true
, mongoHost: 'localhost'
, mongoPort: 27017
, mongoMax: 2160
, mongoPrefix: true
, mongoName: 'statsD'
, backends: ['/opt/statsd/mongo-statsd-backend/lib/index.js']
}
EOF
 
sudo cp /tmp/localConfig.js /opt/statsd/localConfig.js
popd
 
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
 
# ElasticSearch
echo "#### installing elasticsearch"
sudo apt-get update && sudo apt-get install default-jre default-jdk -y
wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.1.1.deb && sudo dpkg -i elasticsearch-1.1.1.deb
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start
 
## Elasticsearch plugins
sudo /usr/share/elasticsearch/bin/plugin -install mobz/elasticsearch-head
sudo /usr/share/elasticsearch/bin/plugin -install lukas-vlcek/bigdesk
 
# Start StatsD
screen nodemon /opt/statsd/stats.js /opt/statsd/localConfig.js

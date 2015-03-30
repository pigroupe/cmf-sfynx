#!/bin/bash

# ElasticSearch
echo "#### installing elasticsearch"
sudo apt-get update && sudo apt-get install default-jre default-jdk -y
wget https://download.elasticsearch.org/elasticsearch/elasticsearch/elasticsearch-1.1.1.deb && sudo dpkg -i elasticsearch-1.1.1.deb
sudo update-rc.d elasticsearch defaults 95 10
sudo /etc/init.d/elasticsearch start
 
## Elasticsearch plugins
sudo /usr/share/elasticsearch/bin/plugin -install mobz/elasticsearch-head
sudo /usr/share/elasticsearch/bin/plugin -install lukas-vlcek/bigdesk

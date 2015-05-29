#!/bin/bash
DIR=$1

source $DIR/provisioners/shell/env.sh

#
sudo apt-get -y install openjdk-7-jdk
sudo mkdir -p /usr/java
sudo ln -s /usr/lib/jvm/java-7-openjdk-amd64 /usr/java/default

sudo aptitude -y install solr-tomcat
sudo cp $DIR/provisioners/shell/solr/schema.xml /usr/share/solr/conf/
#php app/console nbi:recipe:solr:update --clean
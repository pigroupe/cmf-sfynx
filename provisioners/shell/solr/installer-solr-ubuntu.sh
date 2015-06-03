#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

#
apt-get -y install openjdk-7-jdk
mkdir -p /usr/java
ln -s /usr/lib/jvm/java-7-openjdk-amd64 /usr/java/default

aptitude -y install solr-tomcat
cp $DIR/provisioners/shell/jackrabbit/solr/schema.xml /usr/share/solr/conf/
#php app/console nbi:recipe:solr:update --clean
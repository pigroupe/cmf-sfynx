#!/bin/sh

apt-get -y install openjdk-7-jdk
mkdir /usr/java
ln -s /usr/lib/jvm/java-7-openjdk-amd64 /usr/java/default


aptitude -y install solr-tomcat
cp /home/vagrant/solr/schema.xml /usr/share/solr/conf/
#php app/console nbi:recipe:solr:update --clean
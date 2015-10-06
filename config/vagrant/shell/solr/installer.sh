#!/bin/bash
DIR=$1
source $DIR/provisioners/shell/env.sh

cd /tmp
curl http://archive.apache.org/dist/lucene/solr/4.6.1/solr-4.6.1.tgz |tar xz
sudo cp /tmp/solr-4.6.1/example/lib/ext/* /usr/share/tomcat7/lib/
sudo cp /tmp/solr-4.6.1/dist/solr-4.6.1.war /var/lib/tomcat7/webapps/solr.war
sudo cp -R /tmp/solr-4.6.1/example/solr /var/lib/tomcat7

sudo chown -R tomcat7:tomcat7 /var/lib/tomcat7/solr
sudo cp $DIR/app/config/solr/schema.xml /var/lib/tomcat7/solr/collection1/conf/

# we modify /var/lib/tomcat7/conf/server.xml file to change port connector
sudo sed -i s/8080/8983/g /var/lib/tomcat7/conf/server.xml

#cat <<EOT >>$DIR/app/config/config.yml
#nelmio_solarium:
#    endpoints:
#        default:
#           host: %solr_host%
#            port: %solr_port%
#            path: %solr_path%
#           core: collection1   # nom du core dans lequel se trouve le schema.xml
#            timeout: 5
#EOT

sudo service tomcat7 restart


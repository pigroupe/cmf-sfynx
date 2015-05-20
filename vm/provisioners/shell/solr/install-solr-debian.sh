#!/bin/sh

#
apt-get install tomcat7 tomcat7-admin

#
cd /tmp
curl http://archive.apache.org/dist/lucene/solr/4.6.1/solr-4.6.1.tgz | tar xz
cp /tmp/solr-4.6.1/example/lib/ext/* /usr/share/tomcat7/lib/
cp /tmp/solr-4.6.1/dist/solr-4.6.1.war /var/lib/tomcat7/webapps/solr.war
cp -R /tmp/solr-4.6.1/example/solr /var/lib/tomcat7

#
chown -R tomcat7:tomcat7 /var/lib/tomcat7/solr
cp app/config/solr/schema.xml /var/lib/tomcat7/solr/collection1/conf/

#
#nano /var/lib/tomcat7/conf/server.xml
#=> <Connector port="8181" protocol="HTTP/1.1"
#               connectionTimeout="20000"
#               URIEncoding="UTF-8"
#               redirectPort="8443" /> 

#
#nano app/config/config.yml
#=> nelmio_solarium:
#    endpoints:
#        default:
#            host: %solr_host%
#            port: %solr_port%
#            path: %solr_path%
#            core: collection1   # nom du core dans lequel se trouve le schema.xml
#            timeout: 5

#
service tomcat7 restart

#
#http://localhost:8181/solr
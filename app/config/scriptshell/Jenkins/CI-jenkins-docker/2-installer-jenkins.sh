#!/bin/sh

echo "deb http://pkg.jenkins-ci.org/debian binary/" > /etc/apt/sources.list.d/jenkins.list
wget -q -O - http://pkg.jenkins-ci.org/debian/jenkins-ci.org.key | apt-key add -
#
sudo apt-get update
sudo apt-get install jenkins
sudo service jenkins start
if curl http://localhost:8080 2>/dev/null | grep -iq jenkins; then echo "OK"; else echo "FAIL"; fi OK

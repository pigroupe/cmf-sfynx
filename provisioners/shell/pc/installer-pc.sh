#Download Elementary OS from here: 
#http://sourceforge.net/projects/elementaryos/files/stable/

#!/bin/bash
DIR=$1
DISTRIB=$2
source $DIR/provisioners/shell/env.sh

echo "*****Enable all Startup Applications"
cd /etc/xdg/autostart
sed --in-place 's/NoDisplay=true/NoDisplay=false/g' *.desktop

echo "*****Install a Firewall Application"
apt-get -y install gufw > /dev/null

echo "*****Install the Dynamic Kernel Module Support Framework"
apt-get -y install dkms > /dev/null

echo "*****Clean-up System"
apt-get -y purge midori-granite
apt-get -y purge noise
apt-get -y purge software-center
apt-get -y purge scratch-text-editor
apt-get -y purge bluez
apt-get -y purge modemmanager
apt-get -y autoremove
apt-get -y autoclean

echo "*****Remove some Switchboard Plug's"
rm -rf /usr/lib/plugs/GnomeCC/gnomecc-bluetooth.plug
rm -rf /usr/lib/plugs/GnomeCC/gnomecc-wacom.plug

echo "*****Install File Compression Libs"
apt-get -y install unace zip unzip xz-utils p7zip p7zip-full sharutils uudeview mpack arj cabextract file-roller > /dev/null

echo "*****Install python"
apt-get -y install python-software-properties > /dev/null

echo "*****Install Java 7"
add-apt-repository ppa:webupd8team/java -y
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null
apt-get -y install openjdk-7-jdk openjdk-7-jre > /dev/null

echo "*****Install the latest git Version"
add-apt-repository ppa:git-core/ppa -y
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null
apt-get -y install git git-core  > /dev/null

echo "Install OpenSSH Server"
apt-get -y install openssh-server > /dev/null

echo "Install CURL dev package"
apt-get -y install libcurl4-openssl-dev

echo "Install Nmap"
apt-get -y install nmap > /dev/null

echo "Install Acl"
apt-get -y install acl > /dev/null

echo "Install tree"
apt-get -y install tree > /dev/null

echo "Install debconf utils"
apt-get -y install debconf-utils > /dev/null

echo "Install sendmail"
apt-get -y install sendmail > /dev/null

echo "Install NFS client"
apt-get -y install nfs-common portmap > /dev/null

echo "Install Make"
apt-get -y install make > /dev/null

echo "Install Curl and Nodejs"
add-apt-repository -y ppa:chris-lea/node.js
apt-get -y update > /dev/null
apt-get -y install curl nodejs > /dev/null

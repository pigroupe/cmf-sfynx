#!/bin/bash
DIR=$1

# Get the code
mkdir -p /opt/jackrabbit-startup
cp -R $DIR/provisioners/shell/jackrabbit/Jackrabbit-startup-script/* /opt/jackrabbit-startup

# Configure the script
# edit jackrabbit.sh to configure some settings
# Create JMX config files
cp /opt/jackrabbit-startup/jmx.role.template /opt/jackrabbit-startup/jmx.role
cp /opt/jackrabbit-startup/jmx.user.template /opt/jackrabbit-startup/jmx.user

chmod 0600 /opt/jackrabbit-startup/jmx.role
chmod 0600 /opt/jackrabbit-startup/jmx.user

# Create an alias to the script
ln -s /opt/jackrabbit-startup/jackrabbit.sh /etc/init.d/jackrabbit
chmod 755 /etc/init.d/jackrabbit

# create directory of the bdd of jackrabbit
mkdir -p /opt/jackrabbit-startup/bdd
mkdir -p /opt/jackrabbit-startup/log

# on debian, register with
update-rc.d jackrabbit defaults
# if not using a system that provides update-rc.d, you hopefully know how
# to proceed...

#create port 8080
sudo iptables -A INPUT -p tcp -m tcp --dport 8080 -j ACCEPT

sudo /etc/init.d/jackrabbit stop
sudo /etc/init.d/jackrabbit start 1> /dev/null & # 2> /opt/jackrabbit-startup/log/error.log &

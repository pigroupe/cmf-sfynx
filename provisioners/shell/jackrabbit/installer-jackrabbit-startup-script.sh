#!/bin/bash

# Get the code
mkdir -p /opt/jackrabbit-startup    # or wherever you want to put the code
mv /home/vagrant/Jackrabbit-startup-script/* /opt/jackrabbit-startup

# 
cd jackrabbit-startup

# Configure the script
## <edit jackrabbit.sh to configure some settings> ##
# Create JMX config files
cp jmx.role.template jmx.role
cp jmx.user.template jmx.user

chmod 0600 jmx.role
chmod 0600 jmx.user

# Create an alias to the script
ln -s /opt/jackrabbit-startup/jackrabbit.sh /etc/init.d/jackrabbit
chmod 755 /etc/init.d/jackrabbit

# on debian, register with
update-rc.d jackrabbit defaults
# if not using a system that provides update-rc.d, you hopefully know how
# to proceed...

/etc/init.d/jackrabbit stop
/etc/init.d/jackrabbit start
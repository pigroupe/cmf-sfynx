#!/bin/bash
DIR=$1
DIRJACKRABBIT=/usr/local/jackrabbit/develop
JACKRABBIT_JAR=jackrabbit-standalone-2.7.5.jar

if [ ! -f $DIR/provisioners/shell/jackrabbit/Jackrabbit-startup-script/$JACKRABBIT_JAR ]; then
    cd $DIR/provisioners/shell/jackrabbit/Jackrabbit-startup-script
    wget https://archive.apache.org/dist/jackrabbit/2.7.5/jackrabbit-standalone-2.7.5.jar  > /dev/null
fi


# Get the code
mkdir -p $DIRJACKRABBIT
cp -R $DIR/provisioners/shell/jackrabbit/Jackrabbit-startup-script/* $DIRJACKRABBIT

# Configure the script
# edit jackrabbit.sh to configure some settings
# Create JMX config files
cp $DIRJACKRABBIT/jmx.role.template $DIRJACKRABBIT/jmx.role
cp $DIRJACKRABBIT/jmx.user.template $DIRJACKRABBIT/jmx.user

chmod 0600 $DIRJACKRABBIT/jmx.role
chmod 0600 $DIRJACKRABBIT/jmx.user

# Create an alias to the script
sudo rm  /etc/init.d/jackrabbit
sudo ln -s $DIRJACKRABBIT/jackrabbit.sh /etc/init.d/jackrabbit
sudo chmod 755 /etc/init.d/jackrabbit


# register jackrabbit in the boot 
sudo update-rc.d jackrabbit defaults 

# if not using a system that provides update-rc.d, you hopefully know how
# to proceed...

#create port 8081
#sudo iptables -A INPUT -p tcp -m tcp --dport 8081 -j ACCEPT

$DIR/provisioners/shell/phpcr-browser/installer-browser.sh $DIR

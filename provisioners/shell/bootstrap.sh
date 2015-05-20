#!/bin/bash
DIR=$1
DISTRIB=$2
source $DIR/provisioners/shell/env.sh

echo "***** We set permmissions for all scriptshell"
chmod -R 755 /tmp/
chmod -R +x /tmp/vagrant-shell
chmod -R +x $DIR

echo "***** First we copy own sources.list to box *****"
if [ -f $DIR/provisioners/shell/etc/apt/$DISTRIB/sources.list ];
then
    cp $DIR/provisioners/shell/etc/apt/$DISTRIB/sources.list /etc/apt/sources.list
    apt-get -y update > /dev/null
    apt-get -y dist-upgrade > /dev/null
fi

echo "***** Second we update the system *****"
apt-get -y install build-essential > /dev/null
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null

$DIR/provisioners/shell/pc/installer-pc.sh $DIR $DISTRIB
$DIR/provisioners/shell/lemp/installer-lemp.sh $DIR
#$DIR/provisioners/shell/jackrabbit/installer-jackrabbit-startup-script.sh $DIR
#if [ -f $DIR/provisioners/shell/solr/installer-solr-$DISTRIB.sh ];
#then
    #$DIR/provisioners/shell/solr/installer-solr-$DISTRIB.sh $DIR
#fi
#$DIR/provisioners/shell/nbi/installer-nbi.sh $DIR

echo "***** End we clean-up the system *****"
apt-get -y autoremove > /dev/null
apt-get -y clean > /dev/null
apt-get -y autoclean > /dev/null

echo "Finished provisioning."

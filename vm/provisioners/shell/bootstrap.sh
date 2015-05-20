#!/bin/bash
DIR=$1
DISTRIB=$2
source $DIR/vm/provisioners/shell/env.sh

echo "***** First we copy own sources.list to box *****"
if [ -f $DIR/etc/apt/$DISTRIB/sources.list ];
then
    cp $DIR/etc/apt/$DISTRIB/sources.list /etc/apt/sources.list
    apt-get -y update > /dev/null
    apt-get -y dist-upgrade > /dev/null
fi

echo "***** Second we update the system "*****"
apt-get -y install build-essential > /dev/null
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null

$DIR/vm/provisioners/shell/pc/installer-pc.sh $DIR $DISTRIB
$DIR/vm/provisioners/shell/lemp/installer-lemp.sh $DIR
#$DIR/vm/provisioners/shell/jackrabbit/installer-jackrabbit-startup-script.sh $DIR
if [ -f $DIR/vm/provisioners/shell/solr/installer-solr-$DISTRIB.sh ];
then
    #$DIR/vm/provisioners/shell/solr/installer-solr-$DISTRIB.sh $DIR
fi
#$DIR/vm/provisioners/shell/nbi/installer-nbi.sh $DIR

echo "***** End we clean-up the system *****"
apt-get -y autoremove > /dev/null
apt-get -y clean > /dev/null
apt-get -y autoclean > /dev/null

echo "Finished provisioning."
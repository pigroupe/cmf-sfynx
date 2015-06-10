#!/bin/bash
DIR=$1
DISTRIB=$2
PLATEFORM_INSTALL_NAME=$3
PLATEFORM_INSTALL_TYPE=$4
PLATEFORM_INSTALL_VERSION=$5
PLATEFORM_PROJET_NAME=$6
PLATEFORM_PROJET_GIT=$7
PLATEFORME_USERNAME_GIT=$8
INSTALL_USERWWW=$9

#
is_pc=$9
is_lemp=${10}
is_plateform=${11}
is_phpqatools=${12}
is_jackrabbit=${13}
is_solr=${14}
is_mongodb=${15}
is_elasticsearch=${16}
is_jenkins=${17}
is_gitlab=${18}
is_swap=${19}

source $DIR/provisioners/shell/env.sh

echo "***** We set permmissions for all scriptshell"
mkdir -p /tmp
sudo chmod -R 777 /tmp
sudo chmod -R +x $DIR
sudo chmod -R 777 $DIR
sudo chmod 755 /etc/apt/sources.list


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

echo "***** Add vagrant to www-data group *****"
sudo usermod -aG www-data vagrant
#chown -R ${INSTALL_USERNAME}:${INSTALL_USERGROUP} ${INSTALL_USERWWW}/${PROJET_NAME}

echo "***** Provisionning *****"
$DIR/provisioners/shell/SWAP/installer-swap.sh "$DIR" # important to allow the composer to have enough memory
$DIR/provisioners/shell/pc/installer-pc.sh "$DIR" "$DISTRIB"
$DIR/provisioners/shell/lemp/installer-lemp.sh "$DIR" "$PLATEFORM_PROJET_NAME"
#$DIR/provisioners/shell/QA/installer-phpqatools.sh "$DIR"
$DIR/provisioners/shell/jackrabbit/installer-jackrabbit.sh "$DIR" "$INSTALL_USERWWW"
if [ -f $DIR/provisioners/shell/solr/installer-solr-$DISTRIB.sh ];
then
    #$DIR/provisioners/shell/solr/installer-solr-$DISTRIB.sh $DIR
    $DIR/provisioners/shell/solr/installer.sh "$DIR"
    #echo "pas solr"
fi
$DIR/provisioners/shell/plateform/installer-$PLATEFORM_INSTALL_NAME.sh "$DIR" "$PLATEFORM_INSTALL_NAME" "$PLATEFORM_INSTALL_TYPE" "$PLATEFORM_INSTALL_VERSION" "$PLATEFORM_PROJET_NAME" "$PLATEFORM_PROJET_GIT" "$INSTALL_USERWWW"

echo "***** End we clean-up the system *****"
sudo apt-get -y autoremove > /dev/null
sudo apt-get -y clean > /dev/null
sudo apt-get -y autoclean > /dev/null

echo "Finished provisioning."


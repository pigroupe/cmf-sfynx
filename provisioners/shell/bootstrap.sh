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

PLATEFORM_PROJET_NAME_LOWER=$(echo $PLATEFORM_PROJET_NAME | awk '{print tolower($0)}') # we lower the string
PLATEFORM_PROJET_NAME_UPPER=$(echo $PLATEFORM_PROJET_NAME | awk '{print toupper($0)}') # we lower the string
DATABASE_NAME="symfony_${PLATEFORM_PROJET_NAME_LOWER}"

echo "Removing Windows newlines on Linux (sed vs. awk)"
#find $DIR/provisioners/* -type f -exec dos2unix {} \;

echo "***** We set permmissions for all scriptshell"
mkdir -p /tmp
sudo chmod -R 777 /tmp
sudo chmod -R +x $DIR/provisioners
sudo chmod -R 777 $DIR/provisioners
sudo chmod 755 /etc/apt/sources.list

echo "***** First we copy own sources.list to box *****"
if [ -f $DIR/provisioners/shell/etc/apt/$DISTRIB/sources.list ];
then
    cp $DIR/provisioners/shell/etc/apt/$DISTRIB/sources.list /etc/apt/sources.list
    apt-get -y update > /dev/null
    apt-get -y dist-upgrade > /dev/null
fi
sudo dpkg --configure -a

echo "***** Second we update the system *****"
apt-get -y install build-essential > /dev/null
apt-get -y update > /dev/null
apt-get -y dist-upgrade > /dev/null

echo "***** Add vagrant to www-data group *****"
sudo usermod -aG www-data vagrant
#chown -R ${INSTALL_USERNAME}:${INSTALL_USERGROUP} ${INSTALL_USERWWW}/${PROJET_NAME}

echo "***** Provisionning PC *****"
$DIR/provisioners/shell/SWAP/installer-swap.sh "$DIR" # important to allow the composer to have enough memory
$DIR/provisioners/shell/pc/installer-pc.sh "$DIR" "$DISTRIB"

echo "***** Provisionning LEMP *****"
$DIR/provisioners/shell/lemp/installer-lemp.sh "$DIR" "$PLATEFORM_PROJET_NAME"

echo "**** we install/update the composer file ****"
#wget https://getcomposer.org/composer.phar -O ./composer.phar
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

#$DIR/provisioners/shell/QA/installer-phpqatools.sh "$DIR"

echo "***** Provisionning JACKRABBIT *****"
$DIR/provisioners/shell/jackrabbit/installer-jackrabbit.sh "$DIR" "$INSTALL_USERWWW"

echo "***** Provisionning SOLR *****"
if [ -f $DIR/provisioners/shell/solr/installer-solr-$DISTRIB.sh ];
then
    #$DIR/provisioners/shell/solr/installer-solr-$DISTRIB.sh $DIR
    $DIR/provisioners/shell/solr/installer.sh "$DIR"
    #echo "pas solr"
fi

echo "***** Provisionning JACKRABBIT *****"
if [ -f $DIR/provisioners/shell/xhprof/installer-xhprof-$DISTRIB.sh ];
then
    $DIR/provisioners/shell/xhprof/installer-xhprof-$DISTRIB.sh
fi

echo "**** we install plateform ****"
$DIR/provisioners/shell/plateform/installer-$PLATEFORM_INSTALL_NAME.sh "$DIR" "$PLATEFORM_INSTALL_NAME" "$PLATEFORM_INSTALL_TYPE" "$PLATEFORM_INSTALL_VERSION" "$PLATEFORM_PROJET_NAME" "$PLATEFORM_PROJET_GIT" "$INSTALL_USERWWW"

echo "we install the mysql dump files if the DUMP/mysqldump.sql file exist"
if [ -f $DIR/DUMP/mysqldump.sql ]; then
    sudo $DIR/provisioners/shell/plateform/importBDD.sh "$DIR/DUMP/mysqldump.sql" "$DATABASE_NAME"
fi

echo "we install all uploads files if the DUMP/uploads.tar.gz file exist"
if [ -f $DIR/DUMP/uploads.sql ]; then
    sudo $DIR/provisioners/shell/plateform/importUpload.sh "$DIR/DUMP/uploads.tar.gz" "$DIR" "$INSTALL_USERWWW" "$PLATEFORM_PROJET_NAME"
fi

echo "we install the jackribbit database if the DUMP/jr.tar.gz file exist"
if [ -f $DIR/DUMP/jr.sql ]; then
    sudo $DIR/provisioners/shell/plateform/importJR.sh "$DIR/DUMP/jr.tar.gz"
fi

echo "***** End we clean-up the system *****"
sudo apt-get -y autoremove > /dev/null
sudo apt-get -y clean > /dev/null
sudo apt-get -y autoclean > /dev/null

echo "Finished provisioning."


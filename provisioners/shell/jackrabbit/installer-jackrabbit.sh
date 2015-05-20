#!/bin/bash

JACKRABBIT_URL="http://www.eu.apache.org/dist/jackrabbit/2.6.5/jackrabbit-standalone-2.6.5.jar"
JACKRABBIT_MD5="d1aff4436f46f97908164549e4e6b854"
echo Welcome to Jackrabbit Installer

cd /app
wget $JACKRABBIT_URL -O jackrabbit-standalone.jar --port 8888 #Download Jackrabbit
DOWNLOAD_MD5=`md5sum /app/jackrabbit-standalone.jar  | cut -d" " -f1` # Get download hash

if [ $JACKRABBIT_MD5 != $DOWNLOAD_MD5 ]; then
    echo The downloaded jackrabbit version has a wrong MD5 hash
    echo Expected MD5 = $JACKRABBIT_MD5
    echo MD5 of download = $DOWNLOAD_MD5
    exit 1
fi

mkdir /app/jackrabbit

echo Jackrabbit successfully installed
exit 0
#!/bin/bash

DUMP=$1
DIR=$2
INSTALL_USERWWW=$3
PLATEFORM_PROJET_NAME=$4

cd $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME

if [ -f $DUMP ]; 
then
        echo "start import upload directory"
        sudo rm -rf web/uploads/*
        sudo tar -zxf $DUMP -C web/
        #sudo chmod -R 777 web/uploads
        echo "upload directory imported"
else
        echo "error -> No upload directory to import"
        exit 3
fi
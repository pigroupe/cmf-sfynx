#!/bin/bash

DUMP=$1
DIR=$2

if [ -f $DUMP ]; 
then
        echo "start import upload directory"
        sudo rm -rf $DIR/web/uploads/*
        sudo tar -zxf $DUMP -C $DIR/web/
        #sudo chmod -R 777 $DIR/web/uploads
        echo "upload directory imported"
else
        echo "error -> No upload directory to import"
        exit 3
fi
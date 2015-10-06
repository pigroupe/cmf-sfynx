#!/bin/bash

DUMP=$1
DIRJACKRABBIT=/usr/local/jackrabbit/develop

if [ -f $DUMP ]; 
then
        echo "start import Jackrabbit database"
        sudo rm -rf $DIRJACKRABBIT/jackrabbit/*
        sudo tar -zxf $DUMP -C $DIRJACKRABBIT
        sudo chmod -R 777 $DIRJACKRABBIT/jackrabbit
        echo "Jackrabbit database imported"
else
        echo "error -> No Jackrabbit database to import"
        exit 3
fi
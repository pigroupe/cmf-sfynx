#!/bin/bash

DUMP=$1
DATABASE_NAME=$2

if [ -f $DUMP ]; 
then
        echo "start import database"
        mysql -uroot -ppacman  $DATABASE_NAME < $DUMP
        echo "database imported"
else
        echo "error -> No database to import"
        exit 3
fi
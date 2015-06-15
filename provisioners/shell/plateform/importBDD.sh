#!/bin/bash

DUMP=$1

if [ -f $DUMP ]; 
then
        echo "start import database"
        mysql -uroot -ppacman  BelProd_dev < $DUMP
        echo "database imported"
else
        echo "error -> No database to import"
        exit 3
fi
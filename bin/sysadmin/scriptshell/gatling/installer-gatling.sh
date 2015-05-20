#!/bin/bash

PATH_DIR="/var/www/gatling"

if [ $# -eq 0 ]; then # s'il n'y a pas de paramètres
    read PATH_DIR # on saisis la valeur
else
    PATH_DIR=$1
fi

#
PATH_CONF_FILE="${PATH_DIR}/conf/gatling.conf"

mkdir -p $PATH_DIR

# we dowbload the source file
wget https://repo1.maven.org/maven2/io/gatling/highcharts/gatling-charts-highcharts-bundle/2.1.5/gatling-charts-highcharts-bundle-2.1.5-bundle.zip
unzip gatling-charts-highcharts-bundle-2.1.5-bundle.zip

# we move gatling arctifacts in the path directory
mv gatling-charts-highcharts-bundle-2.1.5/* $PATH_DIR
rm gatling-charts-highcharts-bundle-2.1.5-bundle.zip
rm -rf gatling-charts-highcharts-bundle-2.1.5

# we give permission
chmod +x $PATH_DIR/bin/gatling.sh

# We change the configuration gatling file
if [ -f $PATH_CONF_FILE ]; then
    sudo sed -i -e 's/^#maxRetry = 0$/maxRetry = 4/' $PATH_CONF_FILE
fi
#!/bin/sh

# Tests fonctionnels
mkdir -p build/logs/behat
vendor/bin/behat --lang=fr --format=pretty --out=build/logs/behat/std --format=junit --out=build/logs/behat/junit

# Tests de tire de charge Gatling
mkdir -p build/logs/gatling
mkdir -p config/gatling/user-files/simulations
PATH_GATLING_DIR="vendor/bin/gatling"
if [ ! -d $PATH_GATLING_DIR ]; then    
    wget https://repo1.maven.org/maven2/io/gatling/highcharts/gatling-charts-highcharts-bundle/2.1.5/gatling-charts-highcharts-bundle-2.1.5-bundle.zip
    unzip gatling-charts-highcharts-bundle-2.1.5-bundle.zip
    mkdir -p $PATH_GATLING_DIR
    mv gatling-charts-highcharts-bundle-2.1.5/* $PATH_GATLING_DIR
    rm gatling-charts-highcharts-bundle-2.1.5-bundle.zip
    rm -rf gatling-charts-highcharts-bundle-2.1.5
    chmod +x $PATH_GATLING_DIR/bin/gatling.sh
fi
NOW=$(date +%F\-%T)
$PATH_GATLING_DIR/bin/gatling.sh -sf config/gatling/user-files/simulations  -s SymfonyDefault -rf build/logs/gatling -sd "Charge test firing - ${NOW}"

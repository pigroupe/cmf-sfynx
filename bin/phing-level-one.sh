#!/bin/sh

DdbUser="DdbUser"
DdbPw="DdbPw"
DdbName="DdbName"

mkdir -p build/logs/php/coverage
echo "" > build/logs/phpunit.xml

echo "**** we run the phing script to initialize the project ****"
vendor/bin/phing -f build.xml build:test-deploy -logger phing.listener.DefaultLogger -DdbUser=$DdbUser -DdbPw=$DdbPw -DdbName=$DdbName
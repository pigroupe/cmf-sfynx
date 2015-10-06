#!/bin/sh

DdbUser="DdbUser"
DdbPw="DdbPw"
DdbName="DdbName"

echo "Tests unitaires sans couvertures de code"
vendor/bin/phing -f build.xml build:test-deploy -logger phing.listener.DefaultLogger -DdbUser=$DdbUser -DdbPw=$DdbPw -DdbName=$DdbName
#!/bin/sh

DdbUser="DdbUser"
DdbPw="DdbPw"
DdbName="DdbName"

echo "Tests unitaires avec couvertures de code et Analyse statique de code (PhpCodeSniffer, Phpcpd, Phpmd, PhpDepend, PhpLoc)"
vendor/bin/phing -f build.xml build:jenkins-normal -logger phing.listener.DefaultLogger -DdbUser=$DdbUser -DdbPw=$DdbPw -DdbName=$DdbName
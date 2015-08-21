#!/bin/sh

DdbUser="DdbUser"
DdbPw="DdbPw"
DdbName="DdbName"

mkdir -p build/logs/php
mkdir -p build/logs/pdepend
echo "" > build/logs/phpunit.xml

# Tests unitaires avec couvertures de code et Analyse statique de code (PhpCodeSniffer, Phpcpd, Phpmd, PhpDepend, PhpLoc)
# vendor/bin/phpunit --log-junit build/logs/php/phpunit.xml --coverage-clover build/logs/php/coverage/clover.xml --coverage-html build/logs/php/coverage/ -c app --debug
vendor/bin/phing -f build.xml build:jenkins-normal -logger phing.listener.DefaultLogger -DdbUser=$DdbUser -DdbPw=$DdbPw -DdbName=$DdbName
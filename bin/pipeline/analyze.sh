#!/bin/sh

mkdir -p build/logs/php
mkdir -p build/logs/pdepend
echo "" > build/logs/phpunit.xml

# Code mesure (nombre de ligne, de classses, d'interfaces, de méthodes, complexité cyclomatique, etc)
vendor/bin/phploc   src  --count-tests --log-xml="build/logs/php/loc.xml" > /dev/null

# php Mess Detector (anomalies directes, complexité cyclomatique=nombre de chemins indépendants d’un programme, fonctions à risque, code mort)
vendor/bin/phpmd    src  xml codesize,unusedcode,naming,design --exclude **/map/*,**/om/* --reportfile build/logs/php/pmd.xml > /dev/null

# PHP JDEPEND - métriques orienté objet (X:abstraction=temps de production rapide(0) à infini (1), Y:instabilité=maintenable infini ou maintenance nulle(0) à maintenance infinie(1))
vendor/bin/pdepend  --jdepend-xml=build/logs/pdepend/jdepend.xml --jdepend-chart=build/logs/pdepend/dependencies.svg --overview-pyramid=build/logs/pdepend/overview-pyramid.svg  src

# QA
mkdir -p build/logs/phpmetrics
if [ ! -f phpmetrics.phar ]; then
    wget https://github.com/Halleck45/PhpMetrics/raw/master/build/phpmetrics.phar -O phpmetrics.phar --no-check-certificate
fi
#php phpmetrics.phar src --report-html=build/metrics.html --report-xml=build/metrics.xml --chart-bubbles=build/metrics.svg  > /dev/null
php phpmetrics.phar src --excluded-dirs="\.git|vendor|web|documentation|build|app|bin" -q --chart-bubbles=build/logs/phpmetrics/metrics.svg  --report-html=build/logs/phpmetrics/metrics.html --report-xml=build/logs/phpmetrics/metrics.xml  ./ > /dev/null
#./bin/metrics.sh src > build/logs/phpmetrics/metrics.txt

# Security checker :: contrôle des potentielles vulnérabilités des librairies installées par le composer
mkdir -p build/logs/security
if [ ! -f security-checker.phar ]; then
    wget http://get.sensiolabs.org/security-checker.phar
fi
php security-checker.phar security:check composer.lock > build/logs/security/security-checker.txt

# Security php.ini :: relever les problèmes potentiels liés à la sécurité du fichier php.ini
PATH_PHP_INI=$(echo $(php -i | grep "Loaded Configuration File") | sed -e 's/Loaded Configuration File => //g')
vendor/bin/iniscan     scan --format=xml --path=$PATH_PHP_INI  > build/logs/security/iniscan.xml

# Security PHP :: relever les problèmes potentiels liés à la sécurité de la version PHP
vendor/bin/versionscan scan --sort=risk           > build/logs/security/versionscan.txt

# Security parse :: relever les problèmes potentiels liés à la sécurité du code
#vendor/bin/parse       scan --format=xml src                   > build/logs/security/parse.xml

# Tests unitaires avec couvertures de code
vendor/bin/phpunit --log-junit build/logs/php/phpunit.xml --coverage-clover build/logs/php/coverage/clover.xml --coverage-html build/logs/php/coverage/ -c app --debug

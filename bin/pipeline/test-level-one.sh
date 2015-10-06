#!/bin/sh

mkdir -p build/logs/php/coverage
echo "" > build/logs/phpunit.xml

# PHPCPD - PHP COPY/PASTE DETECTION
vendor/bin/phpcpd  --min-tokens=50 --min-lines=5 --names-exclude=*/Resources/*,**/map/*,**/om/* --log-pmd build/logs/php/pmd-cpd.xml src

# php Dead Code Detection (code static mort)
vendor/bin/phpdcd  src > build/logs/php/dcd.txt

# PHP Code sniffer (without warning)
vendor/bin/phpcs --standard=PSR2 src --ignore=*/Resources/* --encoding=utf-8  --tab-width=4 --report= --report-file=build/logs/php/cs-checkstyle.xml

# PHP Code sniffer Fix for psr2
vendor/bin/php-cs-fixer fix src --level=psr2  > build/logs/php/cs-fixer.txt

# Tests unitaires sans couvertures de code
vendor/bin/phpunit --log-junit build/logs/php/phpunit.xml -c app --debug

#!/bin/sh

DIR=$1
PLATEFORM_VERSION=$2

echo "**** QA install with composer ****"

echo "** we add QA bundle and dependencies in composer.json and app/AppKernel.php **"
#composer require --dev  --update-with-dependencies  phpdocumentor/phpdocumentor:2.*
#composer require --dev  --update-with-dependencies  mayflower/php-codebrowser:~1.1
#composer require --dev  --update-with-dependencies  theseer/phpdox:*
#composer require --dev  --update-with-dependencies  halleck45/phpmetrics:@dev
composer require --dev  --update-with-dependencies  squizlabs/php_codesniffer:*
composer require --dev  --update-with-dependencies  fabpot/php-cs-fixer:*
composer require --dev  --update-with-dependencies  phpunit/phpunit:*
composer require --dev  --update-with-dependencies  phpunit/php-invoker:dev-master
#composer require --dev  --update-with-dependencies  sebastian/phpcpd:*
#composer require --dev  --update-with-dependencies  sebastian/phpdcd:*
#composer require --dev  --update-with-dependencies  phpmd/phpmd:@stable
#composer require --dev  --update-with-dependencies  pdepend/pdepend:@stable
#composer require --dev  --update-with-dependencies  phploc/phploc:*
#composer require --dev  --update-with-dependencies  sebastian/hhvm-wrapper:*
composer require --dev  --update-with-dependencies  phake/phake:*
#composer require --dev  --update-with-dependencies  phing/phing:dev-master
composer require --dev  --update-with-dependencies  behat/behat:3.0.*@dev
#composer require --dev  --update-with-dependencies  instaclick/php-webdriver:~1.1
composer require --dev  --update-with-dependencies  behat/mink:1.6.*@dev
composer require --dev  --update-with-dependencies  behat/mink-bundle:~1.4
composer require --dev  --update-with-dependencies  behat/symfony2-extension:~2.0@dev
composer require --dev  --update-with-dependencies  behat/mink-extension:~2.0@dev
composer require --dev  --update-with-dependencies  behat/mink-selenium2-driver:*@dev
composer require --dev  --update-with-dependencies  behat/mink-browserkit-driver:~1.1@dev
composer require --dev  --update-with-dependencies  behat/mink-goutte-driver:*@stable
composer require --dev  --update-with-dependencies  behat/mink-zombie-driver:*@stable
composer require --dev  --update-with-dependencies  facebook/xhprof:dev-master@dev        
#composer require --dev  --update-with-dependencies  phpcasperjs/phpcasperjs:dev-master
#composer require --dev  --update-with-dependencies  psecio/iniscan:dev-master
#composer require --dev  --update-with-dependencies  psecio/versionscan:dev-master
#composer require --dev  --update-with-dependencies  psecio/parse:dev-master
#composer require --dev  --update-with-dependencies  mayflower/php-codebrowser:~1.1

echo "** we lauch composer install **"
composer update --with-dependencies

#!/bin/sh

DIR=$1
PLATEFORM_VERSION=$2

echo "**** JMS Security install with composer ****"

echo "** we add JMS Security bundle and dependencies in composer.json and app/AppKernel.php **"
composer require --no-update  jms/security-extra-bundle:1.5.*
composer require --no-update  jms/di-extra-bundle:1.4.*
composer require --no-update  jms/serializer-bundle:0.13.*@dev
composer require --no-update  symfony/translation:2.6.*@dev
composer require --no-update  jms/translation-bundle:1.1.*@dev
composer require --dev --no-update  jms/debugging-bundle:dev-master --

echo "** we lauch composer install **"
composer update --with-dependencies

echo "** we define bundles in app/AppKernel.php file **"
if ! grep -q "JMSDiExtraBundle" app/AppKernel.php; then
    sed -i '/SensioFrameworkExtraBundle(),/a \            # JMS' app/AppKernel.php
    sed -i '/# JMS/a \            new JMS\\AopBundle\\JMSAopBundle(),' app/AppKernel.php
    sed -i '/JMSAopBundle()/a \            new JMS\\DiExtraBundle\\JMSDiExtraBundle($this),' app/AppKernel.php
    sed -i '/JMSDiExtraBundle($this)/a \            new JMS\\SecurityExtraBundle\\JMSSecurityExtraBundle(),' app/AppKernel.php
    sed -i '/JMSSecurityExtraBundle()/a \            new JMS\\TranslationBundle\\JMSTranslationBundle(),' app/AppKernel.php
    sed -i '/JMSTranslationBundle/a \            new JMS\\SerializerBundle\\JMSSerializerBundle(),' app/AppKernel.php
fi

echo "** we add JMSSecurityExtraBundle/JMSDiExtraBundle configuration **"
if ! grep -q "jms_security_extra" app/config/security.yml; then
    echo "$(cat $DIR/provisioners/shell/plateform/jms/addSecurity.yml)" >> app/config/security.yml
fi

echo "** we add JMSDebugging configuration **"
if ! grep -q "jms_debugging" app/config/config.yml; then
    echo "$(cat $DIR/provisioners/shell/plateform/jms/addConfig.yml)" >> app/config/config.yml
fi

echo "** we add config in AppKernel **"
if ! grep -q 'JMS\\DebuggingBundle\\DependencyInjection\\TraceableContainer' app/AppKernel.php; then
    sed -i -e "/parent::getContainerBaseClass/r $DIR/provisioners/shell/plateform/jms/addJMSDebuggAppKernel.txt" -e //N app/AppKernel.php
fi
if ! grep -q 'JMS\\DebuggingBundle\\JMSDebuggingBundle($this)' app/AppKernel.php; then
    sed -i '/SensioGeneratorBundle/a \            $bundles[] = new JMS\\DebuggingBundle\\JMSDebuggingBundle($this);' app/AppKernel.php
fi

#echo "** we add JMS Security configuration **"
# since sf 2.4
#php app/console config:dump-reference JMSSecurityExtraBundle --format=yaml 1>> app/config/config.yml
#php app/console config:dump-reference JMSDiExtraBundle --format=yaml 1>> app/config/config.yml
#php app/console config:dump-reference JMSDebugging --format=yaml 1>> app/config/config.yml

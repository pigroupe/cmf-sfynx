#!/bin/sh

DIR=$1
PLATEFORM_VERSION=$2
DOMAINE=$3

echo "** we add FOSRestBundle and dependencies in composer.json and app/AppKernel.php **"
composer require  --no-update  friendsofsymfony/rest-bundle

echo "** we lauch composer install **"
composer update --with-dependencies

echo "** we define bundles in app/AppKernel.php file **"
if ! grep -q "FOSRestBundle" app/AppKernel.php; then
    sed -i '/SensioFrameworkExtraBundle(),/a \            # REST' app/AppKernel.php
    sed -i '/# REST/a \            new FOS\\RestBundle\\FOSRestBundle(),' app/AppKernel.php
fi

echo "** we add FOSRestBundle configuration **"
if ! grep -q "fos_user" app/config/config.yml; then
    echo "$(cat $DIR/provisioners/shell/plateform/fosrest/addConfig.yml)" >> app/config/config.yml
fi
#!/bin/sh

DIR=$1
PLATEFORM_VERSION=$2
DOMAINE=$3
FOSUSER_PREFIX=$4
MYAPP_BUNDLE_NAME=$5
MYAPP_PREFIX=$6

echo "**** FOSUser install with composer ****"

echo "** we add FOSUser bundle and dependencies in composer.json and app/AppKernel.php **"
composer require  --no-update  friendsofsymfony/user-bundle:2.0.*@dev

echo "** we lauch composer install **"
composer update --with-dependencies

echo "** we generate ${DOMAINE}AuthBundle with User and Group entities **"
if [ ! -d src/${DOMAINE}/AuthBundle ]; then
    php app/console generate:bundle --namespace="${DOMAINE}/AuthBundle" --bundle-name="${DOMAINE}AuthBundle" --format=annotation --structure --dir=src --no-interaction
    php app/console generate:doctrine:entity --no-interaction --entity="${DOMAINE}AuthBundle:User" --fields="groups:array name:string(50) nickname:string(50) birthday:datetime address:text zip_code:string(6) city:string(50) country:string(50) created_at:datetime updated_at_at:datetime archive_at:datetime" --format=annotation --with-repository --no-interaction
    php app/console generate:doctrine:entity --no-interaction --entity="${DOMAINE}AuthBundle:Group" --fields="created_at:datetime updated_at_at:datetime archive_at:datetime enabled:boolean" --format=annotation --with-repository --no-interaction
fi

echo "** we define bundles in app/AppKernel.php file **"
if ! grep -q "FOSUserBundle" app/AppKernel.php; then
    sed -i '/SensioFrameworkExtraBundle(),/a \            # tools' app/AppKernel.php
    sed -i '/# tools/a \            new FOS\\UserBundle\\FOSUserBundle(),' app/AppKernel.php
fi

echo "** we add extends bundle **"
if ! grep -q "FOSUserBundle" src/${DOMAINE}/AuthBundle/${DOMAINE}AuthBundle.php; then
    sed  -i "/{/r $DIR/provisioners/shell/plateform/fosuser/addBundle.txt" src/${DOMAINE}/AuthBundle/${DOMAINE}AuthBundle.php
fi

echo "** we add FOSUser configuration **"
if ! grep -q "fos_user" app/config/config.yml; then
    echo "$(cat $DIR/provisioners/shell/plateform/fosuser/addConfig.yml)" >> app/config/config.yml
    sed -i "s/DOMAINE/${DOMAINE}/g" app/config/config.yml
fi

echo "** we add FOSUser routing **"
if ! grep -q "FOSUserBundle" app/config/routing.yml; then
    echo "$(cat $DIR/provisioners/shell/plateform/fosuser/addRouting.yml)" >> app/config/routing.yml
fi

echo "** we modify entities with extends classes and protected attributes **"
if ! grep -q "AbstractUser" src/${DOMAINE}/AuthBundle/Entity/User.php; then
    sed -i '/namespace/a \use FOS\\UserBundle\\Model\\User as AbstractUser;' src/${DOMAINE}/AuthBundle/Entity/User.php
    sed -i 's/class User/class User extends AbstractUser/g' src/${DOMAINE}/AuthBundle/Entity/User.php
    sed -i 's/private/protected/g' src/${DOMAINE}/AuthBundle/Entity/User.php
    sed -i 's/Table()/Table(name="fos_user")/g' src/${DOMAINE}/AuthBundle/Entity/User.php

    sed -i '/namespace/a \use FOS\\UserBundle\\Model\\Group as BaseGroup;' src/${DOMAINE}/AuthBundle/Entity/Group.php
    sed -i 's/class Group$/class Group extends BaseGroup/g' src/${DOMAINE}/AuthBundle/Entity/Group.php
    sed -i 's/private/protected/g' src/${DOMAINE}/AuthBundle/Entity/Group.php
    sed -i 's/Table()/Table(name="fos_group")/g' src/${DOMAINE}/AuthBundle/Entity/Group.php
fi

echo "** we create datatable in database **"
rm -rf app/cache/*
php app/console doctrine:schema:create --env=dev --process-isolation  -v
php app/console doctrine:schema:update --env=dev --force
php app/console doctrine:schema:create --env=test --process-isolation  -v
php app/console doctrine:schema:update --env=test --force

#echo "** If you want to add Roles you can use the FOSUserBundle command line tools **"
#php app/console fos:user:promote admin ROLE_ADMIN

echo "** we modify fos_user datatable **"
php app/console doctrine:query:sql 'UPDATE `fos_user` SET roles ="a:0:{}" WHERE roles = "";'
php app/console doctrine:query:sql 'UPDATE `fos_user` SET groups ="a:0:{}" WHERE groups = "";'

echo "** we generate User and Group CRUD **"
if [ ! -d src/${DOMAINE}/AuthBundle/Form ]; then
    php app/console generate:doctrine:crud --entity="${DOMAINE}AuthBundle:User" --route-prefix="$FOSUSER_PREFIX/user" --with-write --format=annotation --no-interaction
    php app/console generate:doctrine:crud --entity="${DOMAINE}AuthBundle:Group" --route-prefix="$FOSUSER_PREFIX/group" --with-write --format=annotation --no-interaction
fi

echo "** we modify twig and php artifact files **"
if ! grep -q "plainPassword" src/${DOMAINE}/AuthBundle/Form/UserType.php; then
    sed -i 's/entity.groups/dump(entity.groups)/g' src/${DOMAINE}/AuthBundle/Resources/views/User/index.html.twig
    sed -i 's/entity.groups/dump(entity.groups)/g' src/${DOMAINE}/AuthBundle/Resources/views/User/show.html.twig
    sed -i "/->add('name')/a \            ->add('username')" src/${DOMAINE}/AuthBundle/Form/UserType.php
    sed -i "/->add('username')/a \            ->add('email')" src/${DOMAINE}/AuthBundle/Form/UserType.php

    #sed -i '/groups/d' src/${DOMAINE}/AuthBundle/Form/UserType.php
    #sed -i "/->add('email')/r $DIR/provisioners/shell/plateform/fosuser/addFieldUserForm.txt" src/${DOMAINE}/AuthBundle/Form/UserType.php
    sed -e '/username/ {' -e "r $DIR/provisioners/shell/plateform/fosuser/addFieldUserForm.txt" -e 'd' -e '}' -i src/${DOMAINE}/AuthBundle/Form/UserType.php

    sed -i '/namespace/a \use Doctrine\\ORM\\EntityRepository;' src/${DOMAINE}/AuthBundle/Form/UserType.php
    sed -i '/namespace/a \use Symfony\\Component\\Validator\\Constraints;' src/${DOMAINE}/AuthBundle/Form/UserType.php
fi

echo "** we add encoder in security.yml **"
if ! grep -q "sha512" app/config/security.yml; then
    sed -i 's/Symfony\\Component\\Security\\Core\\User\\User: plaintext/'$DOMAINE'\\AuthBundle\\Entity\\User: sha512/g' app/config/security.yml
fi
if ! grep -q "path: ^/login_check" app/config/security.yml; then
    sed -i "/[^_]access_control:/r $DIR/provisioners/shell/plateform/fosuser/addAccessControlSecurity.yml" app/config/security.yml
    sed -i "s/myapp/${MYAPP_PREFIX}/g" app/config/security.yml
fi
if ! grep -q "main:" app/config/security.yml; then
    sed -i "/firewalls/r $DIR/provisioners/shell/plateform/fosuser/addMainFirewall.yml" app/config/security.yml
    sed -i "s/myapp/${MYAPP_PREFIX}/g" app/config/security.yml
fi
if ! grep -q "fos_userbundle:" app/config/security.yml; then
    sed -i "/providers/r $DIR/provisioners/shell/plateform/fosuser/addFosProvider.yml" app/config/security.yml
    sed -i "s/myapp/${MYAPP_PREFIX}/g" app/config/security.yml
fi

echo "** we add twig resource files **"
cp -R $DIR/provisioners/shell/plateform/fosuser/Resources/views/* src/${DOMAINE}/AuthBundle/Resources/views
sed -i "s/MyAppBundle/${DOMAINE}${MYAPP_BUNDLE_NAME}Bundle/g" src/${DOMAINE}/AuthBundle/Resources/views/layout.html.twig

#echo "** we add JMS Security configuration **"
# since sf 2.4
#php app/console config:dump-reference FOSUserBundle --format=yaml 1>> app/config/config.yml

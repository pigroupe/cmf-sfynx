#!/bin/sh

DIR=$1
PLATEFORM_VERSION=$2

echo "**** FOSUser install with composer ****"

echo "** we add FOSUser bundle and dependencies in composer.json and app/AppKernel.php **"
composer require  --no-update  friendsofsymfony/user-bundle:2.0.*@dev

echo "** we lauch composer install **"
composer update --with-dependencies

echo "** we define bundles in app/AppKernel.php file **"
if ! grep -q "FOSUserBundle" app/AppKernel.php; then
    sed -i '/SensioFrameworkExtraBundle(),/a \            # tools' app/AppKernel.php
    sed -i '/# tools/a \            new FOS\\UserBundle\\FOSUserBundle(),' app/AppKernel.php
fi

echo "** we add FOSUser configuration **"
    # since sf 2.4
    #php app/console config:dump-reference FOSUserBundle --format=yaml 1>> app/config/config.yml
if ! grep -q "fos_user" app/config/config.yml; then
    # since sf 2.4
    #php app/console config:dump-reference StofDoctrineExtensionsBundle --format=yaml 1>> app/config/config.yml
cat <<EOT >> app/config/config.yml

#
# FOSUserBundle configuration
#
fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: Acme\AuthBundle\Entity\User
    use_listener:           true
    use_username_form_type: true
    model_manager_name:     null  # change it to the name of your entity/document manager if you don't want to use the default one.
    from_email:
        address:       contact@sfynx.fr
        sender_name:   Admin    
    profile:
        form:
            type:               fos_user_profile
            name:               fos_user_profile_form
            validation_groups:  [Profile]
    change_password:
        form:
            type:               fos_user_change_password
            name:               fos_user_change_password_form
            validation_groups:  [ChangePassword]
    registration:
        confirmation:
            from_email: # Use this node only if you don't want the global email address for the confirmation email
                address:        contact@sfynx.fr
                sender_name:    commercial
            enabled:    true # change to true for required email confirmation
            #template:   FOSUserBundle:Registration:email.txt.twig
        #email:
            template:   FOSUserBundle:Registration:registration.email.twig
        form:
            type:               bootstrap_user_registration
            name:               fos_user_registration_form
            validation_groups:  [Registration]
    resetting:
        token_ttl: 86400
        email:
            from_email: # Use this node only if you don't want the global email address for the resetting email
                address:        contact@sfynx.fr
                sender_name:    admin
            template:   FOSUserBundle:Resetting:email.txt.twig
        form:
            type:               fos_user_resetting
            name:               fos_user_resetting_form
            validation_groups:  [ResetPassword]            
    service:
        mailer:                 fos_user.mailer.default
        email_canonicalizer:    fos_user.util.canonicalizer.default
        username_canonicalizer: fos_user.util.canonicalizer.default
        user_manager:           fos_user.user_manager.default
    group:
        group_class: Acme\AuthBundle\Entity\Group
        group_manager:  fos_user.group_manager.default
        form:
            type:               fos_user_group
            name:               fos_user_group_form
            validation_groups:  [Registration]

EOT
fi

echo "** we generate AcmeAuthBundle with User and Group entities **"
if [ ! -d src/Acme/AuthBundle ]; then
    php app/console generate:bundle --namespace=Acme/AuthBundle --bundle-name=AcmeAuthBundle --format=annotation --structure --dir=src --no-interaction
    php app/console generate:doctrine:entity --no-interaction --entity=AcmeAuthBundle:User --fields="groups:array name:string(50) nickname:string(50) birthday:datetime address:text zip_code:string(6) city:string(50) country:string(50) created_at:datetime updated_at_at:datetime archive_at:datetime" --format=annotation --with-repository --no-interaction
    php app/console generate:doctrine:entity --no-interaction --entity=AcmeAuthBundle:Group --fields="created_at:datetime updated_at_at:datetime archive_at:datetime enabled:boolean" --format=annotation --with-repository --no-interaction
fi

echo "** we modify entities with extends classes and protected attributes **"
if ! grep -q "AbstractUser" src/Acme/AuthBundle/Entity/User.php; then
    sed -i '/namespace/a \use FOS\\UserBundle\\Model\\User as AbstractUser;' src/Acme/AuthBundle/Entity/User.php
    sed -i 's/class User/class User extends AbstractUser/g' src/Acme/AuthBundle/Entity/User.php
    sed -i 's/private/protected/g' src/Acme/AuthBundle/Entity/User.php
    sed -i 's/Table()/Table(name="fos_user")/g' src/Acme/AuthBundle/Entity/User.php

    sed -i '/namespace/a \use FOS\\UserBundle\\Model\\Group as BaseGroup;' src/Acme/AuthBundle/Entity/Group.php
    sed -i 's/class Group$/class Group extends BaseGroup/g' src/Acme/AuthBundle/Entity/Group.php
    sed -i 's/private/protected/g' src/Acme/AuthBundle/Entity/Group.php
    sed -i 's/Table()/Table(name="fos_group")/g' src/Acme/AuthBundle/Entity/Group.php
fi

echo "** we create datatable in database **"
rm -rf app/cache/*
php app/console doctrine:schema:create --env=dev --process-isolation  -vvv
php app/console doctrine:schema:update --force

#echo "** If you want to add Roles you can use the FOSUserBundle command line tools **"
#php app/console fos:user:promote admin ROLE_ADMIN

echo "** we modify fos_user datatable **"
php app/console doctrine:query:sql 'UPDATE `fos_user` SET roles ="a:0:{}" WHERE roles = "";'
php app/console doctrine:query:sql 'UPDATE `fos_user` SET groups ="a:0:{}" WHERE groups = "";'

echo "** we generate User and Group CRUD **"
if [ ! -d src/Acme/AuthBundle/Form ]; then
    php app/console generate:doctrine:crud --entity=AcmeAuthBundle:User --route-prefix=md --with-write --format=annotation --no-interaction
    php app/console generate:doctrine:crud --entity=AcmeAuthBundle:Group --route-prefix=md --with-write --format=annotation --no-interaction
fi

echo "** we modify twig and php artifact files **"
sed -i 's/entity.groups/dump(entity.groups)/g' src/Acme/AuthBundle/Resources/views/User/index.html.twig
sed -i 's/entity.groups/dump(entity.groups)/g' src/Acme/AuthBundle/Resources/views/User/show.html.twig
sed -i "/->add('name')/a \            ->add('username')" src/Acme/AuthBundle/Form/UserType.php
sed -i "/->add('username')/a \            ->add('email')" src/Acme/AuthBundle/Form/UserType.php
sed -i '/groups/d' src/Acme/AuthBundle/Form/UserType.php
sed -i "/->add('email')/r $DIR/fosuser/addFieldUserForm.txt" src/Acme/AuthBundle/Form/UserType.php
sed -i '/namespace/a \use Doctrine\\ORM\\EntityRepository;' src/Acme/AuthBundle/Form/UserType.php
sed -i '/namespace/a \use Symfony\\Component\\Validator\\Constraints;' src/Acme/AuthBundle/Form/UserType.php

echo "** we add encoder in security.yml **"
sed -i 's/Symfony\\Component\\Security\\Core\\User\\User: plaintext/Acme\\AuthBundle\\Entity\\User: sha512/g' app/config/security.yml
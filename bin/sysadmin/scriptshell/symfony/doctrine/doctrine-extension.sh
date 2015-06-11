#!/bin/sh

DIR=$1
PLATEFORM_VERSION=$2

echo "**** Doctrine install with composer ****"

echo "** we add doctrine bundle and dependencies in composer.json and app/AppKernel.php **"
composer require  --no-update  doctrine/doctrine-fixtures-bundle:dev-master
composer require  --no-update  doctrine/data-fixtures:1.0.*
composer require  --no-update  doctrine/doctrine-cache-bundle:1.0.*
composer require  --no-update  gedmo/doctrine-extensions:2.3.12
composer require  --no-update  stof/doctrine-extensions-bundle:1.1.*@dev

echo "** we lauch composer install **"
composer update --with-dependencies

echo "** we define bundles in app/AppKernel.php file **"
if ! grep -q "StofDoctrineExtensionsBundle" app/AppKernel.php; then
    sed -i '/DoctrineBundle(),/a \            new Stof\\DoctrineExtensionsBundle\\StofDoctrineExtensionsBundle(),' app/AppKernel.php
fi
if ! grep -q "DoctrineFixturesBundle" app/AppKernel.php; then
    sed -i '/DoctrineBundle(),/a \            new Doctrine\\Bundle\\FixturesBundle\\DoctrineFixturesBundle(),' app/AppKernel.php
fi

echo "** we add doctrine extension configuration **"
if ! grep -q "stof_doctrine_extensions" app/config/config.yml; then
cat <<EOT >> app/config/config.yml

#
# StofDoctrineExtensionsBundle configuration
#
stof_doctrine_extensions:
    orm:
        default:
            translatable:         true
            timestampable:        true
            blameable:            false
            sluggable:            true
            tree:                 true
            loggable:             true
            sortable:             true
            softdeleteable:       false
            uploadable:           true
            reference_integrity:  false
    class:
        translatable:         Gedmo\Translatable\TranslatableListener
        timestampable:        Gedmo\Timestampable\TimestampableListener
        blameable:            Gedmo\Blameable\BlameableListener
        sluggable:            Gedmo\Sluggable\SluggableListener
        tree:                 Gedmo\Tree\TreeListener
        loggable:             Gedmo\Loggable\LoggableListener
        sortable:             Gedmo\Sortable\SortableListener
        softdeleteable:       Gedmo\SoftDeleteable\SoftDeleteableListener
        uploadable:           Gedmo\Uploadable\UploadableListener
        reference_integrity:  Gedmo\ReferenceIntegrity\ReferenceIntegrityListener
    uploadable:
        # Default file path: This is one of the three ways you can configure the path for the Uploadable extension
        default_file_path:    %kernel.root_dir%/../web/uploads
        # Mime type guesser class: Optional. By default, we provide an adapter for the one present in the HttpFoundation component of Symfony
        mime_type_guesser_class:  Stof\DoctrineExtensionsBundle\Uploadable\MimeTypeGuesserAdapter
        # Default file info class implementing FileInfoInterface: Optional. By default we provide a class which is prepared to receive an UploadedFile instance.
        default_file_info_class:  Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo
        validate_writable_directory:  true
    default_locale: "%locale%"
    translation_fallback:  true
    persist_default_translation:  true
    skip_translation_on_load:  true

EOT
fi

#echo "** we add JMS Security configuration **"
# since sf 2.4
# php app/console config:dump-reference StofDoctrineExtensionsBundle --format=yaml 1>> app/config/config.yml
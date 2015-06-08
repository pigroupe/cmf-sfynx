Tree - this extension automates the tree handling process and adds some tree specific functions on repository. (closure, nestedset or materialized path)
Translatable - gives you a very handy solution for translating records into diferent languages. Easy to setup, easier to use.
Sluggable - urlizes your specified fields into single unique slug
Timestampable - updates date fields on create, update and even property change.
Blameable - updates string or assocation fields on create, update and even property change with a user name resp. reference.
Loggable - helps tracking changes and history of objects, also supports version managment.
Sortable - makes any document or entity sortable
Translator - explicit way to handle translations
Softdeleteable - allows to implicitly remove records
Uploadable - provides file upload handling in entity fields
Reference Integrity - provides reference integrity for MongoDB, supports 'nullify' and 'restrict'




# app/config/config.yml
doctrine:
    orm:
        entity_managers:
            default:
                mappings:
                    gedmo_translatable:
                        type: annotation
                        prefix: Gedmo\Translatable\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
                        alias: GedmoTranslatable # this one is optional and will default to the name set for the mapping
                        is_bundle: false
                    gedmo_translator:
                        type: annotation
                        prefix: Gedmo\Translator\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translator/Entity"
                        alias: GedmoTranslator # this one is optional and will default to the name set for the mapping
                        is_bundle: false
                    gedmo_loggable:
                        type: annotation
                        prefix: Gedmo\Loggable\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
                        alias: GedmoLoggable # this one is optional and will default to the name set for the mapping
                        is_bundle: false
                    gedmo_tree:
                        type: annotation
                        prefix: Gedmo\Tree\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
                        alias: GedmoTree # this one is optional and will default to the name set for the mapping
                        is_bundle: false


# app/config/config.yml
doctrine:
    orm:
        entity_managers:
            default:
                filters:
                    softdeleteable:
                        class: Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter
                        enabled: true


# Default configuration for "StofDoctrineExtensionsBundle"
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



Using Uploadable extension
___________________________

$document = new Document();
$form = $this->createFormBuilder($document)
    ->add('name')
    ->add('myFile')
    ->getForm()
;

if ($this->getRequest()->getMethod() === 'POST') {
    $form->bind($this->getRequest());

    if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();

        $em->persist($document);

        $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

        // Here, "getMyFile" returns the "UploadedFile" instance that the form bound in your $myFile property
        $uploadableManager->markEntityToUpload($document, $document->getMyFile());

        $em->flush();

        $this->redirect($this->generateUrl(...));
    }
}

return array('form' => $form->createView());




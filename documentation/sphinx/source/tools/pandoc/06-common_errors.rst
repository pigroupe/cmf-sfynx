Erreurs possibles à l'installation
==================================

1.

::

    Use connection named default in dev environment.
    No database name found.

Définir le nom de la base dans app/config/parameters.yml

2.

::

    [Propel] Exception caught
    Unable to open PDO connection [wrapped: SQLSTATE[42000] [1049] Unknown database 'test']

Créer la base de données à la main, plus besoin d'appeler la commande
suivante :

::

    php app/console doctrine:database:create --env test

3.

::

    [InvalidArgumentException]
    There are no commands defined in the "server" namespace.

Vérifier que la version de php est bien supérieure à 5.4

4.

::

    PHP Fatal error:  Call to undefined function Genemu\Bundle\FormBundle\Gd\imagecreatetruecolor() in /web/current/vendor/genemu/form-bundle/Genemu/Bundle/FormBundle/Gd/Gd.php on line 255

Installer le package php5-gd

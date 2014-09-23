CMF SFYNX Bundles
=====================


SFYNX is not just to simplify the developper's work, and to speed up the creation and maintenance of your PHP web 
applications. It also allows you to create your own CMS. It's a CMF easy to use, build your own template (layout), add your own custom block
 with your own logic, build all the widget you need.

## Project PI-GROUPE Development
**For more information** : 
* http://www.sfynx.fr
* http://www.pi-groupe.fr
* http://www.pi-groupe.net

## Structure of the framework

The framework has been split into 15 bundles :


``` bash
* CmfBundle : A bundle which construct all the CMF with all managers of the creation of page with blocks and widgets.
( management varnish and memcache with reverse proxy, search lucene manager, render ESI, SEO pages, etc. )
* TemplateBundle :  A bundle which is used to stock all template of layout and others.
* AuthBundle : A bundle which overload the FOSUserBundle with role, permission and group dynamic system, and set handlers for login behavior, logout behavior and failure connection behavior
* ToolBundle : A bundle which provides tool libraries and services of route and role and twig extensions
* CoreBundle : A bundle which provides models of classes allowing to work and develop with doctrine (translation, tree, CRUD  generate Datatable table and multi-forms)
* CacheBundle : A bundle which provides classes to cache handlers (memcache, files).
* DatabaseBundle : A bundle which provides classes and commands to run DB vendor`s utilities to backup and restore databases. 
* AclManagerBundle : A bundle which provides classes to run ACL Manager`s utilities for Symfony2.
* BrowserBundle : A bundle which provides library to run Browscap Manager`s utilities and MobileDetect Manager`s utilities.
* WsBundle : A bundle which provides web services allowing to connect authentication service with the SS0 protocol.
* EncryptBundle : A bundle which provides annotations to encrypt fields
* PositionBundle : A bundle which provides annotations to manage position of entiy rows
* AdminBundle : A bundle which overload the SonataAdminBundle.
* MediaBundle : A bundle which overload the SonataMediaBundle, with crop system.
* TranslatorBundle : A bundle which provides entity and models of classes allowing to work with translation words.
* GedmoBundle : A bundle which is used to create a project with the CMF.
```

## Documentation
 
* [Example of CMF usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/cmf-bundle/Sfynx/CmfBundle/Resources/doc/index.md)
* [Example of Auth usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/auth-bundle/Sfynx/AuthBundle/Resources/doc/index.md)
* [Example of Template usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/template-bundle/Sfynx/TemplateBundle/Resources/doc/index.md)
* [Example of Core usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/core-bundle/Sfynx/CoreBundle/Resources/doc/index.md)
* [Example of Tool usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/tool-bundle/Sfynx/ToolBundle/Resources/doc/index.md)
* [Example of Media usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/media-bundle/Sfynx/MediaBundle/Resources/doc/index.md)
* [Example of Encrypt annotation usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/annotation-bundle/Sfynx/EncryptBundle/Resources/doc/index.md)
* [Example of Position annotation usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/annotation-bundle/Sfynx/PositionBundle/Resources/doc/index.md)
* [Example of Ws usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/ws-bundle/Sfynx/WsBundle/Resources/doc/index.md)
* [Example of Browser usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/browser-bundle/Sfynx/BrowserBundle/Resources/doc/index.md)

## License
**Copyright © 20012-2014, contact@pi-groupe.fr.**
**This bundle is under the [GNU General Public License](https://github.com/pigroupe/cmf-sfynx/blob/master/web/COPYING.txt), permitting combination and redistribution with software that uses the MIT License**

SFYNX is a free software distributed under the GPL license. This license guarantees the following freedoms:

``` bash
- the freedom to install and use SFYNX for any usage whatsoever;
- the freedom to look into SFYNX’s code and adapt it to your own needs by modifying the source code, to which you have direct access since SFYNX is entirely developed in PHP;
- the freedom to distribute copies of the software to anyone, provided you do not modify or delete the license;
- the freedom to enhance SFYNX and to distribute your enhancements among the public so that the entire community may benefit from it, provided you do not modify or delete the license.
```

- This application is a free software; you can distribute it and/or modify it according to the terms of the GNU General Public License, as published by the Free Software Foundation; version 2 or (upon your choice) any later version.

- This software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; not even the implicit warranty for COMMERCIALISATION or CUSTOMISATION FOR A PARTICULAR PURPOSE. For more details, refer to the GNU General Public License.

- A copy of the GNU General Public License must be provided with this software; if it is not, please write to the Free Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

- You can download this software from http://pigroupe.github.io/cmf-sfynx/; you will also find a complete user manual and additional information on this site.

- In French law, SFYNX falls under the regulations stipulated in the code of intellectual property rights (CPI). The SFYNX kernel is a collaborative work by its authors, listed above as per article L 113-1 of the CPI. The entire SFYNX project is comprised of a collective work in respect of articles L 113-2 and L 113-5 of the CPI. The authors release the work to the public in accordance with the rights and obligations as defined by the GNU public license.


## Dependencies

Register all bundle in your `app/AppKernel.php` file:

``` php

    public function registerBundles()
    {
              $bundles = array(
                new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
                new Symfony\Bundle\SecurityBundle\SecurityBundle(),
                new Symfony\Bundle\TwigBundle\TwigBundle(),
                new Symfony\Bundle\MonologBundle\MonologBundle(),
                new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
                new Symfony\Bundle\AsseticBundle\AsseticBundle(),
                new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
                
                # secure
                new JMS\AopBundle\JMSAopBundle(),
                new JMS\DiExtraBundle\JMSDiExtraBundle($this),
                new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),  
                new JMS\TranslationBundle\JMSTranslationBundle(),
                
                # doctrine
                new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
                new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
                new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),               
                
                # route
                new BeSimple\I18nRoutingBundle\BeSimpleI18nRoutingBundle(),             
                
                # sonata admin
                new Sonata\CoreBundle\SonataCoreBundle(),
                new Sonata\AdminBundle\SonataAdminBundle(),
                new Sonata\NotificationBundle\SonataNotificationBundle(),
                new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
                new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
                new Sonata\CacheBundle\SonataCacheBundle(),
                new Sonata\BlockBundle\SonataBlockBundle(),
                new Sonata\jQueryBundle\SonatajQueryBundle(),
                new Sonata\MediaBundle\SonataMediaBundle(),             

                # tools
                new FOS\UserBundle\FOSUserBundle(),
                new Knp\Bundle\MenuBundle\KnpMenuBundle(),
                new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),  
                new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),             

                # Sfynx
                new Sfynx\AclManagerBundle\SfynxAclManagerBundle(),
                new Sfynx\DatabaseBundle\SfynxDatabaseBundle(),
                new Sfynx\WsBundle\SfynxWsBundle(),
                new Sfynx\CacheBundle\SfynxCacheBundle(),
                new Sfynx\ToolBundle\SfynxToolBundle(),
                new Sfynx\CoreBundle\SfynxCoreBundle(),
                new Sfynx\TranslatorBundle\SfynxTranslatorBundle(),
                new Sfynx\BrowserBundle\SfynxBrowserBundle(),
                new Sfynx\EncryptBundle\SfynxEncryptBundle(),
                new Sfynx\PositionBundle\SfynxPositionBundle(),
                new Sfynx\AuthBundle\SfynxAuthBundle(),
                new Sfynx\MediaBundle\SfynxMediaBundle(),
                new Sfynx\AdminBundle\SfynxAdminBundle(),
                new Sfynx\CmfBundle\SfynxCmfBundle(),
                new Sfynx\TemplateBundle\SfynxTemplateBundle(),
                new Sfynx\SmoothnessBundle\SfynxSmoothnessBundle(),
                
                new BootStrap\MediaBundle\BootStrapMediaBundle(),
                new PiApp\GedmoBundle\PiAppGedmoBundle(),
                
                #override Sfynx bundles
                new OrApp\OrAdminBundle\OrAppOrAdminBundle(),
                new OrApp\OrGedmoBundle\OrAppOrGedmoBundle(),
                new OrApp\OrTemplateBundle\OrAppOrTemplateBundle(),      

                # recaptcha
                new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),        
        );
        
```
## composer.json

``` json

    {
        "name": "symfony/framework-standard-edition",
        "license": "MIT",
        "type": "project",
        "description": "The \"Symfony Standard Edition\" distribution",
        "repositories": [
        {
            "type":"git",
            "url":"https://github.com/vincecore/BeSimpleI18nRoutingBundle.git"
        }
        ],
        "require": {
            "php": ">=5.3.3",
            "symfony/symfony": "2.2.4",
            "doctrine/orm": "2.3",
            "doctrine/doctrine-bundle": "1.2.0",
            "twig/extensions": "1.0.0",
            "symfony/assetic-bundle": "2.1.3",
            "symfony/swiftmailer-bundle": "2.2.4",
            "symfony/monolog-bundle": "2.2.0",
            "sensio/distribution-bundle": "2.2.4",
            "sensio/framework-extra-bundle": "2.2.4",
            "sensio/generator-bundle": "2.2.4",
            
            "jms/translation-bundle": "1.1.*@dev",
            "jms/security-extra-bundle": "1.4.*",
            "jms/di-extra-bundle": "1.3.*",
            
            "gedmo/doctrine-extensions": "2.3.*@dev",
            "doctrine/data-fixtures": "1.0.*@dev",
            "doctrine/doctrine-fixtures-bundle": "2.1.*@dev",
            "stof/doctrine-extensions-bundle": "1.1.0",
            
            "friendsofsymfony/user-bundle": "2.0.*@dev",
            "besimple/i18n-routing-bundle": "2.2.x-dev",
            
            "imagine/Imagine": "*@stable",
            "kriswallsmith/buzz": "0.*",        
            
            "knplabs/knp-menu-bundle": ">=1.1,<2.0",
            "knplabs/knp-menu": "1.1.*",
            "knplabs/knp-components": "1.2.2",
            "knplabs/knp-paginator-bundle": "2.3.*@dev",        
            "knplabs/knp-snappy": "dev-master",
            "knplabs/knp-snappy-bundle": "dev-master",        
            
            "sonata-project/intl-bundle": "2.1.*",
            "sonata-project/exporter": "1.*",
            "sonata-project/admin-bundle": "2.2.*@dev",
            "sonata-project/block-bundle": ">=2.2.1,<3.0",
            "sonata-project/cache-bundle": "2.1.*@dev",
            "sonata-project/doctrine-orm-admin-bundle": "2.2.*@dev",
            "sonata-project/easy-extends-bundle": "2.1.*@dev",
            "sonata-project/jquery-bundle": "1.8.*@dev",
            "sonata-project/media-bundle": "2.2.*@dev",
            "sonata-project/notification-bundle": "2.2.*@dev",
            "sonata-project/core-bundle": "2.2.*@dev",
            
            "excelwebzone/recaptcha-bundle": "dev-master"
        },
        "require-dev": {
            "benjam1/symfttpd": "2.1.*",
            "phpunit/phpunit": "4.0.20",
            "whatthejeff/nyancat-phpunit-resultprinter": "~1.2",
            "squizlabs/php_codesniffer": "2.0.*@dev",
            "phpmd/phpmd": "2.1.*",
            "guzzlehttp/guzzle": "~4.0",
            "sebastian/phpcpd": "2.0.*@dev",
            "pdepend/pdepend": "2.0.*",
            "phpunit/php-invoker": "dev-master",
            "phake/phake": "*",
            "phing/phing": "dev-master",
        },       
        "scripts": {
            "post-install-cmd": [
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile"
            ],
            "post-update-cmd": [
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
                "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile"
            ]
        },
        "config": {
            "bin-dir": "bin"
        },
        "minimum-stability": "stable",
        "extra": {
            "symfony-app-dir": "app",
            "symfony-web-dir": "web",
            "branch-alias": {
                "dev-master": "2.2-dev"
            }
        },
        "autoload": {
            "psr-0": {
                "OrApp" : "src",
                "PiApp": "vendor/Sfynx",
                "BootStrap": "vendor/Sfynx",
                'Sfynx\\AclManagerBundle': '/sfynx-project/acl-manager-bundle',
                'Sfynx\\DatabaseBundle': '/sfynx-project/database-bundle',
                'Sfynx\\WsBundle': '/sfynx-project/ws-bundle',
                'Sfynx\\TranslatorBundle': '/sfynx-project/translator-bundle',
                'Sfynx\\CacheBundle': '/sfynx-project/cache-bundle',
                'Sfynx\\ToolBundle': '/sfynx-project/tool-bundle',
                'Sfynx\\CoreBundle': '/sfynx-project/core-bundle',
                'Sfynx\\CmfBundle': '/sfynx-project/cmf-bundle',
                'Sfynx\\AdminBundle': '/sfynx-project/admin-bundle',
                'Sfynx\\AuthBundle': '/sfynx-project/auth-bundle',
                'Sfynx\\MediaBundle': '/sfynx-project/media-bundle',
                'Sfynx\\BrowserBundle': '/sfynx-project/browser-bundle',
                'Sfynx\\EncryptBundle': '/sfynx-project/annotation-bundle',
                'Sfynx\\PositionBundle': '/sfynx-project/annotation-bundle',
                'Sfynx\\TemplateBundle': '/sfynx-project/template-bundle',
                'Sfynx\\SmoothnessBundle': '/sfynx-project/template-bundle',
                "Zend_": "vendor/Zend/library"
            }
        }    
    }    
```

## Installation


### Step 0: Configuring Serveur

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

``` bash

    php app/check.php

```

**Required** : 

- PHP with at least version 5.3.2 of PHP
- Sqlite3 must be enabled
- JSON must be enabled
- Ctype must be enabled
- PHP-XML module must be installed
- Installtion the gd library (for images): apt-get install php5-gd [command linux]
- PHP.ini must have the extensions:

    - date.timezone
    - php_fileinfo.dll
    - PDO_SQLITE.dll
    - php_intl.dll
    - php_memcache.dll (facultatif pour une gestion performante de cache de doctrine)
    - php_curl.dll
    - php_openssl.dll (enabled Socket transport “ssl” in PHP)
    - php-mcrypt
    - activation d'envoi de mail
         - sous windows : 
              - SMTP = smtp-host-value
              - smtp_port = smtp-port-value
         - sous linux :
              - sendmail_path = "var-bin-sendmail"    
    
**Doctrine** : 

To use Doctrine, you will need to have installed PDO. And you must have installed the PDO driver for the database server you want to use.    

### Step 1: Configuring parameters  BDD and mailer

* Open the file cmfpi_project / app / config / parameters.ini.
* Give the name "mydatabase" for example in the database and choose the type pdo_mysql to use a MySQL database.
* Give your user and password of your Gmail count.
* Change the secret code that will be used to protect your application from XSS attacks.

``` bash
parameters:
    database_driver:   pdo_mysql
    database_host:     127.0.0.1
    database_port:     ~
    database_name:     symfsfynx22
    database_user:     root
    database_password: ~

    mailer_transport:  sendmail
    mailer_host:       127.0.0.1
    mailer_user:       ~
    mailer_password:   ~

    locale:            en_GB
    secret:            5b5a0ff57bd45284dafe7f104fc7d8e15
    
    # memcache params
    session_memcache_host: 127.0.0.1 # 127.0.0.1
    session_memcache_port: 11211 # 11211
    session_memcache_prefix: sess_memcache_
    session_memcache_expire: 864000
    session_memcache_locking: true      
    session_memcache_spin_lock_wait: 150000      

    #pi_app_admin 
    pi_cookie_lifetime: 604800
    pi_session_name: "PHPSESSID"
    
    #boot_strap_ws
    ws_key: 0A1TG4GO  
    
    #esi key
    esi_key: 9eu9ghv9
```

### Step 2: Setting up Permissions

* The directories app / cache app / logs should be writable by both the web server and the user.
* On a UNIX system, if your web server is different from your user, you can run the following commands once in your project to ensure that the permissions are correctly installed. 
* We must change www-data on your web server.

Many systems allow you to use ACL chmod a +.
**For more information** : http://symfony.com/doc/current/book/installation.html

Then you must add the uploads/media folder to allow specific users to load :

``` bash
chmod –R 0777 app/cache
chmod –R 0777 app/log
chmod –R 0777 vendor/Sfynx/BootStrap/TranslatorBundle/Resources/translations

mkdir web/uploads
mkdir web/uploads/media
mkdir web/yui
chmod –R 0777 web/uploads
chmod –R 0777 web/yui
```

### Step 4: Installing the vendor

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

``` bash

    curl -s http://getcomposer.org/installer | php
    
```    
    
**Setting vendor with composer.json**
``` bash

    php  composer.phar selfupdate
    php  composer.phar install -v

```

### Step 5: Create database, tables and fixtures

- There's no way to configure these defaults inside Doctrine, as it tries to be as agnostic as possible in terms of environment configuration. 
- One way to solve this problem is to configure server-level defaults.

**Setting UTF8 defaults for MySQL is as simple as adding a few lines to your configuration file (typically my.cnf)**

``` bash

    [mysqld]
    collation-server     = utf8_general_ci
    character-set-server = utf8

```

**or open file \Doctrine\DBAL\Platforms\AbstractPlatform in getCreateTableSQL method and add this following line**

``` bash
    
    $options = $table->getOptions();
    ...
    $options['charset'] = 'utf8';
    $options['collate'] = 'utf8_general_ci';

```


- Open your console (cmd) or Putty.
- Go to the root of the application cmfpi_project.

**Type the following command to create the database**

``` bash

    php  app/console  doctrine:database:create

```

**Type the following command to create the tables**

``` bash

    php  app/console  doctrine:schema:create

```

**Type the following command to install fixtures of the tables**

``` bash

    php  app/console  doctrine:fixtures:load

```

**Type the following command to install assets of the bundles**

``` bash

    php app/console assets:install web

```

**For more information** : http://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html

### Step 6: Connexion on /login

To connect as default super administrator:

``` bash

    Username: superadmin
    Password: superadmin

```

**The password must be changed at the first use.**
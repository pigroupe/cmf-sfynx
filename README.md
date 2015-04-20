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

The framework works with Symfony 2.3 and has been split into 17 bundles :


``` bash
* CoreBundle : A bundle which provides models of classes allowing to work and develop with doctrine (translation, tree, CRUD  generate Datatable table and multi-forms)
* AuthBundle : A bundle which overload the FOSUserBundle with role, permission and group dynamic system, and set handlers for login behavior, logout behavior and failure connection behavior
* ToolBundle : A bundle which provides tool libraries and services of route and role and twig extensions
* BehatBundle : A bundle which provides context tools to run mink tests
* MigrationBundle : A bundle which provides a command to set migration files with a version handler
* CmfBundle : A bundle which construct all the CMF with all managers of the creation of page with blocks and widgets.
( management varnish and memcache with reverse proxy, search lucene manager, render ESI, SEO pages, etc. )
* GedmoBundle : A bundle which is used to create a project with the CMF.
* MediaBundle : A bundle which overload the SonataMediaBundle, with crop system.
* TranslatorBundle : A bundle which provides entity and models of classes allowing to work with translation words.
* TemplateBundle :  A bundle which is used to stock all template of layout and others.
* SmoothnessBundle: A bundle which is used to define a complet admin template with all layers
* CacheBundle : A bundle which provides classes to cache handlers (memcache, files).
* DatabaseBundle : A bundle which provides classes and commands to run DB vendor`s utilities to backup and restore databases. 
* AclManagerBundle : A bundle which provides classes to run ACL Manager`s utilities for Symfony2.
* BrowserBundle : A bundle which provides library to run Browscap Manager`s utilities and MobileDetect Manager`s utilities.
* WsBundle : A bundle which provides web services allowing to connect authentication service with the SS0 protocol.
* WsseBundle : A bundle which provides web services allowing to connect authentication service with the Wsse protocol.
* EncryptBundle : A bundle which provides annotations to encrypt fields
* PositionBundle : A bundle which provides annotations to manage position of entiy rows
* AdminBundle : A bundle which overload the SonataAdminBundle.
```

## Documentation
 
* [Example of CMF usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/cmf-bundle/Sfynx/CmfBundle/Resources/doc/index.md)
* [Example of Auth usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/auth-bundle/Sfynx/AuthBundle/Resources/doc/index.md)
* [Example of Template usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/template-bundle/Sfynx/TemplateBundle/Resources/doc/index.md)
* [Example of Core usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/core-bundle/Sfynx/CoreBundle/Resources/doc/index.md)
* [Example of Tool usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/tool-bundle/Sfynx/ToolBundle/Resources/doc/index.md)
* [Example of Tool Behat usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/tool-bundle/Sfynx/BehatBundle/Resources/doc/index.md)
* [Example of Tool Migration usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/tool-bundle/Sfynx/MigrationBundle/Resources/doc/index.md)
* [Example of Media usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/sonata-bundle/Sfynx/MediaBundle/Resources/doc/index.md)
* [Example of Encrypt annotation usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/annotation-bundle/Sfynx/EncryptBundle/Resources/doc/index.md)
* [Example of Position annotation usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/annotation-bundle/Sfynx/PositionBundle/Resources/doc/index.md)
* [Example of Ws usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/ws-bundle/Sfynx/WsBundle/Resources/doc/index.md)
* [Example of Wsse usage](https://github.com/pigroupe/cmf-sfynx/tree/master/vendor/sfynx-project/ws-bundle/Sfynx/WsseBundle/Resources/doc/index.md)
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

## Installing automaticly all-in-one

We have create an installer script shell which execute the sfynx install.

### Step 1: Installation with apache or Nginx

**If you want to install sfynx with apache-mysql-php** :
``` bash

    curl https://github.com/pigroupe/cmf-sfynx/tree/master/sysadmin/scriptshell/installer-apache.sh | sudo sh
    curl https://github.com/pigroupe/cmf-sfynx/tree/master/sysadmin/scriptshell/installer-sfynx-apache.sh | sudo sh

``` 

**If you want to install sfynx with gninx-mysql-php-fpm server** :

``` bash

    curl https://github.com/pigroupe/cmf-sfynx/tree/master/sysadmin/scriptshell/installer-nginx.sh | sudo sh
    curl https://github.com/pigroupe/cmf-sfynx/tree/master/sysadmin/scriptshell/installer-sfynx-nginx.sh | sudo sh
    
``` 

### Step 2: Connexion on /login

To connect as default super administrator, go to your navogator with on of the following url :

``` bash
http://dev.sfynx.local
http://test.sfynx.local
http://prod.sfynx.local
```

and use this to connect

``` bash

    Username: superadmin
    Password: superadmin

```

**The password must be changed at the first use.**

### Step 3: Run the phpunit tests

``` bash
    
    bin/phpunit -c app vendor/sfynx-project
    casperjs test app/Tests/casperjs/ --base-url=127.0.0.1:4042 --pre=app/Resources/casperjs/pre.js

```

### Step 4: Run Behat tests

In first step, you have to install and configure Selenium Server :

**Install the selenium server lauching**

``` bash

    curl https://github.com/pigroupe/cmf-sfynx/tree/master/sysadmin/scriptshell/selenium/installer-selenium-server.sh | sudo sh

```

**Run the server with the following command (update the version number to the one you downloaded)**

``` bash

    sudo /etc/init.d/selenium <stop|start|restart>

```

After you just have to run behat :

**To test the connexion handler**

``` bash
    
    bin/behat --suite=auth
    or
    php app/console sfynx:behat:execute --env=test --suite=auth

```

**To test the Cmf (creation of page with widget)**

``` bash
    
    bin/behat --suite=cmf
    or
    php app/console sfynx:behat:execute --env=test --suite=cmf

```

## Re-initialize project

``` bash
    
     bin/phing -f app/config/phing/initialize.xml rebuild

```

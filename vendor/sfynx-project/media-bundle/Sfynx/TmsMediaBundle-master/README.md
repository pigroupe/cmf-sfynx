TmsMediaBundle
==================

Symfony2's TMS Media Bundle.


Installation
------------

Add dependencies in your `composer.json` file:

```json
"repositories": [
    ...,
    {
        "type": "vcs",
        "url": "https://github.com/Tessi-Tms/TmsMediaBundle.git"
    }
],
"require": {
        ...,
        "knplabs/gaufrette": "0.2.*@dev",
        "knplabs/knp-gaufrette-bundle": "dev-master",
        "idci/exporter-bundle": "dev-master",
        "tms/media-bundle": "dev-master"
    },
```

Install these new dependencies of your application:
```sh
$ composer update
```

Enable the bundles in your application kernel :

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        //
        new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
        new IDCI\Bundle\ExporterBundle\IDCIExporterBundle(),
        new Tms\Bundle\MediaBundle\TmsMediaBundle(),
    );
}
```

Now import the bundle configuration in your `app/config.yml`

```yml
# app/config/config.yml

imports:
    ...
    - { resource: @TmsMediaBundle/Resources/config/config.yml }
```

Documentation
-------------

[Read the Documentation](Resources/doc/index.md)


Tests
-----

Install bundle dependencies:
```sh
$ php composer.phar update
```

To execute unit tests:
```sh
$ phpunit --coverage-text
```

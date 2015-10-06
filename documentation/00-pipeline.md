Généralités du tunnel des tests de qualification
=====

**Le contenu des jobs est piloté par le code, comme suit** :

    + `prepare`
    + `verify`
    + `functional`
    + `load.sh`
    + `analyse.sh`
    + `package.sh`

Matrice de quallification fonctionnelle
=====

    +---------+  +------+  +------+  +---------+  +---------+
    | prepare +--> test +--> load +--> package +--> go-xxx  |
    +---------+  +------+  +------+  +---------+  +---------+
                    |
                    |     +---------+
                    +-----> analysis|
                          +---------+

Définition du worflow du tunnel des tests de qualification
=====

+ prepare
    + git clone
    + composer install
    + app/console assets:install --symlinks
+ verify
    + smoke (php -l)
    + sanity    
+ functional testing
    + unit (phpunit group=unit)
    + integration (phpunit group=integration, group=specification, group=regression)
    + system (behat)
+ analysis
    + QA
        + PhpCopyPastDetection
        + PhpDeadCodeDetection
        + PhpCodeSnifferFix
        + PhpLoc
        + PhpMessDetector
        + PhpDepend
    + Metrics
        + PhpMetrcis
    + Documentor
        + PhpCodeBrowser
+ load
    + performance (gatling, etc.)
+ package
    + composer install --no-dev --optimise-autoload
    + installation
+ go-xxx
    + deploy
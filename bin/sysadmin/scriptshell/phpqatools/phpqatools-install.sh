#!/bin/sh

export SUDO_USER="user"

# http://www.frandieguez.com/blog/2014/10/easy-way-to-install-php-qa-tools/

#Add lines to the beginning and the end of the huge file
sudo ed -s ~/.profile << 'EOF'
0a
#prepend these lines to the beginning
.
$a

#append these lines to the end
# set your $PATH environment variable to include it
export PATH=$PATH:~/.composer/vendor/bin
.
w
EOF

#Reload bash's .profile without logging out and back in again
. ~/.profile

# Install composer based tools
cat > ~/.composer/composer.json <<EOF
{
    "require": {
        "halleck45/phpmetrics": "@dev",
        "squizlabs/php_codesniffer": "*",
        "fabpot/php-cs-fixer": "*",
        "phpunit/phpunit": "*",
        "phpunit/php-invoker": "dev-master",
        "sebastian/phpcpd": "*",
        "sebastian/phpdcd": "*",
        "phpmd/phpmd" : "@stable",
        "pdepend/pdepend" : "@stable",
        "phploc/phploc": "*",
        "sebastian/hhvm-wrapper": "*",
        "theseer/phpdox": "*",
        "phake/phake": "*",
        "phing/phing": "dev-master",
        "behat/behat": "3.0.*@dev",
        "instaclick/php-webdriver": "~1.1",
        "behat/mink": "1.6.*@dev",
        "behat/mink-bundle": "~1.4",
        "behat/symfony2-extension": "~2.0@dev",
        "behat/mink-extension":  "~2.0@dev",
        "behat/mink-selenium2-driver":  "*@dev",
        "behat/mink-browserkit-driver": "~1.1@dev",
        "behat/mink-goutte-driver": "*@stable",
        "behat/mink-zombie-driver": "*@stable",
        "facebook/xhprof": "dev-master@dev",        
        "phpcasperjs/phpcasperjs": "dev-master"
    }
}
EOF
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
/usr/local/bin/composer global install
chown -R $SUDO_USER.$SUDO_USER ~/.composer

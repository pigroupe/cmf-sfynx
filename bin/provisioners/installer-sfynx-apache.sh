#!/bin/bash
PLATEFORM_PROJET_NAME="cmfsfynx"
PLATEFORM_PROJET_GIT="https://github.com/pigroupe/cmf-sfynx.git"

if [ $# -eq 0 ]; then # s'il n'y a pas de paramètres
    read INSTALL_USERWWW # on saisis la valeur
else
    INSTALL_USERWWW=$1
fi

PLATEFORM_PROJET_NAME_LOWER=$(echo $PLATEFORM_PROJET_NAME | awk '{print tolower($0)}') # we lower the string
PLATEFORM_PROJET_NAME_UPPER=$(echo $PLATEFORM_PROJET_NAME | awk '{print toupper($0)}') # we lower the string
DATABASE_NAME="sfynx_${PLATEFORM_PROJET_NAME_LOWER}"
DATABASE_NAME_TEST="sfynx_${PLATEFORM_PROJET_NAME_LOWER}_test"

#if var is empty
if [ -z "$PLATEFORM_PROJET_GIT" ]; then
    PLATEFORM_PROJET_GIT="https://github.com/pigroupe/cmf-sfynx.git"
fi

echo "**** we create directories ****"
if [ ! -d $INSTALL_USERWWW ]; then
    mkdir -p $INSTALL_USERWWW
fi
cd $INSTALL_USERWWW

# we create project
if [ ! -d $PLATEFORM_PROJET_NAME ]; then
    git clone $PLATEFORM_PROJET_GIT $PLATEFORM_PROJET_NAME
    #mkdir -p $PLATEFORM_PROJET_NAME
fi
cd $PLATEFORM_PROJET_NAME

echo "**** we create default directories ****"
if [ ! -d web/yui ]; then
    mkdir -p app/cache
    mkdir -p app/logs
    mkdir -p app/cachesfynx/loginfailure
    mkdir -p web/uploads/media
    mkdir -p web/yui
fi

echo "**** we modify parameters.yml.dist ****"
sed -i "s/myproject/${PLATEFORM_PROJET_NAME_LOWER}/g" app/config/parameters.yml.dist

echo "**** we create parameters.yml ****"
if [ -f app/config/parameters.yml ]; then
    rm app/config/parameters.yml
fi
cp app/config/parameters.yml.dist app/config/parameters.yml
sed -i 's/%%/%/g' app/config/parameters.yml

if [ ! -f app/phpunit.xml ]; then
    cp app/phpunit.xml.dist app/phpunit.xml
fi

echo "**** we add env variables ****"
sudo bash -c "cat << EOT > /etc/profile.d/$PLATEFORM_PROJET_NAME_LOWER.sh
# env vars for SFYNFONY platform
export SYMFONY__DATABASE__NAME__ENV__$PLATEFORM_PROJET_NAME_UPPER=$DATABASE_NAME;
export SYMFONY__DATABASE__USER__ENV__$PLATEFORM_PROJET_NAME_UPPER=root;
export SYMFONY__DATABASE__PASSWORD__ENV__$PLATEFORM_PROJET_NAME_UPPER=pacman;
export SYMFONY__TEST__DATABASE__NAME__ENV__$PLATEFORM_PROJET_NAME_UPPER=$DATABASE_NAME_TEST;
export SYMFONY__TEST__DATABASE__USER__ENV__$PLATEFORM_PROJET_NAME_UPPER=root;
export SYMFONY__TEST__DATABASE__PASSWORD__ENV__$PLATEFORM_PROJET_NAME_UPPER=pacman;

EOT"
. /etc/profile.d/${PLATEFORM_PROJET_NAME_LOWER}.sh
printenv |grep "__ENV__$PLATEFORM_PROJET_NAME_UPPER" # list of all env
# unset envName # delete a env var

# we create the virtualhiost of sfynx for apache
mkdir -p /tmp
cat <<EOT >/tmp/$PLATEFORM_PROJET_NAME
<VirtualHost *:80>
        ServerName  dev.$PLATEFORM_PROJET_NAME_LOWER.local
        ServerAlias dev.$PLATEFORM_PROJET_NAME_LOWER.local             
        DocumentRoot $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web/

        SetEnv SYMFONY__DATABASE__NAME__ENV__${PLATEFORM_PROJET_NAME_UPPER} $DATABASE_NAME;
        SetEnv SYMFONY__DATABASE__USER__ENV__${PLATEFORM_PROJET_NAME_UPPER} root;
        SetEnv SYMFONY__DATABASE__PASSWORD__ENV__${PLATEFORM_PROJET_NAME_UPPER} pacman;
        SetEnv SYMFONY__TEST__DATABASE__NAME__ENV__${PLATEFORM_PROJET_NAME_UPPER} $DATABASE_NAME_TEST;
        SetEnv SYMFONY__TEST__DATABASE__USER__ENV__${PLATEFORM_PROJET_NAME_UPPER} root;
        SetEnv SYMFONY__TEST__DATABASE__PASSWORD__ENV__${PLATEFORM_PROJET_NAME_UPPER} pacman;

        <Directory "$INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                RewriteEngine On

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)\$ app_dev.php [QSA,L]

                #php_value auto_prepend_file "/websites/xhprof/external/header.php"
                #php_value auto_append_file "/websites/xhprof/external/footer.php"

                #Require all granted
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error_$PLATEFORM_PROJET_NAME_dev.log
        LogFormat "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" varnishcombined
        CustomLog \${APACHE_LOG_DIR}/access-dev.log varnishcombined
</VirtualHost>

<VirtualHost *:80>
        ServerName  test.$PLATEFORM_PROJET_NAME_LOWER.local
        DocumentRoot $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web/

        SetEnv SYMFONY__DATABASE__NAME__ENV__${PLATEFORM_PROJET_NAME_UPPER} $DATABASE_NAME;
        SetEnv SYMFONY__DATABASE__USER__ENV__${PLATEFORM_PROJET_NAME_UPPER} root;
        SetEnv SYMFONY__DATABASE__PASSWORD__ENV__${PLATEFORM_PROJET_NAME_UPPER} pacman;
        SetEnv SYMFONY__TEST__DATABASE__NAME__ENV__${PLATEFORM_PROJET_NAME_UPPER} $DATABASE_NAME_TEST;
        SetEnv SYMFONY__TEST__DATABASE__USER__ENV__${PLATEFORM_PROJET_NAME_UPPER} root;
        SetEnv SYMFONY__TEST__DATABASE__PASSWORD__ENV__${PLATEFORM_PROJET_NAME_UPPER} pacman;

        <Directory "$INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                RewriteEngine On

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)\$ app_test.php [QSA,L]

                #php_value auto_prepend_file "/websites/xhprof/external/header.php"
                #php_value auto_append_file "/websites/xhprof/external/footer.php"

                #Require all granted
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error_$PLATEFORM_PROJET_NAME_test.log
        LogFormat "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" varnishcombined
        CustomLog \${APACHE_LOG_DIR}/access-test.log varnishcombined
</VirtualHost>

<VirtualHost *:80>
        ServerName prod.$PLATEFORM_PROJET_NAME_LOWER.local
        DocumentRoot $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web/

        SetEnv SYMFONY__DATABASE__NAME__ENV__${PLATEFORM_PROJET_NAME_UPPER} $DATABASE_NAME;
        SetEnv SYMFONY__DATABASE__USER__ENV__${PLATEFORM_PROJET_NAME_UPPER} root;
        SetEnv SYMFONY__DATABASE__PASSWORD__ENV__${PLATEFORM_PROJET_NAME_UPPER} pacman;
        SetEnv SYMFONY__TEST__DATABASE__NAME__ENV__${PLATEFORM_PROJET_NAME_UPPER} $DATABASE_NAME_TEST;
        SetEnv SYMFONY__TEST__DATABASE__USER__ENV__${PLATEFORM_PROJET_NAME_UPPER} root;
        SetEnv SYMFONY__TEST__DATABASE__PASSWORD__ENV__${PLATEFORM_PROJET_NAME_UPPER} pacman;

        <Directory $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                RewriteEngine On

	        RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)\$ app.php [QSA,L]

	         # Desactiver l'utilistion des logiciels de type rapidLink
                RewriteCond %{HTTP_REFERER} .*kristjanlilleoja.com.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*megaupload.byethost7.com.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*openurls.eu.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*urlopener.com.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*rapidlinkr.com.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*multilinkr.com.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*openmultipleurl.com.*\$  [OR]
                RewriteCond %{HTTP_REFERER} .*pastebin.com.*\$
                RewriteCond %{REQUEST_URI} !^/404error\$\$
                RewriteRule ^(.*)\$ http://prod.$PLATEFORM_PROJET_NAME_LOWER.local/404error\$                
		  
		# autorize all IPs                
	        Order allow,deny
                allow from all

		<IfModule mpm_worker_module>
                    StartServers            5
                    MinSpareServers         32  #5
                    MaxSpareServers         64  #10
                    ServerLimit             500
                    MaxRequestWorkers       500
                    MaxConnectionsPerChild  0
                    ThreadsPerChild         256      #25
                    MaxClients              256      #150
                </IfModule>
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error_$PLATEFORM_PROJET_NAME_prod.log
        LogFormat "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" varnishcombined
        CustomLog \${APACHE_LOG_DIR}/access-prod.log varnishcombined

</VirtualHost>
EOT
echo "**** we create the symbilic link ****"
sudo rm /etc/apache2/sites-enabled/$PLATEFORM_PROJET_NAME
sudo rm /etc/apache2/sites-available/$PLATEFORM_PROJET_NAME
sudo mv /tmp/$PLATEFORM_PROJET_NAME /etc/apache2/sites-available/$PLATEFORM_PROJET_NAME
sudo ln -s /etc/apache2/sites-available/$PLATEFORM_PROJET_NAME /etc/apache2/sites-enabled/$PLATEFORM_PROJET_NAME

echo "**** we add host in the /etc/hosts file ****"
if ! grep -q "dev.$PLATEFORM_PROJET_NAME_LOWER.local" /etc/hosts; then
    echo "# Adding hostname of the $PLATEFORM_PROJET_NAME project" | sudo tee --append /etc/hosts
    echo "127.0.0.1    dev.$PLATEFORM_PROJET_NAME_LOWER.local" | sudo tee --append /etc/hosts
    echo "127.0.0.1    test.$PLATEFORM_PROJET_NAME_LOWER.local" | sudo tee --append /etc/hosts
    echo "127.0.0.1    prod.$PLATEFORM_PROJET_NAME_LOWER.local" | sudo tee --append /etc/hosts
    echo "   " | sudo tee --append /etc/hosts
fi

echo "**** we restart apache2 server ****"
sudo service apache2 restart

#if [ ! -f composer.phar ]; then
#    echo "**** we install/update the composer file ****"
#    #wget https://getcomposer.org/composer.phar -O ./composer.phar
#    curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
#else
#    echo "update composer.phar"
#    php composer.phar self-update    
#fi
echo "**** we lauch the composer ****"
sudo composer self-update
composer install --no-interaction
echo "**** Generating optimized autoload files ****"
composer dump-autoload --optimize

echo "**** we remove cache files ****"
rm -rf app/cache/*
rm -rf app/logs/*

echo "**** we set all necessary permissions ****"
# Utiliser l'ACL sur un système qui supporte chmod +a
#HTTPDUSER=`ps aux |grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' |grep -v root | head -1 | cut -d\  -f1`
#sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
#sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs

# Utiliser l'ACL sur un système qui ne supporte pas chmod +a
#HTTPDUSER=`ps aux |grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' |grep -v root | head -1 | cut -d\  -f1`
HTTPDUSER=$(ps -o user= -p $$ | awk '{print $1}')
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

# sans utiliser ACL
## Définit une permission 0775 aux fichiers
#echo "umask(0002);" | sudo tee --prepend app/console
#echo "umask(0002);" | sudo tee --prepend web/app_dev.php
#echo "umask(0002);" | sudo tee --prepend web/app.php
## Définit une permission 0777 aux fichiers
#echo "umask(0000);" | sudo tee --prepend app/console
#echo "umask(0000);" | sudo tee --prepend web/app_dev.php
#echo "umask(0000);" | sudo tee --prepend web/app.php

sudo usermod -aG www-data $HTTPDUSER
sudo chown -R $HTTPDUSER:www-data $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME
sudo chmod -R 0755 $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME
sudo chmod -R 0775 app/config/parameters.yml
sudo chmod -R 0775 app/cache
sudo chmod -R 0755 app/cachesfynx/loginfailure
sudo chmod -R 0775 app/logs
sudo chmod -R 0775 web/uploads
sudo chmod -R 0755 web/yui

echo "**** we create database ****"
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load
php app/console sfynx:classification:fixtures
php app/console lexik:monolog-browser:schema-create
php app/console assets:install
php app/console assetic:dump
php app/console clear:cache

echo "**** we run the phing script to initialize the project ****"
vendor/bin/phing -f config/phing/initialize.xml rebuild

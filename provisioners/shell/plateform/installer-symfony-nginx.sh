#!/bin/bash
DIR=$1
PLATEFORM_INSTALL_NAME=$2
PLATEFORM_INSTALL_TYPE=$3
PLATEFORM_INSTALL_VERSION=$4
PLATEFORM_PROJET_NAME=$5
PLATEFORM_PROJET_GIT=$6
source $DIR/provisioners/shell/env.sh

# we create directories
if [ ! -d $INSTALL_USERWWW ]; then
    mkdir -p $INSTALL_USERWWW
fi
cd $INSTALL_USERWWW

# we create project
if [ ! -d $PLATEFORM_PROJET_NAME ]; then
    case $PLATEFORM_INSTALL_TYPE in
        'composer' )
            curl -s https://getcomposer.org/installer | php
            php composer.phar create-project symfony/framework-standard-edition $INSTALL_USERWWW/$PLATEFORM_PROJET_NAME $PLATEFORM_VERSION
            cd $PLATEFORM_PROJET_NAME
        ;;
        'stack' )
            curl -LsS http://symfony.com/installer -o /usr/local/bin/symfony
            chmod a+x /usr/local/bin/symfony
            symfony new $PLATEFORM_PROJET_NAME $PLATEFORM_VERSION
            cd $PLATEFORM_PROJET_NAME
        ;;
        'tar' )
            mkdir  $PLATEFORM_PROJET_NAME
            cd $PLATEFORM_PROJET_NAME
            wget http://symfony.com/download?v=Symfony_Standard_Vendors_$PLATEFORM_VERSION.tgz
            tar -zxvf download?v=Symfony_Standard_Vendors_$PLATEFORM_VERSION.tgz
            mv Symfony/* ./
            rm -rf download?v=Symfony_Standard_Vendors_$PLATEFORM_VERSION.tgz
            rm -rf Symfony
        ;;
    esac
fi

# we create default directories
mkdir -p app/cache
mkdir -p app/logs
mkdir -p web/uploads/media
rm app/config/parameters.yml
cp app/config/parameters.yml.dist app/config/parameters.yml

if ! grep -q "SYMFONY__DATABASE__NAME__ENV" ~/.profile; then
# we add env var
cat <<EOT >> ~/.profile

# env vars for SFYNFONY platform
export SYMFONY__DATABASE__NAME__ENV=sf_$PLATEFORM_PROJET_NAME_dev;
export SYMFONY__DATABASE__USER__ENV=root;
export SYMFONY__DATABASE__PASSWORD__ENV=pacman;
export SYMFONY__TEST__DATABASE__NAME__ENV=sf_$PLATEFORM_PROJET_NAME_test;
export SYMFONY__TEST__DATABASE__USER__ENV=root;
export SYMFONY__TEST__DATABASE__PASSWORD__ENV=pacman;
EOT
source ~/.profile
fi

# we create the virtualhiost of sfynx for nginx
mkdir -p /tmp
cat <<EOT >/tmp/$PLATEFORM_PROJET_NAME
#upstream php5-fpm-sock {  
#    server unix:/var/run/php5-fpm.sock;  
#}

server {
    set \$website_root "$INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web";
    set \$default_env  "app_dev.php";

    listen 80;

    # Server name being used (exact name, wildcards or regular expression)
    server_name dev.$PLATEFORM_PROJET_NAME.local;

    # Document root, make sure this points to your Symfony2 /web directory
    root \$website_root;

    # Don't show the nginx version number, a security best practice
    server_tokens off;

    # charset
    charset utf-8;

    # Gzip
    gzip on;
    gzip_min_length 1100;
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;
    gzip_disable "MSIE [1-6]\.";
    gzip_proxied any; 
    gzip_comp_level 6;
    gzip_buffers 16 8k; 
    gzip_http_version 1.1; 

    # Logging
    access_log off; 
    log_not_found off; 
    #error_log  /var/log/nginx/sfynx-error.log;

    # Cache information about frequently accessed files
    open_file_cache max=2000 inactive=20s; 
    open_file_cache_valid 60s; 
    open_file_cache_min_uses 5; 
    open_file_cache_errors off;

    # Adjust client timeouts
    client_max_body_size 50M; 
    client_body_buffer_size 1m; 
    client_body_timeout 15; 
    client_header_timeout 15; 
    keepalive_timeout 2 2; 
    send_timeout 15; 
    sendfile on; 
    tcp_nopush on; 
    tcp_nodelay on;

    # Adjust output buffers
    fastcgi_buffers 256 16k; 
    fastcgi_buffer_size 128k; 
    fastcgi_connect_timeout 3s; 
    fastcgi_send_timeout 120s; 
    fastcgi_read_timeout 120s; 
    fastcgi_busy_buffers_size 256k; 
    fastcgi_temp_file_write_size 256k; 
    reset_timedout_connection on; 
    #server_names_hash_bucket_size 100;

    location / {
        # try to serve file directly, fallback to rewrite
        try_files \$uri @rewriteapp;

    }

    location @rewriteapp {
        rewrite ^(.*)\$ /\$default_env/\$1 last;
    }

    # Pass the PHP scripts to FastCGI server
    location ~ ^/(app|app_dev|app_test|config)\.php(/|\$) {
        fastcgi_pass php5-fpm-sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)\$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param  HTTPS off;
        # fastcgi_param PHP_VALUE "auto_prepend_file=$INSTALL_USERWWW/xhprof/external/header.php \n auto_append_file=$INSTALL_USERWWW/xhprof/external/footer.php";
        fastcgi_param SYMFONY__DATABASE__NAME__ENV sf_$PLATEFORM_PROJET_NAME_dev;
        fastcgi_param SYMFONY__DATABASE__USER__ENV root;
        fastcgi_param SYMFONY__DATABASE__PASSWORD__ENV pacman;
        fastcgi_param SYMFONY__TEST__DATABASE__NAME__ENV sf_$PLATEFORM_PROJET_NAME_test;
        fastcgi_param SYMFONY__TEST__DATABASE__USER__ENV root;
        fastcgi_param SYMFONY__TEST__DATABASE__PASSWORD__ENV pacman;
    }

    # Nginx Cache Control for Static Files
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml)\$ { 
        access_log        off; 
        log_not_found     off; 
        expires           360d; 
    } 

    # Prevent (deny) Access to Hidden Files with Nginx
    location ~ /\. { 
        access_log off; 
        log_not_found off; 
        deny all; 
    }


   location /phpmyadmin {
               root /usr/share/;
               index index.php index.html index.htm;
               location ~ ^/phpmyadmin/(.+\.php)\$ {
                       try_files \$uri =404;
                       root /usr/share/;
                       fastcgi_pass php5-fpm-sock;
                       fastcgi_index index.php;
                       fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                       include /etc/nginx/fastcgi_params;
               }
               location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))\$ {
                       root /usr/share/;
               }
   }

   location /phpMyAdmin {
            rewrite ^/* /phpmyadmin last;
   }

}

server {
    set \$website_root "$INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web";
    set \$default_env  "app_test.php";

    listen 80;

    # Server name being used (exact name, wildcards or regular expression)
    server_name test.$PLATEFORM_PROJET_NAME.local;

    # Document root, make sure this points to your Symfony2 /web directory
    root \$website_root;

    # Don't show the nginx version number, a security best practice
    server_tokens off;

    # charset
    charset utf-8;

    # Gzip
    gzip on;
    gzip_min_length 1100;
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;
    gzip_disable "MSIE [1-6]\.";
    gzip_proxied any; 
    gzip_comp_level 6;
    gzip_buffers 16 8k; 
    gzip_http_version 1.1; 

    # Logging
    access_log off; 
    log_not_found off; 
    #error_log  /var/log/nginx/sfynx-error.log;

    # Cache information about frequently accessed files
    open_file_cache max=2000 inactive=20s; 
    open_file_cache_valid 60s; 
    open_file_cache_min_uses 5; 
    open_file_cache_errors off;

    # Adjust client timeouts
    client_max_body_size 50M; 
    client_body_buffer_size 1m; 
    client_body_timeout 15; 
    client_header_timeout 15; 
    keepalive_timeout 2 2; 
    send_timeout 15; 
    sendfile on; 
    tcp_nopush on; 
    tcp_nodelay on;

    # Adjust output buffers
    fastcgi_buffers 256 16k; 
    fastcgi_buffer_size 128k; 
    fastcgi_connect_timeout 3s; 
    fastcgi_send_timeout 120s; 
    fastcgi_read_timeout 120s; 
    fastcgi_busy_buffers_size 256k; 
    fastcgi_temp_file_write_size 256k; 
    reset_timedout_connection on; 
    #server_names_hash_bucket_size 100;

    location / {
        # try to serve file directly, fallback to rewrite
        try_files \$uri @rewriteapp;

    }

    location @rewriteapp {
        rewrite ^(.*)\$ /\$default_env/\$1 last;
    }

    # Pass the PHP scripts to FastCGI server
    location ~ ^/(app|app_dev|app_test|config)\.php(/|\$) {
        fastcgi_pass php5-fpm-sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)\$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param  HTTPS off;
        # fastcgi_param PHP_VALUE "auto_prepend_file=$INSTALL_USERWWW/xhprof/external/header.php \n auto_append_file=$INSTALL_USERWWW/xhprof/external/footer.php";
        fastcgi_param SYMFONY__DATABASE__NAME__ENV sf_$PLATEFORM_PROJET_NAME_dev;
        fastcgi_param SYMFONY__DATABASE__USER__ENV root;
        fastcgi_param SYMFONY__DATABASE__PASSWORD__ENV pacman;
        fastcgi_param SYMFONY__TEST__DATABASE__NAME__ENV sf_$PLATEFORM_PROJET_NAME_test;
        fastcgi_param SYMFONY__TEST__DATABASE__USER__ENV root;
        fastcgi_param SYMFONY__TEST__DATABASE__PASSWORD__ENV pacman;
    }

    # Nginx Cache Control for Static Files
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml)\$ { 
        access_log        off; 
        log_not_found     off; 
        expires           360d; 
    } 

    # Prevent (deny) Access to Hidden Files with Nginx
    location ~ /\. { 
        access_log off; 
        log_not_found off; 
        deny all; 
    }


   location /phpmyadmin {
               root /usr/share/;
               index index.php index.html index.htm;
               location ~ ^/phpmyadmin/(.+\.php)\$ {
                       try_files \$uri =404;
                       root /usr/share/;
                       fastcgi_pass php5-fpm-sock;
                       fastcgi_index index.php;
                       fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                       include /etc/nginx/fastcgi_params;
               }
               location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))\$ {
                       root /usr/share/;
               }
   }

   location /phpMyAdmin {
            rewrite ^/* /phpmyadmin last;
   }

}

server {
    set \$website_root "$INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web";
    set \$default_env  "app.php";

    listen 80;

    # Server name being used (exact name, wildcards or regular expression)
    server_name prod.$PLATEFORM_PROJET_NAME.local;

    # Document root, make sure this points to your Symfony2 /web directory
    root \$website_root;

    # Don't show the nginx version number, a security best practice
    server_tokens off;

    # charset
    charset utf-8;

    # Gzip
    gzip on;
    gzip_min_length 1100;
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;
    gzip_disable "MSIE [1-6]\.";
    gzip_proxied any; 
    gzip_comp_level 6;
    gzip_buffers 16 8k; 
    gzip_http_version 1.1; 

    # Logging
    access_log off; 
    log_not_found off; 
    #error_log  /var/log/nginx/sfynx-error.log;

    # Cache information about frequently accessed files
    open_file_cache max=2000 inactive=20s; 
    open_file_cache_valid 60s; 
    open_file_cache_min_uses 5; 
    open_file_cache_errors off;

    # Adjust client timeouts
    client_max_body_size 50M; 
    client_body_buffer_size 1m; 
    client_body_timeout 15; 
    client_header_timeout 15; 
    keepalive_timeout 2 2; 
    send_timeout 15; 
    sendfile on; 
    tcp_nopush on; 
    tcp_nodelay on;

    # Adjust output buffers
    fastcgi_buffers 256 16k; 
    fastcgi_buffer_size 128k; 
    fastcgi_connect_timeout 3s; 
    fastcgi_send_timeout 120s; 
    fastcgi_read_timeout 120s; 
    fastcgi_busy_buffers_size 256k; 
    fastcgi_temp_file_write_size 256k; 
    reset_timedout_connection on; 
    #server_names_hash_bucket_size 100;

    location / {
        # try to serve file directly, fallback to rewrite
        try_files \$uri @rewriteapp;

    }

    location @rewriteapp {
        rewrite ^(.*)\$ /\$default_env/\$1 last;
    }

    # Pass the PHP scripts to FastCGI server
    location ~ ^/(app|app_dev|app_test|config)\.php(/|\$) {
        fastcgi_pass php5-fpm-sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)\$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param  HTTPS off;
        # fastcgi_param PHP_VALUE "auto_prepend_file=$INSTALL_USERWWW/xhprof/external/header.php \n auto_append_file=$INSTALL_USERWWW/xhprof/external/footer.php";
        fastcgi_param SYMFONY__DATABASE__NAME__ENV sf_$PLATEFORM_PROJET_NAME_dev;
        fastcgi_param SYMFONY__DATABASE__USER__ENV root;
        fastcgi_param SYMFONY__DATABASE__PASSWORD__ENV pacman;
        fastcgi_param SYMFONY__TEST__DATABASE__NAME__ENV sf_$PLATEFORM_PROJET_NAME_test;
        fastcgi_param SYMFONY__TEST__DATABASE__USER__ENV root;
        fastcgi_param SYMFONY__TEST__DATABASE__PASSWORD__ENV pacman;
    }

    # Nginx Cache Control for Static Files
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|xml)\$ { 
        access_log        off; 
        log_not_found     off; 
        expires           360d; 
    } 

    # Prevent (deny) Access to Hidden Files with Nginx
    location ~ /\. { 
        access_log off; 
        log_not_found off; 
        deny all; 
    }


   location /phpmyadmin {
               root /usr/share/;
               index index.php index.html index.htm;
               location ~ ^/phpmyadmin/(.+\.php)\$ {
                       try_files \$uri =404;
                       root /usr/share/;
                       fastcgi_pass php5-fpm-sock;
                       fastcgi_index index.php;
                       fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                       include /etc/nginx/fastcgi_params;
               }
               location ~* ^/phpmyadmin/(.+\.(jpg|jpeg|gif|css|png|js|ico|html|xml|txt))\$ {
                       root /usr/share/;
               }
   }

   location /phpMyAdmin {
            rewrite ^/* /phpmyadmin last;
   }

}
EOT
mv /tmp/$PLATEFORM_PROJET_NAME /etc/nginx/sites-available/$PLATEFORM_PROJET_NAME

# we create the symbilic link
ln -s /etc/nginx/sites-available/$PLATEFORM_PROJET_NAME /etc/nginx/sites-enabled/$PLATEFORM_PROJET_NAME

# we add host in the /etc/hosts file
if ! grep -q "dev.$PLATEFORM_PROJET_NAME.local" /etc/hosts; then
    echo "Adding hostname to your /etc/hosts"
    echo "127.0.0.1    dev.$PLATEFORM_PROJET_NAME.local" | tee --append /etc/hosts
    echo "127.0.0.1    test.$PLATEFORM_PROJET_NAME.local" | tee --append /etc/hosts
    echo "127.0.0.1    prod.$PLATEFORM_PROJET_NAME.local" | tee --append /etc/hosts
fi

# we restart nginx server
sudo service nginx restart

# we delete bin-dir config to have the default value egal to "vendor/bin"
if [ -f "composer.json" ]; then
     sed -i '/bin-dir/d' composer.json
fi

# we install the composer file
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
fi
php -d memory_limit=1024M composer.phar install --no-interaction
php composer.phar dump-autoload --optimize

# install doctrine bundles
#composer require --dev  --update-with-dependencies  doctrine/doctrine-fixtures-bundle:dev-master
#composer require --dev  --update-with-dependencies  doctrine/data-fixtures:1.0.*
#composer require --dev  --update-with-dependencies  doctrine/doctrine-cache-bundle:1.0.*
#composer require --dev  --update-with-dependencies  gedmo/doctrine-extensions:2.3.12
#composer require --dev  --update-with-dependencies  stof/doctrine-extensions-bundle:1.1.*@dev

# install jms bundle
#composer require --dev  --update-with-dependencies  jms/security-extra-bundle:1.5.*
#composer require --dev  --update-with-dependencies  jms/di-extra-bundle:1.4.*
#composer require --dev  --update-with-dependencies  jms/serializer-bundle:0.13.*@dev
#composer require --dev  --update-with-dependencies  symfony/translation:2.6.*@dev
#composer require --dev  --update-with-dependencies  jms/translation-bundle:1.1.*@dev
        
# install QA depo in dev environment
#composer require --dev  --update-with-dependencies  phpdocumentor/phpdocumentor:2.*
#composer require --dev  --update-with-dependencies  mayflower/php-codebrowser:~1.1
#composer require --dev  --update-with-dependencies  theseer/phpdox:*
#composer require --dev  --update-with-dependencies  halleck45/phpmetrics:@dev
#composer require --dev  --update-with-dependencies  squizlabs/php_codesniffer:*
#composer require --dev  --update-with-dependencies  fabpot/php-cs-fixer:*
#composer require --dev  --update-with-dependencies  phpunit/phpunit:*
#composer require --dev  --update-with-dependencies  phpunit/php-invoker:dev-master
#composer require --dev  --update-with-dependencies  sebastian/phpcpd:*
#composer require --dev  --update-with-dependencies  sebastian/phpdcd:*
#composer require --dev  --update-with-dependencies  phpmd/phpmd:@stable
#composer require --dev  --update-with-dependencies  pdepend/pdepend:@stable
#composer require --dev  --update-with-dependencies  phploc/phploc:*
#composer require --dev  --update-with-dependencies  sebastian/hhvm-wrapper:*
#composer require --dev  --update-with-dependencies  phake/phake:*
#composer require --dev  --update-with-dependencies  phing/phing:dev-master
#composer require --dev  --update-with-dependencies  behat/behat:3.0.*@dev
#composer require --dev  --update-with-dependencies  instaclick/php-webdriver:~1.1
#composer require --dev  --update-with-dependencies  behat/mink:1.6.*@dev
#composer require --dev  --update-with-dependencies  behat/mink-bundle:~1.4
#composer require --dev  --update-with-dependencies  behat/symfony2-extension:~2.0@dev
#composer require --dev  --update-with-dependencies  behat/mink-extension:~2.0@dev
#composer require --dev  --update-with-dependencies  behat/mink-selenium2-driver:*@dev
#composer require --dev  --update-with-dependencies  behat/mink-browserkit-driver:~1.1@dev
#composer require --dev  --update-with-dependencies  behat/mink-goutte-driver:*@stable
#composer require --dev  --update-with-dependencies  behat/mink-zombie-driver:*@stable
#composer require --dev  --update-with-dependencies  facebook/xhprof:dev-master@dev        
#composer require --dev  --update-with-dependencies  phpcasperjs/phpcasperjs:dev-master
#composer require --dev  --update-with-dependencies  psecio/iniscan:dev-master
#composer require --dev  --update-with-dependencies  psecio/versionscan:dev-master
#composer require --dev  --update-with-dependencies  psecio/parse:dev-master
#composer require --dev  --update-with-dependencies  mayflower/php-codebrowser:~1.1
#composer update --with-dependencies

# create database
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console doctrine:fixtures:load
php app/console assets:install
php app/console assetic:dump
php app/console clear:cache

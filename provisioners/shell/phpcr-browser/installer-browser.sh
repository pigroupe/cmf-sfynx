#!/bin/bash
DIR=$1

PLATEFORM_PROJET_NAME=phpcr-browser

#Web interface for browsing PHPCR repositories, using Silex and AngularJS 
#https://github.com/marmelab/phpcr-browser

echo "*****Install Web interface for browsing PHPCR repositories, using Silex and AngularJS"
mkdir -p /tmp
mkdir -p /websites/phpcr-browser
sudo chmod -R 777 /websites/phpcr-browser
cd /tmp

echo "***** Clone the repository "
sudo git clone https://github.com/marmelab/phpcr-browser
cd phpcr-browser
sudo cp -r * /websites/phpcr-browser
cd /websites/phpcr-browser

echo "***** We configure phpcr-browser "
if [ ! -f config/prod.yml ]; then
    cp config/prod.yml-dist config/prod.yml
    sed -i 's/8080/8081/g' config/prod.yml
fi

# we create the virtualhiost of sfynx for nginx
cat <<EOT >/tmp/$PLATEFORM_PROJET_NAME
#upstream php5-fpm-sock {  
#    server unix:/var/run/php5-fpm.sock;  
#}

server {
    set \$website_root "/websites/phpcr-browser/web";
    set \$default_env  "index.php";

    listen 80;

    # Server name being used (exact name, wildcards or regular expression)
    server_name www.$PLATEFORM_PROJET_NAME.local;

    # Document root, make sure this points to your Symfony2 /web directory
    root \$website_root;

    # Don't show the nginx version number, a security best practice
    server_tokens off;

    # Logging
    access_log on; 
    #log_not_found off; 
    #error_log  /var/log/nginx/phpcrbrowser-error.log;

    location / {
        # try to serve file directly, fallback to rewrite
        try_files \$uri @rewriteapp;

    }

    location @rewriteapp {
        rewrite ^(.*)\$ /\$default_env/\$1 last;
    }

    # Pass the PHP scripts to FastCGI server
    location ~ ^/(index)\.php(/|\$) {
        fastcgi_pass php5-fpm-sock;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param  HTTPS off;
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
}
EOT
sudo mv /tmp/$PLATEFORM_PROJET_NAME /etc/nginx/sites-available/$PLATEFORM_PROJET_NAME

# we create the symbilic link
sudo ln -s /etc/nginx/sites-available/$PLATEFORM_PROJET_NAME /etc/nginx/sites-enabled/$PLATEFORM_PROJET_NAME

# we add host in the /etc/hosts file
if ! grep -q "dev.$PLATEFORM_PROJET_NAME.local" /etc/hosts; then
    echo "Adding hostname to your /etc/hosts"
    echo "127.0.0.1    www.$PLATEFORM_PROJET_NAME.local" | tee --append /etc/hosts
fi

# we install the composer file
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
    # curl -s https://getcomposer.org/installer | php
fi
#composer require jackalope/jackalope-doctrine-dbal:1.1.* --no-update
#composer update jackalope/jackalope-doctrine-dbal

echo "***** Install project"
#make install
php composer.phar install --no-interaction
bower install --config.interactive=false

# we restart nginx server
sudo service nginx restart

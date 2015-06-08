#!/bin/bash
PLATEFORM_PROJET_NAME=phpcr-browser

#Web interface for browsing PHPCR repositories, using Silex and AngularJS 
#https://github.com/marmelab/phpcr-browser

DIR=$1
INSTALL_USERWWW=$2

echo "*****Install Web interface for browsing PHPCR repositories, using Silex and AngularJS"

mkdir -p $DIR/phpcr-browser

cd /tmp
echo "***** Clone the repository "
sudo git clone https://github.com/marmelab/phpcr-browser
cd phpcr-browser
cp -r * $DIR/phpcr-browser

cd $DIR/phpcr-browser

# we install the composer file
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
    # curl -s https://getcomposer.org/installer | php
fi
echo "***** Install dependencies and configure the browser"
php -d memory_limit=1024M composer.phar install --no-interaction

# we create the virtualhiost of sfynx for nginx
mkdir -p /tmp
cat <<EOT >/tmp/$PLATEFORM_PROJET_NAME
#upstream php5-fpm-sock {  
#    server unix:/var/run/php5-fpm.sock;  
#}

server {
    set \$website_root "$INSTALL_USERWWW/$PLATEFORM_PROJET_NAME/web";
    set \$default_env  "index.php";

    listen 80;

    # Server name being used (exact name, wildcards or regular expression)
    server_name www.$PLATEFORM_PROJET_NAME.local;

    # Document root, make sure this points to your Symfony2 /web directory
    root \$website_root;

    # Don't show the nginx version number, a security best practice
    server_tokens off;

    # charset
    charset utf-8;

    # Logging
    access_log off; 
    log_not_found off; 
    #error_log  /var/log/nginx/sfynx-error.log;

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
    location ~ ^/index\.php(/|\$) {
        fastcgi_pass php5-fpm-sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)\$;
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
if ! grep -q "www.$PLATEFORM_PROJET_NAME.local" /etc/hosts; then
    echo "Adding hostname to your /etc/hosts"
    echo "127.0.0.1    www.$PLATEFORM_PROJET_NAME.local" | tee --append /etc/hosts
fi

# we restart nginx server
sudo service nginx restart

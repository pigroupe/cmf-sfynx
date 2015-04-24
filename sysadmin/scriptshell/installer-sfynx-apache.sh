#!/bin/sh

cd /var/www

if [ ! -d "cmf-sfynx" ]; then
 git clone https://github.com/pigroupe/cmf-sfynx.git cmf-sfynx
fi

cd cmf-sfynx

# we install the composer file
if [ ! -f composer.phar ]; then
    wget https://getcomposer.org/composer.phar -O ./composer.phar
    php -d memory_limit=1024M composer.phar install --no-interaction
    php composer.phar dump-autoload --optimize
fi

# we create default directories
mkdir -p app/cache
mkdir -p app/logs
mkdir -p app/cachesfynx/loginfailure
mkdir -p web/uploads/media
mkdir -p web/yui

# permission
chown -R root:www-data app/cache
chown -R root:www-data app/cachesfynx
chown -R root:www-data app/logs
chown -R root:www-data app/config/parameters.yml
chown -R root:www-data web/uploads
chown -R root:www-data web/yui

sudo chmod -R 775 app/config/parameters.yml
sudo chmod -R 775 app/cachesfynx
sudo chmod -R 775 app/cache
sudo chmod -R 775 app/logs
sudo chmod -R 775 web/uploads
sudo chmod -R 775 web/yui

# we run the phing script to initialize the sfynx project
bin/phing -f app/config/phing/initialize.xml rebuild

sudo chmod -R 775 app/config/parameters.yml
sudo chmod -R 775 app/cachesfynx
sudo chmod -R 775 app/cache
sudo chmod -R 775 app/logs
sudo chmod -R 775 web/uploads
sudo chmod -R 775 web/yui

# we create the virtualhiost of sfynx for apache
sudo cat >> /tmp/sfynx << 'EOF'
<VirtualHost *:80>
        ServerName  dev.sfynx.local
        ServerAlias dev.sfynx.local             
        DocumentRoot /var/www/cmf-sfynx/web/
        <Directory "/var/www/cmf-sfynx/web">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                RewriteEngine On

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ app_dev.php [QSA,L]

                #php_value auto_prepend_file "/var/www/xhprof/external/header.php"
                #php_value auto_append_file "/var/www/xhprof/external/footer.php"

                #Require all granted
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error_sfynx_dev.log
        LogFormat "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" varnishcombined
        CustomLog ${APACHE_LOG_DIR}/access-dev.log varnishcombined
</VirtualHost>

<VirtualHost *:80>
        ServerName  test.sfynx.local
        DocumentRoot /var/www/cmf-sfynx/web/

        <Directory "/var/www/cmf-sfynx/web">
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                RewriteEngine On

                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ app_test.php [QSA,L]

                #php_value auto_prepend_file "/var/www/xhprof/external/header.php"
                #php_value auto_append_file "/var/www/xhprof/external/footer.php"

                #Require all granted
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error_sfynx_test.log
        LogFormat "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" varnishcombined
        CustomLog ${APACHE_LOG_DIR}/access-test.log varnishcombined
</VirtualHost>

<VirtualHost *:80>
        ServerName prod.sfynx.local
        DocumentRoot /var/www/cmf-sfynx/web/

        <Directory /var/www/cmf-sfynx/web>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                RewriteEngine On

	        RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule ^(.*)$ app.php [QSA,L]

	         # Desactiver l'utilistion des logiciels de type rapidLink
                RewriteCond %{HTTP_REFERER} .*kristjanlilleoja.com.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*megaupload.byethost7.com.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*openurls.eu.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*urlopener.com.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*rapidlinkr.com.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*multilinkr.com.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*openmultipleurl.com.*$  [OR]
                RewriteCond %{HTTP_REFERER} .*pastebin.com.*$
                RewriteCond %{REQUEST_URI} !^/404error$$
                RewriteRule ^(.*)$ http://prod.sfynx.local/404error$                
		  
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

        ErrorLog ${APACHE_LOG_DIR}/error_sfynx_prod.log
        LogFormat "%{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" varnishcombined
        CustomLog ${APACHE_LOG_DIR}/access-prod.log varnishcombined

</VirtualHost>
EOF
sudo mv /tmp/sfynx /etc/apache2/sites-available/

# we create the symbilic link
sudo ln -s /etc/apache2/sites-available/sfynx /etc/apache2/sites-enabled/sfynx

# we add host in the /etc/hosts file
if ! grep -q "dev.sfynx.local" /etc/hosts; then
    echo "Adding QA hostname to your /etc/hosts"
    echo "127.0.0.1    dev.sfynx.local" | sudo tee --append /etc/hosts
    echo "127.0.0.1    test.sfynx.local" | sudo tee --append /etc/hosts
    echo "127.0.0.1    prod.sfynx.local" | sudo tee --append /etc/hosts
fi

# we restart apache server
sudo /etc/init.d/apache2 restart
#!/bin/bash
 
echo "#### Starting"
echo "#### apt-get updating and installing"
sudo apt-get update
sudo apt-get install screen libexpat1-dev libicu-dev git build-essential curl software-properties-common python-software-properties -y
 
echo "#### Installing node"
# Node
. ~/.bashrc
export "PATH=$HOME/local/bin:$PATH"
mkdir -p $HOME/local
mkdir -p $HOME/node-latest-install
 
pushd $HOME/node-latest-install
 curl http://nodejs.org/dist/node-latest.tar.gz | tar xz --strip-components=1
 ./configure --prefix=~/local
 make install
popd
 
sudo ln -s /usr/bin/nodejs /usr/bin/node
 
echo "#### npming nodemon"
sudo apt-get install npm -y
sudo npm install -g nodemon
 
echo "#### installing statsd"
# StatsD
sudo mkdir -p /opt
pushd /opt
 sudo git clone https://github.com/etsy/statsd.git
 cat >> /tmp/localConfig.js << EOF
{
  graphitePort: 2003
, graphiteHost: "127.0.0.1"
, port: 8125
, dumpMessages: false
, debug: false
, backends: ['./backends/graphite']
}
EOF
 
 sudo cp /tmp/localConfig.js /opt/statsd/localConfig.js
popd
 
echo "#### GRAPHITE"
## install prereqs
sudo apt-get update
sudo apt-get upgrade -y
 
sudo apt-get install -y apache2
sudo apt-get install -y erlang-os-mon
sudo apt-get install -y erlang-snmp
sudo apt-get install -y expect
sudo apt-get install -y libapache2-mod-python
sudo apt-get install -y libapache2-mod-wsgi
sudo apt-get install -y memcached
sudo apt-get install -y python-cairo-dev
sudo apt-get install -y python-dev
sudo apt-get install -y python-ldap
sudo apt-get install -y python-memcache
sudo apt-get install -y python-pip
sudo apt-get install -y python-pysqlite2
sudo apt-get install -y sqlite3
 
sudo pip install graphite-web
sudo pip install carbon
sudo pip install whisper
sudo pip install Twisted==11.1.0
sudo pip install django==1.5
sudo pip install django-tagging
 
## setup configuration
sudo cp /opt/graphite/conf/carbon.conf.example /opt/graphite/conf/carbon.conf
sudo cp /opt/graphite/conf/graphite.wsgi.example /opt/graphite/conf/graphite.wsgi
 
sudo tee -a /opt/graphite/conf/storage-schemas.conf > /dev/null << EOF
[stats]
pattern = ^stats.*
retentions = 10s:6h,1min:6d,10min:1800d
EOF
# or:
# sudo cp /opt/graphite/conf/storage-schemas.conf.example /opt/graphite/conf/storage-schemas.conf
 
cat >> /opt/graphite/webapp/graphite/local_settings.py << EOF
DEBUG = True
TIME_ZONE = 'Greenwich'
ALLOWED_HOSTS = [ '*' ]
EOF
# or:
# sudo cp /opt/graphite/webapp/graphite/local_settings.py.example /opt/graphite/webapp/graphite/local_settings.py
 
## setup django super user
cd /opt/graphite/webapp/graphite
sudo python manage.py syncdb --noinput
 
sudo python manage.py createsuperuser --noinput --username "admin" --email "admin@example.com"
expect << DONE
    spawn sudo python manage.py changepassword "admin"
    expect "Password: "
    send -- "LOVELYP@SSWORD!\r"
    expect "Password (again): "
    send -- "LOVELYP@SSWORD!\r"
    expect eof
DONE
 
# start the carbon daemon
sudo /opt/graphite/bin/carbon-cache.py start
 
## Configure Apache
sudo chown -R www-data:www-data /opt/graphite/storage
sudo mkdir -p /etc/httpd/wsgi
 
sudo cat >> /tmp/graphite-vhost.conf << EOF
WSGISocketPrefix /etc/httpd/wsgi
 
<VirtualHost *:80>
        DocumentRoot /opt/graphite/webapp
 
        Header set Access-Control-Allow-Origin "*"
        Header set Access-Control-Allow-Methods "GET, OPTIONS"
        Header set Access-Control-Allow-Headers "origin, authorization, accept"
 
        ErrorLog /opt/graphite/storage/log/webapp/error.log
        CustomLog /opt/graphite/storage/log/webapp/access.log common
 
        # I've found that an equal number of processes & threads tends
        # to show the best performance for Graphite (ymmv).
        WSGIDaemonProcess graphite processes=5 threads=5 display-name='%{GROUP}' inactivity-timeout=120
        WSGIProcessGroup graphite
        WSGIApplicationGroup %{GLOBAL}
        WSGIImportScript /opt/graphite/conf/graphite.wsgi process-group=graphite application-group=%{GLOBAL}
 
        WSGIScriptAlias / /opt/graphite/conf/graphite.wsgi
        Alias /content/ /opt/graphite/webapp/content/
 
        # python -c "from distutils.sysconfig import get_python_lib; print get_python_lib()"
        Alias /media/ "/usr/lib/python2.7/dist-packages/django/contrib/admin/media/"
 
        <Directory /opt/graphite/webapp>
                Options All
                AllowOverride All
                Require all granted
        </Directory>
 
        <Directory /opt/graphite/conf/>
                Options All
                AllowOverride All
                Require all granted
        </Directory>
 
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
 
</VirtualHost>
EOF
sudo mv /tmp/graphite-vhost.conf /etc/apache2/sites-available/
 
## attempt to get the graphite website running
sudo a2dissite default
sudo a2dissite 000-default
 
sudo a2enmod wsgi
sudo a2enmod headers
sudo a2ensite graphite-vhost
 
sudo service apache2 restart
 
echo "**** ALL DONE! ****"
 
# Start statsD
screen nodemon /opt/statsd/stats.js /opt/statsd/localConfig.js

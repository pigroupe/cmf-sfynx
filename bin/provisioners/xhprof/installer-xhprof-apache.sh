#!/bin/bash

HOME_HTTP="/websites"
HTTP_SERVER_NAME="www.xhprof.local"
DATABASE_NAME="xhprof"
if [ -z "$SYMFONY__DATABASE__USER__ENV" ]; then
    DATABASE_TYPE="pdo_mysql"
    DATABASE_HOST="localhost"
    DATABASE_PORT="null"
    DATABASE_USER="root"
    DATABASE_PASS="pacman"
else
    DATABASE_TYPE=$SYMFONY__DATABASE__TYPE__ENV
    DATABASE_HOST=$SYMFONY__DATABASE__HOST__ENV
    DATABASE_PORT=$SYMFONY__DATABASE__PORT__ENV
    DATABASE_USER=$SYMFONY__DATABASE__USER__ENV
    DATABASE_PASS=$SYMFONY__DATABASE__PASSWORD__ENV
fi

#
if [ ! -d $HOME_HTTP ]; then
    sudo mkdir -p $HOME_HTTP
fi
sudo chmod -R 777 $HOME_HTTP

#
cd $HOME_HTTP
git clone git://github.com/preinheimer/xhprof.git xhprof
sudo chmod o+w $HOME_HTTP/xhprof

# we create the xhprof.ini configuration file
sudo mkdir -p /tmp/xhprof
if [ ! -f "/etc/php5/mods-available/xhprof.ini" ]; then
sudo sh -c "cat > /etc/php5/mods-available/xhprof.ini" <<EOF
extension=xhprof.so
xhprof.output_dir=/tmp/xhprof
EOF
fi
# we create the symbilic links
if [ ! -f "/etc/php5/fpm/conf.d/20-xhprof.ini" ]; then
    sudo ln -s /etc/php5/mods-available/xhprof.ini /etc/php5/fpm/conf.d/20-xhprof.ini
fi

# we create the symbilic links
if [ ! -f "/usr/local/etc/php/conf.d/20-xhprof.ini" ]; then
sudo sh -c "cat > /usr/local/etc/php/conf.d/20-xhprof.ini" <<EOF
extension=xhprof.so
xhprof.output_dir=/tmp/xhprof
EOF
fi

# we create the virtualhiost of xhprof for apache
sudo cat <<EOT >/tmp/xhprof
<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	ServerName $HTTP_SERVER_NAME
	DocumentRoot $HOME_HTTP/xhprof/xhprof_html 
	<Directory $HOME_HTTP/xhprof/xhprof_html>
		Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                RewriteEngine On
                Order allow,deny
                allow from all
	</Directory>
	ErrorLog \${APACHE_LOG_DIR}/error.log	
	LogLevel warn
	CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

EOT
sudo mv /tmp/xhprof /etc/apache2/sites-available/
sudo ln -s /etc/apache2/sites-available/xhprof /etc/apache2/sites-enabled/xhprof

# we add host in the /etc/hosts file
if ! grep -q "$HTTP_SERVER_NAME" /etc/hosts; then
    echo "Adding QA hostname to your /etc/hosts"
    echo "127.0.0.1    $HTTP_SERVER_NAME" |sudo tee --append /etc/hosts
fi

##########################################
# Create the xhprof database with MySql
##########################################

sudo cat <<EOT >$HOME_HTTP/xhprof/xhprof-sql.sql
CREATE TABLE `details` (
 `id` char(17) NOT NULL,
 `url` varchar(255) default NULL,
 `c_url` varchar(255) default NULL,
 `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
 `server name` varchar(64) default NULL,
 `perfdata` MEDIUMBLOB,
 `type` tinyint(4) default NULL,
 `cookie` BLOB,
 `post` BLOB,
 `get` BLOB,
 `pmu` int(11) unsigned default NULL,
 `wt` int(11) unsigned default NULL,
 `cpu` int(11) unsigned default NULL,
 `server_id` char(3) NOT NULL default 't11',
 `aggregateCalls_include` varchar(255) DEFAULT NULL,
 PRIMARY KEY  (`id`),
 KEY `url` (`url`),
 KEY `c_url` (`c_url`),
 KEY `cpu` (`cpu`),
 KEY `wt` (`wt`),
 KEY `pmu` (`pmu`),
 KEY `timestamp` (`timestamp`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

EOT

if [[ "$DATABASE_TYPE" == "pdo_mysql" ]]; then
    # Delete the database if already existed
    mysql -u $DATABASE_USER -h $DATABASE_HOST --port=$DATABASE_PORT -p$DATABASE_PASS -Bse "DROP DATABASE IF EXISTS ${DATABASE_NAME};"
    # Create the GitLab CI database
    mysql -u $DATABASE_USER -h $DATABASE_HOST --port=$DATABASE_PORT -p$DATABASE_PASS -Bse "CREATE DATABASE IF NOT EXISTS ${DATABASE_NAME};"
    # Create tables
    mysql -u $DATABASE_USER -h $DATABASE_HOST --port=$DATABASE_PORT -p$DATABASE_PASS $DATABASE_NAME < $HOME_HTTP/xhprof/xhprof-sql.sql
fi
if [[ "$DATABASE_TYPE" == "pdo_pgsql" ]]; then
    # Delete the database if already existed
    createdb -p $DATABASE_PORT -h $DATABASE_HOST -U $DATABASE_USER  -w $DATABASE_PASS "DROP DATABASE IF EXISTS ${DATABASE_NAME};"
    # Create the GitLab CI database
    createdb -p $DATABASE_PORT -h $DATABASE_HOST -U $DATABASE_USER  -w $DATABASE_PASS "CREATE DATABASE IF NOT EXISTS ${DATABASE_NAME};"
    # Create tables
    createdb -p $DATABASE_PORT -h $DATABASE_HOST -U $DATABASE_USER  -w $DATABASE_PASS -e $DATABASE_NAME < $HOME_HTTP/xhprof/xhprof-sql.sql
fi

# we install graphviz
sudo apt-get -y install graphviz
sudo apt-get clean

# we create the config file
sudo cat <<EOT >$HOME_HTTP/xhprof/xhprof_lib/config.php
<?php
\$_xhprof = array();

// Change these:
\$_xhprof['dbtype'] = $DATABASE_TYPE; // Only relevant for PDO
\$_xhprof['dbhost'] = $DATABASE_HOST;
\$_xhprof['dbuser'] = $DATABASE_USER;
\$_xhprof['dbpass'] = $DATABASE_PASS;
\$_xhprof['dbname'] = $DATABASE_NAME;
\$_xhprof['dbadapter'] = 'Pdo';
\$_xhprof['servername'] = 'localhost';
\$_xhprof['namespace'] = 'localhost';
\$_xhprof['url'] = "http://$HTTP_SERVER_NAME";

/*
 * MySQL/MySQLi/PDO ONLY
 * Switch to JSON for better performance and support for larger profiler data sets.
 * WARNING: Will break with existing profile data, you will need to TRUNCATE the profile data table.
 */
\$_xhprof['serializer'] = 'php'; 

//Uncomment one of these, platform dependent. You may need to tune for your specific environment, but they're worth a try

//These are good for Windows
/*
\$_xhprof['dot_binary']  = 'C:\\Programme\\Graphviz\\bin\\dot.exe';
\$_xhprof['dot_tempdir'] = 'C:\\WINDOWS\\Temp';
\$_xhprof['dot_errfile'] = 'C:\\WINDOWS\\Temp\\xh_dot.err';
*/

//These are good for linux and its derivatives.
\$_xhprof['dot_binary']  = '/usr/bin/dot';
\$_xhprof['dot_tempdir'] = '/tmp';
\$_xhprof['dot_errfile'] = '/tmp/xh_dot.err';

\$ignoreURLs = array();

\$ignoreDomains = array();

\$exceptionURLs = array();

\$exceptionPostURLs = array();
\$exceptionPostURLs[] = "login";

\$_xhprof['display'] = true;
\$_xhprof['doprofile'] = true;

//Control IPs allow you to specify which IPs will be permitted to control when profiling is on or off within your application, and view the results via the UI.
\$controlIPs = false; //Disables access controlls completely. 
//\$controlIPs = array();
//\$controlIPs[] = "127.0.0.1";   // localhost, you'll want to add your own ip here
//\$controlIPs[] = "::1";         // localhost IP v6

//\$otherURLS = array();

// ignore builtin functions and call_user_func* during profiling
//\$ignoredFunctions = array('call_user_func', 'call_user_func_array', 'socket_select');

//Default weight - can be overidden by an Apache environment variable 'xhprof_weight' for domain-specific values
\$weight = 100;

if(\$domain_weight = getenv('xhprof_weight')) {
	$weight = \$domain_weight;
}

unset(\$domain_weight);

  /**
  * The goal of this function is to accept the URL for a resource, and return a "simplified" version
  * thereof. Similar URLs should become identical. Consider:
  * http://example.org/stories.php?id=2323
  * http://example.org/stories.php?id=2324
  * Under most setups these two URLs, while unique, will have an identical execution path, thus it's
  * worthwhile to consider them as identical. The script will store both the original URL and the
  * Simplified URL for display and comparison purposes. A good simplified URL would be:
  * http://example.org/stories.php?id=
  * 
  * @param string $url The URL to be simplified
  * @return string The simplified URL 
  */
  function _urlSimilartor(\$url)
  {
      //This is an example 
      \$url = preg_replace("!\d{4}!", "", \$url);
      
      // For domain-specific configuration, you can use Apache setEnv xhprof_urlSimilartor_include [some_php_file]
      if(\$similartorinclude = getenv('xhprof_urlSimilartor_include')) {
      	require_once(\$similartorinclude);
      }
      
      \$url = preg_replace("![?&]_profile=\d!", "", \$url);
      return \$url;
  }
  
  function _aggregateCalls(\$calls, \$rules = null)
  {
    \$rules = array(
        'Loading' => 'load::',
        'mysql' => 'mysql_'
    );

    // For domain-specific configuration, you can use Apache setEnv xhprof_aggregateCalls_include [some_php_file]
    if(isset(\$run_details['aggregateCalls_include']) && strlen(\$run_details['aggregateCalls_include']) > 1)
    {
    	require_once(\$run_details['aggregateCalls_include']);
    }        
        
    \$addIns = array();
    foreach(\$calls as \$index => \$call)
    {
        foreach(\$rules as \$rule => \$search)
        {
            if (strpos(\$call['fn'], \$search) !== false)
            {
                if (isset(\$addIns[\$search]))
                {
                    unset(\$call['fn']);
                    foreach(\$call as \$k => \$v)
                    {
                        \$addIns[\$search][\$k] += \$v;
                    }
                } else {
                    \$call['fn'] = \$rule;
                    \$addIns[\$search] = \$call;
                }
                unset(\$calls[\$index]);  //Remove it from the listing
                break;  //We don't need to run any more rules on this
            } else {
                //echo "nomatch for $search in {$call['fn']}<br />\n";
            }
        }
    }
    return array_merge(\$addIns, \$calls);
  }
EOT
 
# In virualhost add the following lines
#     php_value auto_prepend_file "/var/www/xhprof/external/header.php"
#     php_value auto_append_file "/var/www/xhprof/external/footer.php"
# launch xhprof like this ::  http://www.myproject.com?_profile=1

#
sudo cat > $HOME_HTTP/xhprof/header.php <<EOF
<?php
if (
    (
        (isset(\$_GET['XHPROF']) && \$_GET['XHPROF'])
        || (isset(\$_POST['XHPROF']) && \$_POST['XHPROF'])
        || (isset(\$_COOKIE['XHPROF']) && \$_COOKIE['XHPROF'])
    )
    && extension_loaded('xhprof')) {
    require_once 'xhprof_lib/utils/xhprof_lib.php';
    require_once 'xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}
EOF

sudo cat > $HOME_HTTP/xhprof/footer.php <<EOF
<?php
if (
    (
        (isset(\$_GET['XHPROF']) && \$_GET['XHPROF'])
        || (isset(\$_POST['XHPROF']) && \$_POST['XHPROF'])
        || (isset(\$_COOKIE['XHPROF']) && \$_COOKIE['XHPROF'])
    )
    && extension_loaded('xhprof')) {
    \$profiler_namespace = 'myapp';  // namespace for your application
    \$xhprof_data = xhprof_disable();
    \$xhprof_runs = new XHProfRuns_Default();
    \$run_id = \$xhprof_runs->save_run(\$xhprof_data, \$profiler_namespace);
 
    // url to the XHProf UI libraries (change the host name and path)
    \$profiler_url = sprintf('http://%s/xhprof/xhprof_html/index.php?run=%s&source=%s', \$_SERVER['HTTP_HOST'], \$run_id, \$profiler_namespace);
    echo '<a href="'. \$profiler_url .'" target="_blank">Profiler output</a>';
}
EOF

sudo cat > $HOME_HTTP/xhprof/.htaccess <<EOF
php_value auto_prepend_file $HOME_HTTP/xhprof/header.php
php_value auto_append_file $HOME_HTTP/xhprof/footer.php
EOF

#
sudo chown -R www-data:www-data $HOME_HTTP/xhprof

#
sudo /etc/init.d/apache2 restart
sudo service php5-fpm restart

# test
php -m |grep xhprof

# add in the virtual host of the project that you want to profile
# fastcgi_param PHP_VALUE "auto_prepend_file=/var/www/xhprof/external/header.
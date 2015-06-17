#!/bin/bash

DIR=$1
HOME_HTTP=$2

#
if [ ! -d $HOME_HTTP ]; then
    mkdir -p $HOME_HTTP
fi

#
cd $HOME_HTTP
git clone git://github.com/preinheimer/xhprof.git xhprof
sudo chmod o+w $HOME_HTTP/xhprof

# we create the xhprof.ini configuration file
if [ -q "/etc/php5/mods-available/xhprof.ini" ]; then
sudo sh -c "cat > /etc/php5/mods-available/xhprof.ini" <<EOF
extension=xhprof.so
xhprof.output_dir=/tmp
EOF
fi

# we create the symbilic links
if [ -q "/etc/php5/cli/conf.d/20-xhprof.ini" ]; then
    sudo ln -s /etc/php5/mods-available/xhprof.ini /etc/php5/cli/conf.d/20-xhprof.ini
fi

if [ -q "/etc/php5/fpm/conf.d/20-xhprof.ini" ]; then
    sudo ln -s /etc/php5/mods-available/xhprof.ini /etc/php5/fpm/conf.d/20-xhprof.ini
fi

# we create the virtualhiost of xhprof for apache
sudo cat <<EOT >/tmp/xhprof
server {
    listen 80;

    # Server name being used (exact name, wildcards or regular expression)
    server_name www.xhprof.local;

    # Document root, make sure this points to your Symfony2 /web directory
    root $HOME_HTTP/xhprof/xhprof_html;

    location / {
        index index.php;
        try_files \$uri \$uri/ /index.php?\$args;
    }

    # charset
    charset utf-8;

    location ~ /\. {
        error_log  /var/log/nginx/xhprof-error.log;
        access_log  /var/log/nginx/xhprof-access.log;
        log_not_found off;
        deny all;
    }

    # Pass the PHP scripts to FastCGI server
    location ~ \.php\$ {
        fastcgi_pass php5-fpm-sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)\$;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param  HTTPS off;
    }
}
EOT
sudo mv /tmp/xhprof /etc/nginx/sites-available/xhprof
sudo ln -s /etc/nginx/sites-available/xhprof /etc/nginx/sites-enabled/xhprof

# we add host in the /etc/hosts file
if ! grep -q "www.xhprof.local" /etc/hosts; then
    echo "Adding QA hostname to your /etc/hosts"
    echo "127.0.0.1    www.xhprof.local" | sudo tee --append /etc/hosts
fi

##########################################
# Create the xhprof database with MySql
##########################################

sudo cat > $HOME_HTTP/xhprof/xhprof-sql.sql << 'EOF'
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
EOF

#
MyUSER="root"
MyPASS="pacman"
HostName="localhost"
dbName="xhprof"

# Delete the database if already existed
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "DROP DATABASE IF EXISTS ${dbName};"
# Create the GitLab CI database
mysql -u $MyUSER -h $HostName -p$MyPASS -Bse "CREATE DATABASE IF NOT EXISTS ${dbName};"
# Create tables
mysql -u $MyUSER -h $HostName -p$MyPASS ${dbName} < $HOME_HTTP/xhprof/xhprof-sql.sql

# we install graphviz
sudo apt-get -y install graphviz
sudo apt-get clean

# we create the config file
sudo cat > $HOME_HTTP/xhprof/xhprof_lib/config.php << 'EOF'
<?php
$_xhprof = array();

// Change these:
$_xhprof['dbtype'] = 'mysql'; // Only relevant for PDO
$_xhprof['dbhost'] = 'localhost';
$_xhprof['dbuser'] = 'root';
$_xhprof['dbpass'] = 'pacman';
$_xhprof['dbname'] = 'xhprof';
$_xhprof['dbadapter'] = 'Pdo';
$_xhprof['servername'] = 'localhost';
$_xhprof['namespace'] = 'http://www.xhprof.local';
$_xhprof['url'] = 'http://www.xhprof.local';

/*
 * MySQL/MySQLi/PDO ONLY
 * Switch to JSON for better performance and support for larger profiler data sets.
 * WARNING: Will break with existing profile data, you will need to TRUNCATE the profile data table.
 */
$_xhprof['serializer'] = 'php'; 

//Uncomment one of these, platform dependent. You may need to tune for your specific environment, but they're worth a try

//These are good for Windows
/*
$_xhprof['dot_binary']  = 'C:\\Programme\\Graphviz\\bin\\dot.exe';
$_xhprof['dot_tempdir'] = 'C:\\WINDOWS\\Temp';
$_xhprof['dot_errfile'] = 'C:\\WINDOWS\\Temp\\xh_dot.err';
*/

//These are good for linux and its derivatives.

$_xhprof['dot_binary']  = '/usr/bin/dot';
$_xhprof['dot_tempdir'] = '/tmp';
$_xhprof['dot_errfile'] = '/tmp/xh_dot.err';


$ignoreURLs = array();

$ignoreDomains = array();

$exceptionURLs = array();

$exceptionPostURLs = array();
$exceptionPostURLs[] = "login";


$_xhprof['display'] = true;
$_xhprof['doprofile'] = true;

//Control IPs allow you to specify which IPs will be permitted to control when profiling is on or off within your application, and view the results via the UI.
//$controlIPs = false; //Disables access controlls completely. 
$controlIPs = array();
$controlIPs[] = "127.0.0.1";   // localhost, you'll want to add your own ip here
$controlIPs[] = "::1";         // localhost IP v6

//$otherURLS = array();

// ignore builtin functions and call_user_func* during profiling
//$ignoredFunctions = array('call_user_func', 'call_user_func_array', 'socket_select');

//Default weight - can be overidden by an Apache environment variable 'xhprof_weight' for domain-specific values
$weight = 100;

if($domain_weight = getenv('xhprof_weight')) {
	$weight = $domain_weight;
}

unset($domain_weight);

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
  function _urlSimilartor($url)
  {
      //This is an example 
      $url = preg_replace("!\d{4}!", "", $url);
      
      // For domain-specific configuration, you can use Apache setEnv xhprof_urlSimilartor_include [some_php_file]
      if($similartorinclude = getenv('xhprof_urlSimilartor_include')) {
      	require_once($similartorinclude);
      }
      
      $url = preg_replace("![?&]_profile=\d!", "", $url);
      return $url;
  }
  
  function _aggregateCalls($calls, $rules = null)
  {
    $rules = array(
        'Loading' => 'load::',
        'mysql' => 'mysql_'
        );

    // For domain-specific configuration, you can use Apache setEnv xhprof_aggregateCalls_include [some_php_file]
  	if(isset($run_details['aggregateCalls_include']) && strlen($run_details['aggregateCalls_include']) > 1)
		{
    	require_once($run_details['aggregateCalls_include']);
		}        
        
    $addIns = array();
    foreach($calls as $index => $call)
    {
        foreach($rules as $rule => $search)
        {
            if (strpos($call['fn'], $search) !== false)
            {
                if (isset($addIns[$search]))
                {
                    unset($call['fn']);
                    foreach($call as $k => $v)
                    {
                        $addIns[$search][$k] += $v;
                    }
                }else
                {
                    $call['fn'] = $rule;
                    $addIns[$search] = $call;
                }
                unset($calls[$index]);  //Remove it from the listing
                break;  //We don't need to run any more rules on this
            }else
            {
                //echo "nomatch for $search in {$call['fn']}<br />\n";
            }
        }
    }
    return array_merge($addIns, $calls);
  }
EOF

 
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

sudo cat > $HOME_HTTP/xhprof/.htaccess << 'EOF'
php_value auto_prepend_file $HOME_HTTP/xhprof/header.php
php_value auto_append_file $HOME_HTTP/xhprof/footer.php
EOF

#
sudo chown -R www-data:www-data $HOME_HTTP/xhprof

# we restart nginx server
sudo /etc/init.d/nginx restart

# test
php -m | grep xhprof

# add in the virtual host of the project that you want to profile
# fastcgi_param PHP_VALUE "auto_prepend_file=/var/www/xhprof/external/header.php \n auto_append_file=/var/www/xhprof/external/footer.php";
<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * phpMyAdmin sample configuration, you can use it as base for
 * manual configuration. For easier setup you can use setup/
 *
 * All directives are explained in documentation in the doc/ folder
 * or at <http://docs.phpmyadmin.net/>.
 *
 * @package PhpMyAdmin
 */

/**
 * This is needed for cookie based authentication to encrypt password in
 * cookie
 */
$cfg['blowfish_secret'] = getenv('BLOWFISH_SECRET'); /* YOU MUST FILL IN THIS FOR COOKIE AUTH! */

/**
 * all servers
 */
$data_hosts = explode('|', getenv('MYSQL_HOST'));
$data_ports= explode('|', getenv('MYSQL_PORT'));
foreach($data_hosts as $i => $v) {
    /* Authentication type */
    $cfg['Servers'][$i]['auth_type'] = 'cookie';
    /* Server parameters */
    $cfg['Servers'][$i]['host'] = $data_hosts[$i];
    $cfg['Servers'][$i]['port'] = $data_ports[$i];
    $cfg['Servers'][$i]['connect_type'] = 'tcp';
    $cfg['Servers'][$i]['compress'] = false;
    $cfg['Servers'][$i]['AllowNoPassword'] = false;

    $cfg['UploadDir'] = '';
    $cfg['SaveDir'] = '';
}



<?php
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\ClassLoader\ApcUniversalClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
//if (extension_loaded('apc')) {
//    $loader = new ApcUniversalClassLoader('sfynx23', $loader);
//    $loader->register(true);
//
//    //apc_clear_cache();
//    //
//    //# clear user cache
//    //apc_clear_cache('user');
//    //
//    //# clear opcode cache
//    //apc_clear_cache('opcode'); 
//    //
//    //# clear sf2 cache
//    //apc_clear_cache('sfynx23'); 
//    //
//    //var_dump(realpath_cache_get());
//}
 
//  sudo varnishadm "ban req.http.host ~ www.sfynx.local/"
 
//$m = new \Memcached();
//$m->addServer('localhost', 11211);
//
//// invalide tous les éléments dans 0 secondes
//$m->flush(0);

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
/*if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}*/


if(preg_match("/app_test.php/",$_SERVER['REQUEST_URI']) 
        || preg_match("/app.php/",$_SERVER['REQUEST_URI'] )
) {
    header('Location: /');
} else {
    Debug::enable();

    $kernel = new AppKernel('test', true);
    $kernel->loadClassCache();
    $kernel = new AppCache($kernel); // si Appcache activé, activer alors aussi Esi dans config.yml
    Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}

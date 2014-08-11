<?php
/*
 use Symfony\Component\ClassLoader\ApcClassLoader;
*/
use Symfony\Component\HttpFoundation\Request;
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
*/

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

if(preg_match("/app_dev.php/",$_SERVER['REQUEST_URI']) || preg_match("/app.php/",$_SERVER['REQUEST_URI'] )) {
    header('Location: /');
} else {
    $kernel = new AppKernel('prod', false);
    $kernel->loadClassCache();
	$kernel = new AppCache($kernel); // si Appcache activé, activer alors aussi Esi dans config.yml
    Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}
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

//         $PROXY_HOST = "proxy.example.com"; // Proxy server address
//         $PROXY_PORT = "1234";    // Proxy server port
//         $PROXY_USER = "LOGIN";    // Username
//         $PROXY_PASS = "PASSWORD";   // Password
//         // Username and Password are required only if your proxy server needs basic authentication
//         $auth = base64_encode("$PROXY_USER:$PROXY_PASS");
//         stream_context_set_default(
//          array(
//           'http' => array(
//            'proxy' => "tcp://$PROXY_HOST:$PROXY_PORT",
//            'request_fulluri' => true,
//            'header' => "Proxy-Authorization: Basic $auth"
//            // Remove the 'header' option if proxy authentication is not required
//           )
//          )
//         );

if(preg_match("/app_dev.php/",$_SERVER['REQUEST_URI']) || preg_match("/app.php/",$_SERVER['REQUEST_URI'] )) {
    header('Location: /');
} else {
    $kernel = new AppKernel('prod', false);
    $kernel->loadClassCache();
	$kernel = new AppCache($kernel);
    Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}

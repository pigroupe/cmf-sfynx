=====================
Symfony 3 Study Guide
=====================


The Request object
===============

properties :

+--------------------------------------+--------------------------------------+
| query                                | $\_GET                               |
+--------------------------------------+--------------------------------------+
| request                              | $\_POST                              |
+--------------------------------------+--------------------------------------+
| cookies                              | $\_COOKIES                           |
+--------------------------------------+--------------------------------------+
| attributes                           | no equivalent                        |
+--------------------------------------+--------------------------------------+
| files                                | $\_FILES                             |
+--------------------------------------+--------------------------------------+
| headers                              | $\_SERVER header                     |
+--------------------------------------+--------------------------------------+
| server                               | $\_SERVER                            |
+--------------------------------------+--------------------------------------+

This properties are parametersBag and contains this methods :

+--------------------------------------+--------------------------------------+
| all()                                | return all parameters                |
+--------------------------------------+--------------------------------------+
| key()                                | return keys of parameters            |
+--------------------------------------+--------------------------------------+
| replace()                            | replace a parameter                  |
+--------------------------------------+--------------------------------------+
| add()                                | add a parameter                      |
+--------------------------------------+--------------------------------------+
| get()                                | return a parameter                   |
+--------------------------------------+--------------------------------------+
| set()                                | declare a parameter                  |
+--------------------------------------+--------------------------------------+
| has()                                | verify if a parameter exists         |
+--------------------------------------+--------------------------------------+
| remove()                             | delete a parameter                   |
+--------------------------------------+--------------------------------------+

parameterBag have filter methods too :

+--------------------------------------+--------------------------------------+
| getAlpha()                           | return alpha chars                   |
+--------------------------------------+--------------------------------------+
| getAlphaNum()                        | return alpha numeric chars           |
+--------------------------------------+--------------------------------------+
| getBoolean()                         | return the parameter casted in       |
|                                      | boolean                              |
+--------------------------------------+--------------------------------------+
| getDigit()                           | return numbers of parameter          |
+--------------------------------------+--------------------------------------+
| getInt()                             | return the parameter casted in INT   |
+--------------------------------------+--------------------------------------+
| Filter()                             | Filter the parameter with            |
|                                      | filer\_var()                         |
+--------------------------------------+--------------------------------------+

All getters take until 3 arguments :

-  name of parameter
-  default value

Methods of Request object

+--------------------------------------+--------------------------------------+
| getContent()                         | return Raw Data of the request       |
+--------------------------------------+--------------------------------------+
| getPathInfo()                        | return path of the request           |
|                                      | “/my/web/ur”                         |
+--------------------------------------+--------------------------------------+
| create(url,method,parameters)        | Permit to simulate a request         |
|                                      |                                      |
|                                      | Arguments:                           |
|                                      |                                      |
|                                      | -  Url : Url of the request          |
|                                      | -  Method : verb of the request     |
|                                      | -  Parameters : parameters array     |
|                                      |                                      |
+--------------------------------------+--------------------------------------+
| overrideGlobals()                    | Permit to erase global parameter with|
|                                      | a request created manualy or modified|
|                                      | manuellement ou des parametres       |
+--------------------------------------+--------------------------------------+
| duplicate()                          | Duplicate a request                  |
+--------------------------------------+--------------------------------------+
| initialize()                         | Load a parameters group              |
+--------------------------------------+--------------------------------------+
| getSession()                         | get the Session                      |
+--------------------------------------+--------------------------------------+
| hasPreviousSession()                 | return true if the request has a     |
|                                      | opened session                       |
+--------------------------------------+--------------------------------------+
| getAcceptableContentTypes()          | return the accepted content types    |
|                                      | ordered by prioroity                 |
+--------------------------------------+--------------------------------------+
| getAcceptableLanguages()             | return the accepted languages ordered|
|                                      | by priority                          |
+--------------------------------------+--------------------------------------+
| getCharsets()                        | return the list of accepted charset  |
|                                      | ordered by priority                  |
+--------------------------------------+--------------------------------------+
| getEncodings()                       | return accepted encodings ordered by |
|                                      | priority                             |
+--------------------------------------+--------------------------------------+


The class AcceptHeader permit to retrieve all Accept header once :

+--------------------------------------------------------------------------+
| $accept=AcceptHeader::fromString($request->headers->get('Accept'));      |
|                                                                          |
| | if ($accept->has('text/html')) {                                       |
| |     $item = $accept->get('text/html');                                 |
| |     $charset = $item->getAttribute('charset', 'utf-8');                |
| |     $quality = $item->getQuality();                                    |
| | }                                                                      |
+--------------------------------------------------------------------------+

Define a Request Factory :

+--------------------------------------------------------------------------+
| Request::setFactory() Parameters are array for  \_GET,\_POST |
| etc                                                                      |
+--------------------------------------------------------------------------+

Then the calls to Request::createFromGlobals() will use this factory

The response object
================

The constructor take 3 arguments :

-  The data
-  The status code
-  The Headers

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\HttpFoundation\\Response;                      |
| | $response = new Response(                                              |
| |     'Content',                                                         |
| |     Response::HTTP\_OK,                                                |
| |     array('content-type' => 'text/html')                               |
| | );                                                                     |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Methods of Response class :

+--------------------------------------+--------------------------------------+
| setContent()                         | Define the content                   |
+--------------------------------------+--------------------------------------+
| headers->set()                       | Define a  header                     |
+--------------------------------------+--------------------------------------+
| setStatusCode()                      | Define the status code               |
+--------------------------------------+--------------------------------------+
| setCharset()                         | Define the charset                   |
+--------------------------------------+--------------------------------------+
| prepare()                            | Prepare the reponse to make it       |
|                                      | compatible with HTTP                 |
+--------------------------------------+--------------------------------------+
| send()                               | Send the response                    |
+--------------------------------------+--------------------------------------+
| headers->setCookie(new Cookie('foo', | Define cookie                        |
| 'bar'))                              |                                      |
|                                      |                                      |
                                                                             
+--------------------------------------+--------------------------------------+
| clearCookie()                        | Delete all cookies                   |
+--------------------------------------+--------------------------------------+

The Response class have methods to manipulate HTTP cacge :

+--------------------------------------+--------------------------------------+
| setPublic()                          | Cached by proxies                    |
+--------------------------------------+--------------------------------------+
| setPrivate()                         | Not cached by proxies                |
+--------------------------------------+--------------------------------------+
| expire()                             | Expire maximum                       |
+--------------------------------------+--------------------------------------+
| setExpires(DateTime)                 | Set expire date of the Response      |
+--------------------------------------+--------------------------------------+
| setMAxAge(secondes)                  | Set the time after the Response is   |
|                                      | unvaiable                            |
|                                      |                                      |
|                                      | -  Priority on Expires               |
+--------------------------------------+--------------------------------------+
| setSharedMaxAge(secondes)            | Set the time after the response is   |
|                                      | unvaiable                            |
|                                      |                                      |
|                                      |  -  Priority on Expires              |
+--------------------------------------+--------------------------------------+
| setTtl(seconds)                      | Set The Time To Live                 |
+--------------------------------------+--------------------------------------+
| setClientTtl(seconds)                | Set Time To Live for a private       |
|                                      | response                             |
+--------------------------------------+--------------------------------------+
| setLastModified()                    | Specify the last modified date       |
+--------------------------------------+--------------------------------------+
| setEtag()                            | Specify the Etag                     |
+--------------------------------------+--------------------------------------+
| setVAry()                            | Specify the Vary Header              |
+--------------------------------------+--------------------------------------+
| setCache(array d’header)             | Specify all cache parameter at ounce |
+--------------------------------------+--------------------------------------+
| isNotModified()                      | Verify that head or request and      |
|                                      | response coincide                    |
+--------------------------------------+--------------------------------------+

The RedirectResponse class
--------------------------

Permit to redirect user.

+--------------------------------------------------------------------------+
| $response = new                                                          |
| RedirectResponse('`http://example.com/ <https://www.google.com/url?q=htt |
| p://example.com/&sa=D&ust=1458042068882000&usg=AFQjCNEV_vxcUAEqPdObRE8Rv |
| uj2ZtPeBA>`__\ ');                                                       |
|                                                                          |
| $response->send();                                                       |
+--------------------------------------------------------------------------+

The StreamedResponse class
--------------------------

Permit to give a callback function to the response.

+--------------------------------------------------------------------------+
| | $response = new StreamedResponse();                                    |
| | $response->setCallback(function () {                                   |
| |     var\_dump('Hello World');                                          |
| |     flush();                                                           |
| |     sleep(2);                                                          |
| |     var\_dump('Hello World');                                          |
| |     flush();                                                           |
| | });                                                                    |
| | $response->send();                                                     |
+--------------------------------------------------------------------------+

Deliver file
-------------------

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\HttpFoundation\\ResponseHeaderBag;             |
| | $d = $response->headers->makeDisposition(                              |
| |     ResponseHeaderBag::DISPOSITION\_ATTACHMENT,                        |
| |     'foo.pdf'                                                          |
| | );                                                                     |
| | $response->headers->set('Content-Disposition', $d);                    |
+--------------------------------------------------------------------------+

Deliver static file
--------------------------

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\HttpFoundation\\BinaryFileResponse;            |
| | $file = 'path/to/file.txt';                                            |
| | $response = new BinaryFileResponse($file);                             |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Create a Json Response
----------------------

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\HttpFoundation\\Response;                      |
| | $response = new Response();                                            |
| | $response->setContent(json\_encode(array(                              |
| |     'data' => 123,                                                     |
| | )));                                                                   |
| | $response->headers->set('Content-Type', 'application/json');           |
+--------------------------------------------------------------------------+

The JsonResponse class

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\HttpFoundation\\JsonResponse;                  |
| | $response = new JsonResponse();                                        |
| | $response->setData(array(                                              |
| |     'data' => 123                                                      |
| | ));                                                                    |
+--------------------------------------------------------------------------+

JsonResponse methods :

+--------------------------------------+--------------------------------------+
| setCallBack()                        | specify the json callback function   |
+--------------------------------------+--------------------------------------+

The symfony component
======================

The asset component
-----------------


Handle Url and versioning of picture, js, and css files

The package Class
~~~~~~~~~~~~~~~~~

The package class permit to version, to parameter the path by default and to handle url of CDN resources

Methods of Package Class

+--------------------------------------+--------------------------------------+
| getVersion()                         | return the version number of a       |
|                                      | resource                             |
+--------------------------------------+--------------------------------------+
| getUrl()                             | return the url of a resource         |
+--------------------------------------+--------------------------------------+

The assets versionning
~~~~~~~~~~~~~~~~~~~~~~~

The EmptyVersionStrategy strategy
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This strategy don't add assets versioning.

The StaticVersionStrategy stratégy
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Asset\\Package;                                |
| | use Symfony\\Component\\Asset\\VersionStrategy\\StaticVersionStrategy; |
| | $package = new Package(new StaticVersionStrategy('v1'));               |
| | echo $package->getUrl('/image.png');                                   |
| | // result: /image.png?v1                                               |
|                                                                          |
| | // put the 'version' word before the version value                     |
| | $package = new Package(new StaticVersionStrategy('v1',                 |
| '%s?version=%s'));                                                       |
| | echo $package->getUrl('/image.png');                                   |
| | // result: /image.png?version=v1                                       |
| | // put the asset version before its path                               |
| | $package = new Package(new StaticVersionStrategy('v1', '%2$s/%1$s'));  |
| | echo $package->getUrl('/image.png');                                   |
| | // result: /v1/image.png                                               |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Define our own strategy with StaticVersionInterface Interface
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+--------------------------------------------------------------------------+
| | use                                                                    |
| Symfony\\Component\\Asset\\VersionStrategy\\VersionStrategyInterface;    |
| | class DateVersionStrategy implements VersionStrategyInterface          |
| | {                                                                      |
| |     private $version;                                                  |
| |     public function \_\_construct()                                    |
| |     {                                                                  |
| |         $this->version = date('Ymd');                                  |
| |     }                                                                  |
| |     public function getVersion($path)                                  |
| |     {                                                                  |
| |         return $this->version;                                         |
| |     }                                                                  |
| |     public function applyVersion($path)                                |
| |     {                                                                  |
| |         return sprintf('%s?v=%s', $path, $this->getVersion($path));    |
| |     }                                                                  |
| | }                                                                      |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Use of PathPackage class for assets saved in a shared directory
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This class permit to add a path before path of assets

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Asset\\PathPackage;                            |
| | // ...                                                                 |
| | $package = new PathPackage('/static/images', new                       |
| StaticVersionStrategy('v1'));                                            |
| | echo $package->getUrl('/logo.png');                                    |
| | // result: /static/images/logo.png?v1                                  |
+--------------------------------------------------------------------------+

The class UrlPackage for assets hosted on a CDN
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Asset\\UrlPackage;                             |
| | // ...                                                                 |
| | $urls = array(                                                         |
| |     '//static1.example.com/images/',                                   |
| |     '//static2.example.com/images/',                                   |
| | );                                                                     |
| | $package = new UrlPackage($urls, new StaticVersionStrategy('v1'));     |
|                                                                          |
| |                                                                        |
| | echo $package->getUrl('/logo.png');                                    |
| | // result: http://static.example.com/images/logo.png?v1                |
+--------------------------------------------------------------------------+

Named Packages
^^^^^^^^^^^^^^^^^^^

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Asset\\Package;                                |
| | use Symfony\\Component\\Asset\\PathPackage;                            |
| | use Symfony\\Component\\Asset\\UrlPackage;                             |
| | use Symfony\\Component\\Asset\\Packages;                               |
| | // ...                                                                 |
| | $versionStrategy = new StaticVersionStrategy('v1');                    |
| | $defaultPackage = new Package($versionStrategy);                       |
| | $namedPackages = array(                                                |
| |     'img' => new UrlPackage('http://img.example.com/',                 |
| $versionStrategy),                                                       |
| |     'doc' => new PathPackage('/somewhere/deep/for/documents',          |
| $versionStrategy),                                                       |
| | );                                                                     |
| | $packages = new Packages($defaultPackage, $namedPackages)              |
|                                                                          |
| | echo $packages->getUrl('/main.css');                                   |
| | // result: /main.css?v1                                                |
| | echo $packages->getUrl('/logo.png', 'img');                            |
| | // result: http://img.example.com/logo.png?v1                          |
| | echo $packages->getUrl('/resume.pdf', 'doc');                          |
| | // result: /somewhere/deep/for/documents/resume.pdf?v1                 |
+--------------------------------------------------------------------------+

The BrowserKit component
-----------------------

This component permit to simulate a web browser creating request, clicking link and form buttons.
We need to create a client which implements the function doRequest and extends baseclient.

The doRequest method must return a Response object.

+--------------------------------------------------------------------------+
| | namespace Acme;                                                        |
| | use Symfony\\Component\\BrowserKit\\Client as BaseClient;              |
| | use Symfony\\Component\\BrowserKit\\Response;                          |
| | class Client extends BaseClient                                        |
| | {                                                                      |
| |     protected function doRequest($request)                             |
| |     {                                                                  |
| |         // ... convert request into a response                         |
| |         return new Response($content, $status, $headers);              |
| |     }                                                                  |
| | }                                                                      |
+--------------------------------------------------------------------------+

Create Request
~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Acme\\Client;                                                      |
| | $client = new Client();                                                |
| | $crawler = $client->request('GET', 'http://symfony.com');              |
+--------------------------------------------------------------------------+

Click on a link
~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | $client = new Client();                                                |
| | $crawler = $client->request('GET', 'http://symfony.com');              |
| | $link = $crawler->selectLink('Go elsewhere...')->link();               |
| | $client->click($link);                                                 |
+--------------------------------------------------------------------------+

Submit forms
~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Acme\\Client;                                                      |
| | // make a real request to an external site                             |
| | $client = new Client();                                                |
| | $crawler = $client->request('GET', 'https://github.com/login');        |
| | // select the form and fill in some values                             |
| | $form = $crawler->selectButton('Log in')->form();                      |
| | $form['login'] = 'symfonyfan';                                         |
| | $form['password'] = 'anypass';                                         |
| | // submit that form                                                    |
| | $crawler = $client->submit($form);                                     |
+--------------------------------------------------------------------------+

The ClassLoader component
------------------------

The ClassLoaderComponent permit to load automaticaly classes and to put them in a cache to improve performance.

The PSR-0 class loader
~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | require\_once                                                          |
| '/path/to/src/Symfony/Component/ClassLoader/ClassLoader.php';            |
| | use Symfony\\Component\\ClassLoader\\ClassLoader;                      |
| | $loader = new ClassLoader();                                           |
| | // to enable searching the include path (eg. for PEAR packages)        |
| | $loader->setUseIncludePath(true);                                      |
| | // register a single namespaces                                        |
| | $loader->addPrefix('Symfony',                                          |
| \_\_DIR\_\_.'/vendor/symfony/symfony/src');                              |
| | // register several namespaces at once                                 |
| | $loader->addPrefixes(array(                                            |
| |     'Symfony' => \_\_DIR\_\_.'/../vendor/symfony/symfony/src',         |
| |     'Monolog' => \_\_DIR\_\_.'/../vendor/monolog/monolog/src',         |
| | ));                                                                    |
| | // register a prefix for a class following the PEAR naming conventions |
| | $loader->addPrefix('Twig\_', \_\_DIR\_\_.'/vendor/twig/twig/lib');     |
| | $loader->addPrefixes(array(                                            |
| |     'Swift\_' =>                                                       |
| \_\_DIR\_\_.'/vendor/swiftmailer/swiftmailer/lib/classes',               |
| |     'Twig\_'  => \_\_DIR\_\_.'/vendor/twig/twig/lib',                  |
| | ));                                                                    |
|                                                                          |
| | $loader->addPrefixes(array(                                            |
| |     'Doctrine\\\\Common'           =>                                  |
| \_\_DIR\_\_.'/vendor/doctrine/common/lib',                               |
| |     'Doctrine\\\\DBAL\\\\Migrations' =>                                |
| \_\_DIR\_\_.'/vendor/doctrine/migrations/lib',                           |
| |     'Doctrine\\\\DBAL'             =>                                  |
| \_\_DIR\_\_.'/vendor/doctrine/dbal/lib',                                 |
| |     'Doctrine'                   =>                                    |
| \_\_DIR\_\_.'/vendor/doctrine/orm/lib',                                  |
| | ));                                                                    |
|                                                                          |
| | // ... register namespaces and prefixes here - see below               |
| | $loader->register();                                                   |
+--------------------------------------------------------------------------+

The PSR-4 class Loader
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\ClassLoader\\Psr4ClassLoader;                  |
| | use Symfony\\Component\\Yaml\\Yaml;                                    |
| | require \_\_DIR\_\_.'/lib/ClassLoader/Psr4ClassLoader.php';            |
| | $loader = new Psr4ClassLoader();                                       |
| | $loader->addPrefix('Symfony\\\\Component\\\\Yaml\\\\',                 |
| \_\_DIR\_\_.'/lib/Yaml');                                                |
| | $loader->register();                                                   |
| | $data = Yaml::parse(file\_get\_contents(\_\_DIR\_\_.'/config.yml'));   |
+--------------------------------------------------------------------------+


The PSR-0 class loader differs of PSR-4 loader by the fact the first one is compatible with the old PEAR notation.

The map Loader
~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | require\_once                                                          |
| '/path/to/src/Symfony/Component/ClassLoader/MapClassLoader.php';         |
| | $mapping = array(                                                      |
| |     'Foo' => '/path/to/Foo',                                           |
| |     'Bar' => '/path/to/Bar',                                           |
| | );                                                                     |
| | $loader = new MapClassLoader($mapping);                                |
| | $loader->register();                                                   |
+--------------------------------------------------------------------------+

Put a class loader in a cache
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The classes APCClassLoader and XcacheClassLoader permit to put class loader in cache.


+--------------------------------------------------------------------------+
| | require\_once                                                          |
| '/path/to/src/Symfony/Component/ClassLoader/ApcClassLoader.php';         |
| | // instance of a class that implements a findFile() method, like the   |
| ClassLoader                                                              |
| | $loader = ...;                                                         |
| | // sha1(\_\_FILE\_\_) generates an APC namespace prefix                |
| | $cachedLoader = new ApcClassLoader(sha1(\_\_FILE\_\_), $loader);       |
| | // register the cached class loader                                    |
| | $cachedLoader->register();                                             |
| | // deactivate the original, non-cached loader if it was registered     |
| previously                                                               |
| | $loader->unregister();                                                 |
+--------------------------------------------------------------------------+

+--------------------------------------------------------------------------+
| | require\_once                                                          |
| '/path/to/src/Symfony/Component/ClassLoader/XcacheClassLoader.php';      |
| | // instance of a class that implements a findFile() method, like the   |
| ClassLoader                                                              |
| | $loader = ...;                                                         |
| | // sha1(\_\_FILE\_\_) generates an XCache namespace prefix             |
| | $cachedLoader = new XcacheClassLoader(sha1(\_\_FILE\_\_), $loader);    |
| | // register the cached class loader                                    |
| | $cachedLoader->register();                                             |
| | // deactivate the original, non-cached loader if it was registered     |
| previously                                                               |
| | $loader->unregister();                                                 |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

The config component
-------------------

The config component permit to search, validate and load configuration value from XML, YML, INI file or a database.

The FileLocate class : Search files on FileSystem
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Config\\FileLocator;                           |
| | $configDirectories = array(\_\_DIR\_\_.'/app/config');                 |
| | $locator = new FileLocator($configDirectories);                        |
| | $yamlUserFiles = $locator->locate('users.yml', null, false);           |
+--------------------------------------------------------------------------+

FileLocator class Methods
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+--------------------------------------+--------------------------------------+
| locate                               | Permit to search files :             |
|                                      |                                      |
|                                      | Parameters :                         |
|                                      |                                      |
|                                      | -  name of file to find              |
|                                      | -  The current path                  |
|                                      | -  first (if true return only first  |
|                                      |    result)                           |
+--------------------------------------+--------------------------------------+

The resources loader
~~~~~~~~~~~~~~~~~~~~~~~~~~~

For each type of resource a loader must be defined.
The must implement the LoaderInterface interface or extend the FileLoader class.

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Config\\Loader\\FileLoader;                    |
| | use Symfony\\Component\\Yaml\\Yaml;                                    |
| | class YamlUserLoader extends FileLoader                                |
| | {                                                                      |
| |     public function load($resource, $type = null)                      |
| |     {                                                                  |
| |         $configValues = Yaml::parse(file\_get\_contents($resource));   |
| |         // ... handle the config values                                |
| |         // maybe import some other resource:                           |
| |         // $this->import('extra\_users.yml');                          |
| |     }                                                                  |
| |     public function supports($resource, $type = null)                  |
| |     {                                                                  |
| |         return is\_string($resource) && 'yml' === pathinfo(            |
| |             $resource,                                                 |
| |             PATHINFO\_EXTENSION                                        |
| |         );                                                             |
| |     }                                                                  |
| | }                                                                      |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

The configuration cache : ConfigCache
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The configuration cache permits to create a unique file with all configuration parameters loaded before.
It is generate when a file is modified.

+--------------------------------------------------------------------------+
| $userMatcherCache = new ConfigCache($cachePath, true);                   |
|                                                                          |
| | foreach ($yamlUserFiles as $yamlUserFile) {                            |
| |         // see the previous article "Loading resources" to             |
| |         // see where $delegatingLoader comes from                      |
| |         $delegatingLoader->load($yamlUserFile);                        |
| |         $resources[] = new FileResource($yamlUserFile);                |
| |     }                                                                  |
|                                                                          |
| $userMatcherCache->write($code, $resources);                             |
+--------------------------------------------------------------------------+

Validate configuration variables
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The "Definition" part of Config component permits to validate informations.


Create a hierarchical variables tree with the TreeBuilder
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
All rules about configuration variables can be defined with the TreeBuilder


+--------------------------------------------------------------------------+
| | namespace Acme\\DatabaseConfiguration;                                 |
| | use Symfony\\Component\\Config\\Definition\\ConfigurationInterface;    |
| | use Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder;      |
| | class DatabaseConfiguration implements ConfigurationInterface          |
| | {                                                                      |
| |     public function getConfigTreeBuilder()                             |
| |     {                                                                  |
| |         $treeBuilder = new TreeBuilder();                              |
| |         $rootNode = $treeBuilder->root('database');                    |
| |                                                                        |
|                                                                          |
| | $rootNode                                                              |
| |     ->children()                                                       |
| |         ->booleanNode('auto\_connect')                                 |
| |             ->defaultTrue()                                            |
| |         ->end()                                                        |
| |         ->scalarNode('default\_connection')                            |
| |             ->defaultValue('default')                                  |
| |         ->end()                                                        |
| |     ->end();                                                           |
|                                                                          |
|   return $treeBuilder;                                                   |
|                                                                          |
|       }                                                                  |
|                                                                          |
| }                                                                        |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

The console component
--------------------

This component ease the creation of command line tools.


Your new command must extends the class :

+--------------------------------------------------------------------------+
| Symfony\\Component\\Console\\Command\\Command;                           |
+--------------------------------------------------------------------------+

The class must implements two methods

-  Configure() :

        Configure the name, the description and options of your new command.

-  Execute() :

        Execute the new command.

+--------------------------------------------------------------------------+
| | namespace Acme\\Console\\Command;                                      |
| | use Symfony\\Component\\Console\\Command\\Command;                     |
| | use Symfony\\Component\\Console\\Input\\InputArgument;                 |
| | use Symfony\\Component\\Console\\Input\\InputInterface;                |
| | use Symfony\\Component\\Console\\Input\\InputOption;                   |
| | use Symfony\\Component\\Console\\Output\\OutputInterface;              |
| | class GreetCommand extends Command                                     |
| | {                                                                      |
| |     protected function configure()                                     |
| |     {                                                                  |
| |         $this                                                          |
| |             ->setName('demo:greet')                                    |
| |             ->setDescription('Greet someone')                          |
| |             ->addArgument(                                             |
| |                 'name',                                                |
| |                 InputArgument::OPTIONAL,                               |
| |                 'Who do you want to greet?'                            |
| |             )                                                          |
| |             ->addOption(                                               |
| |                'yell',                                                 |
| |                null,                                                   |
| |                InputOption::VALUE\_NONE,                               |
| |                'If set, the task will yell in uppercase letters'       |
| |             )                                                          |
| |         ;                                                              |
| |     }                                                                  |
| |     protected function execute(InputInterface $input, OutputInterface  |
| $output)                                                                 |
| |     {                                                                  |
| |         $name = $input->getArgument('name');                           |
| |         if ($name) {                                                   |
| |             $text = 'Hello '.$name;                                    |
| |         } else {                                                       |
| |             $text = 'Hello';                                           |
| |         }                                                              |
| |         if ($input->getOption('yell')) {                               |
| |             $text = strtoupper($text);                                 |
| |         }                                                              |
| |         $output->writeln($text);                                       |
| |     }                                                                  |
| | }                                                                      |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Use colors :
~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | // green text                                                          |
| | $output->writeln('<info>foo</info>');                                  |
| | // yellow text                                                         |
| | $output->writeln('<comment>foo</comment>');                            |
| | // black text on a cyan background                                     |
| | $output->writeln('<question>foo</question>');                          |
| | // white text on a red background                                      |
| | $output->writeln('<error>foo</error>');                                |
+--------------------------------------------------------------------------+

Create our own style :
~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Console\\Formatter\\OutputFormatterStyle;      |
| | // ...                                                                 |
| | $style = new OutputFormatterStyle('red', 'yellow', array('bold',       |
| 'blink'));                                                               |
| | $output->getFormatter()->setStyle('fire', $style);                     |
| | $output->writeln('<fire>foo</>');                                      |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

The verbosity levels :
~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------+--------------------------+--------------------------+
| Value                    | Meaning                  | Console option           |
+--------------------------+--------------------------+--------------------------+
| OutputInterface::VERBOSI | Do not output any        | -q or --quiet            |
| TY\_QUIET                | messages                 |                          |
+--------------------------+--------------------------+--------------------------+
| OutputInterface::VERBOSI | The default verbosity    | (none)                   |
| TY\_NORMAL               | level                    |                          |
+--------------------------+--------------------------+--------------------------+
| OutputInterface::VERBOSI | Increased verbosity of   | -v                       |
| TY\_VERBOSE              | messages                 |                          |
+--------------------------+--------------------------+--------------------------+
| OutputInterface::VERBOSI | Informative non          | -vv                      |
| TY\_VERY\_VERBOSE        | essential messages       |                          |
+--------------------------+--------------------------+--------------------------+
| OutputInterface::VERBOSI | Debug messages           | -vvv                     |
| TY\_DEBUG                |                          |                          |
+--------------------------+--------------------------+--------------------------+

+--------------------------------------------------------------------------+
| | if ($output->getVerbosity() >= OutputInterface::VERBOSITY\_VERBOSE) {  |
| |     $output->writeln(...);                                             |
| | }                                                                      |
|                                                                          |
| | if ($output->isQuiet()) {                                              |
| |     // ...                                                             |
| | }                                                                      |
| | if ($output->isVerbose()) {                                            |
| |     // ...                                                             |
| | }                                                                      |
| | if ($output->isVeryVerbose()) {                                        |
| |     // ...                                                             |
| | }                                                                      |
| | if ($output->isDebug()) {                                              |
| |     // ...                                                             |
| | }                                                                      |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Arguments
~~~~~~~~~~~~~

Arguments are string separated by spaces.

They can be optional or mandatory.


+--------------------------------------+--------------------------------------+
| InputArgument::REQUIRED              | The argument is required             |
+--------------------------------------+--------------------------------------+
| InputArgument::OPTIONAL              | The argument is optional and         |
|                                      | therefore can be omitted             |
+--------------------------------------+--------------------------------------+
| InputArgument::IS\_ARRAY             | The argument can contain an          |
|                                      | indefinite number of arguments and   |
|                                      | must be used at the end of the       |
|                                      | argument list                        |
+--------------------------------------+--------------------------------------+

+--------------------------------------------------------------------------+
| | $this                                                                  |
| |     // ...                                                             |
| |     ->addArgument(                                                     |
| |         'names',                                                       |
| |         InputArgument::IS\_ARRAY \| InputArgument::REQUIRED,           |
| |         'Who do you want to greet (separate multiple names with a      |
| space)?'                                                                 |
| |     );                                                                 |
+--------------------------------------------------------------------------+

Options
~~~~~~~~~~~


Options are preceded by -- and are not ordered. (--all)

It's possible to add shortcut with only one letter and hyphen. (-a)


+--------------------------------------------------------------------------+
| | $this                                                                  |
| |     // ...                                                             |
| |     ->addOption(                                                       |
| |         'iterations',                                                  |
| |         null,                                                          |
| |         InputOption::VALUE\_REQUIRED,                                  |
| |         'How many times should the message be printed?',               |
| |         1                                                              |
| |     );                                                                 |
+--------------------------------------------------------------------------+

+--------------------------------------+--------------------------------------+
| Option                               | Value                                |
+--------------------------------------+--------------------------------------+
| InputOption::VALUE\_IS\_ARRAY        | This option accepts multiple values  |
|                                      | (e.g. --dir=/foo --dir=/bar)         |
+--------------------------------------+--------------------------------------+
| InputOption::VALUE\_NONE             | Do not accept input for this option  |
|                                      | (e.g. --yell)                        |
+--------------------------------------+--------------------------------------+
| InputOption::VALUE\_REQUIRED         | This value is required (e.g.         |
|                                      | --iterations=5), the option itself   |
|                                      | is still optional                    |
+--------------------------------------+--------------------------------------+
| InputOption::VALUE\_OPTIONAL         | This option may or may not have a    |
|                                      | value (e.g. --yell or --yell=loud)   |
+--------------------------------------+--------------------------------------+

Helpers
~~~~~~~~~~~

QuestionHelper :
^^^^^^^^^^^^^^^^

 Permit to ask question to user :
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+--------------------------------------------------------------------------+
| | public function execute(InputInterface $input, OutputInterface         |
| $output)                                                                 |
| |     {                                                                  |
| |         $helper = $this->getHelper('question');                        |
| |         $question = new ConfirmationQuestion('Continue with this       |
| action?', false);                                                        |
| |         if (!$helper->ask($input, $output, $question)) {               |
| |             return;                                                    |
| |         }                                                              |
| |     }                                                                  |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

FormatterHelper :
^^^^^^^^^^^^^^^^^

Permit to format the output

ProgressBarHelper
^^^^^^^^^^^^^^^^^

Permit to display a progressBar

+--------------------------------------------------------------------------+
| | // create a new progress bar (50 units)                                |
| | $progress = new ProgressBar($output, 50);                              |
| | // start and displays the progress bar                                 |
| | $progress->start();                                                    |
| | $i = 0;                                                                |
| | while ($i++ < 50) {                                                    |
| |     // ... do some work                                                |
| |     // advance the progress bar 1 unit                                 |
| |     $progress->advance();                                              |
| |     // you can also advance the progress bar by more than 1 unit       |
| |     // $progress->advance(3);                                          |
| | }                                                                      |
| | // ensure that the progress bar is at 100%                             |
| | $progress->finish();                                                   |
+--------------------------------------------------------------------------+

TableHelper
^^^^^^^^^^^

Permit to display tabular data.

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Console\\Helper\\Table;                        |
| | // ...                                                                 |
| | class SomeCommand extends Command                                      |
| | {                                                                      |
| |     public function execute(InputInterface $input, OutputInterface     |
| $output)                                                                 |
| |     {                                                                  |
| |         $table = new Table($output);                                   |
| |         $table                                                         |
| |             ->setHeaders(array('ISBN', 'Title', 'Author'))             |
| |             ->setRows(array(                                           |
| |                 array('99921-58-10-7', 'Divine Comedy', 'Dante         |
| Alighieri'),                                                             |
| |                 array('9971-5-0210-0', 'A Tale of Two                  |
| Cities', 'Charles Dickens'),                                             |
| |                 array('960-425-059-0', 'The Lord of the Rings', 'J. R. |
| R. Tolkien'),                                                            |
| |                 array('80-902734-1-6', 'And Then There Were            |
| None', 'Agatha Christie'),                                               |
| |             ))                                                         |
| |         ;                                                              |
| |         $table->render();                                              |
| |     }                                                                  |
| | }                                                                      |
+--------------------------------------------------------------------------+

The CSSSelector component
------------------------

This component transform CSS selector in XPATH path.

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\CssSelector\\CssSelectorConverter;             |
| | $converter = new CssSelectorConverter();                               |
| | var\_dump($converter->toXPath('div.item > h4 > a'));                   |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

The Debug component
------------------

The debug component provide debug tools

It's necessary to activate it before use.

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Debug\\Debug;                                  |
| | Debug::enable();                                                       |
+--------------------------------------------------------------------------+

L’errorHandler
~~~~~~~~~~~~~~

The errorHandler permit to converts errors in exception

It's necessary to activate it before use.

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\Debug\\ErrorHandler;                           |
| | ErrorHandler::register();                                              |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

L’exceptionHandler
~~~~~~~~~~~~~~~~~~

The exceptionHandler permit to convert exceptions in Symfony Response.

The dependancy injection component
-----------------------------------

This component permits to standardize the creation of object in the framework.

Save a service in the container :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\DependencyInjection\\ContainerBuilder;         |
| | $container = new ContainerBuilder();                                   |
| | $container->setParameter('mailer.transport', 'sendmail');              |
| | $container                                                             |
| |     ->register('mailer', 'Mailer')                                     |
| |     ->addArgument('%mailer.transport%');                               |
+--------------------------------------------------------------------------+

Give a service as dependency as another service :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | use Symfony\\Component\\DependencyInjection\\ContainerBuilder;         |
| | use Symfony\\Component\\DependencyInjection\\Reference;                |
| | $container = new ContainerBuilder();                                   |
| | $container->setParameter('mailer.transport', 'sendmail');              |
| | $container                                                             |
| |     ->register('mailer', 'Mailer')                                     |
| |     ->addArgument('%mailer.transport%');                               |
| | $container                                                             |
| |     ->register('newsletter\_manager', 'NewsletterManager')             |
| |     ->addArgument(new Reference('mailer'));                            |
+--------------------------------------------------------------------------+

Give a service as dependency as another service by setter :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | class NewsletterManager                                                |
| | {                                                                      |
| |     private $mailer;                                                   |
| |     public function setMailer(\\Mailer $mailer)                        |
| |     {                                                                  |
| |         $this->mailer = $mailer;                                       |
| |     }                                                                  |
| |     // ...                                                             |
| | }                                                                      |
|                                                                          |
| | use Symfony\\Component\\DependencyInjection\\ContainerBuilder;         |
| | use Symfony\\Component\\DependencyInjection\\Reference;                |
| | $container = new ContainerBuilder();                                   |
| | $container->setParameter('mailer.transport', 'sendmail');              |
| | $container                                                             |
| |     ->register('mailer', 'Mailer')                                     |
| |     ->addArgument('%mailer.transport%');                               |
| | $container                                                             |
| |     ->register('newsletter\_manager', 'NewsletterManager')             |
| |     ->addMethodCall('setMailer', array(new Reference('mailer')));      |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Loading of services by configuration file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| Load XML config file :                                                   |
|                                                                          |
| | use Symfony\\Component\\DependencyInjection\\ContainerBuilder;         |
| | use Symfony\\Component\\Config\\FileLocator;                           |
| | use Symfony\\Component\\DependencyInjection\\Loader\\XmlFileLoader;    |
| | $container = new ContainerBuilder();                                   |
| | $loader = new XmlFileLoader($container, new FileLocator(\_\_DIR\_\_)); |
| | $loader->load('services.xml');                                         |
|                                                                          |
| Loading a YAML config file:                                              |
|                                                                          |
| | use Symfony\\Component\\DependencyInjection\\ContainerBuilder;         |
| | use Symfony\\Component\\Config\\FileLocator;                           |
| | use Symfony\\Component\\DependencyInjection\\Loader\\YamlFileLoader;   |
| | $container = new ContainerBuilder();                                   |
| | $loader                                                                |
| = new YamlFileLoader($container, new FileLocator(\_\_DIR\_\_));          |
| | $loader->load('services.yml');                                         |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Services YML configuration file sample
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
| | parameters:                                                            |
| |     # ...                                                              |
| |     mailer.transport: sendmail                                         |
| | services:                                                              |
| |     mailer:                                                            |
| |         class:     Mailer                                              |
| |         arguments: ['%mailer.transport%']                              |
| |     newsletter\_manager:                                               |
| |         class:     NewsletterManager                                   |
| |         calls:                                                         |
| |             - [setMailer, ['@mailer']]                                 |
|                                                                          |
| |         properties:                                                    |
| |              mailer: '@mailer'                                         |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Parameters
~~~~~~~~~~~~~~


Parameters can be defined and used by the dependency injection component.

+--------------------------------------------------------------------------+
| $container->hasParameter('mailer.transport');                            |
|                                                                          |
| $container->getParameter('mailer.transport');                            |
|                                                                          |
| $container->setParameter('mailer.transport', 'sendmail');                |
+--------------------------------------------------------------------------+

Parameters in a configuration file
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

+--------------------------------------------------------------------------+
| | parameters:                                                            |
| |     mailer.transport: sendmail                                         |
| | services:                                                              |
| |     mailer:                                                            |
| |         class:     Mailer                                              |
| |         arguments: ['%mailer.transport%']                              |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

The definition
~~~~~~~~~~~~~~~

The definitions are definitions of service created by ContainerBuilder or present in a configuration file.

+--------------------------------------------------------------------------+
| $container->hasDefinition($serviceId);                                   |
|                                                                          |
| $container->getDefinition($serviceId);                                   |
|                                                                          |
| $container->findDefinition($serviceId);                                  |
|                                                                          |
| $container->setDefinition($id, $definition);                             |
+--------------------------------------------------------------------------+

Arguments handling :

+--------------------------------------------------------------------------+
| $definition->getArguments();                                             |
|                                                                          |
| $definition->getArgument($index);                                        |
|                                                                          |
| $definition->addArgument($argument);                                     |
|                                                                          |
| $definition->addArgument(new Reference('service\_id'));                  |
|                                                                          |
| $definition->replaceArgument($index, $argument);                         |
|                                                                          |
| $definition->setArguments($arguments);                                   |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Call of DI functions

+--------------------------------------------------------------------------+
| $definition->getMethodCalls();                                           |
|                                                                          |
| $definition->addMethodCall($method, $arguments);                         |
|                                                                          |
| $definition->setMethodCalls($methodCalls);                               |
+--------------------------------------------------------------------------+

Definitions are objects so a modification will be impacted immediately.
All modification on definition must be doing before the compilation of container.


Creation a definition with a configuration file :

+--------------------------------------------------------------------------+
| $definition->setFile('/src/path/to/file/foo.php');                       |
+--------------------------------------------------------------------------+

L’autowiring
~~~~~~~~~~~~

The autowiring principle permit to inject automaticaly good dependencies during objects instanciation.
Autowiring use the type of the parameters of the constructor to determine which service uses.

The type of argument is a class :

+--------------------------------------------------------------------------+
| | # app/config/services.yml                                              |
| | services:                                                              |
| |     twitter\_client:                                                   |
| |         class:    'AppBundle\\TwitterClient'                           |
| |         autowire: true                                                 |
+--------------------------------------------------------------------------+

The type of argument is an interface :

+--------------------------------------------------------------------------+
| | # app/config/services.yml                                              |
| | services:                                                              |
| |     rot13\_transformer:                                                |
| |         class: 'AppBundle\\Rot13Transformer'                           |
| |     twitter\_client:                                                   |
| |         class:    'AppBundle\\TwitterClient'                           |
| |         autowire: true                                                 |
+--------------------------------------------------------------------------+



The autowiring_type key permit to specify which implementation use by default.

+--------------------------------------------------------------------------+
| | # app/config/services.yml                                              |
| | services:                                                              |
| |     rot13\_transformer:                                                |
| |         class:            AppBundle\\Rot13Transformer                  |
| |         autowiring\_types: AppBundle\\TransformerInterface             |
| |     twitter\_client:                                                   |
| |         class:    AppBundle\\TwitterClient                             |
| |         autowire: true                                                 |
| |     uppercase\_rot13\_transformer:                                     |
| |         class:    AppBundle\\UppercaseRot13Transformer                 |
| |         autowire: true                                                 |
| |     uppercase\_twitter\_client:                                        |
| |         class:     AppBundle\\TwitterClient                            |
| |         arguments: ['@uppercase\_rot13\_transformer']                  |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+

Copilation of container
---------------------

The compilation of container permits to delete circular reference and to delete all no used services.
That permit too, to use "parent services".


Services Tags
~~~~~~~~~~~~~~~~~~~~


Tags permit to group services which are a common aim.role commun
(Extension twig, etc).

+--------------------------------------------------------------------------+
| services:                                                                |
|                                                                          |
|     oc\_platform.antispam:                                               |
|                                                                          |
|         class:     OC\\PlatformBundle\\Antispam\\OCAntispam              |
|                                                                          |
|         arguments: [@mailer, %locale%, 50]                               |
|                                                                          |
|         tags:                                                            |
|                                                                          |
|             -  { name: twig.extension }                                  |
|                                                                          |
                                                                          
+--------------------------------------------------------------------------+


Use a factory to create service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------------------------------------------------------------+
|  class NewsletterManagerFactory                                          |
|  {                                                                       |
|      public static function createNewsletterManager()                    |
|      {                                                                   |
|          $newsletterManager = new NewsletterManager();                   |
|                                                                          |
|          // ...                                                          |
|                                                                          |
|          return $newsletterManager;                                      |
|      }                                                                   |
|  }                                                                       |
+--------------------------------------------------------------------------+

+--------------------------------------------------------------------------+
|  services:                                                               |
|      newsletter_manager:                                                 |
|          class:   NewsletterManager                                      |
|          factory: [NewsletterManagerFactory, createNewsletterManager]    |
|          arguments:                                                      |
                - '@templating'                                            |
+--------------------------------------------------------------------------+

The service configurator
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The service configurator permits to configure a service which need a complex configuration.
The service is give as argument to a callback function which will configure the service.

+--------------------------------------------------------------------------+
|    services:                                                             |
|        my_mailer:                                                        |
|            # ...                                                         |
|                                                                          |
|        email_formatter_manager:                                          |
|            class:     EmailFormatterManager                              |
|            # ...                                                         |
|                                                                          |
|        email_configurator:                                               |
|            class:     EmailConfigurator                                  |
|            arguments: ['@email_formatter_manager']                       |
|            # ...                                                         |
|                                                                          |
|        newsletter_manager:                                               |
|            class:     NewsletterManager                                  |
|            calls:                                                        |
|                - [setMailer, ['@my_mailer']]                             |
|            configurator: ['@email_configurator', configure]              |
|                                                                          |
|        greeting_card_manager:                                            |
|            class:     GreetingCardManager                                |
|            calls:                                                        |
|                - [setMailer, ['@my_mailer']]                             |
|            configurator: ['@email_configurator', configure]              |
+--------------------------------------------------------------------------+


The parent service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you need to use the sames dependencies in both classes you can use parent keyword to avoid to repeat the dependencies definitions.

+--------------------------------------------------------------------------+
|  services:                                                               |
|      # ...                                                               |
|      mail_manager:                                                       |
|          abstract:  true                                                 |
|          calls:                                                          |
|              - [setMailer, ['@my_mailer']]                               |
|              - [setEmailFormatter, ['@my_email_formatter']]              |
|                                                                          |
|      newsletter_manager:                                                 |
|          class:  "NewsletterManager"                                     |
|          parent: mail_manager                                            |
|                                                                          |
|      greeting_card_manager:                                              |
|          class:  "GreetingCardManager"                                   |
|          parent: mail_manager                                            |
+--------------------------------------------------------------------------+

You can override calls of parent by defining a new call in children.

Advanced configuration of container
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

keywords :

    - deprecated (deprecate the service)
    - decorates (rename the service)
    - alias (give additional name to the service)


Lazy service
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can use Lazy service to gain performance.
A proxy of dependency will be injected instead the real service.


The domCrawler component
-----------------------------------

This component permit to navigate into XML or HTML file.

Retrieve element with Xpath
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$crawler = $crawler->filterXPath('descendant-or-self::body/p');

Retrieve element with CssSelector
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$crawler = $crawler->filter('body > p');


Access node by its position on the list:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$crawler->filter('body > p')->eq(0);

Get all the child or parent nodes:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$crawler->filter('body')->children();
$crawler->filter('body > p')->parents();

Accessing Node Values
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$message = $crawler->filterXPath('//body/p')->text();
$class = $crawler->filterXPath('//body/p')->attr('class');


Adding content
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$crawler->addContent('<html><body /></html>');
$crawler->addContent('<root><node /></root>', 'text/xml');


Works with links
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
+--------------------------------------------------------------------------+
|    $linksCrawler = $crawler->selectLink('Go elsewhere...');              |
|    $link = $linksCrawler->link();                                        |
|                                                                          |
|    // or do this all at once                                             |
|    $link = $crawler->selectLink('Go elsewhere...')->link();              |
+--------------------------------------------------------------------------+

Works with form
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
+--------------------------------------------------------------------------+
|   $form = $crawler->selectButton('validate')->form();                    |
|                                                                          |
|   // or "fill" the form fields with data                                 |
|   $form = $crawler->selectButton('validate')->form(array(                |
|       'name' => 'Ryan',                                                  |
|   ));                                                                    |
|                                                                          |
|   $uri = $form->getUri();                                                |
|                                                                          |
|   $method = $form->getMethod();                                          |
|                                                                          |
|   // set values on the form internally                                   |
|   $form->setValues(array(                                                |
|       'registration[username]' => 'symfonyfan',                          |
|       'registration[terms]'    => 1,                                     |
|   ));                                                                    |
|                                                                          |
|   // get back an array of values - in the "flat" array like above        |
|   $values = $form->getValues();                                          |
|                                                                          |
|   // returns the values like PHP would see them,                         |
|   // where "registration" is its own array                               |
|   $values = $form->getPhpValues();                                       |
+--------------------------------------------------------------------------+

The EventDispatcher Component
-----------------------------------

The event dispatcher component permit to application to communicate with its different section by dispatching event.

The dispatcher
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The dispatcher handle listener which will handle events.

+-----------------------------------------------------------------------------+
|   use Symfony\Component\EventDispatcher\EventDispatcher;                    |
|   $dispatcher = new EventDispatcher();                                      |
|   $listener = new AcmeListener();                                           |
|   $dispatcher->addListener('acme.action', array($listener, 'onFooAction')); |
+-----------------------------------------------------------------------------+

The listener

+-----------------------------------------------------------------------------+
|   use Symfony\Component\EventDispatcher\Event;                              |
|                                                                             |
|   class AcmeListener                                                        |
|   {                                                                         |
|       // ...                                                                |
|                                                                             |
|       public function onFooAction(Event $event)                             |
|       {                                                                     |
|           // ... do something                                               |
|       }                                                                     |
|   }                                                                         |
+-----------------------------------------------------------------------------+

Dispatch an event
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+-----------------------------------------------------------------------------+
|   use Acme\Store\Order;                                                     |
|   use Acme\Store\Event\OrderPlacedEvent;                                    |
|                                                                             |
|   // the order is somehow created or retrieved                              |
|   $order = new Order();                                                     |
|   // ...                                                                    |
|                                                                             |
|   // create the OrderPlacedEvent and dispatch it                            |
|   $event = new OrderPlacedEvent($order);                                    |
|   $dispatcher->dispatch(OrderPlacedEvent::NAME, $event);                    |
+-----------------------------------------------------------------------------+

The EventSubscriber
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+-----------------------------------------------------------------------------+
|   namespace Acme\Store\Event;                                               |
|                                                                             |
|   use Symfony\Component\EventDispatcher\EventSubscriberInterface;           |
|   use Symfony\Component\HttpKernel\Event\FilterResponseEvent;               |
|   use Symfony\Component\HttpKernel\KernelEvents;                            |
|   use Acme\Store\Event\OrderPlacedEvent;                                    |
|                                                                             |
|   class StoreSubscriber implements EventSubscriberInterface                 |
|   {                                                                         |
|       public static function getSubscribedEvents()                          |
|       {                                                                     |
|           return array(                                                     |
|               KernelEvents::RESPONSE => array(                              |
|                   array('onKernelResponsePre', 10),                         |
|                   array('onKernelResponsePost', -10),                       |
|               ),                                                            |
|               OrderPlacedEvent::NAME => 'onStoreOrder',                     |
|           );                                                                |
|       }                                                                     |
|                                                                             |
|       public function onKernelResponsePre(FilterResponseEvent $event)       |
|       {                                                                     |
|           // ...                                                            |
|       }                                                                     |
|                                                                             |
|       public function onKernelResponsePost(FilterResponseEvent $event)      |
|       {                                                                     |
|                                                                             |
|              // ...                                                         |
|       }                                                                     |
|                                                                             |
|       public function onStoreOrder(OrderPlacedEvent $event)                 |
|       {                                                                     |
|           // ...                                                            |
|       }                                                                     |
|   }                                                                         |
+-----------------------------------------------------------------------------+

Stopping Event Flow/Propagation
~~~~~~~~~~~~~~~~~~~~~~~~~~~~
+-----------------------------------------------------------------------------+
|    $event->stopPropagation();                                               |
+-----------------------------------------------------------------------------+


The Container Aware Event Dispatcher
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

It permits to specify service as Event listener with tags.
Services must implements EventSubscriberInterface (getSubscribedEvents())

+-----------------------------------------------------------------------------+
|    use Symfony\Component\DependencyInjection\ContainerBuilder;              |
|    use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;     |
|                                                                             |
|    $container = new ContainerBuilder();                                     |
|    $dispatcher = new ContainerAwareEventDispatcher($container);             |
+-----------------------------------------------------------------------------+

Methods :

     - addListenerService($eventName, array('foo', 'logListener'))
     - addSubscriberService('kernel.store_subscriber','StoreSubscriber');


The event object
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The Immutable Event Dispatcher
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This event dispatcher can't add or remove new Listener.
It's a proxy of the original dispatcher.
You need to create a normal dispatcher and inject it in the Immutable Event Dispatcher.

+-----------------------------------------------------------------------------+
|   $immutableDispatcher = new ImmutableEventDispatcher($dispatcher);         |
+-----------------------------------------------------------------------------+

The Traceable Event Dispatcher
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This dispatcher wrap other dispatcher and is able to know which listener handle the event.

+-----------------------------------------------------------------------------+
|   use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;     |
|   use Symfony\Component\Stopwatch\Stopwatch;                                |
|                                                                             |
|   // the event dispatcher to debug                                          |
|   $eventDispatcher = ...;                                                   |
|                                                                             |
|   $traceableEventDispatcher = new TraceableEventDispatcher(                 |
|       $eventDispatcher,                                                     |
|       new Stopwatch()                                                       |
|   );                                                                        |
|   // register an event listener                                             |
|   $eventListener = ...;                                                     |
|   $priority = ...;                                                          |
|   $traceableEventDispatcher->addListener(                                   |
|       'event.the_name',                                                     |
|       $eventListener,                                                       |
|       $priority                                                             |
|   );                                                                        |
|                                                                             |
|   // dispatch an event                                                      |
|   $event = ...;                                                             |
|   $traceableEventDispatcher->dispatch('event.the_name', $event);            |
|                                                                             |
|   $calledListeners = $traceableEventDispatcher->getCalledListeners();       |
|   $notCalledListeners = $traceableEventDispatcher->getNotCalledListeners(); |
+-----------------------------------------------------------------------------+


The Filesystem component
-----------------------------------

Provide methods to work with FileSystem.
+-------------------------------------------------------------------------------+
|   mkdir()  exists() copy() touch() chown()  chgrp() chmod() remove() rename() |
|   symlink() isAbsolutPath() dumpFile()                                        |
+-------------------------------------------------------------------------------+



The finder component
-----------------------------------

The find component find files and directory.

$finder->files()->in(__DIR__)->in("/another/path")->exclude("/a/dir")->size('< 100K')->date('since 1 hour ago');
$finder->directories()->in(__DIR__)->in("/another/path")->exclude("/a/dir")->size('< 100K')->date('since 1 hour ago');


The Form component
-----------------------------------

Advantages to use the form factory :

    - Request Handling (If used with HTTPFundation component)
    - CSRF Protection
    - Templating
    - Translation
    - Validation

+-------------------------------------------------------------------------------+
|   use Symfony\Component\Form\Forms;                                           |
|   use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;|
|                                                                               |
|   $formFactory = Forms::createFormFactoryBuilder()                            |
|       ->addExtension(new HttpFoundationExtension())                           |
|       ->getFormFactory();                                                     |
|                                                                               |
+-------------------------------------------------------------------------------+



Add CSRF protection to a form
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

+-------------------------------------------------------------------------------+
|  use Symfony\Component\Form\Forms;                                            |
|  use Symfony\Component\Form\Extension\Csrf\CsrfExtension;                     |
|  use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;  |
|  use Symfony\Component\HttpFoundation\Session\Session;                        |
|                                                                               |
|  // generate a CSRF secret from somewhere                                     |
|  $csrfSecret = '<generated token>';                                           |
|                                                                               |
|  // create a Session object from the HttpFoundation component                 |
|  $session = new Session();                                                    |
|                                                                               |
|  $csrfProvider = new SessionCsrfProvider($session, $csrfSecret);              |
|                                                                               |
|  $formFactory = Forms::createFormFactoryBuilder()                             |
|      // ...                                                                   |
|      ->addExtension(new CsrfExtension($csrfProvider))                         |
|      ->getFormFactory();                                                      |
+-------------------------------------------------------------------------------+
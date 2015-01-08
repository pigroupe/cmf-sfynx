<?php
/**
 * This file is part of the <Translator> project.
 *
 * @subpackage Sfynx_Manager
 * @package    translator
 * @author     Riad HELLAL <hellal.riad@gmail.com>
 * @since 2012-11-14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TranslatorBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Routing\RouteCollection;

/**
 * Words translator management.
 *
 * @subpackage Sfynx_Manager
 * @package    translator
 * @author     Riad HELLAL <hellal.riad@gmail.com>
 */
class WordsLoader implements LoaderInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container        = $container;
    }
        
    /**
     * @param string $resource
     * @param null $type
     * 
     * @return RouteCollection
     * @access public
     * @author Riad HELLAL <hellal.riad@gmail.com>
     * @author etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since  2012-11-14
     */    
    public function load($resource, $userLocale, $domain = 'messages')
    {
        // get a new instance of the catalogue
        $catalogue  = new MessageCatalogue($userLocale);
        // set the cache file path of all translations words of the locale.
        $foundBundle = $this->container->get('kernel')->getBundle('SfynxTranslatorBundle');
        $basePath    = $foundBundle->getPath() . "/Resources/translations/";
        $filepath    = $basePath."messages.".$userLocale.".yml";
        if (!file_exists($filepath)){
          $this->wordsTranslation();
        }
        $path_message     = realpath($filepath);    
        // get all bundles translaions in user locale
        $bundles     = $this->container->get("kernel")->getBundles();
        if (is_array($bundles)) {
            foreach ($bundles as $bundle) {
                $dir_path = realpath($bundle->getPath() . '/Resources/translations/');
                if ($dir_path){                    
                    $files = \JMS\TranslationBundle\Util\FileUtils::findTranslationFiles($dir_path);
                    foreach ($files as $domain => $locales) {
                        foreach ($locales as $locale => $data) {
                            if ($locale !== $userLocale) {
                                continue;
                            }                            
                            list($format, $file) = $data;
                            // merge catalogues
                            $loader = $this->loadFile($file, $format, $locale, $domain);
                            $catalogue->addCatalogue($loader);
                        }
                    }
                }    
            }        
        }        
        // add words translations here
        try {
            $loader = $this->loadFile($path_message, 'yml', $userLocale, 'messages');
            $catalogue->addCatalogue($loader);            
        } catch (\Exception $e) {
        }
        
        return $catalogue;
    }
    
    /**
     * @param $format
     * @throws \InvalidArgumentException
     * @return \Sfynx\TranslatorBundle\Manager\Loader\LoaderInterface
     * @access private
     * 
     * @author Riad HELLAL <hellal.riad@gmail.com>
     */
    private function getLoader($format)
    {
        if ($format == 'yml') {
            $loader = $this->container->get('translation.loader.yml');
        } elseif ($format == 'php') {
            $loader = $this->container->get('translation.loader.php');
        } elseif ( ($format == 'xliff') || ($format == 'xlf') ) {
            $loader = $this->container->get('translation.loader.xliff');
        } elseif ($format == 'csv') {
            $loader = $this->container->get('translation.loader.csv');
        } else {
            throw new \InvalidArgumentException(sprintf('The format "%s" does not exist.', $format));
        }
        
        return $loader;
    }    

    /**
     * @param $file
     * @param $format
     * @param $locale
     * @param string $domain
     * @return mixed
     * @access private
     * 
     * @author Riad HELLAL <hellal.riad@gmail.com>
     */
    private function loadFile($file, $format, $locale, $domain = 'messages')
    {
        $loader = $this->getLoader($format);
        return $loader->load($file, $locale, $domain);
    }  
  
    /**
     * Sets the specific sortOrders.
     *
     * @return void
     * @access private
     *
     * @author Riad HELLAL <hellal.riad@gmail.com>
     */
    public function wordsTranslation()
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $locale        = $this->container->get('request')->getLocale();
        $foundBundle   = $this->container->get('kernel')->getBundle('SfynxTranslatorBundle');
        $basePath      = $foundBundle->getPath() . "/Resources/translations/";
        $dir           = \Sfynx\ToolBundle\Util\PiFileManager::mkdirr($basePath);
        $array = array();
        if (!isset($_GET['_end_wordsloader_'])) {
            $_GET['_end_wordsloader_'] = false;
            // we create qury
            $Words     = $entityManager->getRepository("SfynxTranslatorBundle:Word")->createQueryBuilder('a')
            ->select('a')
            ->where('a.archived = 0')
            ->getQuery()
            ->getResult();
            // we create for all languages
            $this->container->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
            $all_locales = $this->container->get('sfynx.auth.locale_manager')->getAllLocales();
            foreach ($all_locales as $key => $lang) {
                $filename  = $basePath."messages.".$lang.".yml";
                foreach ($Words as $word) {
                    if ($lang != $locale) {
                        $word->setTranslatableLocale($lang);
                        $entityManager->refresh($word);
                    }
                    $array["{$word->getKeyword()}"] = $word->translate($lang)->getLabel() ? $word->translate($lang)->getLabel():' ';
                }
                $yaml = \Symfony\Component\Yaml\Yaml::dump($array, 2);
                file_put_contents($filename, $yaml);
                $array = array();
            }
        }
        $basePath_cache_translations = realpath($this->container->getParameter("kernel.cache_dir"). '/translations/');
        if ($basePath_cache_translations){
            $all_files = \Sfynx\ToolBundle\Util\PiFileManager::ListFiles($basePath_cache_translations);
            foreach ($all_files as $filename ) {
                 unlink ($filename);
            }  
        }
    }  
}
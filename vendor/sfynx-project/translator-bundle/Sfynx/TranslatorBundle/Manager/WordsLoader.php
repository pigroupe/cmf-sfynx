<?php
/**
 * This file is part of the <Translator> project.
 *
 * @subpackage Sfynx_Manager
 * @package    translator
 * @author     Riad HELLAL <hellal.riad@gmail.com>
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2012-11-14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\TranslatorBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Sfynx\ToolBundle\Util\PiFileManager;

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
        $this->container = $container;
    }
        
    /**
     * @param mixed  $resource A resource
     * @param string $locale   A locale
     * @param string $domain   The domain
     *
     * @return MessageCatalogue A MessageCatalogue instance
     * @access public
     * @since  2012-11-14
     */    
    public function load($resource, $locale, $domain = 'messages')
    {
        // we get a new instance of the catalogue
        $catalogue  = new MessageCatalogue($locale);
        // we get all translation words of the database
        $catalogue =  $this->wordsTranslationLoad($catalogue, $locale, $domain);
        // we get all translations of all translation files
        $catalogue =  $this->bundlesLoad($catalogue, $locale, $domain);
        
        return $catalogue;
    }
    
    /**
     * Sets the specific sortOrders.
     * 
     * @param MessageCatalogue $catalogue A cataloque
     * @param string           $locale    A locale
     * @param string           $domain    The domain
     * 
     * @return MessageCatalogue A MessageCatalogue instance
     * @access private
     */
    private function wordsTranslationLoad(MessageCatalogue $catalogue, $locale, $domain = 'messages')
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        if (!isset($_GET['_end_wordsloader_'])) {
            $_GET['_end_wordsloader_'] = false;
            // we create qury
            $Words     = $entityManager->getRepository("SfynxTranslatorBundle:Word")
            ->getAllWords()
            ->getQuery()
            ->getResult();
            // we create for all languages
            $this->container->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
            foreach ($Words as $word) {
//                print_r("\n");
//                print_r($word->getId());
//                print_r("-");
//                print_r($word->getKeyword());
//                print_r("-");                    
//                print_r($word->getLabel());
//                print_r("\n");
                $catalogue->set($word->getKeyword(), $word->getLabel() ? $word->getLabel():' ', $domain);
            }
        }
        
        return $catalogue;
    }  
    
    /**
     * 
     * 
     * @param MessageCatalogue $catalogue  A cataloque
     * @param string           $userLocale A locale
     * @param string           $domain     The domain
     * 
     * @return MessageCatalogue A MessageCatalogue instance
     * @access private
     * @since  2012-11-14
     */    
    private function bundlesLoad(MessageCatalogue $catalogue, $userLocale, $domain = 'messages')
    {
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
                            try{
                                $loader = $this->loadFile($file, $format, $locale, $domain);
                                $catalogue->addCatalogue($loader);
                            } catch (\Exception $e) {                                
                            }
                        }
                    }
                }    
            }        
        }     
        
        return $catalogue;
    }        
    
    /**
     * @param string $file   A file
     * @param string $format A format value
     * @param string $locale A locale
     * @param string $domain The domain
     * 
     * @return mixed
     * @access private
     */
    private function loadFile($file, $format, $locale, $domain = 'messages')
    {
        $loader = $this->getLoader($format);
        
        return $loader->load($file, $locale, $domain);
    }     
    
    /**
     * Returns Symfony requested format loader
     *
     * @param string $format A format value
     * 
     * @access private
     * @return \Symfony\Component\Translation\Loader\LoaderInterface
     * @throws \InvalidArgumentException
     */
    private function getLoader($format)
    {
        $service = sprintf('translation.loader.%s', $format);

        if (!$this->container->has($service)) {
            throw new \InvalidArgumentException(sprintf('Unable to find Symfony Translation loader for format "%s"', $format));
        }

        return $this->container->get($service);
    }    
    
    /**
     * Delete all cache transaltion files
     *
     * @return void
     * @access public
     */
    public function deleteCacheTranslationFiles()
    {
        $basePath_cache_translations = realpath($this->container->getParameter("kernel.cache_dir"). '/translations/');
        if ($basePath_cache_translations){
            $all_files = PiFileManager::ListFiles($basePath_cache_translations);
            foreach ($all_files as $filename ) {
                 unlink ($filename);
            }  
        }
    }     
    
    /**
     * Sets the specific sortOrders.
     * 
     * @param string $domain    The domain
     * @param string $extension The extension name
     * 
     * @return void
     * @access public
     */
    public function createTranslationFiles($domain = "messages", $extension = "yml")
    {
        $foundBundle   = $this->container->get('kernel')->getBundle('SfynxTranslatorBundle');
        $basePath      = $foundBundle->getPath() . "/Resources/translations/";
        $transfiles    = PiFileManager::GlobFiles($basePath . '*.'.$extension ); // more fast in linux but not in windows
        $dir           = PiFileManager::mkdirr($basePath);
        if (count($transfiles) == 0) {
            $all_locales = $this->container->get('sfynx.auth.locale_manager')->getAllLocales();
            foreach ($all_locales as $key => $lang) {
                $filename  = $basePath . $domain. "." . $lang .".".$extension;
                @file_put_contents($filename, '', LOCK_EX);
            }
        }
    }  
}

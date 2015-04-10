<?php
/**
 * This file is part of the <Encrypt> project.
 *
 * @subpackage Encrypt
 * @package    EventSubscriber
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2014-06-27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\EncryptBundle\EventSubscriber;

use Doctrine\ORM\Events;
use Gedmo\Mapping\MappedEventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\Annotations\Reader;
use \ReflectionClass;
use Sfynx\CoreBundle\Builder\PiEncryptorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Doctrine event subscriber which encrypt/decrypt entities
 * 
 * @subpackage Encrypt
 * @package    EventSubscriber 
 * @author     etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class EncryptSubscriber extends MappedEventSubscriber
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
            
    /**
     * Locale which is set on this listener.
     * If Entity being translated has locale defined it
     * will override this one
     *
     * @var string
     */
    public $locale = 'en_GB';
    
    /**
     * Encryptor interface namespace
     * @var String
     */
    
    public $interfaceclass = 'Sfynx\CoreBundle\Builder\PiEncryptorInterface';    
    
    /**
     * Sets autorization of load process
     *
     * @var string
     */
    public $_load_enabled = false;    
    
    /**
     * Sets autorization of update process
     * 
     * @var string
     */
    public $_update_enabled = false;
    
    /**
     * Options
     * @var Array
     */
    protected $options;
    
    /**
     * Encryptor
     * @var EncryptorInterface
     */
    protected $encryptor;    

    /**
     * Annotation reader
     * @var \Doctrine\Common\Annotations\Reader
     */
    protected $annReader;
    
    /**
     * Registr to avoid multi decode operations for one entity
     * @var array
     */
    protected $decodedRegistry = array();    

    /**
     * Initialization of subscriber
     * 
     * @param string $encryptorClass  The encryptor class.  This can be empty if 
     * a service is being provided.
     * @param string $secretKey The secret key. 
     * @param EncryptorInterface|NULL $service (Optional)  An EncryptorInterface.
     * This allows for the use of dependency injection for the encrypters.
     */
    public function __construct(Reader $annReader, $options, ContainerInterface $container) {
        $this->annReader = $annReader;
        $this->options   = $options;
        $this->container = $container;
    }    

    /**
     * Realization of EventSubscriber interface method.
     * @return Array Return all events which this subscriber is listening
     */
    public function getSubscribedEvents() {
    	return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postLoad,
    	);
    }    

    /**
     * Listen a preUpdate lifecycle event. Checking and encrypt entities fields
     * which have @Encrypted annotation. Using changesets to avoid preUpdate event
     * restrictions
     * 
     * @param LifecycleEventArgs $args 
     * 
     * @return void
     * @access public
     * @author etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function preUpdate(PreUpdateEventArgs $args) {
        if ($this->_update_enabled == true) {
            $entity = $args->getEntity();
            $em     = $args->getEntityManager();
            $uow    = $em->getUnitOfWork();
            $reflectionClass = new ReflectionClass($args->getEntity());
            $properties      = $reflectionClass->getProperties();
            $className = get_class($entity);
            foreach ($properties as $refProperty) {
                foreach ($this->options as $key => $encrypter) {
                    if (
                        isset($encrypter['encryptor_annotation_name'])
                        &&
                        isset($encrypter['encryptor_class'])
                        &&
                        isset($encrypter['encryptor_options'])
                    ) {
                        $this->encryptor = $this->getEncryptorService($key);
                        if ($this->annReader->getPropertyAnnotation($refProperty, $encrypter['encryptor_annotation_name'])) {
                            // we have annotation and if it decrypt operation, we must avoid duble decryption
                            $propName = $refProperty->getName();
                            // we encrypt the field
                            if ($refProperty->isPublic()) {
                                    $entity->$propName = $this->encryptor->encrypt($refProperty->getValue());
                            } else {
                                $methodName = \Sfynx\ToolBundle\Util\PiStringManager::capitalize($propName);
                                if ($reflectionClass->hasMethod($getter = 'get' . $methodName) && $reflectionClass->hasMethod($setter = 'set' . $methodName)) {
                                    // we get the locale value
                                    $locale = false;                                
                                    $om     = $args->getObjectManager();
                                    $object = $args->getObject();
                                    $meta   = $om->getClassMetadata(get_class($object));
                                    $config = $this->getConfiguration($om, $meta->name);
                                    if (isset($config['fields'])) {
                                        $locale = $this->getTranslatableLocale($object, $meta);
                                    }
                                    // we set the encrypt value
                                    $currentPropValue     = $entity->$getter();
                                    if (!empty($currentPropValue)) {
                                        $currentPropValue = $this->encryptor->encrypt($currentPropValue);
                                    }
                                    // we set locale value
                                    if (
                                        $locale
                                    ) {
//                                        if ($locale == $this->locale) {
//                                            $entity->$setter($currentPropValue);
//                                        }
                                        $entity->$setter($currentPropValue);
                                        $entity->translate($locale)->$setter($currentPropValue);
                                        //$uow->persist($entity);
                                        //$uow->computeChangeSets();
                                    }      
                                } else {
                                    throw new \RuntimeException(sprintf("Property %s isn't public and doesn't has getter/setter"));
                                }
                            }  
                        }
                    } else {
                    	throw new \RuntimeException(sprintf("encrypter is not correctly configured"));
                    }
                }
            }
        }
    }

    /**
     * Listen a prePersist lifecycle event. Checking and encrypt entities
     * which have @Encrypted annotation
     * 
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) {
    	$this->processFields($args, true);
    }
        
    /**
     * Listen a postLoad lifecycle event. Checking and decrypt entities
     * which have @Encrypted annotations (This event is called after an entity is constructed by the EntityManager)
     * 
     * @param LifecycleEventArgs $args 
     */
    public function postLoad(LifecycleEventArgs $args) {
        $this->processFields($args, false);
    }

    /**
     * Process (encrypt/decrypt) entities fields
     * 
     * @param LifecycleEventArgs $args 
     * @param Boolean $isEncryptOperation If true - encrypt, false - decrypt entity 
     * 
     * @return void
     * @access protected
     * @author etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function processFields(LifecycleEventArgs $args, $isEncryptOperation = true)
    {
        if ($this->_load_enabled == true) {
            $entity = $args->getEntity();
            $em     = $args->getEntityManager();
            $className = get_class($entity);
            $metadata = $em->getClassMetadata($className);        
            $encryptorMethod = $isEncryptOperation ? 'encrypt' : 'decrypt';
            $reflectionClass = new ReflectionClass($entity);
            $properties      = $reflectionClass->getProperties();
            foreach ($properties as $refProperty) {            
                foreach ($this->options as $key => $encrypter) {
                    if (
                        isset($encrypter['encryptor_annotation_name'])
                        &&
                        isset($encrypter['encryptor_class'])
                        &&
                        isset($encrypter['encryptor_options'])
                    ) {
                        $this->encryptor = $this->getEncryptorService($key);
                        if ($this->annReader->getPropertyAnnotation($refProperty, $encrypter['encryptor_annotation_name'])) {
                            // we have annotation and if it decrypt operation, we must avoid duble decryption
                            $propName = $refProperty->getName();
                            if ($refProperty->isPublic()) {
                                $entity->$propName = $this->encryptor->$encryptorMethod($refProperty->getValue());
                            } else {
                                $methodName = \Sfynx\ToolBundle\Util\PiStringManager::capitalize($propName);
                                if ($reflectionClass->hasMethod($getter = 'get' . $methodName) && $reflectionClass->hasMethod($setter = 'set' . $methodName)) {
                                    if ($isEncryptOperation) {
                                        // we set the encrypt value
                                        $currentPropValue = $entity->$getter();
                                        if (!empty($currentPropValue)) {
                                            $currentPropValue = $this->encryptor->$encryptorMethod($currentPropValue);
                                        }
                                        // we set locale value
                                        $entity->$setter($currentPropValue);
                                    } else {
                                        // we get the locale value
                                        $locale = $entity->getTranslatableLocale();
                                        //
                                        if (!empty($locale) && !is_null($locale)) {
                                        } elseif (isset($_GET['_locale'])) {
                                            $locale = $_GET['_locale'];
                                        } else {
                                            $locale = $this->locale;
                                        }
                                        //
                                        if (!$this->annReader->getPropertyAnnotation($refProperty, 'Gedmo\Mapping\Annotation\Translatable')) {
                                            if (!$this->hasInDecodedRegistry($className, $entity->getId(), $locale, $methodName)) {
                                                $currentPropValue = $entity->$getter();
                                                if (!empty($currentPropValue)) {
                                                    $currentPropValue = $this->encryptor->$encryptorMethod($currentPropValue);
                                                }
                                                $entity->$setter($currentPropValue);
                                                $this->addToDecodedRegistry($className, $entity->getId(), $locale, $methodName, $currentPropValue);
                                            }
                                        } else{
                                            $locales = $this->container->get('sfynx.auth.locale_manager')->getAllLocales(true);
                                            foreach( $locales as $key => $lang) {
                                                if ($lang['enabled'] == 1) {
                                                    if (!$this->hasInDecodedRegistry($className, $entity->getId(), $lang['id'], $methodName)) {
                                                        $currentPropValue_locale = $entity->translate($lang['id'])->$getter();
                                                        if (!empty($currentPropValue_locale)) {
                                                            $currentPropValue_locale = $this->encryptor->$encryptorMethod($currentPropValue_locale);
                                                        } 
                                                        if ($locale ==  $lang['id']) {
                                                            $entity->$setter($currentPropValue_locale);
                                                        }
                                                        $entity->translate($lang['id'])->$setter($currentPropValue_locale);
                                                        $this->addToDecodedRegistry($className, $entity->getId(), $lang['id'], $methodName, $currentPropValue_locale);
                                                        //print_r($this->decodedRegistry);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    throw new \RuntimeException(sprintf("Property %s isn't public and doesn't has getter/setter"));
                                }
                            }
                        }
                    } else {
                        throw new \RuntimeException(sprintf("encrypter %s is not correctly configured", $key));
                    }
                } 
            }
        }
    }

    /**
     * Encryptor factory. Checks and create needed encryptor
     * @param string $classFullName Encryptor namespace and name
     * @param string $secretKey Secret key for encryptor
     * 
     * @return EncryptorInterface
     * @throws \RuntimeException
     * @access protected
     * @author etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    protected function encryptorFactory($classFullName, $encryptor_options) {
    	$refClass = new \ReflectionClass($classFullName);
    	if ($refClass->implementsInterface($this->interfaceclass)) {
            return new $classFullName($encryptor_options);
    	} else {
            throw new \RuntimeException('Encryptor must implements interface EncryptorInterface');
    	}
    }
    
    protected function getEncryptorService($encrypter_name) {
    	$encryptorClass    = isset($this->options[$encrypter_name]['encryptor_class']) ? (string) $this->options[$encrypter_name]['encryptor_class'] : '';
    	$encryptor_options = isset($this->options[$encrypter_name]['encryptor_options']) ? (array) $this->options[$encrypter_name]['encryptor_options'] : null;
    	return $this->encryptorFactory($encryptorClass, $encryptor_options);
    }   
    
    /**
     * {@inheritDoc}
     */
    protected function getNamespace()
    {
    	return "Gedmo\Translatable";
    }
    
    /**
     * Validates the given locale
     *
     * @param string $locale - locale to validate
     * 
     * @throws \Gedmo\Exception\InvalidArgumentException if locale is not valid
     * @return void
     */
    protected function validateLocale($locale)
    {
    	if (!is_string($locale) || !strlen($locale)) {
            throw new \Gedmo\Exception\InvalidArgumentException('Locale or language cannot be empty and must be set through Listener or Entity');
    	}
    }    
        
    /**
     * Gets the locale to use for translation. Loads object
     * defined locale first..
     *
     * @param object $object
     * @param object $meta
     * 
     * @throws \Gedmo\Exception\RuntimeException - if language or locale property is not found in entity
     * @return string
     * @access protected
     */
    protected function getTranslatableLocale($object, $meta)
    {
    	$locale = $this->locale;
    	if (isset(self::$configurations[$this->name][$meta->name]['locale'])) {
            /** @var \ReflectionClass $class */
            $class = $meta->getReflectionClass();
            $reflectionProperty = $class->getProperty(self::$configurations[$this->name][$meta->name]['locale']);
            if (!$reflectionProperty) {
                $column = self::$configurations[$this->name][$meta->name]['locale'];
                throw new \Gedmo\Exception\RuntimeException("There is no locale or language property ({$column}) found on object: {$meta->name}");
            }
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($object);
            try {
                $this->validateLocale($value);
                $locale = $value;
            } catch(\Gedmo\Exception\InvalidArgumentException $e) {}
    	}
    	    
    	return $locale;
    }    
    
    /**
     * Check if we have entity in decoded registry
     * 
     * @param LifecycleEventArgs $args 
     * @return boolean
     */
    protected function hasInDecodedRegistry($className, $id, $locale, $methodeName) {
    	return isset($this->decodedRegistry[$className][$id][$locale][$methodeName]);
    }
    
    /**
     * Adds entity to decoded registry
     * 
     * @param LifecycleEventArgs $args 
     * 
     * @return void
     */
    protected function addToDecodedRegistry($className, $id, $locale, $methodeName, $currentPropValue) {
    	$this->decodedRegistry[$className][$id][$locale][$methodeName] = $currentPropValue;
    }    
}

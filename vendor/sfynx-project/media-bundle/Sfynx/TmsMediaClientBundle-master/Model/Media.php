<?php

/**
 * @author Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 */

namespace Tms\Bundle\MediaClientBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tms\Bundle\MediaClientBundle\Exception\MediaClientException;

class Media
{
    /*
     * @var string
     */
    const REMOVE_ACTION = 'remove';

    /*
     * @var string
     */
    const CREATE_ACTION = 'create';

    /*
     * @var string
     */
    const UPDATE_ACTION = 'update';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $publicUri;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var string
     */
    protected $providerName;

    /**
     * @var string
     */
    protected $providerReference;

    /**
     * @var array
     */
    protected $providerData;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var Datetime
     */
    protected $createdAt;

    /**
     * @var Datetime
     */
    protected $updatedAt;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;


    /**
     * @var array synchronizedActions
     */
    private $synchronizedActions = array(
        self::REMOVE_ACTION => true,
        self::CREATE_ACTION => true,
        self::UPDATE_ACTION => true
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->url = null;
        $this->initSynchronizedActionsValues();
    }

    /**
     * isImageable
     */
    public function isImageable()
    {
        if (null === $this->getPublicUri()) {
            return false;
        }

        if ("application/pdf" === $this->getMimeType()) {
            return true;
        }

        return (boolean)preg_match("#^image/#", $this->getMimeType());
    }

    /**
     * Get public data
     *
     * @return array
     */
    public function getPublicData()
    {
        return array(
            'providerName'      => $this->getProviderName(),
            'providerReference' => $this->getProviderReference(),
            'publicUri'         => $this->getPublicUri(),
            'extension'         => $this->getExtension(),
            'mimeType'          => $this->getMimeType()
        );
    }

    /**
     * initialize synchronized actions values
     */
    protected function initSynchronizedActionsValues(){
        $this->synchronizedActions = array(
            self::REMOVE_ACTION => true,
            self::CREATE_ACTION => true,
            self::UPDATE_ACTION => true
        );
    }

    /**
     * enable synchronization for a given action
     *
     * @param string $key synchornized action
     */
    public function enableSynchronizedAction($key)
    {
        $this->setSynchronizedActionValue($key, true);
    }

    /**
     * disable synchronization for a given action
     *
     * @param string $key synchornized action
     */
    public function disableSynchronizedAction($key)
    {
        $this->setSynchronizedActionValue($key, false);
    }

    /**
     * check if a given action is synchronized or not
     *
     * @param string $key synchornized action
     * @return boolean
     */
    public function isSynchronizedAction($key)
    {
        return $this->getSynchronizedActionValue($key);
    }

    /**
     * check if a given action exists
     *
     * @param  string $key synchornized action
     * @return true
     * @throw  Exception when action does'nt exist
     */
    private function existsSynchronizedAction($key)
    {
        if(!array_key_exists($key, $this->synchronizedActions)) {
            throw new MediaClientException(sprintf('Undefined action "%s"', $key));
        }

        return true;
    }

    /**
     * set synchronism's value of a given action
     *
     * @param string $key synchornized action
     * @param booleab $value synchornized action
     */
    protected function setSynchronizedActionValue($key, $value)
    {
        if($this->existsSynchronizedAction($key)) {
            $this->synchronizedActions[$key] = $value;
        }
    }

    /**
     * get synchronism's value of a given action
     *
     * @param string $key synchornized action
     * @return boolean
     */
    protected function getSynchronizedActionValue($key)
    {
        if($this->existsSynchronizedAction($key)) {
            return $this->synchronizedActions[$key];
        }
    }

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%s] %s',
            $this->getProviderName(),
            $this->getProviderReference()
        );
    }

    /**
     * Set uploaded file
     *
     * @param UploadedFile $uploadedFile
     * @return Media
     */
    public function setUploadedFile(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    /**
     * Get uploaded file
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Remove uploaded file
     *
     * @return Media
     */
    public function removeUploadedFile()
    {
        unlink($this->uploadedFile->getPathName());
        $this->uploadedFile = null;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set public uri
     *
     * @param string $publicUri
     * @return Media
     */
    public function setPublicUri($publicUri)
    {
        $this->publicUri = $publicUri;

        return $this;
    }

    /**
     * Get public uri
     *
     * @return string
     */
    public function getPublicUri()
    {
        return $this->publicUri;
    }

    /**
     * Get url
     *
     * @param string $extension
     * @return string
     */
    public function getUrl($extension = null, $query = array())
    {
        if (null === $this->getPublicUri()) {
            return '';
        }

        if (null === $extension) {
            $extension = $this->getExtension();
        }

        foreach ($query as $k => $param) {
            if (!$param) {
                unset($query[$k]);
            }
        }
        $query = http_build_query($query);

        $parsedUrl = parse_url($this->getPublicUri());
        $scheme   = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . ':' : '';
        $host     = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $port     = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user     = isset($parsedUrl['user']) ? $parsedUrl['user'] : '';
        $pass     = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsedUrl['path']) ? $parsedUrl['path'].'.'.$extension : '';
        $query    = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'].'&'.$query : $query ? '?'.$query : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return "$scheme//$user$pass$host$port$path$query$fragment";
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Media
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set providerName
     *
     * @param string $providerName
     * @return Media
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;

        return $this;
    }

    /**
     * Get providerName
     *
     * @return string 
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * Set providerReference
     *
     * @param string $providerReference
     * @return Media
     */
    public function setProviderReference($providerReference)
    {
        $this->providerReference = $providerReference;

        return $this;
    }

    /**
     * Get providerReference
     *
     * @return string
     */
    public function getProviderReference()
    {
        return $this->providerReference;
    }

    /**
     * Set providerData
     *
     * @param array $providerData
     * @return Media
     */
    public function setProviderData($providerData)
    {
        $this->providerData = $providerData;

        return $this;
    }

    /**
     * Get providerData
     *
     * @return array 
     */
    public function getProviderData()
    {
        return $this->providerData;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Media
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set created at
     *
     * @param Datetime $createdAt
     * @return Media
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get created at
     *
     * @return Datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updated at
     *
     * @param Datetime $updatedAt
     * @return Media
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updated at
     *
     * @return Datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

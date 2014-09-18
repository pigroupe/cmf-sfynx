<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace Tms\Bundle\MediaBundle\StorageMapper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gaufrette\Filesystem;
use Tms\Bundle\MediaBundle\StorageMapper\Rule\RuleInterface;

class StorageMapper implements StorageMapperInterface
{
    
    protected $storageProvider;
    protected $storageProviderServiceName;
    protected $rules = array();

    /**
     * Constructor
     */
    public function __construct(Filesystem $storageProvider, $storageProviderServiceName)
    {
        $this->storageProvider = $storageProvider;
        $this->storageProviderServiceName = $storageProviderServiceName;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageProviderServiceName()
    {
        return $this->storageProviderServiceName;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorageProvider()
    {
        return $this->storageProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * {@inheritdoc}
     */
    public function checkRules($mediaPath)
    {
        foreach($this->rules as $rule) {
            if (!$rule->check($mediaPath)) {
                return false;
            }
        }

        return true;
    }
}

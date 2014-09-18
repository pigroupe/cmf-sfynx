<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tms\Bundle\MediaBundle\Exception\ImagickException;

/**
 * Media
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\AdminBundle\Entity\MediaRepository")
 */
class Media
{
    
    const PICTURE_WIDTH_SIZE_MAX = 500;
    const PLACEMENT_VALIDATION_DELAY_ID = 1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\ManyToMany(targetEntity="App\AdminBundle\Entity\Croping", inversedBy="media")
    */
    protected $croping;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=150)
     */
    private $libelle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255, nullable=true)
     */
    private $extension;
    
    
    /**
     * @Assert\File(maxSize="6000000000000")
     */
    public $file;
    
    
     public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        //var_dump(__DIR__.'/../../../../web/'.$this->getUploadDir());die;
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/documents';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {           
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }        
        // Génère une codification du nom du fichier uploaded
        $this->preUpload();
//        var_dump(getimagesize($this->file));
//        var_dump(getimagesize($this->file));die;
        $this->setType($this->file->getMimeType());
        $this->setDateCreation(new \DateTime("now"));
        $this->setExtension($this->file->guessExtension());
        $this->file->move($this->getUploadRootDir(), $this->path.'.'.$this->file->guessExtension());
        $this->file = null;
    }
    
    
    private function definePictureFormat() 
    {
        $iRatio = 0;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
     * Set type
     *
     * @param string $type
     * @return Media
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Media
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Media
     */
    public function setDateCreation()
    {
        $this->dateCreation = new \DateTime();
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param string $dateModification
     * @return Media
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return string 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->croping = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add croping
     *
     * @param \App\AdminBundle\Entity\Croping $croping
     * @return Media
     */
    public function addCroping(\App\AdminBundle\Entity\Croping $croping)
    {
        $this->croping[] = $croping;

        return $this;
    }

    /**
     * Remove croping
     *
     * @param \App\AdminBundle\Entity\Croping $croping
     */
    public function removeCroping(\App\AdminBundle\Entity\Croping $croping)
    {
        $this->croping->removeElement($croping);
    }

    /**
     * Get croping
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCroping()
    {
        return $this->croping;
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
     * Set temp
     *
     * @param string $temp
     * @return Media
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;

        return $this;
    }

    /**
     * Get temp
     *
     * @return string 
     */
    public function getTemp()
    {
        return $this->temp;
    }
}

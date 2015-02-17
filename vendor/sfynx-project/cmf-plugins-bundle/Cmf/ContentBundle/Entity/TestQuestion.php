<?php

namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sfynx\CoreBundle\Model\AbstractDefault;

/**
 * Cmf\ContentBundle\Entity\TestQuestion
 * @ORM\Table(name="cont_test_question")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\TestQuestionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\TestQuestionTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class TestQuestion extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array('title','reponse1','reponse2','reponse3');

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\TestQuestionTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\TestQuestionTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;    
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=128, nullable=true)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string $reponse1
     * @Gedmo\Translatable
     * @ORM\Column(name="reponse1", type="string", length=128, nullable=true)
     * @Assert\NotBlank()
     */
    private $reponse1;

    /**
     * @var string $reponse2
     * @Gedmo\Translatable
     * @ORM\Column(name="reponse2", type="string", length=128, nullable=true)
     * @Assert\NotBlank()
     */
    private $reponse2;

    /**
     * @var string $title
     * @Gedmo\Translatable
     * @ORM\Column(name="reponse3", type="string", length=128, nullable=true)
     * @Assert\NotBlank()
     */
    private $reponse3;
    
    /**
     * @var integer $profil1
     * 
     * @ORM\Column(name="profil1", type="integer", length=128, nullable=true)
     */
    protected $profil1;
    
    /**
     * @var integer $profil2
     * 
     * @ORM\Column(name="profil2", type="integer", length=128, nullable=true)
     */
    protected $profil2;
    
    /**
     * @var integer $profil3
     * 
     * @ORM\Column(name="profil3", type="integer", length=128, nullable=true)
     */
    protected $profil3;

    /**
     * @ORM\ManyToMany(targetEntity="Cmf\ContentBundle\Entity\Test", mappedBy="questions");
     */
    protected $test;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return TestQuestion
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set reponse1
     *
     * @param string $reponse1
     * @return TestQuestion
     */
    public function setReponse1($reponse1)
    {
        $this->reponse1 = $reponse1;
    
        return $this;
    }

    /**
     * Get reponse1
     *
     * @return string 
     */
    public function getReponse1()
    {
        return $this->reponse1;
    }

    /**
     * Set reponse2
     *
     * @param string $reponse2
     * @return TestQuestion
     */
    public function setReponse2($reponse2)
    {
        $this->reponse2 = $reponse2;
    
        return $this;
    }

    /**
     * Get reponse2
     *
     * @return string 
     */
    public function getReponse2()
    {
        return $this->reponse2;
    }

    /**
     * Set reponse3
     *
     * @param string $reponse3
     * @return TestQuestion
     */
    public function setReponse3($reponse3)
    {
        $this->reponse3 = $reponse3;
    
        return $this;
    }

    /**
     * Get reponse3
     *
     * @return string 
     */
    public function getReponse3()
    {
        return $this->reponse3;
    }    
    /**
     * Set profil1
     *
     * @param integer $profil1
     * @return TestQuestion
     */
    public function setProfil1($profil1 = null)
    {
        $this->profil1 = $profil1;
    
        return $this;
    }

    /**
     * Get profil1
     *
     * @return integer 
     */
    public function getProfil1()
    {
        return $this->profil1;
    }
    /**
     * Set profil2
     *
     * @param integer $profil2
     * @return TestQuestion
     */
    public function setProfil2($profil2 = null)
    {
        $this->profil2 = $profil2;
    
        return $this;
    }

    /**
     * Get profil2
     *
     * @return integer 
     */
    public function getProfil2()
    {
        return $this->profil2;
    }
    /**
     * Set profil3
     *
     * @param integer $profil3
     * @return TestQuestion
     */
    public function setProfil3( $profil3 = null)
    {
        $this->profil3 = $profil3;
    
        return $this;
    }

    /**
     * Get profil3
     *
     * @return integer
     */
    public function getProfil3()
    {
        return $this->profil3;
    }  
    
    public function addTest(\Cmf\ContentBundle\Entity\Test $test)
    {
        $this->test[] = $test;
    
        return $this;
    }   
   
}
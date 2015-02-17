<?php

namespace Cmf\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Sfynx\CoreBundle\Model\AbstractDefault;

/**
 * Cmf\ContentBundle\Entity\Test
 * @ORM\Table(name="cont_test")
 * @ORM\Entity(repositoryClass="Cmf\ContentBundle\Repository\TestRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\TranslationEntity(class="Cmf\ContentBundle\Entity\Translation\TestTranslation")
 *
 * @category   plugins_Entities
 * @package    Entity
 * 
 */
class Test extends AbstractDefault 
{
    /**
     * List of al translatable fields
     * 
     * @var array
     * @access  protected
     */
    protected $_fields    = array();

    /**
     * Name of the Translation Entity
     * 
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'Cmf\ContentBundle\Entity\Translation\TestTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cmf\ContentBundle\Entity\Translation\TestTranslation", mappedBy="object", cascade={"persist", "remove"})
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
     * @var integer $blocgeneral
     *
     * @ORM\OneToOne(targetEntity="Cmf\ContentBundle\Entity\BlocGeneral" , cascade={"all"}, inversedBy="test");
     * @ORM\JoinColumn(name="blocgeneral_id", referencedColumnName="id", nullable=true)
     * @Assert\Valid
     */
    protected $blocgeneral;  

    /**
     *
     * @ORM\ManyToMany(targetEntity="Cmf\ContentBundle\Entity\TestQuestion", cascade={"all"}, inversedBy="test")
     * @ORM\JoinTable(name="cont_test_questions_test",
     *   joinColumns={@ORM\JoinColumn(name="test_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")}
     * )
     * @Assert\Valid
     */
    private $questions;
    
    /**
     * @var string $profil1
     * 
     * @ORM\Column(name="profil1", type="text", nullable=true)
     */
    private $profil1;
    
    /**
     * @var string $profil2
     * 
     * @ORM\Column(name="profil2", type="text", nullable=true)
     */
    private $profil2;
    
    /**
     * @var string $profil3
     * 
     * @ORM\Column(name="profil3", type="text", nullable=true)
     */
    private $profil3;
    
    /**
     * @var string $titreprofil1
     * 
     * @ORM\Column(name="titreprofil1", type="string", nullable=true)
     */
    private $titreprofil1;
    
    /**
     * @var string $titreprofil2
     * 
     * @ORM\Column(name="titreprofil2", type="string", nullable=true)
     */
    private $titreprofil2;
    
    /**
     * @var string $titreprofil3
     * 
     * @ORM\Column(name="titreprofil3", type="string", nullable=true)
     */
    private $titreprofil3;   
    
    /**
     * @var integer $old_id
     * 
     * @ORM\Column(name="old_id", type="string", length=255, nullable=true)
     */
    private $old_id;    
    
    /**
     * @var string $old_url
     * 
     * @ORM\Column(name="old_url", type="text", nullable=true)
     */
    private $old_url; 
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set blocgeneral
     *
     * @param \Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral
     * @return Test
     */
    public function setBlocgeneral(\Cmf\ContentBundle\Entity\BlocGeneral $blocgeneral = null)
    {
        $this->blocgeneral = $blocgeneral;
    
        return $this;
    }

    /**
     * Get blocgeneral
     *
     * @return \Cmf\ContentBundle\Entity\BlocGeneral 
     */
    public function getBlocgeneral()
    {
        return $this->blocgeneral;
    }

    /**
     * Remove questions
     *
     * @param \Cmf\ContentBundle\Entity\TestQuestion $questions
     */
    public function removeQuestions(\Cmf\ContentBundle\Entity\TestQuestion $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
    
    public function setQuestions( $questions)
    {
        foreach ($questions as $q) {
            $q->addTest($this);
        }

        $this->questions = $questions;
    }    
    /**
     * Set titreprofil1
     *
     * @param string $titreprofil1
     * @return Test
     */
    public function setTitreProfil1($titreprofil1)
    {
        $this->titreprofil1 = $titreprofil1;
    
        return $this;
    }

    /**
     * Get titreprofil1
     *
     * @return string 
     */
    public function getTitreProfil1()
    {
        return $this->titreprofil1;
    }

    /**
     * Set titreprofil2
     *
     * @param string $titreprofil2
     * @return Test
     */
    public function setTitreProfil2($titreprofil2)
    {
        $this->titreprofil2 = $titreprofil2;
    
        return $this;
    }

    /**
     * Get titreprofil2
     *
     * @return string 
     */
    public function getTitreProfil2()
    {
        return $this->titreprofil2;
    }

    /**
     * Set titreprofil3
     *
     * @param string $profil3
     * @return Test
     */
    public function setTitreProfil3($titreprofil3)
    {
        $this->titreprofil3 = $titreprofil3;
    
        return $this;
    }

    /**
     * Get titreprofil3
     *
     * @return string 
     */
    public function getTitreProfil3()
    {
        return $this->titreprofil3;
    }

    /**
     * Set profil1
     *
     * @param string $profil1
     * @return Test
     */
    public function setProfil1($profil1)
    {
        $this->profil1 = $profil1;
    
        return $this;
    }

    /**
     * Get profil1
     *
     * @return string 
     */
    public function getProfil1()
    {
        return $this->profil1;
    }

    /**
     * Set profil2
     *
     * @param string $profil2
     * @return Test
     */
    public function setProfil2($profil2)
    {
        $this->profil2 = $profil2;
    
        return $this;
    }

    /**
     * Get profil2
     *
     * @return string 
     */
    public function getProfil2()
    {
        return $this->profil2;
    }

    /**
     * Set profil3
     *
     * @param string $profil3
     * @return Test
     */
    public function setProfil3($profil3)
    {
        $this->profil3 = $profil3;
    
        return $this;
    }

    /**
     * Get profil3
     *
     * @return string 
     */
    public function getProfil3()
    {
        return $this->profil3;
    }

    /**
     * Set old_url
     *
     * @param integer $old_url
     * @return Test
     */
    public function setOldUrl($old_url)
    {
        $this->old_url = $old_url;
    
        return $this;
    }

    /**
     * Get old_url
     *
     * @return integer 
     */
    public function getOldUrl()
    {
        return $this->old_url;
    } 

    /**
     * Set old_id
     *
     * @param integer $old_id
     * @return Test
     */
    public function setOldId($old_id)
    {
        $this->old_id = $old_id;
    
        return $this;
    }

    /**
     * Get old_id
     *
     * @return integer 
     */
    public function getOldId()
    {
        return $this->old_id;
    }    
}
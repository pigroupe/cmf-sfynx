<?php

namespace MyApp\SiteBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Film
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length="255")
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     */    
    private $titre;

    /**
     * @ORM\Column(type="string",length="500")
     */    
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @Assert\NotBlank()
     */    
    private $categorie;

    /**
     * @ORM\ManyToMany(targetEntity="Acteur")
     */    
    private $acteurs;

    public function __construct()
    {
        $this->acteurs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * Get titre
     *
     * @return string $titre
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set categorie
     *
     * @param MyApp\SiteBundle\Entity\Categorie $categorie
     */
    public function setCategorie(\MyApp\SiteBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * Get categorie
     *
     * @return MyApp\SiteBundle\Entity\Categorie $categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Add acteurs
     *
     * @param MyApp\SiteBundle\Entity\Acteur $acteurs
     */
    public function addActeurs(\MyApp\SiteBundle\Entity\Acteur $acteurs)
    {
        $this->acteurs[] = $acteurs;
    }

    /**
     * Get acteurs
     *
     * @return Doctrine\Common\Collections\Collection $acteurs
     */
    public function getActeurs()
    {
        return $this->acteurs;
    }
}
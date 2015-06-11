<?php

namespace MyApp\SiteBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Acteur
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
     * @Assert\MinLength(limit = 3, message = "erreur.nom.minlength")
     */    
    private $nom;

    /**
     * @ORM\Column(type="string",length="255")
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     */    
    private $prenom;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */    
    private $dateNaissance;

    /**
     * @ORM\Column(type="string",length="1")
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"M", "F"})
     */    
    private $sexe;

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
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string $nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get prenom
     *
     * @return string $prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param date $dateNaissance
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * Get dateNaissance
     *
     * @return date $dateNaissance
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    /**
     * Get sexe
     *
     * @return string $sexe
     */
    public function getSexe()
    {
        return $this->sexe;
    }
    
    
    /**
     * Get prenom nom
     *
     * @return string $prenom.' '.$nom
     */
    public function getPrenomNom()
    {
        return $this->prenom.' '.$this->nom;
    }    
}
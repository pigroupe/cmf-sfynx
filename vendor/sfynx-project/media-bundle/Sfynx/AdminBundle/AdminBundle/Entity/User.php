<?php

namespace App\AdminBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\AdminBundle\Entity\UserRepository")
 */
class User extends BaseUser { 
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
//     public function getContent()
//    {
//        return $this->content;
//    }
//    
//     public function setContent($content)
//    {
//       $this->content = $content;
//    }
    
     /**
     * @ORM\ManyToMany(targetEntity="App\AdminBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

//    /**
//     * Add groups
//     *
//     * @param \App\AdminBundle\Entity\Group $groups
//     * @return User
//     */
//    public function addGroup(\App\AdminBundle\Entity\Group $groups)
//    {
//        $this->groups[] = $groups;
//
//        return $this;
//    }

//    /**
//     * Remove groups
//     *
//     * @param \App\AdminBundle\Entity\Group $groups
//     */
//    public function removeGroup(\App\AdminBundle\Entity\Group $groups)
//    {
//        $this->groups->removeElement($groups);
//    }
//
//    /**
//     * Get groups
//     *
//     * @return \Doctrine\Common\Collections\Collection 
//     */
//    public function getGroups()
//    {
//        return $this->groups;
//    }
    
    
    public function setGroups(Group $groups) {
       $this->groups[] = $groups;
       return $this;
      //$this->groups = $groups;
    }
}

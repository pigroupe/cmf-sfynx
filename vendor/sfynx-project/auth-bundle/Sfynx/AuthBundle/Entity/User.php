<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @subpackage   Auth
 * @package    Entity
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-11-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\Entity;

use FOS\UserBundle\Model\User as AbstractUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\AuthBundle\Validator\Constraint as MyAssert;
use Sfynx\AuthBundle\Repository\PermissionRepository;

/**
 * Storage agnostic overloding fos user object
 * 
 * @ORM\Entity(repositoryClass="Sfynx\AuthBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user", indexes={
 *      @ORM\Index(name="emailCanonical_idx", columns={"email_canonical"}),
 *      @ORM\Index(name="email_idx", columns={"email"})
 * })
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Your E-Mail adress has already been registered",
 *     groups={"registration"}
 * )
 * @UniqueEntity(
 *     fields={"emailCanonical"},
 *     message="Your E-Mail adress has already been registered",
 *     groups={"registration"}
 * )
 * @ORM\HasLifecycleCallbacks()
 * 
 * @subpackage   Auth
 * @package    Entity 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class User extends AbstractUser
{
    const ROLE_DEFAULT = 'ROLE_ALLOWED_TO_SWITCH';
    
    /**
     * @var bigint $id
     * 
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Assert\Length(max=50, groups={"registration"}, maxMessage="user.first_name.max_length")
     * @Assert\NotBlank(groups={"registration"}, message="user.field_required")
     * @Assert\Regex(pattern="/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u",groups={"registration"}, message="user.first_name.regex")
     */
    protected $username;    
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable = true)
     * @Assert\Length(max=50, groups={"registration"}, maxMessage="user.first_name.max_length")
     * @Assert\NotBlank(groups={"registration"}, message="user.field_required")
     * @Assert\Regex(pattern="/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u",groups={"registration"}, message="user.first_name.regex")
     */
    protected $name;
        
    /**
     * @var string $nickname
     *
     * @ORM\Column(name="nickname", type="string", nullable = true)
     * @Assert\Length(max=50, groups={"registration"}, maxMessage="user.first_name.max_length")
     * @Assert\NotBlank(groups={"registration"}, message="user.field_required")
     * @Assert\Regex(pattern="/^[[:alpha:]\s'\x22\-_&@!?()\[\]-]*$/u",groups={"registration"}, message="user.first_name.regex")
     */
    protected $nickname;   

    /**
     * @Assert\NotBlank(groups={"registration"},message="user.field_required")
     * @Assert\Email(groups={"registration"},message="user.field_email_format")
     * @MyAssert\EmailBlackList(groups={"registration"},message="user.field_email_black_list")
     */
    protected $email;    
    
     /**
     * @ORM\ManyToMany(targetEntity="Sfynx\AuthBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
    /**
     * @var string $langCode
     *
     * @ORM\ManyToOne(targetEntity="Sfynx\AuthBundle\Entity\Langue", cascade={"persist", "detach"})
     * @ORM\JoinColumn(name="lang_code", referencedColumnName="id", nullable=true)
     */
    protected $langCode; 
    
    /**
     * @var array
     * @ORM\Column(type="array")
     */
    protected $permissions = array('VIEW', 'EDIT', 'CREATE', 'DELETE');
    
    /**
     * @var \DateTime
     */
    public $expiresAt;
    
    /**
     * @var \DateTime
     */
    public $credentialsExpireAt;    
    
    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $created_at;  

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updated_at;   
    
    /**
     * @var datetime $archive_at
     *
     * @ORM\Column(name="archive_at", type="datetime", nullable=true)
     */
    protected $archive_at;    

    /**
     * @var boolean $archived
     *
     * @ORM\Column(name="archived", type="boolean", nullable=false)
     */
    protected $archived = false;    
    
    /**
     * @var array
     * @ORM\Column(name="application_tokens", type="array", nullable=true)
     */
    protected $applicationTokens;        

    public function __construct()
    {
        parent::__construct();
        $this->groups        = new \Doctrine\Common\Collections\ArrayCollection();        
        $this->applicationTokens = array();
    }  
    
    /**
     *
     * This method is used when you want to convert to string an object of
     * this Entity
     * ex: $value = (string)$entity;
     *
     */
    public function __toString() {
        return (string) $this->username;
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
     * Set id
     *
     * @return integer
     */
    public function setId($id)
    {
    	$this->id = (int) $id;
    }    

    /**
     * Add groups
     *
     * @param \Sfynx\AuthBundle\Entity\Group $groups
     */
    public function addGroupUser(\Sfynx\AuthBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGroupsUser()
    {
        return $this->groups;
    }
    
    /**
     * Set permissions
     *
     * @param array $permissions
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = array();
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }
    }
    
    /**
     * Get permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        $permissions = $this->permissions;
        foreach ($this->getGroups() as $group) {
            $permissions = array_merge($permissions, $group->getPermissions());
        }
        // we need to make sure to have at least one role
        $permissions[] = PermissionRepository::ShowDefaultPermission();
    
        return array_unique($permissions);
    }   

    /**
     * Adds a permission to the user.
     *
     * @param string $permission
     */
    public function addPermission($permission)
    {
        $permission = strtoupper($permission);
        if ($permission === PermissionRepository::ShowDefaultPermission()) {
        	return;
        }
        if (!in_array($permission, $this->permissions, true)) {
        	$this->permissions[] = $permission;
        }
    }  

    /**
     * Remove a permission to the user.
     *
     * @param string $permission
     */
    public function removePermission($permission)
    {
        $permission = strtoupper($permission);
        if (in_array($permission, $this->permissions, true)) {
            $key = array_search($permission, $this->permissions);
            unset($this->permissions[$key]);
        }
    }     

    /**
     * Adds a role to the user.
     *
     * @param string $role
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    /**
     * Returns the user roles
     *
     * Implements SecurityUserInterface
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;
        
        return array_unique($roles);
    }    

    /**
     * Set langCode
     *
     * @param Langue $langCode Language entity
     */
    public function setLangCode(Langue $langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     * Get langCode
     *
     * @return Langue 
     */
    public function getLangCode()
    {
        return $this->langCode;
    }
    
    /**
     * Set name
     *
     * @param text $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Get name
     *
     * @return text
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set nickname
     *
     * @param text $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }
    
    /**
     * Get nickname
     *
     * @return text
     */
    public function getNickname()
    {
        return $this->nickname;
    }
    
    public function getEnabled()
    {
    	return $this->enabled;
    }
        
    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }
    
    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }    
    
    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
    	$this->updated_at = $updatedAt;
    	return $this;
    }
    
    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
    	return $this->updated_at;
    }    
    
    /**
     * Set archive_at
     *
     * @param datetime $archiveAt
     */
    public function setArchiveAt($archiveAt)
    {
    	$this->archive_at = $archiveAt;
    	return $this;
    }
    
    /**
     * Get archive_at
     *
     * @return datetime
     */
    public function getArchiveAt()
    {
    	return $this->archive_at;
    }    
    
    /**
     * Set archived
     *
     * @param boolean $enabled
     */
    public function setArchived($archived)
    {
    	$this->archived = $archived;
    	return $this;
    }
    
    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
    	return $this->archived;
    }    
    
    /**
     * we add a token associate to an application
     *
     * @param string $application
     * @param string $token
     * 
     * @return integer
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function addTokenByApplicationName($application, $token)
    {
    	$this->setApplicationTokens(array(strtoupper($application.'::'.$token)));
    } 
    
    /**
     * Set application tokens
     *
     * @param array $all
     */
    public function setApplicationTokens( array $all)
    {
        foreach ($all as $one) {
            $one = strtoupper($one);
            if (is_null($this->applicationTokens)) {
                $this->applicationTokens[] = $one;
            } else {
                $info = explode("::", $one);
                $name = $info[0];
                $is_in = false;
                foreach ($this->applicationTokens as $key => $appl) {
                    $appl = strtoupper($appl);
                    $info_ = explode("::", $appl);
                    $name_ = $info_[0];
                    if ($name == $name_) {
                        $this->applicationTokens[ $key ] = $one;
                        $is_in = true;
                    }
                }
                if (!$is_in) {
                    $this->applicationTokens[] = $one;
                }
            }
        }
    }
    
    /**
     * Get application tokens
     *
     * @return array
     */
    public function getApplicationTokens()
    {
    	return $this->applicationTokens;
    }    
    
    /**
     * we return the token associated to the name given in param.
     *
     * @param string $name
     * 
     * @return integer
     * @access public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     */
    public function getTokenByApplicationName($name)
    {
    	$all_appl =  $this->applicationTokens;
    	if (!is_null($all_appl)) {
            foreach ($all_appl as $appl) {
                $string = strtoupper($appl);
                $replace = strtoupper($name.'::');
                $token = str_replace($replace, '', $string, $count);
                if ($count == 1) {
                    return strtoupper($token);
                }
            }
    	}
    	 
    	return '';
    }   
}

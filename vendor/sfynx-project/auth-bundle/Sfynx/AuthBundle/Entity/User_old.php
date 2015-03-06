<?php
/**
 * This file is part of the <Auth> project.
 * 
 * @category   Auth
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
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
 * @category   Auth
 * @package    Entity
 * @subpackage Model
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
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
     * @var boolean $global_opt_in
     * 
     * @ORM\Column(name="global_opt_in", type="boolean", nullable = true)
     */
    protected $global_opt_in;

    /**
     * @var boolean $site_opt_in
     * 
     * @ORM\Column(name="site_opt_in", type="boolean", nullable = true)
     */
    protected $site_opt_in;

    /**
     * @var string $birthday
     * 
     * @ORM\Column(name="birthday", type="string", nullable = true)
     */
    protected $birthday;

    /**
     * @var string $address
     * 
     * @ORM\Column(name="address", type="text", nullable = true)
     */
    protected $address;

    /**
     * @var string $zip_code
     * 
     * @ORM\Column(name="zip_code", type="string", nullable = true)
     */
    protected $zip_code;

    /**
     * @var string $city
     * 
     * @ORM\Column(name="city", type="string", nullable = true)
     */
    protected $city;

    /**
     * @var string $country
     * 
     * @ORM\Column(name="country", type="string", nullable = true)
     */
    protected $country;    
    
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
    
    public function setSalt($salt)
    {
        $this->salt = $salt;
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
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }
    
    /**
     * Get enabled
     *
     * @return boolean
     */    
    public function getEnabled()
    {
    	return $this->enabled;
    }
    
   /**
     * Get the [global_opt_in] column value.
     *
     * @return boolean
     */
    public function getGlobalOptIn()
    {
        return $this->global_opt_in;
    }

    /**
     * Get the [site_opt_in] column value.
     *
     * @return boolean
     */
    public function getSiteOptIn()
    {
        return $this->site_opt_in;
    }

    /**
     * Get the [optionally formatted] temporal [birthday] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * 
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthday($format = null)
    {
        if ($this->birthday === null) {
            return null;
        }

        if ($this->birthday === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new \DateTime($this->birthday);
        } catch (\Exception $x) {
            throw new \InvalidArgumentException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birthday, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);
    }

    /**
     * Get the [address] column value.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get the [zip_code] column value.
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the [country] column value.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
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
    
    /**
     * Sets the value of the [global_opt_in] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * 
     * @return User The current object (for fluent API support)
     */
    public function setGlobalOptIn($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }
        if ($this->global_opt_in !== $v) {
            $this->global_opt_in = $v;
        }


        return $this;
    }

    /**
     * Sets the value of the [site_opt_in] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * 
     * @return User The current object (for fluent API support)
     */
    public function setSiteOptIn($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }
        if ($this->site_opt_in !== $v) {
            $this->site_opt_in = $v;
        }

        return $this;
    }

    /**
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * 
     * @return User The current object (for fluent API support)
     */
    public function setBirthday($v)
    {
        $dt = $v;
        if ($this->birthday !== null || $v !== null) {
            $currentDateAsString = ($this->birthday !== null && $tmpDt = new \DateTime($this->birthday)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $v ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->birthday = $newDateAsString;
            }
        } // if either are not null

        return $this;
    }
    
    /**
     * Set the value of [address] column.
     *
     * @param string $v new value
     * 
     * @return User The current object (for fluent API support)
     */
    public function setAddress($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        if ($this->address !== $v) {
            $this->address = $v;
        }

        return $this;
    }

    /**
     * Set the value of [zip_code] column.
     *
     * @param string $v new value
     * 
     * @return User The current object (for fluent API support)
     */
    public function setZipCode($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        if ($this->zip_code !== $v) {
            $this->zip_code = $v;
        }

        return $this;
    }

    /**
     * Set the value of [city] column.
     *
     * @param string $v new value
     * 
     * @return User The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        if ($this->city !== $v) {
            $this->city = $v;
        }

        return $this;
    }

    /**
     * Set the value of [country] column.
     *
     * @param string $v new value
     * 
     * @return User The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }
        if ($this->country !== $v) {
            $this->country = $v;
        }

        return $this;
    }
    
    /**
     * @param  int  $expired
     * @return bool
     */
    public function isConnected($expired = 1800)
    {
        if ($this->lastLogin) {
            $dateLastLogin = $this->lastLogin;
            $dateTime = time() - $expired;

            if ($dateLastLogin->getTimestamp() > $dateTime) {
                return true;
            }
        }

        return false;
    }    
}
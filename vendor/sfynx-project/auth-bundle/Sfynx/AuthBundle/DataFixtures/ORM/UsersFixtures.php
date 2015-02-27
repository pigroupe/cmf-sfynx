<?php
/**
 * This file is part of the <Auth> project.
 *
 * @subpackage   Authentication
 * @package    DataFixtures
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2011-12-28
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sfynx\AuthBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Sfynx\AuthBundle\Entity\User;

/**
 * Users DataFixtures.
 *
 * @subpackage   Authentication
 * @package    DataFixtures
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class UsersFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load user fixtures
     *
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2011-12-28
     */    
    public function load(ObjectManager $manager, $env = '')
    {
        $field1 = new User();
        $field1->setUsername('admin');
        $field1->getUsernameCanonical('admin');
        $field1->setPlainPassword('admin');
        $field1->setEmail('admin@hotmail.com');
        $field1->setEmailCanonical('admin@hotmail.com');
        $field1->setEnabled(true);
        $field1->setRoles(array('ROLE_ADMIN'));
        $field1->setPermissions(array('VIEW', 'EDIT', 'CREATE', 'DELETE'));
        $field1->addGroupUser($this->getReference('group-admin'));
        $field1->setLangCode($this->getReference('lang-en'));
        $manager->persist($field1);

        $field2 = new User();
        $field2->setUsername('superadmin');
        $field2->getUsernameCanonical('superadmin');
        $field2->setPlainPassword('superadmin');
        $field2->setEmail('superadmin@gmail.com');
        $field2->setEmailCanonical('superadmin@gmail.com');
        $field2->setEnabled(true);
        $field2->setRoles(array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN'));
        $field2->setPermissions(array('VIEW', 'EDIT', 'CREATE', 'DELETE'));
        $field2->addGroupUser($this->getReference('group-superadmin'));
        $field2->setLangCode($this->getReference('lang-en'));
        $manager->persist($field2);
        
        $field3 = new User();
        $field3->setUsername('user');
        $field3->getUsernameCanonical('user');
        $field3->setPlainPassword('user');
        $field3->setEmail('user@gmail.com');
        $field3->setEmailCanonical('user@gmail.com');
        $field3->setEnabled(true);
        $field3->setRoles(array('ROLE_USER'));
        $field3->setPermissions(array('VIEW', 'EDIT', 'CREATE'));
        $field3->addGroupUser($this->getReference('group-user'));
        $field3->setLangCode($this->getReference('lang-fr'));
        $manager->persist($field3);  
        
        $field4 = new User();
        $field4->setName('Islam');
        $field4->setNickname('Ahmad');
        $field4->getUsernameCanonical('admin123');
        $field4->setUsername('admin123');
        $field4->setPlainPassword('testtest');
        $field4->setSalt('5467p78mqssowokg4gc0k4kcs08kkk8');
        $field4->setPassword('jMhPNtk/r/aDmrihsK2jw+D+zpnSxBxCL5v1tvCWZd/I4N7/gJiAjVPS0Xy2XkbVpVOPjgSHBBsskDmHWqEo4Q==');
        $field4->setEmail('admin@example.org');
        $field4->setEmailCanonical('admin@example.org');
        $field4->setEnabled(true);
        $field4->setRoles(array('ROLE_ADMIN'));
        $field4->setPermissions(array('VIEW', 'EDIT', 'CREATE', 'DELETE'));
        $field4->addGroupUser($this->getReference('group-admin'));
        $field4->setLangCode($this->getReference('lang-en'));        
        $manager->persist($field4);

        $field5 = new User();
        $field5->setName('Islam');
        $field5->setNickname('Issa');
        $field5->getUsernameCanonical('user123');
        $field5->setUsername('user123');
        $field5->setPlainPassword('testtest');
        $field5->setSalt('5467p78mqssowokg4gc0k4kcs08kkk8');
        $field5->setPassword('jMhPNtk/r/aDmrihsK2jw+D+zpnSxBxCL5v1tvCWZd/I4N7/gJiAjVPS0Xy2XkbVpVOPjgSHBBsskDmHWqEo4Q==');
        $field5->setEmail('user@example.org');
        $field5->setEmailCanonical('user@example.org');
        $field5->setEnabled(true);
        $field5->setRoles(array('ROLE_USER'));
        $field5->setPermissions(array('VIEW', 'EDIT', 'CREATE', 'DELETE'));
        $field5->addGroupUser($this->getReference('group-user'));
        $field5->setLangCode($this->getReference('lang-fr'));        
        $manager->persist($field5);

//         $path   = "/var/www/rapp_mr_miles/app/cache/connexion.csv";
//         file_put_contents($path, 'username,password'."\n", LOCK_EX);
//         for ($i=1; $i< 10000; $i++){
//         	$field = new User();
//         	$field->setPlainPassword('user_'.$i);
//         	$field->setUsername('user_'.$i.'@mail.com');
//         	$field->getUsernameCanonical('user_'.$i.'@mail.com');
//         	$field->setFirstName('user_'.$i);
//         	$field->setLastName('user_'.$i);
//         	$field->setEmail('user_'.$i.'@mail.com');
//         	$field->setEmailCanonical('user_'.$i.'@mail.com');
//         	$field->setEnabled(true);
//         	$field->setRoles(array('ROLE_USER'));
//         	$manager->persist($field);
//         	file_put_contents($path, 'user_'.$i.'@mail.com,user_'.$i."\n", FILE_APPEND);
//         }

        $manager->flush();
        
        $this->addReference('user-admin', $field1);
        $this->addReference('user-superadmin', $field2);
        $this->addReference('user-user', $field3);
        $this->addReference('user-admin-test', $field4);
        $this->addReference('user-user-test', $field5);
    }
    
    /**
     * Retrieve the order number of current fixture
     *
     * @return integer
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
     * @since 2011-12-28
     */
    public function getOrder()
    {
        // The order in which fixtures will be loaded
        return 2;
    }
}

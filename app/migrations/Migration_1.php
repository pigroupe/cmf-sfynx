<?php

use Sfynx\MigrationBundle\Model\abstractMigration;

/**
 * Class Migration_1
 *
 * Remove duplicated page
 */
class Migration_1 extends abstractMigration
{

    public function test()
    {
        return true;
    }

    public function Up()
    {
        $this->updateGroup();
    }
    
    public function updateGroup()
    {
        $em = $this->container->get('doctrine')->getManager();
        
        $group = new Dirisi\AuthBundle\Entity\Group("Group admin", array('ROLE_ADMIN'));
        $group->setEnabled(true);
        $em->persist($group);
        $em->flush();        
    }
}

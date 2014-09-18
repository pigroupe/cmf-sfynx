#Example Of Usage

Bundle allows to create doctrine entities with fields that will be protected with help of some encryption algorithm in database and it will be clearly for developer, because bundle is uses doctrine life cycle events.

Lets imagine that we are storing some private data in our database and we don't want 
to somebody can see it even if he will get raw database on his hands in some dirty way. 
With Sfynx this task can be easily made and we even don't see these processes 
because bundle uses some doctrine life cycle events. In database information will 
be encoded. In the same time entities in program will be clear as always and all 
these things will be happen automatically.

## Simple example

For example, we have some user entity with two fields which we want to encode in database.
We must import annotation `@Encrypted` first and then mark fields with it.

###Doctrine Entity

```php
namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// importing @Encrypted annotation
use Sfynx\EncryptBundle\Annotation as PI;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_v")
 */
class Word extends AbstractDefault
{
    /**
     * List of al translatable fields
     *
     * @var array
     * @access  protected
     */
    protected $_fields    = array('label');
    
    /**
     * Name of the Translation Entity
     *
     * @var array
     * @access  protected
     */
    protected $_translationClass = 'BootStrap\TranslatorBundle\Entity\Translation\WordTranslation';
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BootStrap\TranslatorBundle\Entity\Translation\WordTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;
        
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $label
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="label", type="string", length=255, nullable=true)
     * @Aesencrypted
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="string", length=128, nullable=false, unique=true)
     * @Aesencrypted
     * @Assert\NotBlank()
     */
    protected $keyword;
    
    //common getters/setters here...

}
```

###Controller

```php

namespace BootStrap\TranslatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BootStrap\TranslationBundle\Controller\abstractController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Word controller.
 *
 *
 * @category   Translator_Controllers
 * @package    Controller
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class WordController extends abstractController
{
    ... 
    
    /**
     * Finds and displays a Word entity.
     * 
     * @Route("/show-word/{id}", name="word", requirements={"id" = "\d+"})
     * @Secure(roles="ROLE_EDITOR")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @access    public
     * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>    
     */
    public function showAction($id)
    {
        $em     = $this->getDoctrine()->getManager();
        $locale    = $this->container->get('request')->getLocale();

        $NoLayout   = $this->container->get('request')->query->get('NoLayout');
        if (!$NoLayout)     $template = "show.html.twig"; else $template = "show.html.twig";
        
        $this->get('sfynx.annotation.subscriber.encrypters')->_load_enabled = true;
        $entity = $em->getRepository("SfynxTranslatorBundle:Word")->findOneByEntity($locale, $id, 'object');
        
        if (!$entity) {
            throw ControllerException::NotFoundEntity('Word');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render("SfynxTranslatorBundle:Word:$template", array(
            'entity'      => $entity,
            'NoLayout'      => $NoLayout,
            'delete_form' => $deleteForm->createView(),

        ));
    }    
}
```

###Template

```twig
<div>
    Decoded info:
    <tr>
        <td>{{ entity.id}}</td>
        <td>
            {{ entity.category }}
        </td>   
        <td>
           {{ entity.keyword }}
        </td>
        <td>
           {{ entity.label }}
        </td>   
    </tr>
</div> 
```

When we follow link /show-word/{x}, where x - id of our user in DB, we will see that 
user's information is decoded and in the same time information in database will 
be encoded. In database we'll have something like this:

```
id              |   1     | 1
category        | cat     | cat
keyword         | pi.test | iPkk/F+UBoVV7u4lFJokE+FVuKbUsPnwRRGFopKj7pQ=
label           | EN test | ui0oqawhOo1SX6ba90dKIzFYA1vD659MSLjjRmppV90=

```

So our information is encoded and all okay.

###Requirements

You need `php-mcrypt` extension for this example

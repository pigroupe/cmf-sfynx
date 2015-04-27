#Example Of Usage

Bundle allows to create doctrine entities with fields that will be .positioned


##Doctrine Entity

```php
namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// importing @Positioned annotation
use Sfynx\PositionBundle\Annotation as PI;

/**
 * @ORM\Entity
 * @ORM\Table(name="MyTable")
 */
class Word extends AbstractDefault
{

    ....
    
    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     * @PI\Positioned(SortableOrders = {"type":"relationship","field":"block","columnName":"block_id"})
     */
    protected $position; 
    
    ....
    
}
```
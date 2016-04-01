-------------------------------------------------------------------
DemoContext\\Domain\\Specification\\Handler\\InterfaceSpecification
-------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Specification\\Handler

.. php:interface:: InterfaceSpecification

    This file is part of the <Trigger> project.

    <code>
    $anyObject = new StdClass;
    $specification =
    new MySpecification1()
      ->andSpec(new MySpecification2())
      ->andSpec(
          new MySpecification3()
          ->orSpec(new MySpecification4())
      );
    ;
    $isOk = $specification->isSatisfedBy($anyObject);
    </code>

    .. php:method:: isSatisfiedBy($object)

        :param $object:

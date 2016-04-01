---------------------------------------------------------
DemoContext\\Domain\\Repository\\PriceRepositoryInterface
---------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Repository

.. php:interface:: PriceRepositoryInterface

    Film Repository interface

    .. php:method:: save($entity, $flush = false, $mergeCheck = true)

        save method that handles both new and detached instances well, and
        optionally flushes

        :type $entity: Price
        :param $entity:
        :type $flush: boolean
        :param $flush:
        :type $mergeCheck: boolean
        :param $mergeCheck:

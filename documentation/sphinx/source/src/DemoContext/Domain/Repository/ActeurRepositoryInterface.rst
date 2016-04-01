----------------------------------------------------------
DemoContext\\Domain\\Repository\\ActeurRepositoryInterface
----------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Repository

.. php:interface:: ActeurRepositoryInterface

    Acteur Repository interface

    .. php:method:: save($entity, $flush = false, $mergeCheck = true)

        save method that handles both new and detached instances well, and
        optionally flushes

        :param $entity:
        :type $flush: boolean
        :param $flush:
        :type $mergeCheck: boolean
        :param $mergeCheck:

    .. php:method:: allOrderByName($max)

        Select all film in order by title with a max limit

        :type $max: integer
        :param $max:

------------------------------------------------------------
DemoContext\\Domain\\Service\\Acteur\\Manager\\ActeurManager
------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Acteur\\Manager

.. php:class:: ActeurManager

    .. php:attr:: logger

        protected LoggerInterface

    .. php:attr:: repository

        protected ActeurRepositoryInterface

    .. php:attr:: translator

        protected Translator

    .. php:method:: __construct(LoggerInterface $logger, $translator, ActeurRepositoryInterface $repository)

        Constructor.

        :type $logger: LoggerInterface
        :param $logger:
        :param $translator:
        :type $repository: ActeurRepositoryInterface
        :param $repository:

    .. php:method:: saveFormProcess($entity)

        :param $entity:

    .. php:method:: remove($entity)

        :param $entity:

    .. php:method:: all($result = "object", $MaxResults = null, $orderby = '')

        :param $result:
        :param $MaxResults:
        :param $orderby:

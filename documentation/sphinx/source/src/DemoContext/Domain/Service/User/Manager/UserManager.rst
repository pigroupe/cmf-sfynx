--------------------------------------------------------
DemoContext\\Domain\\Service\\User\\Manager\\UserManager
--------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\User\\Manager

.. php:class:: UserManager

    .. php:attr:: logger

        protected LoggerInterface

    .. php:attr:: repository

        protected ActeurRepositoryInterface

    .. php:attr:: translator

        protected Translator

    .. php:method:: __construct(LoggerInterface $logger, $translator, UserRepositoryInterface $repository)

        Constructor.

        :type $logger: LoggerInterface
        :param $logger:
        :param $translator:
        :type $repository: UserRepositoryInterface
        :param $repository:

    .. php:method:: saveFormProcess($entity)

        :param $entity:

    .. php:method:: remove($entity)

        :param $entity:

    .. php:method:: getInactiveUser($period)

        :param $period:

    .. php:method:: getUser($result)

        :param $result:

    .. php:method:: all($result = "object", $MaxResults = null, $orderby = '')

        :param $result:
        :param $MaxResults:
        :param $orderby:

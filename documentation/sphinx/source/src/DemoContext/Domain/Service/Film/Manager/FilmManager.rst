--------------------------------------------------------
DemoContext\\Domain\\Service\\Film\\Manager\\FilmManager
--------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Film\\Manager

.. php:class:: FilmManager

    .. php:attr:: logger

        protected LoggerInterface

    .. php:attr:: repository

        protected FilmRepositoryInterface

    .. php:attr:: translator

        protected Translator

    .. php:method:: __construct(LoggerInterface $logger, $translator, FilmRepositoryInterface $repository)

        Constructor.

        :type $logger: LoggerInterface
        :param $logger:
        :param $translator:
        :type $repository: FilmRepositoryInterface
        :param $repository:

    .. php:method:: saveFormProcess($entity)

        :param $entity:

    .. php:method:: remove($entity)

        :param $entity:

    .. php:method:: all($result = "object", $MaxResults = null, $orderby = '')

        :param $result:
        :param $MaxResults:
        :param $orderby:

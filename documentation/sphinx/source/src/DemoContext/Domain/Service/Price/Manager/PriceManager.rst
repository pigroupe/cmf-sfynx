----------------------------------------------------------
DemoContext\\Domain\\Service\\Price\\Manager\\PriceManager
----------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Price\\Manager

.. php:class:: PriceManager

    .. php:attr:: logger

        protected LoggerInterface

    .. php:attr:: translator

        protected DataCollectorTranslator

    .. php:attr:: repository

        protected PriceRepositoryInterface

    .. php:method:: __construct(LoggerInterface $logger, $translator, PriceRepositoryInterface $repository)

        Constructor.

        :type $logger: LoggerInterface
        :param $logger:
        :param $translator:
        :type $repository: PriceRepositoryInterface
        :param $repository:

    .. php:method:: remove($entity)

        :param $entity:

---------------------------------------------------------
DemoContext\\Infrastructure\\Request\\CollectionConverter
---------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\Request

.. php:class:: CollectionConverter

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: __construct(ManagerRegistry $registry, ContainerInterface $container)

        :type $registry: ManagerRegistry
        :param $registry: Manager registry
        :type $container: ContainerInterface
        :param $container:

    .. php:method:: supports(ParamConverter $configuration)

        {@inheritdoc}

        Check, if object supported by our converter

        :type $configuration: ParamConverter
        :param $configuration:

    .. php:method:: apply(Request $request, ParamConverter $configuration)

        {@inheritdoc}

        Applies converting

        :type $request: Request
        :param $request:
        :type $configuration: ParamConverter
        :param $configuration:

    .. php:method:: getEntityManager()

        :returns: \Doctrine\ORM\EntityManager

    .. php:method:: getSecurityContext()

        :returns: \Symfony\Component\Security\Core\SecurityContext

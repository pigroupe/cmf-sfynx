------------------------------------------------------------
DemoApiContext\\Infrastructure\\EventListener\\HandlerLocale
------------------------------------------------------------

.. php:namespace: DemoApiContext\\Infrastructure\\EventListener

.. php:class:: HandlerLocale

    Custom locale handler.

    .. php:attr:: defaultLocale

        protected string

    .. php:attr:: container

        protected \Symfony\Component\DependencyInjection\ContainerInterface

    .. php:method:: __construct(ContainerInterface $container, $defaultLocale = 'en')

        Constructor.

        :type $container: ContainerInterface
        :param $container: The container service
        :param $defaultLocale:

    .. php:method:: onKernelRequest(GetResponseEvent $event)

        Invoked to modify the controller that should be executed.

        :type $event: GetResponseEvent
        :param $event: The event
        :returns: null|void

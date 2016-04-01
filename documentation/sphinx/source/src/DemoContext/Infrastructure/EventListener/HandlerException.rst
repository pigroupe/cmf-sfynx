------------------------------------------------------------
DemoContext\\Infrastructure\\EventListener\\HandlerException
------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\EventListener

.. php:class:: HandlerException

    Custom Exception handler.

    .. php:attr:: templating

        protected EngineInterface

    .. php:attr:: locale

        protected string

    .. php:attr:: container

        protected ContainerInterface

    .. php:attr:: kernel

        protected \AppKernel

    .. php:method:: __construct(EngineInterface $templating, AppKernel $kernel, ContainerInterface $container)

        Constructor.

        :type $templating: EngineInterface
        :param $templating:
        :type $kernel: AppKernel
        :param $kernel:
        :type $container: ContainerInterface
        :param $container: The containerservice

    .. php:method:: onKernelException(GetResponseForExceptionEvent $event)

        Event handler that renders not found page
        in case of a NotFoundHttpException

        :type $event: GetResponseForExceptionEvent
        :param $event:
        :returns: void

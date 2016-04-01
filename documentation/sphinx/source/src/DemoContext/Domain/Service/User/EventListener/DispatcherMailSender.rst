-----------------------------------------------------------------------
DemoContext\\Domain\\Service\\User\\EventListener\\DispatcherMailSender
-----------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\User\\EventListener

.. php:class:: DispatcherMailSender

    Response handler of authenticate response

    .. php:attr:: container

        protected ContainerInterface

    .. php:attr:: mailUser

        protected

    .. php:method:: __construct(ContainerInterface $container, $mailUser)

        Constructor.

        :type $container: ContainerInterface
        :param $container:
        :param $mailUser:

    .. php:method:: onVisitorSendMail(ObjectEvent $event)

        Invoked to modify the controller that should be executed.

        :type $event: ObjectEvent
        :param $event: The event
        :returns: void

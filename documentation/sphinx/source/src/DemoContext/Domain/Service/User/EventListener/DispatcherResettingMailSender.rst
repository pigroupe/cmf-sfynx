--------------------------------------------------------------------------------
DemoContext\\Domain\\Service\\User\\EventListener\\DispatcherResettingMailSender
--------------------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\User\\EventListener

.. php:class:: DispatcherResettingMailSender

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

    .. php:method:: onResettingSendMail(GenericEvent $evenement)

        Invoked to modify the controller that should be executed.

        :type $evenement: GenericEvent
        :param $evenement:
        :returns: void

    .. php:method:: getSubscribedEvents()

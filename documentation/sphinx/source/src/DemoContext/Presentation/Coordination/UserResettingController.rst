----------------------------------------------------------------
DemoContext\\Presentation\\Coordination\\UserResettingController
----------------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: UserResettingController

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: requestAction()

        Request reset user password: show form

    .. php:method:: sendEmailAction(Request $request)

        Request reset user password: submit form and send email

        :type $request: Request
        :param $request:

    .. php:method:: resetAction(Request $request, $token)

        Reset user password

        :type $request: Request
        :param $request:
        :param $token:

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

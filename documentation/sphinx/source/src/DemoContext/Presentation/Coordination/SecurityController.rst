-----------------------------------------------------------
DemoContext\\Presentation\\Coordination\\SecurityController
-----------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: SecurityController

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: renderLogin($data)

        Renders the login template with the given parameters. Overwrite this
        function in
        an extended controller to provide additional data for the login template.

        :type $data: array
        :param $data:
        :returns: \Symfony\Component\HttpFoundation\Response

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

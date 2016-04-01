----------------------------------------------------------
DemoContext\\Presentation\\Coordination\\DefaultController
----------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: DefaultController

    .. php:attr:: container

        protected ContainerInterface

    .. php:attr:: kernel

        protected HttpKernelInterface

    .. php:method:: indexAction(Request $request)

        :type $request: Request
        :param $request:

    .. php:method:: enregistrerDonnees()

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

    .. php:method:: setKernel(HttpKernelInterface $http_kernel)

        :type $http_kernel: HttpKernelInterface
        :param $http_kernel:

    .. php:method:: handleForward($controllerAction, $args)

        Forward to another controller:Action with params
        Requirements : Must have container in depedency to get the request service
        and must call [ setKernel, ["@http_kernel"] ] in service definition

        :param $controllerAction:
        :param $args:
        :returns: Response

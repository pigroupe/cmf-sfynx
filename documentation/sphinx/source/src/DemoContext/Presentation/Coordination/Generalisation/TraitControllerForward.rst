-------------------------------------------------------------------------------
DemoContext\\Presentation\\Coordination\\Generalisation\\TraitControllerForward
-------------------------------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination\\Generalisation

.. php:trait:: TraitControllerForward

    Trait Repository

    .. php:attr:: kernel

        protected HttpKernelInterface

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

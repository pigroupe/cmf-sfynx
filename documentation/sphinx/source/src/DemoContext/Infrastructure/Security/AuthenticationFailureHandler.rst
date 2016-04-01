-------------------------------------------------------------------
DemoContext\\Infrastructure\\Security\\AuthenticationFailureHandler
-------------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\Security

.. php:class:: AuthenticationFailureHandler

    .. php:method:: __construct(EventDispatcherInterface $dispatcher, HttpKernelInterface $httpKernel, HttpUtils $httpUtils, $options, LoggerInterface $logger = null)

        :type $dispatcher: EventDispatcherInterface
        :param $dispatcher:
        :type $httpKernel: HttpKernelInterface
        :param $httpKernel:
        :type $httpUtils: HttpUtils
        :param $httpUtils:
        :param $options:
        :type $logger: LoggerInterface
        :param $logger:

    .. php:method:: onAuthenticationFailure(Request $request, AuthenticationException $exception)

        :type $request: Request
        :param $request:
        :type $exception: AuthenticationException
        :param $exception:

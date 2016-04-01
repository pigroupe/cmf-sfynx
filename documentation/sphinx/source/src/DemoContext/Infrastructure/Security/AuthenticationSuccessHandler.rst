-------------------------------------------------------------------
DemoContext\\Infrastructure\\Security\\AuthenticationSuccessHandler
-------------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\Security

.. php:class:: AuthenticationSuccessHandler

    .. php:method:: __construct(LoggerInterface $logger, EventDispatcherInterface $dispatcher, Router $router, AuthorizationChecker $AuthorizationChecker, HttpUtils $httpUtils, $options)

        :type $logger: LoggerInterface
        :param $logger:
        :type $dispatcher: EventDispatcherInterface
        :param $dispatcher:
        :type $router: Router
        :param $router:
        :type $AuthorizationChecker: AuthorizationChecker
        :param $AuthorizationChecker:
        :type $httpUtils: HttpUtils
        :param $httpUtils:
        :param $options:

    .. php:method:: onAuthenticationSuccess(Request $request, TokenInterface $token)

        :type $request: Request
        :param $request:
        :type $token: TokenInterface
        :param $token:

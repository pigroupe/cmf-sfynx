--------------------------------------------------------------
DemoApiContext\\Infrastructure\\Security\\LogoutSuccessHandler
--------------------------------------------------------------

.. php:namespace: DemoApiContext\\Infrastructure\\Security

.. php:class:: LogoutSuccessHandler

    .. php:method:: __construct(LoggerInterface $logger, EventDispatcherInterface $dispatcher, Router $router, EntityManager $em)

        :type $logger: LoggerInterface
        :param $logger:
        :type $dispatcher: EventDispatcherInterface
        :param $dispatcher:
        :type $router: Router
        :param $router:
        :type $em: EntityManager
        :param $em:

    .. php:method:: logout(Request $request, Response $response, TokenInterface $token)

        :type $request: Request
        :param $request:
        :type $response: Response
        :param $response:
        :type $token: TokenInterface
        :param $token:

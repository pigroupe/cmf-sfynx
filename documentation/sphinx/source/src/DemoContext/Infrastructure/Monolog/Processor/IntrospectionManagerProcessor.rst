------------------------------------------------------------------------------
DemoContext\\Infrastructure\\Monolog\\Processor\\IntrospectionManagerProcessor
------------------------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\Monolog\\Processor

.. php:class:: IntrospectionManagerProcessor

    .. php:attr:: container

        protected \Symfony\Component\DependencyInjection\ContainerInterface

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

    .. php:method:: processRecord($record)

        :param $record:

    .. php:method:: isAnonymousToken()

        Return if yes or no the user is anonymous token.

        :returns: boolean

    .. php:method:: isUsernamePasswordToken()

        Return if yes or no the user is UsernamePassword token.

        :returns: boolean

    .. php:method:: getUser()

        Return the connected user entity.

        :returns: UserInterface

    .. php:method:: getToken()

        Return the token object.

        :returns: UsernamePasswordToken

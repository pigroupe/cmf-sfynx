------------------------------------------------------------------------------
DemoApiContext\\Infrastructure\\Monolog\\Processor\\IntrospectionUserProcessor
------------------------------------------------------------------------------

.. php:namespace: DemoApiContext\\Infrastructure\\Monolog\\Processor

.. php:class:: IntrospectionUserProcessor

    .. php:attr:: token_storage

        protected TokenStorageInterface

    .. php:method:: processRecord($record)

        :param $record:

    .. php:method:: __construct(TokenStorageInterface $token_storage)

        Constructor.

        :type $token_storage: TokenStorageInterface
        :param $token_storage:

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

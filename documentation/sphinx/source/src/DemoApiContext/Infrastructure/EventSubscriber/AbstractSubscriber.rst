-------------------------------------------------------------------
DemoApiContext\\Infrastructure\\EventSubscriber\\AbstractSubscriber
-------------------------------------------------------------------

.. php:namespace: DemoApiContext\\Infrastructure\\EventSubscriber

.. php:class:: AbstractSubscriber

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: __construct(ContainerInterface $container)

        Constructor

        :type $container: ContainerInterface
        :param $container:

    .. php:method:: getToken()

        Return the token object.

        :returns: \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken

    .. php:method:: getUserName()

        Return the connected user name.

        :returns: string User name

    .. php:method:: getUserPermissions()

        Return the user permissions.

        :returns: array User permissions

    .. php:method:: getUserRoles()

        Return the user roles.

        :returns: array User roles

    .. php:method:: setFlash($message, $type = "permission")

        Sets the flash message.

        :type $message: string
        :param $message:
        :type $type: string
        :param $type:
        :returns: void

    .. php:method:: getFlashBag()

        Gets the flash bag.

        :returns: \Symfony\Component\HttpFoundation\Session\Flash\FlashBag

    .. php:method:: isAnonymousToken()

        Return if yes or no the user is anonymous token.

        :returns: boolean

    .. php:method:: isUsernamePasswordToken()

        Return if yes or no the user is UsernamePassword token.

        :returns: boolean

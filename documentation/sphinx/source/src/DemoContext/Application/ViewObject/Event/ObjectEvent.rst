--------------------------------------------------------
DemoContext\\Application\\ViewObject\\Event\\ObjectEvent
--------------------------------------------------------

.. php:namespace: DemoContext\\Application\\ViewObject\\Event

.. php:class:: ObjectEvent

    .. php:method:: __construct($eventArgs, ContainerInterface $container, $entity)

        :param $eventArgs:
        :type $container: ContainerInterface
        :param $container:
        :param $entity:

    .. php:method:: getContainer()

        :returns: container

    .. php:method:: getEventArgs()

        :returns: eventArgs

    .. php:method:: getOptions()

        :returns: options

    .. php:method:: setOptions($option, $status)

        :param $option:
        :param $status:
        :returns: unknown void

    .. php:method:: getEntities()

        :returns: redirect

    .. php:method:: setEntities($entity, $status = "persist")

        :param $entity:
        :param $status:
        :returns: unknown void

    .. php:method:: getEntity()

        :returns: object entity

    .. php:method:: getEntityManager()

        :returns: object Manager

    .. php:method:: getUnitOfWork()

        :returns: object UnitOfWork Manager

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

    .. php:method:: isAnonymousToken()

        Return if yes or no the user is anonymous token.

        :returns: boolean

    .. php:method:: isUsernamePasswordToken()

        Return if yes or no the user is UsernamePassword token.

        :returns: boolean

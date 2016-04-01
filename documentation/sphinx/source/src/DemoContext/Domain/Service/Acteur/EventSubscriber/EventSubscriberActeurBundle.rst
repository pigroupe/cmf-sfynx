----------------------------------------------------------------------------------
DemoContext\\Domain\\Service\\Acteur\\EventSubscriber\\EventSubscriberActeurBundle
----------------------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Acteur\\EventSubscriber

.. php:class:: EventSubscriberActeurBundle

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: __construct(ContainerInterface $container)

        Constructs a new instance of SecurityListener.

        :type $container: ContainerInterface
        :param $container:

    .. php:method:: getSubscribedEvents()

        :returns: array

    .. php:method:: recomputeSingleEntityChangeSet(EventArgs $args)

        :type $args: EventArgs
        :param $args:
        :returns: void

    .. php:method:: dispatchActeur(EventArgs $eventArgs, $const)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :param $const:
        :returns: void

    .. php:method:: onClear(OnClearEventArgs $eventArgs)

        :type $eventArgs: OnClearEventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: postUpdate(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: postRemove(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: postPersist(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: preUpdate(PreUpdateEventArgs $eventArgs)

        :type $eventArgs: PreUpdateEventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: preRemove(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: prePersist(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: postLoad(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: preFlush(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: onFlush(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

    .. php:method:: postFlush(EventArgs $eventArgs)

        :type $eventArgs: EventArgs
        :param $eventArgs:
        :returns: void

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

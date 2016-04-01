-------------------------------------------------
DemoApiContext\\Infrastructure\\Event\\AuthEvents
-------------------------------------------------

.. php:namespace: DemoApiContext\\Infrastructure\\Event

.. php:class:: AuthEvents

    Contains all events thrown in the SFYNX

    .. php:const:: HANDLER_LOGIN_FAILURE

        The HANDLER_LOGIN_FAILURE event occurs when the connection is failure.

        This event allows you to modify the default values of the url redirection after a connection user.
        The event listener method receives a Sfynx\ToolBundle\Route\RouteTranslatorFactory instance.

    .. php:const:: HANDLER_LOGIN_CHANGERESPONSE

        The HANDLER_LOGIN_CHANGERESPONSE event occurs when the user connection is successful.

        This event allows you to modify the default values of the response after a user connection .
        The event listener method receives a Symfony\Component\HttpFoundation\Response instance.

    .. php:const:: HANDLER_LOGOUT_CHANGERESPONSE

        The HANDLER_LOGOUT_CHANGERESPONSE event occurs when the user deconnection is successful.

        This event allows you to modify the default values of the response before a user deconnection.
        The event listener method receives a Symfony\Component\HttpFoundation\Response instance.

-------------------------------------------------------------
DemoContext\\Infrastructure\\ViewObject\\Event\\ResponseEvent
-------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\ViewObject\\Event

.. php:class:: ResponseEvent

    .. php:method:: __construct($response, $request = null, $user = null, $locale = '')

        :param $response:
        :param $request:
        :param $user:
        :param $locale:

    .. php:method:: getResponse()

        :returns: Response

    .. php:method:: setResponse(Response $response)

        :type $response: Response
        :param $response:
        :returns: unknown void

    .. php:method:: getRequest()

        :returns: Request

    .. php:method:: setRequest(Request $request)

        :type $request: Request
        :param $request:
        :returns: unknown void

    .. php:method:: getUser()

        :returns: User

    .. php:method:: setUser(User $user)

        :type $user: User
        :param $user:
        :returns: unknown void

    .. php:method:: getLocale()

        :returns: locale

    .. php:method:: setLocale($locale)

        :param $locale:
        :returns: unknown void

    .. php:method:: getRedirect()

        :returns: redirect

    .. php:method:: setRedirect($route_name)

        :param $route_name:
        :returns: unknown void

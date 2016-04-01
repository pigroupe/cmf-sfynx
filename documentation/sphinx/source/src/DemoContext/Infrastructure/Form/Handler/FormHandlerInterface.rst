----------------------------------------------------------------
DemoContext\\Infrastructure\\Form\\Handler\\FormHandlerInterface
----------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\Form\\Handler

.. php:interface:: FormHandlerInterface

    .. php:method:: process($object = null)

        The process function should bind the form, check if it is valid
        and do any post treatment (persisting the entity etc.)

        :type $object: object
        :param $object:
        :returns: Boolean False to notify that postprocessing could not be executed. This can be the case when the form is not valid, the request method not supported etc.

-------------------------------------
DemoApiContext\\Domain\\Entity\\Group
-------------------------------------

.. php:namespace: DemoApiContext\\Domain\\Entity

.. php:class:: Group

    Group

    .. php:attr:: id

        protected integer

    .. php:attr:: permissions

        protected array

    .. php:method:: __construct($name = "", $roles = array())

        :param $name:
        :param $roles:

    .. php:method:: getId()

        Get id

        :returns: integer

    .. php:method:: setPermissions($permissions)

        Set permissions

        :type $permissions: array
        :param $permissions:

    .. php:method:: getPermissions()

        Get permissions

        :returns: array

    .. php:method:: addPermission($permission)

        Adds a permission to the user.

        :type $permission: string
        :param $permission:

    .. php:method:: removePermission($permission)

        Remove a permission to the user.

        :type $permission: string
        :param $permission:

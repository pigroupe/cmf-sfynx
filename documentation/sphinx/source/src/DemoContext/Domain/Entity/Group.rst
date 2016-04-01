----------------------------------
DemoContext\\Domain\\Entity\\Group
----------------------------------

.. php:namespace: DemoContext\\Domain\\Entity

.. php:class:: Group

    Group

    .. php:attr:: id

        protected integer

    .. php:attr:: permissions

        protected array

    .. php:attr:: created_at

        protected datetime

    .. php:attr:: updated_at

        protected datetime

    .. php:attr:: published_at

        protected date

    .. php:attr:: archive_at

        protected datetime

    .. php:attr:: enabled

        protected boolean

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

    .. php:method:: setCreatedValue()

    .. php:method:: setUpdatedValue()

    .. php:method:: setCreatedAt($createdAt)

        {@inheritdoc}

        :param $createdAt:

    .. php:method:: getCreatedAt()

        {@inheritdoc}

    .. php:method:: setUpdatedAt($updatedAt)

        {@inheritdoc}

        :param $updatedAt:

    .. php:method:: getUpdatedAt()

        {@inheritdoc}

    .. php:method:: setPublishedAt($publishedAt)

        {@inheritdoc}

        :param $publishedAt:

    .. php:method:: getPublishedAt()

        {@inheritdoc}

    .. php:method:: setArchiveAt($archiveAt)

        {@inheritdoc}

        :param $archiveAt:

    .. php:method:: getArchiveAt()

        {@inheritdoc}

    .. php:method:: setEnabled($boolean)

        :param $boolean:

    .. php:method:: getEnabled()

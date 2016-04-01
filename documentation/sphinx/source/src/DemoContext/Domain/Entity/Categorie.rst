--------------------------------------
DemoContext\\Domain\\Entity\\Categorie
--------------------------------------

.. php:namespace: DemoContext\\Domain\\Entity

.. php:class:: Categorie

    .. php:attr:: created_at

        protected datetime

    .. php:attr:: updated_at

        protected datetime

    .. php:attr:: published_at

        protected date

    .. php:attr:: archive_at

        protected datetime

    .. php:attr:: id

        protected integer

    .. php:attr:: archived

        protected boolean

    .. php:attr:: enabled

        protected boolean

    .. php:method:: setNom($nom)

        Set nom

        :type $nom: string
        :param $nom:

    .. php:method:: getNom()

        Get nom

        :returns: string $nom

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

    .. php:method:: setId($id)

        :param $id:

    .. php:method:: getId()

    .. php:method:: setArchived($archived)

        :param $archived:

    .. php:method:: getArchived()

    .. php:method:: setEnabled($boolean)

        :param $boolean:

    .. php:method:: getEnabled()

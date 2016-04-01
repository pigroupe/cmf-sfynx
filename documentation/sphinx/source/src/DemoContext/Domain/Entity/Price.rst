----------------------------------
DemoContext\\Domain\\Entity\\Price
----------------------------------

.. php:namespace: DemoContext\\Domain\\Entity

.. php:class:: Price

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

    .. php:method:: setName($name)

        Set nom

        :param $name:

    .. php:method:: getName()

        Get name

        :returns: string $name

    .. php:method:: setFilm(Film $film = null)

        Set film

        :type $film: Film
        :param $film:
        :returns: Price

    .. php:method:: getFilm()

        Get film

        :returns: \DemoContext\Domain\Entity\Film

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

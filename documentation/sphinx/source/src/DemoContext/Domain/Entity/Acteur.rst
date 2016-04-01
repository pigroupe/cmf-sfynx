-----------------------------------
DemoContext\\Domain\\Entity\\Acteur
-----------------------------------

.. php:namespace: DemoContext\\Domain\\Entity

.. php:class:: Acteur

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

    .. php:method:: setPrenom($prenom)

        Set prenom

        :type $prenom: string
        :param $prenom:

    .. php:method:: getPrenom()

        Get prenom

        :returns: string $prenom

    .. php:method:: setDateNaissance($dateNaissance)

        Set dateNaissance

        :type $dateNaissance: date
        :param $dateNaissance:

    .. php:method:: getDateNaissance()

        Get dateNaissance

        :returns: date $dateNaissance

    .. php:method:: setSexe($sexe)

        Set sexe

        :type $sexe: string
        :param $sexe:

    .. php:method:: getSexe()

        Get sexe

        :returns: string $sexe

    .. php:method:: getPrenomNom()

        Get prenom nom

        :returns: string $prenom.' '.$nom

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

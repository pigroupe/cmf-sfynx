---------------------------------
DemoContext\\Domain\\Entity\\Film
---------------------------------

.. php:namespace: DemoContext\\Domain\\Entity

.. php:class:: Film

    .. php:const:: TYPE_PHPNAME

        phpname type
        e.g. 'AuthorId'

    .. php:const:: TYPE_FIELDNAME

        column fieldname type
        e.g. 'author_id'

    .. php:attr:: fieldNames

        protected

        holds an array of fieldnames

        first dimension keys are the type constants e.g.
        static::$fieldNames[static::TYPE_PHPNAME][0] = 'Id'

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

    .. php:method:: __construct()

    .. php:method:: setTitre($titre)

        Set titre

        :type $titre: string
        :param $titre:

    .. php:method:: getTitre()

        Get titre

        :returns: string $titre

    .. php:method:: setDescription($description)

        Set description

        :type $description: string
        :param $description:

    .. php:method:: getDescription()

        Get description

        :returns: string $description

    .. php:method:: setCategorie(Categorie $categorie)

        Set categorie

        :type $categorie: Categorie
        :param $categorie:

    .. php:method:: setSingleIntIdCategorie(SingleIntIdEntity $categorie)

        Set categorie

        :type $categorie: SingleIntIdEntity
        :param $categorie:

    .. php:method:: getCategorie()

        Get categorie

        :returns: DemoContext\Domain\Entity\Categorie $categorie

    .. php:method:: addActeur(Acteur $acteur)

        Add acteur

        :type $acteur: Acteur
        :param $acteur:

    .. php:method:: getActeurs()

        Get acteurs

        :returns: Doctrine\Common\Collections\Collection $acteurs

    .. php:method:: removeActeur(Acteur $acteurs)

        Remove acteurs

        :type $acteurs: Acteur
        :param $acteurs:

    .. php:method:: setActeurs($acteurs)

        Set acteurs

        :type $acteurs: \DemoContext\Domain\Entity\Acteur
        :param $acteurs:
        :returns: Doctrine\Common\Collections\Collection $acteurs

    .. php:method:: addPrice(Price $prices)

        Add prices

        :type $prices: Price
        :param $prices:
        :returns: Film

    .. php:method:: removePrice(Price $price)

        Remove prices

        :type $price: Price
        :param $price:

    .. php:method:: getPrices()

        Get prices

        :returns: \Doctrine\Common\Collections\Collection

    .. php:method:: setPrices($prices)

        Set prices

        :type $prices: \DemoContext\Domain\Entity\Price
        :param $prices:
        :returns: Doctrine\Common\Collections\Collection

    .. php:method:: fromArray($arr, $keyType = self::TYPE_PHPNAME)

        Populates the object using an array.

        This is particularly useful when populating an object from one of the
        request arrays (e.g. $_POST).  This method goes through the column names,
        checking to see whether a matching key exists in populated array. If so
        the setByName() method is called for that column.

        You can specify the key type of the array by additionally passing one of
        the class type constants BasePeer::TYPE_PHPNAME,
        BasePeer::TYPE_STUDLYPHPNAME,
        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
        The default key type is the column's BasePeer::TYPE_PHPNAME

        :type $arr: array
        :param $arr: An array to populate the object from.
        :type $keyType: string
        :param $keyType: The type of keys the array uses.
        :returns: void

    .. php:method:: getFieldNames($type)

        Returns an array of field names.

        :param $type:
        :returns: array           A list of field names

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

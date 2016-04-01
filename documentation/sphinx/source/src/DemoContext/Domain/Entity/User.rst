---------------------------------
DemoContext\\Domain\\Entity\\User
---------------------------------

.. php:namespace: DemoContext\\Domain\\Entity

.. php:class:: User

    User

    .. php:const:: TYPE_PHPNAME

        phpname type
        e.g. 'AuthorId'

    .. php:const:: TYPE_STUDLYPHPNAME

        studlyphpname type
        e.g. 'authorId'

    .. php:const:: TYPE_COLNAME

        column (peer) name type
        e.g. 'book.AUTHOR_ID'

    .. php:const:: TYPE_RAW_COLNAME

        column part of the column peer name
        e.g. 'AUTHOR_ID'

    .. php:const:: TYPE_FIELDNAME

        column fieldname type
        e.g. 'author_id'

    .. php:const:: TYPE_NUM

        num type
        simply the numerical array index, e.g. 4

    .. php:const:: ID

        the column name for the id field

    .. php:const:: USERNAME

        the column name for the username field

    .. php:const:: USERNAME_CANONICAL

        the column name for the username_canonical field

    .. php:const:: EMAIL

        the column name for the email field

    .. php:const:: EMAIL_CANONICAL

        the column name for the email_canonical field

    .. php:const:: ENABLED

        the column name for the enabled field

    .. php:const:: SALT

        the column name for the salt field

    .. php:const:: PASSWORD

        the column name for the password field

    .. php:const:: LAST_LOGIN

        the column name for the last_login field

    .. php:const:: LOCKED

        the column name for the locked field

    .. php:const:: EXPIRED

        the column name for the expired field

    .. php:const:: EXPIRES_AT

        the column name for the expires_at field

    .. php:const:: CONFIRMATION_TOKEN

        the column name for the confirmation_token field

    .. php:const:: PASSWORD_REQUESTED_AT

        the column name for the password_requested_at field

    .. php:const:: CREDENTIALS_EXPIRED

        the column name for the credentials_expired field

    .. php:const:: CREDENTIALS_EXPIRE_AT

        the column name for the credentials_expire_at field

    .. php:const:: ROLES

        the column name for the roles field

    .. php:const:: NAME

        the column name for the last_name field

    .. php:const:: NICKNAME

        the column name for the first_name field

    .. php:const:: BIRTHDAY

        the column name for the birthday field

    .. php:const:: ADDRESS

        the column name for the address field

    .. php:const:: ZIP_CODE

        the column name for the zip_code field

    .. php:const:: CITY

        the column name for the city field

    .. php:const:: COUNTRY

        the column name for the country field

    .. php:const:: CREATED_AT

        the column name for the created_at field

    .. php:const:: UPDATED_AT

        the column name for the updated_at field

    .. php:attr:: fieldNames

        protected

        holds an array of fieldnames

        first dimension keys are the type constants e.g.
        static::$fieldNames[static::TYPE_PHPNAME][0] = 'Id'

    .. php:attr:: id

        integer

    .. php:attr:: plainPassword

        protected string

        Plain password. Used for model validation. Must not be persisted.

    .. php:attr:: groups

        protected array

    .. php:attr:: name

        protected string

    .. php:attr:: nickname

        protected string

    .. php:attr:: email

        protected string

    .. php:attr:: birthday

        protected \DateTime

    .. php:attr:: address

        protected string

    .. php:attr:: zip_code

        protected string

    .. php:attr:: city

        protected string

    .. php:attr:: country

        protected string

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

    .. php:method:: __construct()

    .. php:method:: getId()

        Get id

        :returns: integer

    .. php:method:: setSalt($salt)

        :param $salt:

    .. php:method:: addGroupUser(Group $groups)

        Add groups

        :type $groups: Group
        :param $groups:

    .. php:method:: getGroupsUser()

        Get groups

        :returns: \Doctrine\Common\Collections\ArrayCollection

    .. php:method:: setGroups($groups)

        Set groups

        :type $groups: array
        :param $groups:
        :returns: User

    .. php:method:: getGroups()

        Get groups

        :returns: array

    .. php:method:: setName($name)

        Set name

        :type $name: string
        :param $name:
        :returns: User

    .. php:method:: getName()

        Get name

        :returns: string

    .. php:method:: setNickname($nickname)

        Set nickname

        :type $nickname: string
        :param $nickname:
        :returns: User

    .. php:method:: getNickname()

        Get nickname

        :returns: string

    .. php:method:: setBirthday($birthday)

        Set birthday

        :type $birthday: \DateTime
        :param $birthday:
        :returns: User

    .. php:method:: getBirthday()

        Get birthday

        :returns: \DateTime

    .. php:method:: setAddress($address)

        Set address

        :type $address: string
        :param $address:
        :returns: User

    .. php:method:: getAddress()

        Get address

        :returns: string

    .. php:method:: setZipCode($zipCode)

        Set zipCode

        :type $zipCode: string
        :param $zipCode:
        :returns: User

    .. php:method:: getZipCode()

        Get zipCode

        :returns: string

    .. php:method:: setCity($city)

        Set city

        :type $city: string
        :param $city:
        :returns: User

    .. php:method:: getCity()

        Get city

        :returns: string

    .. php:method:: setCountry($country)

        Set country

        :type $country: string
        :param $country:
        :returns: User

    .. php:method:: getCountry()

        Get country

        :returns: string

    .. php:method:: getRoles()

        Returns the user roles

        :returns: array The roles

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

    .. php:method:: isConnected($expired = 1800)

        :param $expired:
        :returns: bool

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

        :param $arr:
        :type $keyType: string
        :param $keyType: The type of keys the array uses.
        :returns: void

    .. php:method:: getFieldNames($type)

        Returns an array of field names.

        :param $type:
        :returns: array           A list of field names

    .. php:method:: getCivilityChoice()

        Get civilities choice used to get choice value in the FormType

    .. php:method:: getCivilityValues()

        Get Civility used for Form Validation

    .. php:method:: setPassword($plainPassword)

        :param $plainPassword:

    .. php:method:: getPassword()

        Gets the encrypted password.

        :returns: string

    .. php:method:: getPlainPassword()

    .. php:method:: getRoleChoice()

        Get civilities choice used to get choice value in the FormType

    .. php:method:: getRoleValues()

        Get Civility used for Form Validation

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

------------------------------------------------------------------
DemoContext\\Domain\\Service\\User\\Form\\Handler\\UserFormHandler
------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\User\\Form\\Handler

.. php:class:: UserFormHandler

    .. php:attr:: form

        protected FormInterface

    .. php:attr:: request

        protected Request

    .. php:attr:: processHandler

        protected

    .. php:attr:: obj

        protected Object

    .. php:attr:: processManager

        protected Object

    .. php:attr:: validData

        protected Object

    .. php:method:: __construct(FormInterface $form, Request $request)

        :type $form: FormInterface
        :param $form:
        :type $request: Request
        :param $request:

    .. php:method:: getValidMethods()

    .. php:method:: onSuccess()

    .. php:method:: getMessage()

    .. php:method:: validateRequest()

        Validates if the request can be accepted

    .. php:method:: process($object = null)

        {@inheritdoc}

        :param $object:

    .. php:method:: setValidData($object)

        :param $object:

    .. php:method:: getValidData()

    .. php:method:: hydrate($object = null)

        strategy:
        - on récupère les attributs de l'objet et celles de l'objet data du
        formulaire (car cette dernière peut en avoir moins)
        - on boucle sur les propriétés de l'objet comme suit à chaque
        itération :
        + on test que ce n'est pas l'attribut id
        + on vérifie que la propriété de l'objet Data existe bien dans l'objet
        + on génère le nom des getters et des setters
        + on boucle sur chaque méthode de la classe comme suit :
        ++ on vérifie que la méthode de l'objet contient bien le nom de
        l'attribut associé à l'objet Data
        ++ on récupère que si c'est un setter
        ++ on vérifie que les methodes sont identique
        ++ si c'est ok => on applique les setteurs
        ==> si un champ de l'entité a une valeur en BDD et qu'elle ne figure pas
        dans le formulaire, ce dernier n'est pas écrasé

        :type $object: object|null
        :param $object:
        :returns: type

    .. php:method:: setProcessManager($processManager)

        Set Process Manager

        :type $processManager: object
        :param $processManager:

    .. php:method:: getForm()

        Returns the current form

        :returns: Symfony\Component\Form\Form

    .. php:method:: setObject($obj)

        Set object

        :type $obj: object
        :param $obj:

    .. php:method:: getObject()

        Get object

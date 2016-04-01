--------------------------------------------------------
DemoContext\\Presentation\\Coordination\\GroupController
--------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: GroupController

    Group controller.

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: indexAction()

        Lists all Group entities.

    .. php:method:: createAction(Request $request)

        Creates a new Group entity.

        :type $request: Request
        :param $request:

    .. php:method:: createCreateForm(Group $entity)

        Creates a form to create a Group entity.

        :type $entity: Group
        :param $entity: The entity
        :returns: \Symfony\Component\Form\Form The form

    .. php:method:: newAction()

        Displays a form to create a new Group entity.

    .. php:method:: showAction($id)

        Finds and displays a Group entity.

        :param $id:

    .. php:method:: editAction($id)

        Displays a form to edit an existing Group entity.

        :param $id:

    .. php:method:: createEditForm(Group $entity)

        Creates a form to edit a Group entity.

        :type $entity: Group
        :param $entity: The entity
        :returns: \Symfony\Component\Form\Form The form

    .. php:method:: updateAction(Request $request, $id)

        Edits an existing Group entity.

        :type $request: Request
        :param $request:
        :param $id:

    .. php:method:: deleteAction(Request $request, $id)

        Deletes a Group entity.

        :type $request: Request
        :param $request:
        :param $id:

    .. php:method:: createDeleteForm($id)

        Creates a form to delete a Group entity by id.

        :type $id: mixed
        :param $id: The entity id
        :returns: \Symfony\Component\Form\Form The form

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

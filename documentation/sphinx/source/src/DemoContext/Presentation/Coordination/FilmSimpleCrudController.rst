-----------------------------------------------------------------
DemoContext\\Presentation\\Coordination\\FilmSimpleCrudController
-----------------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: FilmSimpleCrudController

    Film controller.

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: indexAction()

        Lists all Category entities.

    .. php:method:: showAction(Film $film)

        :type $film: Film
        :param $film:

    .. php:method:: newEditAction(Request $request, Film $film = null, $message = '')

        Displays a form to create or edit a Film entity.

        :type $request: Request
        :param $request:
        :type $film: Film
        :param $film:
        :type $message: String
        :param $message:
        :returns: Symfony\Component\Form\Form $form

    .. php:method:: createCreateForm(Film $film)

        :type $film: Film
        :param $film:

    .. php:method:: createEditForm(Film $film)

        :type $film: Film
        :param $film:

    .. php:method:: deleteAction(Film $film)

        Deletes a Film entity.

        :type $film: Film
        :param $film:

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

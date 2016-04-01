-------------------------------------------------------
DemoContext\\Presentation\\Coordination\\FilmController
-------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: FilmController

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: listerAction(ArrayCollection $films)

        :type $films: ArrayCollection
        :param $films:

    .. php:method:: topAction(ArrayCollection $films, $max = 5)

        :type $films: ArrayCollection
        :param $films:
        :param $max:

    .. php:method:: voirAction(Film $film)

        :type $film: Film
        :param $film:

    .. php:method:: editerAction(Film $film = null, $message = '')

        :type $film: Film
        :param $film:
        :param $message:

    .. php:method:: supprimerAction(Film $film)

        :type $film: Film
        :param $film:

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

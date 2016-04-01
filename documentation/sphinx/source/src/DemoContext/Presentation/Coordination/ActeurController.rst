---------------------------------------------------------
DemoContext\\Presentation\\Coordination\\ActeurController
---------------------------------------------------------

.. php:namespace: DemoContext\\Presentation\\Coordination

.. php:class:: ActeurController

    .. php:attr:: container

        protected ContainerInterface

    .. php:method:: listerAction(ArrayCollection $acteurs)

        :type $acteurs: ArrayCollection
        :param $acteurs:

    .. php:method:: topAction(ArrayCollection $acteurs, $max = 5)

        :type $acteurs: ArrayCollection
        :param $acteurs:
        :param $max:

    .. php:method:: rechercherAction()

    .. php:method:: editerAction(Acteur $acteur = null, $message = '')

        :type $acteur: Acteur
        :param $acteur:
        :param $message:

    .. php:method:: supprimerAction(Acteur $acteur)

        :type $acteur: Acteur
        :param $acteur:

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

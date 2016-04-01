-----------------------------------------------------------------
DemoContext\\PresentationBundle\\Twig\\Extension\\PiToolExtension
-----------------------------------------------------------------

.. php:namespace: DemoContext\\PresentationBundle\\Twig\\Extension

.. php:class:: PiToolExtension

    .. php:attr:: container

        protected \Symfony\Component\DependencyInjection\ContainerInterface

    .. php:method:: __construct(ContainerInterface $container)

        Constructor.

        :type $container: ContainerInterface
        :param $container: The service container

    .. php:method:: getName()

        Returns the name of the extension.

        :returns: string The extension name

    .. php:method:: getFilters()

        Returns a list of filters to add to the existing list.

        :returns: array An array of filters

    .. php:method:: getFunctions()

        Returns a list of functions to add to the existing list.

        :returns: array An array of functions

    .. php:method:: myfiltre($var, $prefix, $suffix)

        :param $var:
        :param $prefix:
        :param $suffix:

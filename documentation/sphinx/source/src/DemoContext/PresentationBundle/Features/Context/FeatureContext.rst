------------------------------------------------------------------
DemoContext\\PresentationBundle\\Features\\Context\\FeatureContext
------------------------------------------------------------------

.. php:namespace: DemoContext\\PresentationBundle\\Features\\Context

.. php:class:: FeatureContext

    Defines application features from the specific context.

    .. php:method:: __construct()

        Initializes context.

        Every scenario gets its own context instance.
        You can also pass arbitrary arguments to the context constructor through
        behat.yml.

    .. php:method:: iAmLoggedAs($role)

        Log with a role

        :param $role:

    .. php:method:: iWaitForSeconds($time)

        :param $time:

    .. php:method:: clickOn($element)

        Click on element CSS with index name

        :param $element:

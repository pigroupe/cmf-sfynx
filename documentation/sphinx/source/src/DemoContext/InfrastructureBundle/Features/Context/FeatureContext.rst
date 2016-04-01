--------------------------------------------------------------------
DemoContext\\InfrastructureBundle\\Features\\Context\\FeatureContext
--------------------------------------------------------------------

.. php:namespace: DemoContext\\InfrastructureBundle\\Features\\Context

.. php:class:: FeatureContext

    .. php:method:: __construct()

        Initializes context.

        Every scenario gets its own context instance.
        You can also pass arbitrary arguments to the context constructor through
        behat.yml.

    .. php:method:: setupFeature(BeforeFeatureScope $scope)

        :type $scope: BeforeFeatureScope
        :param $scope:

    .. php:method:: teardownFeature(AfterFeatureScope $scope)

        :type $scope: AfterFeatureScope
        :param $scope:

    .. php:method:: gatherContexts(BeforeScenarioScope $scope)

        :type $scope: BeforeScenarioScope
        :param $scope:

    .. php:method:: suppressDepreciationNotices(BeforeScenarioScope $scope)

        :type $scope: BeforeScenarioScope
        :param $scope:

    .. php:method:: afterScenario(AfterScenarioScope $scope)

        :type $scope: AfterScenarioScope
        :param $scope:

    .. php:method:: beforeStep(BeforeStepScope $scope)

        :type $scope: BeforeStepScope
        :param $scope:

    .. php:method:: afterStep(AfterStepScope $scope)

        :type $scope: AfterStepScope
        :param $scope:

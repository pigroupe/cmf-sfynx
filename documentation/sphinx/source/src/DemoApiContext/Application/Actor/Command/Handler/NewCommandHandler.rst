-----------------------------------------------------------------------
DemoApiContext\\Application\\Actor\\Command\\Handler\\NewCommandHandler
-----------------------------------------------------------------------

.. php:namespace: DemoApiContext\\Application\\Actor\\Command\\Handler

.. php:class:: NewCommandHandler

    Class NewCommandHandler.

    .. php:attr:: manager

        protected ManagerInterface

    .. php:attr:: workflowHandler

        protected WorkflowHandlerInterface

    .. php:method:: __construct(ManagerInterface $manager, WorkflowHandlerInterface $workflowHandler)

        :type $manager: ManagerInterface
        :param $manager:
        :type $workflowHandler: WorkflowHandlerInterface
        :param $workflowHandler:

    .. php:method:: process(CommandInterface $command)

        :type $command: CommandInterface
        :param $command:
        :returns: \DemoApiContext\Domain\Entity\Actor $entity

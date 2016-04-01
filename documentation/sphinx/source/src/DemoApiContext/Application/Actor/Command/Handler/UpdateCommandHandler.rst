--------------------------------------------------------------------------
DemoApiContext\\Application\\Actor\\Command\\Handler\\UpdateCommandHandler
--------------------------------------------------------------------------

.. php:namespace: DemoApiContext\\Application\\Actor\\Command\\Handler

.. php:class:: UpdateCommandHandler

    Class UpdateCommandHandler.

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
        :returns: object $entity

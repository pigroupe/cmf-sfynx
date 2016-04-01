-----------------------------------------------------------
DemoApiContext\\Presentation\\Coordination\\ActorController
-----------------------------------------------------------

.. php:namespace: DemoApiContext\\Presentation\\Coordination

.. php:class:: ActorController

    .. php:attr:: request

        protected RequestInterface

    .. php:attr:: resolver

        protected ResolverInterface

    .. php:attr:: responseHandler

        protected ResponseHandlerInterface

    .. php:attr:: allQueryHandler

        protected \DemoApiContext\Application\Actor\Query\Handler\AllQueryHandler

    .. php:attr:: getByIdsQueryHandler

        protected
        \DemoApiContext\Application\Actor\Query\Handler\GetByIdsQueryHandler

    .. php:attr:: oneQueryHandler

        protected \DemoApiContext\Application\Actor\Query\Handler\OneQueryHandler

    .. php:attr:: deleteCommandHandler

        protected
        \DemoApiContext\Application\Actor\Command\Handler\DeleteCommandHandler

    .. php:attr:: deleteManyCommandHandler

        protected
        \DemoApiContext\Application\Actor\Command\Handler\DeleteManyCommandHandler

    .. php:attr:: newValidationHandler

        protected
        \DemoApiContext\Application\Actor\Command\Validation\Handler\NewCommandValidationHandler

    .. php:attr:: updateValidationHandler

        protected
        \DemoApiContext\Application\Actor\Command\Validation\Handler\UpdateCommandValidationHandler

    .. php:method:: __construct(RequestInterface $request, ResolverInterface $resolver, ResponseHandlerInterface $responseHandler, QueryHandlerInterface $allQueryHandler, QueryHandlerInterface $getByIdsQueryHandler, QueryHandlerInterface $oneQueryHandler, CommandHandlerInterface $deleteCommandHandler, CommandHandlerInterface $deleteManyCommandHandler, CommandValidationHandlerInterface $updateValidationHandler, CommandValidationHandlerInterface $newValidationHandler)

        ActorController constructor.

        :type $request: RequestInterface
        :param $request:
        :type $resolver: ResolverInterface
        :param $resolver:
        :type $responseHandler: ResponseHandlerInterface
        :param $responseHandler:
        :type $allQueryHandler: QueryHandlerInterface
        :param $allQueryHandler:
        :type $getByIdsQueryHandler: QueryHandlerInterface
        :param $getByIdsQueryHandler:
        :type $oneQueryHandler: QueryHandlerInterface
        :param $oneQueryHandler:
        :type $deleteCommandHandler: CommandHandlerInterface
        :param $deleteCommandHandler:
        :type $deleteManyCommandHandler: CommandHandlerInterface
        :param $deleteManyCommandHandler:
        :type $updateValidationHandler: CommandValidationHandlerInterface
        :param $updateValidationHandler:
        :type $newValidationHandler: CommandValidationHandlerInterface
        :param $newValidationHandler:

    .. php:method:: getLimitAction()

        :returns: Response

    .. php:method:: getAction()

        :returns: Response

    .. php:method:: getByIdsAction()

        :returns: Response

    .. php:method:: postAction()

        :returns: Response

    .. php:method:: postAllAction()

    .. php:method:: putAction()

        :returns: Response

    .. php:method:: putAllAction()

    .. php:method:: deleteAction()

        :returns: Response

    .. php:method:: deleteManyAction()

        :returns: Response

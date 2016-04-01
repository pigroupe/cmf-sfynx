-------------------------------------------------------------
DemoApiContext\\Domain\\Service\\Actor\\Manager\\ActorManager
-------------------------------------------------------------

.. php:namespace: DemoApiContext\\Domain\\Service\\Actor\\Manager

.. php:class:: ActorManager

    Class ActorManager

    .. php:method:: __construct(LoggerInterface $logger, $translator, EventDispatcherInterface $eventDispatcher, SaveRepository $saveRepository, AbstractRepository $oneRepository, AbstractRepository $allRepository, AbstractRepository $getByIdsRepository, AbstractRepository $deleteOneRepository, AbstractRepository $deleteManyRepository)

        :type $logger: LoggerInterface
        :param $logger:
        :param $translator:
        :type $eventDispatcher: EventDispatcherInterface
        :param $eventDispatcher:
        :type $saveRepository: SaveRepository
        :param $saveRepository:
        :type $oneRepository: AbstractRepository
        :param $oneRepository:
        :type $allRepository: AbstractRepository
        :param $allRepository:
        :type $getByIdsRepository: AbstractRepository
        :param $getByIdsRepository:
        :type $deleteOneRepository: AbstractRepository
        :param $deleteOneRepository:
        :type $deleteManyRepository: AbstractRepository
        :param $deleteManyRepository:

    .. php:method:: create(ProfilVO $profil, SituationVO $situation, ContacTVO $contact, SalaryVO $salary)

        :type $profil: ProfilVO
        :param $profil:
        :type $situation: SituationVO
        :param $situation:
        :type $contact: ContacTVO
        :param $contact:
        :type $salary: SalaryVO
        :param $salary:
        :returns: Actor

    .. php:method:: update(IdVO $id, ProfilVO $profil, SituationVO $situation, ContacTVO $contact, SalaryVO $salary)

        :type $id: IdVO
        :param $id:
        :type $profil: ProfilVO
        :param $profil:
        :type $situation: SituationVO
        :param $situation:
        :type $contact: ContacTVO
        :param $contact:
        :type $salary: SalaryVO
        :param $salary:
        :returns: Actor

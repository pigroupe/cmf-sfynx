-----------------------------------------------------------------------------
DemoContext\\Domain\\Service\\Film\\Form\\Handler\\NewFilmFormHandlerStrategy
-----------------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Film\\Form\\Handler

.. php:class:: NewFilmFormHandlerStrategy

    .. php:attr:: translator

        protected DataCollectorTranslator

    .. php:attr:: filmRepository

        protected FilmRepository

    .. php:method:: __construct(DataCollectorTranslator $translator, FilmRepository $filmRepository)

        Constructor.

        :type $translator: DataCollectorTranslator
        :param $translator: Service of translation
        :type $filmRepository: FilmRepository
        :param $filmRepository:

    .. php:method:: handle(Request $request, Film $film)

        :type $request: Request
        :param $request:
        :type $film: Film
        :param $film:

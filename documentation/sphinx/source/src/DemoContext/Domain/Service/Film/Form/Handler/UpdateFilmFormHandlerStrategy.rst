--------------------------------------------------------------------------------
DemoContext\\Domain\\Service\\Film\\Form\\Handler\\UpdateFilmFormHandlerStrategy
--------------------------------------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Film\\Form\\Handler

.. php:class:: UpdateFilmFormHandlerStrategy

    .. php:attr:: translator

        protected DataCollectorTranslator

    .. php:attr:: filmRepository

        protected FilmRepository

    .. php:attr:: priceRepository

        protected PriceRepository

    .. php:attr:: priceManager

        protected PriceManager

    .. php:method:: __construct(DataCollectorTranslator $translator, FilmRepository $filmRepository, PriceRepository $priceRepository, PriceManager $priceManager)

        Constructor.

        :type $translator: DataCollectorTranslator
        :param $translator: Service of translation
        :type $filmRepository: FilmRepository
        :param $filmRepository:
        :type $priceRepository: PriceRepository
        :param $priceRepository:
        :type $priceManager: PriceManager
        :param $priceManager:

    .. php:method:: handle(Request $request, Film $film)

        :type $request: Request
        :param $request:
        :type $film: Film
        :param $film:

-----------------------------------------------------
DemoContext\\Domain\\Service\\Film\\Event\\FilmEvents
-----------------------------------------------------

.. php:namespace: DemoContext\\Domain\\Service\\Film\\Event

.. php:class:: FilmEvents

    .. php:const:: FILM_EVENT_PREPERSIST

        The FILM_EVENT_PREPERSIST event occurs

        The prePersist event occurs for a given entity before the respective EntityManager persist operation for that entity is executed.

    .. php:const:: FILM_EVENT_POSTPERSIST

        The FILM_EVENT_POSTPERSIST event occurs

        The postPersist event occurs for an entity after the entity has been made persistent. It will be invoked after the database insert operations.
        Generated primary key values are available in the postPersist event.

    .. php:const:: FILM_EVENT_PREUPDATE

        The FILM_EVENT_PREUPDATE event occurs

        The preUpdate event occurs before the database update operations to entity data.

    .. php:const:: FILM_EVENT_POSTUPDATE

        The FILM_EVENT_POSTUPDATE event occurs

        The postUpdate event occurs after the database update operations to entity data.

    .. php:const:: FILM_EVENT_PREREMOVE

        The FILM_EVENT_PREREMOVE event occurs

        The preRemove event occurs for a given entity before the respective EntityManager remove operation for that entity is executed.

    .. php:const:: FILM_EVENT_POSTREMOVE

        The FILM_EVENT_POSTREMOVE event occurs

        The postRemove event occurs for an entity after the entity has been deleted. It will be invoked after the database delete operations.

    .. php:const:: FILM_EVENT_POSTLOAD

        The FILM_EVENT_POSTLOAD event occurs

        The postLoad event occurs for an entity after the entity has been loaded into the current EntityManager from the database or after the refresh operation has been applied to it.

    .. php:const:: FILM_EVENT_PREFLUSH

        The FILM_EVENT_ONFLUSH event occurs

        The preFlush event occurs when the EntityManager#flush() operation is invoked,
        but before any changes to managed entities have been calculated. This event is always raised right after EntityManager#flush() call.

    .. php:const:: FILM_EVENT_ONFLUSH

        The FILM_EVENT_ONFLUSH event occurs

        The onFlush event occurs when the EntityManager#flush() operation is invoked,
        after any changes to managed entities have been determined but before any actual database operations are executed. The event is only raised if there is actually something to do for the underlying UnitOfWork. If nothing needs to be done,
        the onFlush event is not raised.

    .. php:const:: FILM_EVENT_POSTFLUSH

        The FILM_EVENT_ONFLUSH event occurs

        The postFlush event occurs when the EntityManager#flush() operation is invoked and after all actual database operations are executed successfully. The event is only raised if there is actually something to do for the underlying UnitOfWork. If nothing needs to be done,
        the postFlush event is not raised. The event won't be raised if an error occurs during the flush operation.

    .. php:const:: FILM_EVENT_ONCLEAR

        The TRIGGER_EVENT_LOADCLASSMETADATA event occurs

        The onClear event occurs when the EntityManager#clear() operation is invoked,
        after all references to entities have been removed from the unit of work.

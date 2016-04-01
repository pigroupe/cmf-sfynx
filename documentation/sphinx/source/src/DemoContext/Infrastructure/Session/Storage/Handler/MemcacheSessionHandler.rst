------------------------------------------------------------------------------
DemoContext\\Infrastructure\\Session\\Storage\\Handler\\MemcacheSessionHandler
------------------------------------------------------------------------------

.. php:namespace: DemoContext\\Infrastructure\\Session\\Storage\\Handler

.. php:class:: MemcacheSessionHandler

    .. php:const:: DEFAULT_MAX_EXECUTION_TIME

    .. php:method:: __construct(Memcache $memcache, $options = array())

        Constructor.

        List of available options:
        * prefix: The prefix to use for the memcache keys in order to avoid
        collision
        * expiretime: The time to live in seconds
        * locking: Indicates whether session locking is enabled or not
        * spin_lock_wait: Microseconds to wait between acquire lock tries
        * lock_max_wait: Maximum amount of seconds to wait for the lock

        :type $memcache: Memcache
        :param $memcache: A \Memcache instance
        :param $options:

    .. php:method:: open($savePath, $sessionName)

        {@inheritDoc}

        :param $savePath:
        :param $sessionName:

    .. php:method:: lockSession($sessionId)

        :param $sessionId:

    .. php:method:: unlockSession()

    .. php:method:: close()

        {@inheritDoc}

    .. php:method:: read($sessionId)

        {@inheritDoc}

        :param $sessionId:

    .. php:method:: write($sessionId, $data)

        {@inheritDoc}

        :param $sessionId:
        :param $data:

    .. php:method:: destroy($sessionId)

        {@inheritDoc}

        :param $sessionId:

    .. php:method:: gc($lifetime)

        {@inheritDoc}

        :param $lifetime:

    .. php:method:: __destruct()

        Destructor

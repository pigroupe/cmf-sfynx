<?php

namespace Sfynx\AuthBundle\Monolog\Handler;

use Monolog\Handler\SocketHandler as MonologSocketHandler;

class SocketHandler extends MonologSocketHandler
{
    protected function generateDataStream($record)
    {
        $r = $record;
        unset($r['formatted']);

        return json_encode($r)."\n";
    }
}

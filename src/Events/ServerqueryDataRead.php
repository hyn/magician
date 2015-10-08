<?php namespace Hyn\Teamspeak\Daemon\Events;

use Hyn\Teamspeak\Daemon\Abstracts\AbstractEvent;

class ServerqueryDataRead extends AbstractEvent {
    /**
     * Indicates whether this event is used for debugging purposes
     * @var bool
     */
    protected static $debug = true;

    /**
     * @return string
     */
    public function getName()
    {
        return 'data read';
    }
}
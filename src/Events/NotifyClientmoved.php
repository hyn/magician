<?php namespace Hyn\Teamspeak\Daemon\Events;

use Hyn\Teamspeak\Daemon\Abstracts\AbstractEvent;

class NotifyClientmoved extends AbstractEvent {

    /**
     * @return string
     */
    public function getName()
    {
        return 'moved';
    }
}
<?php namespace Hyn\Teamspeak\Daemon\Events;

use Hyn\Teamspeak\Daemon\Abstracts\AbstractEvent;

class NotifyCliententerview extends AbstractEvent {

    /**
     * @return string
     */
    public function getName()
    {
        return 'joined';
    }
}
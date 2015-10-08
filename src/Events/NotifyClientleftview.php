<?php namespace Hyn\Teamspeak\Daemon\Events;

use Hyn\Teamspeak\Daemon\Abstracts\AbstractEvent;

class NotifyClientleftview extends AbstractEvent {

    /**
     * @return string
     */
    public function getName()
    {
        return 'client left';
    }
}
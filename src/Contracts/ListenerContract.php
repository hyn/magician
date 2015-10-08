<?php namespace Hyn\Teamspeak\Daemon\Contracts;

use Hyn\Teamspeak\Daemon\Abstracts\AbstractEvent;

interface ListenerContract {
    /**
     * @param AbstractEvent $event
     * @return mixed|void
     */
    public function catched(AbstractEvent $event);
}
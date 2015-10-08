<?php namespace Hyn\Teamspeak\Daemon\Listeners;

use Hyn\Teamspeak\Daemon\Abstracts\AbstractEvent;
use Hyn\Teamspeak\Daemon\Broadcast\Message;
use Hyn\Teamspeak\Daemon\Contracts\ListenerContract;
use Laracasts\Presenter\PresentableTrait;


class ClientEventListener implements ListenerContract {

    use PresentableTrait;

    protected $presenter = 'Hyn\Teamspeak\Daemon\Presenters\ClientPresenter';

    /**
     * @param AbstractEvent $event
     * @return mixed|void
     */
    public function catched(AbstractEvent $event)
    {
        $this->data = $event->data;
        if($this->present()->nickname) {
            (new Message(
                "{$this->present()->nickname} {$event->getName()} from {$this->present()->country}"
                , $event->getName(), $this->present()->nickname
            ))->send();
        } else {
            // .. let's do nothing on disconnects
        }
    }
}
<?php namespace Hyn\Teamspeak\Daemon\Broadcast;

use Hyn\Teamspeak\Daemon\Config;
use NotifyMeHQ\NotifyMe\NotifyMeFactory;

class Message {
    public function __construct($message, $title, $sender = null) {
        $this->message = (string) $message;
        $this->title = (string) $title;
        $this->sender = $sender ? (string) $sender : null;
    }

    /**
     * @param null $connection
     * @return bool
     * @throws \Exception
     */
    public function send($connection = null) {
        $notifiers = Config::get('notifiers');

        if(!is_null($connection) && array_has($notifiers, $connection)) {
            $notifiers = array_only($notifiers, [$connection]);
        }

        $factory = new NotifyMeFactory();

        $successes = 0;

        foreach($notifiers as $notifier) {
            if($this->sender) {
                array_set($notifier, 'from', $this->sender);
            }

            $service = $factory->make($notifier);

            switch(array_get($notifier, 'driver')) {
                case 'hipchat':
                    $response = $service->notify(array_get($notifier, 'room'), $this->message);
                    break;
                default:
                    throw new \Exception("Driver not yet supported.");
            }

            if($response->isSent()) {
                $successes++;
            } else {
                throw new \Exception($response->message());
            }
        }

        return $successes == count($notifiers);
    }
}
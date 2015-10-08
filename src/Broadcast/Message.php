<?php namespace Hyn\Teamspeak\Daemon\Broadcast;

class Message {
    public function __construct($message, $title, $sender = null) {
        $this->message = (string) $message;
        $this->title = (string) $title;
        $this->sender = $sender ? (string) $sender : null;
    }

    public function send($connection = null) {

    }
}
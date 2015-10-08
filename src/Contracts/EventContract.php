<?php namespace Hyn\Teamspeak\Daemon\Contracts;

interface EventContract {

    /**
     * When the event is triggered, we will call this method
     *
     * @return mixed|void
     */
    public static function hit();
}
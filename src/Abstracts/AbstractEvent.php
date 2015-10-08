<?php namespace Hyn\Teamspeak\Daemon\Abstracts;

use Hyn\Teamspeak\Daemon\Contracts\EventContract;

abstract class AbstractEvent implements EventContract {

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * Indicates whether this event is used for debugging purposes
     * @var bool
     */
    protected static $debug = false;

    /**
     * When the event is triggered, we will call this method
     *
     * @return mixed|void
     */
    public static function hit() {
        if(static::$debug) return;
        /** @var array $arguments */
        $arguments = func_get_args();

        (new static)->handle($arguments);
    }

    /**
     * @param array $arguments
     */
    public function handle($arguments = []) {


        if(method_exists($this, 'pre')) {
            call_user_func_array([$this, 'pre'], $arguments);
        }

        $this->triggerListeners($arguments);


        if(method_exists($this, 'post')) {
            call_user_func_array([$this, 'post'], $arguments);
        }

        // we are debugging still:
        var_dump($arguments);
    }

    /**
     * @param array $callable
     */
    public function addListener($callable = []) {
        $this->listeners[] = $callable;
    }

    /**
     * @param $arguments
     */
    protected function triggerListeners($arguments) {
        foreach($this->listeners as $listener) {
            call_user_func_array($listener, $arguments);
        }
    }
}
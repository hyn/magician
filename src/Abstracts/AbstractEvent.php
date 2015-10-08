<?php namespace Hyn\Teamspeak\Daemon\Abstracts;

abstract class AbstractEvent {

    protected $listeners = [];

    /**
     * When the event is triggered, we will call this method
     *
     * @return mixed
     */
    public function hit() {

        $arguments = func_get_args();

        if(method_exists($this, 'pre')) {
            call_user_func_array([$this, 'pre'], $arguments);
        }

        $this->triggerListeners($arguments);


        if(method_exists($this, 'post')) {
            call_user_func_array([$this, 'post'], $arguments);
        }
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
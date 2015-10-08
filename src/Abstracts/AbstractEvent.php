<?php namespace Hyn\Teamspeak\Daemon\Abstracts;

use Hyn\Teamspeak\Daemon\Config;
use TeamSpeak3_Adapter_ServerQuery_Event;
use Hyn\Teamspeak\Daemon\Contracts\EventContract;

abstract class AbstractEvent implements EventContract {

    /**
     * @var array
     */
    public $data = [];

    /**
     * Indicates whether this event is used for debugging purposes
     * @var bool
     */
    protected static $debug = false;

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * When the event is triggered, we will call this method
     *
     * @return mixed|void
     */
    public static function hit() {
        if(static::$debug) return;
        (new static)->handle(func_get_args());
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
    }

    /**
     * @param $arguments
     */
    protected function triggerListeners($arguments) {
        $listeners = Config::get('listeners');
        foreach(array_get($listeners, class_basename($this), []) as $listener) {
            $instance = new $listener();
            $instance->catched($this);
        }
    }

    /**
     * @param $arguments
     */
    protected function pre($arguments) {
        if(empty($arguments)) {
            return;
        }

        if(!is_array($arguments)) {
            $arguments = [$arguments];
        }

        if($arguments instanceof \TeamSpeak3_Adapter_ServerQuery_Reply) {
            return;
        }
        foreach($arguments as $argument) {
            if($argument instanceof TeamSpeak3_Adapter_ServerQuery_Event) {
                var_dump($argument);
                var_dump($argument->getData());
                $this->data = $argument->getData();
            } else {
                var_dump($argument);
            }
        }
    }
}
<?php namespace Hyn\Teamspeak\Daemon\Console;

use Illuminate\Support\Collection;
use TeamSpeak3;
use TeamSpeak3_Helper_Signal;

/**
 * Class Daemon
 *
 * @package Hyn\Teamspeak\Daemon\Console
 */
class Daemon {

    /**
     * @var array
     */
    protected $connection = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var \TeamSpeak3_Adapter_ServerQuery
     */
    protected $query;

    /**
     * @param array $connection
     * @param array $config
     */
    public function __construct($connection = [], $config = []) {

        $this->connection = $connection;

        $this->config = $config;

        $this->query = TeamSpeak3::factory("serverquery://{$connection['username']}:{$connection['password']}@{$connection['host']}:{$connection['query_port']}/?server_port={$connection['server_port']}&blocking=0");

        $this->registerServerListener();
    }

    /**
     * Registers event listeners for certain signals
     */
    protected function registerServerListener() {
        $this->query->notifyRegister("server");

        foreach($this->readKnownEvents() as $event => $class) {
            TeamSpeak3_Helper_Signal::getInstance()->subscribe($event, [$class, 'hit']);
        }
    }

    /**
     * Daemonizes the query
     */
    public function daemonize() {
        while(true) {
            $this->query->getAdapter()->wait();
        }
    }

    protected function readKnownEvents() {

        $events = new Collection();

        foreach(glob(__DIR__ . '/../Events/*.php') as $eventFile) {
            $events->put(basename($eventFile, '.php'), sprintf('Hyn\Teamspeak\Daemon\Events\%s', basename($eventFile, '.php')));
        }

        return $events;
    }

}
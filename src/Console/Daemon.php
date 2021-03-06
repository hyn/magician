<?php namespace Hyn\Teamspeak\Daemon\Console;

use Carbon\Carbon;
use Hyn\Teamspeak\Daemon\Broadcast\Message;
use Illuminate\Support\Collection;
use TeamSpeak3;
use TeamSpeak3_Helper_Signal;
use TeamSpeak3_Transport_Exception;

/**
 * Class Daemon
 *
 * @package Hyn\Teamspeak\Daemon\Console
 * @see http://media.teamspeak.com/ts3_literature/TeamSpeak%203%20Server%20Query%20Manual.pdf
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
     * @var \TeamSpeak3_Node_Server
     */
    protected $query;

    /**
     * @param array $connection
     * @param array $config
     */
    public function __construct($connection = [], $config = []) {

        $this->connection = $connection;

        $this->config = $config;

        $success = false;
        while($success === false) {
            try {
                $this->query = TeamSpeak3::factory("serverquery://{$connection['username']}:{$connection['password']}@{$connection['host']}:{$connection['query_port']}/?server_port={$connection['server_port']}&blocking=0");

                $this->registerServerListener();
            } catch (TeamSpeak3_Transport_Exception $e) {
                sleep(1);
                continue;
            }

            $success = true;
        }
    }

    /**
     * Registers event listeners for certain signals
     *
     * @debug: servernotifyregister event={server|channel|textserver|textchannel|textprivate} [id={channelID}]
     */
    protected function registerServerListener() {
        $this->query->notifyRegister("server");

        /** @var \TeamSpeak3_Node_Channel $channel */
        foreach($this->query->channelList() as $channel)
        {
            $this->query->notifyRegister("channel", $channel->getId());
        }

        foreach($this->readKnownEvents() as $event => $class) {

            // register the event onto the Signal stack events
            TeamSpeak3_Helper_Signal::getInstance()->subscribe($event, [$class, 'hit']);

            // inform the terminal about the event subscription
            echo "Registered {$event} subscription with class {$class}\n";
        }
    }

    /**
     * Daemonizes the query
     */
    public function daemonize() {
        (new Message('Started daemon', 'Daemon started'))->send();

        $daemonizedAt = Carbon::now();

        while(true) {

            if($daemonizedAt->diffInMinutes() > 9) {
                $this->query->request('whoami');
            }

            try {
                $this->query->getAdapter()->wait();
            } catch (\Exception $e) {
                echo "{$e->getMessage()}\n";
                echo "{$e->getTraceAsString()}\n\n";
                // ends the loop on exceptions
                (new Message("Daemon exceptioned: {$e->getMessage()}", 'Daemon exception'))->send();
                sleep(1);
                return;
            }
        }
    }

    /**
     * Loads all known events from our Events namespace
     *
     * @return Collection
     */
    protected function readKnownEvents() {

        $events = new Collection();

        foreach(glob(__DIR__ . '/../Events/*.php') as $eventFile) {
            $events->put(lcfirst(basename($eventFile, '.php')), sprintf('Hyn\Teamspeak\Daemon\Events\%s', basename($eventFile, '.php')));
        }

        return $events;
    }

    /**
     * {@inheritdoc}
     */
    function __destruct()
    {
        (new Message('The daemon has been forcefully stopped, the __destruct has been triggered.', 'Daemon stopped'))->send();
    }


}
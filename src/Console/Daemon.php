<?php namespace Hyn\Teamspeak\Daemon\Console;

use TeamSpeak3;

class Daemon {

    protected $connection = [];

    protected $config = [];

    protected $query;

    public function __construct($connection = [], $config = []) {

        $this->connection = $connection;

        $this->config = $config;

        $this->query = TeamSpeak3::factory("serverquery://{$connection['username']}:{$connection['password']}@{$connection['host']}:{$connection['query_port']}/?server_port={$connection['server_port']}");
    }
}
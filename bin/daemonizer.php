#!/usr/bin/php
<?php namespace Hyn\Teamspeak\Daemon;

require_once __DIR__ .'/../vendor/autoload.php';



use Hyn\Teamspeak\Daemon\Console\Daemon;

/**
 * Class Daemonizer
 *
 * @package Hyn\Teamspeak\Daemon
 */
class Daemonizer {

    /**
     * Sets up the a daemonized connection
     *
     * @throws \Exception
     */
    public static function run() {
        global $argv;

        $config = static::config();

        if(count($argv) > 1 && array_get($config, "teamspeak.{$argv[1]}")) {
            $connection = array_get($config, "teamspeak.{$argv[1]}");
        } elseif(count(array_get($config, 'teamspeak', [])) === 1) {
            $connection = array_shift(array_get($config, 'teamspeak'));
        } else {
            throw new \Exception("No connection found for Teamspeak");
        }

        (new Daemon($connection, static::config()))->daemonize();
    }

    /**
     * Loads the config from disk, if it exists
     *
     * @return array
     */
    public static function config() {
        $path = __DIR__ . '/../configs/config.php';

        if(is_file($path))
        {
            return require $path;
        }

        return [];
    }
}

Daemonizer::run();
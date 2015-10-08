<?php namespace Hyn\Teamspeak\Daemon;

use Illuminate\Support\Collection;

class Config {

    protected static $instance = null;
    protected static $config = [];

    protected function __construct() {
        static::$config = $this->load();
    }

    /**
     * Loads the configuration file
     *
     * @return array
     */
    protected function load() {
        $path = __DIR__ . '/../configs/config.php';

        if(is_file($path))
        {
            return new Collection(require $path);
        }

        return [];
    }

    /**
     * @return Config
     */
    public static function getInstance() {
        if(empty(static::$instance)) {
            static::$instance = new Self;
        }

        return static::$instance;
    }

    /**
     * @param null $name
     * @return array|bool
     */
    public static function get($name = null) {
        if($name !== null) {
            $config = (self::getInstance());
            return $config->{$name};
        } elseif(is_null(static::$instance))
        {
            static::getInstance();
        }

        return static::$config;
    }

    /**
     * {@inheritdoc}
     */
    function __get($name)
    {
        return array_key_exists($name, static::$config) ? static::$config[$name] : false;
    }

    /**
     * {@inheritdoc}
     */
    function __set($name, $value)
    {
        // .. NO
    }
}
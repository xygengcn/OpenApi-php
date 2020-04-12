<?php

class header
{
    private $config;

    public function __construct()
    {
        $this->config = config("header");

    }
    public static function init()
    {
        $obj = new self();
        date_default_timezone_set($obj->config['timezone']);
        self::_header($obj->config["header"]);
        self::_ini_set($obj->config["ini"]);

    }
    public static function _header($config)
    {

        foreach ($config as $key => $val) {
            header($key . ":" . $val);
        }

    }
    public static function _ini_set($config)
    {

        foreach ($config as $key => $val) {
            ini_set($key, $val);
        }

    }
}
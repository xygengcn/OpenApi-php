<?php

namespace core\controller;

class Monitor{

    public static function run($route)
    {
        if (config("monitor")) {
            $redis = redis();
            $record = $redis->get($route);
            $redis->incr($route);
        }
    }
}
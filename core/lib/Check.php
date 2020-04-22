<?php
/**
 * 检查系统环境
 */
namespace core\lib;
use \core\Exception\SystemException as SystemException;
class Check
{

    public static function run()
    {
        self::checkRedis();

    }

    public static function checkRedis()
    {
        $redis = new \Redis();
        $config = config("redis");
        try {
            if (!$redis->connect($config['server'], $config['port'])) {
                throw new \RedisException('Redis is not Running！');
            }
            if (!$redis->ping()) {
                throw new \RedisException('Redis is not connecting successfully！');
            }
        }catch (\RedisException $e) {
            die($e->getMessage());
        }
       
    }

}

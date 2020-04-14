<?php

/**
 * 数据库操作
 */
function DB($table = null)
{
    if (isset($table) && !empty($table)) {
        if (is_string($table)) {
            return new \core\lib\db\DB($table);
        } else {
            error("数据库表格式不对");
        }
    }
    error("数据库表为空");
}
/**
 * 第三库
 */
function Medoo()
{
    return new \core\lib\db\Medoo(config("database"));
}

/**
 *显示界面，需要在public新建
 */
function display($html = "index.html")
{
    $url = site_url() . '/' . $html;
    $file = __ROOT__ . '\public\\' . $html;
    if (file_exists($file)) {
        header("Location:" . $url);exit;
    } else {
        header("Location:" . '\public\error\404.html');exit;
    }
}
//获取映射
function maps()
{
    return require __ROOT__ . "/core/map/map.php";
}
//获取config配置
function config($configName)
{

    return \core\utils\data::getConfigs($configName);
}
/**
 * redis操作对象
 */
function redis()
{
    $redis = new Redis();
    $config = config("redis");
    $redis->connect($config['server'], $config['port']);
    if ($redis->ping()) {
        return $redis;
    } else {
        error('Redis连接失败！');
    }

}
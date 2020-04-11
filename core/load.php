<?php
date_default_timezone_set('Asia/Shanghai');
require_once __ROOT__ . '/core/basic/function.php';
require_once __ROOT__ . '/core/Exception/SystemException.php';
require_once __ROOT__ . '/core/AutoLoad.php';

ini_set("display_errors", "On"); //打开错误提示
ini_set("error_reporting", E_ALL); //显示所有错误
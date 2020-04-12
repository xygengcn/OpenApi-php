<?php
namespace app\controller;

/**
 * 请保持文件名和类名一致
 *
 * 组件引用use  \app\model\+名字
 * \app\model\test::test();
 *
 */

class test
{
    public function index()
    {
        display("test.html");
    }

    public function test($key)
    {
        echo $key;
    }
}
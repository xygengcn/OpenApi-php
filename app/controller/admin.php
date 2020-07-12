<?php
namespace app\controller;

/**
 *  接口网站管理后台
 */

namespace app\controller;

class Admin
{
    public function index()
    {
        display("admin/index.html");
    }
    public function test()
    {
        response("测试成功");
    }
}

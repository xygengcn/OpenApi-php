<?php

namespace app\controller;

/**控制器需要命名 controller__ +名字*/
/**组件不需要，需要引用use  \app\model\+名字*/
/**
 * 请保持model文件名和类名一致
 */

class test
{
    public function write($controller, $method)
    {
        \app\model\test::test();

        //response($method);
    }
}
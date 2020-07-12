<?php
/**
* 请保持文件名和类名一致
*
* 组件引用use  \app\model\+名字
* \app\model\test::test();
*
*/

namespace app\controller;

/**
* 开发测试平台
*/

class Test {
    public function index() {
        display( 'test.html' );
    }
}

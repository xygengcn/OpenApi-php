<?php
namespace app\controller;

/**
 * 请保持文件名和类名一致
 *
 * 组件引用use  \app\model\+名字
 *
 */

class one
{

    public function index()
    {

        response(DB('one')->rand(1)->select("id", "tag", "origin", "content", "datetime"));

    }
    public function js()
    {

    }
}
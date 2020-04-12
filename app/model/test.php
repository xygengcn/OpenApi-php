<?php

namespace app\model;

class test
{
    public static function test()
    {
        $data = array(
            "name" => "/test/test",
        );

        // display('index.html');

        //插入
        //DB("test")->insert(["name" => "/21123/test"]);
        //批量插入
        //DB("test")->insert(["name" => "11"], ["name" => "22", "remark" => "2"], ["name" => "33", "remark" => "3"]);

        //DB("test")->inserts(["name", "remark"], ["aa", "bb"], ["cc", "dd"]);

        //删除
        //DB("test")->where(["id", "=", "75"])->delete();

        //查询
        //DB("test")->select("id", "name")
        //DB("test")->where(["id", ">", "10"], ["id", ">", "10"])->select()
        //DB("test")->where(["id", ">", "10"])->select()
        //DB("test")->where("id", ">", "10")->select()
        //_e(DB("test")->or(["id", "=", "80"], ["id", "=", "81"])->select("name", "id"));

        //更新
        //DB("test")->where("id", "=", "77")->updata(["remark", "88"]);
        //DB("test")->where("id", "=", "77")->updata(["remark", "88"],['name','88']);

        _e(DB("test")->where("id", ">", "80")->order('id DESC')->select());

        //

    }
}
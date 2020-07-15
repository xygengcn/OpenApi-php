<?php

namespace app\controller;

class Weibo{

    private $hot_api ="https://s.weibo.com/ajax/jsonp/gettopsug";
    public function hot(){
        $data =get($this->hot_api);
        $data=substr($data,13,strlen($data)-25);

        $data =json_decode($data,true);
        response($data["data"]);
    }
}
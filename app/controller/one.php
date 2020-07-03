<?php
namespace app\controller;

/**
 *一言一句
 */

class One
{
    private $APIXYGENGCNONE;
    public function __construct()
    {
        $redis = redis();
        $APIXYGENGCNONE = $redis->get("APIXYGENGCNONE");
        if (isset($APIXYGENGCNONE) && !empty($APIXYGENGCNONE)) {
            $this->APIXYGENGCNONE = json_decode($APIXYGENGCNONE, true);
        } else {
            $this->APIXYGENGCNONE = DB('one')->rand(1)->select("id", "tag", "origin", "content", "datetime");
            $redis->setex("APIXYGENGCNONE", 10 * 60, json_encode($this->APIXYGENGCNONE));
        }
    }

    public function index()
    {
        response($this->APIXYGENGCNONE);
    }
    public function get()
    {
        $data = $this->APIXYGENGCNONE[0]['content'] . "————" . $this->APIXYGENGCNONE[0]['origin'];
        _e("document.write('$data')");
    }
}
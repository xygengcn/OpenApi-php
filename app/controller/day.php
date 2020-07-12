<?php

namespace app\controller;
use \app\model\Lunar as Lunar;

class Day {

    public function index() {
        $lunar = new Lunar();
        $day_lunar = $lunar->convertSolarToLunar( date( 'Y' ), date( 'm' ), date( 'd' ) );
        $festival =  $lunar->getFestival( date( 'Y-m-d' ) );
        response( [
            'solar'=>[
                'year'=>date( 'Y' ),
                'month'=>date( 'm' ),
                'date'=>date( 'd' ),
                'day'=>weekday(),
            ],
            'lunar'=>[
                'year'=>$day_lunar[3],
                'animal'=>$day_lunar[6],
                'month'=>$day_lunar[1],
                'date'=>$day_lunar[2],
            ],
            'festival'=> $festival
        ] );
    }
}
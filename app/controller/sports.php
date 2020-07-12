<?php

namespace app\controller;

class Sports {

    private $soccerApi = 'https://api.dongqiudi.com/data/tab/new/important?version=215&start=';

    public function __construct() {
        Auth();
    }

    public function soccer() {
        $start = getParam( 'start' );

        $data = json_decode( get( $this->soccerApi.$start ) );

        $lists = $data->list;

        $newsList = [];

        foreach ( $lists as $item ) {
            $time = date( $item->sort_timestamp+60*60*8 );
            $date = date( 'Y-m-d', $time );
            $datetime = date( 'H:i:s', $time );
            $item->timestamp = $time;
            $item->time = $datetime;
            $item->date = $date;
            $newsList[$date][] = $item;
        }
        $data->Date = $start;
        $data->list = $newsList;
        response( $data );
    }
}
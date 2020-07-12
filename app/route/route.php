<?php

return [

    '/bing' => [
        'api'=>'bing@index',
        'monitor'=>true
    ],
    '/bing/url'=>[
        'api'=>'bing@url',
        'monitor'=>true
    ],
    '/bing/week' =>[
        'api'=>'bing@week',
        'monitor'=>true
    ],
    '/bt'=>[
        'api'=>'bt@index',
        'monitor'=>true
    ],
    '/api/task'=>[
        'api'=>'api@task',
        'monitor'=>false
    ],
    '/api/total'=>[
        'api'=>'api@total',
        'monitor'=>false
    ],
    '/user/reg'=>[
        'api'=>'user@reg',
        'monitor'=>false
    ],
    '/user/login'=>[
        'api'=>'user@login',
        'monitor'=>false
    ]

];
<?php
$user_agent = $_SERVER['HTTP_USER_AGENT'];

$reg ="/(?<=\()[^\)]+/";

preg_match($reg,$user_agent,$result);

$device['agent'] =$user_agent;

$device['os']=explode(";",$result[0]);

foreach($device['os'] as $key =>$item){
    $device['os'][$key]=trim($item);
}

echo json_encode($device);
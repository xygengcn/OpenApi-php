<?php

// $arr = ["fesf", "fsfe", "fsefe"];

// $arr2 = array_diff($arr, ["fesf"]);

// print_r($arr);
// print_r($arr2);

// $arr = ["fesf", "fsfe", "fsefe"];

// $count = count($arr);
// $arr2 = array_splice($arr, 1, $count - 1);

// print_r($arr);

// print_r($arr2);

$arr = "215--64-*/65sfdsf.46ghft(hf)t--15/5,6";

print_r(preg_replace('/[-\',\.\$\^\*-+!\?\/@"\|\\()]/i', "", $arr));
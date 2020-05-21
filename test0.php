<?php

$arr = array(1, 3);
function test($item, $key, &$arr){
    unset($arr[$key]);
}
var_dump(array_walk($arr, "test", &$arr));

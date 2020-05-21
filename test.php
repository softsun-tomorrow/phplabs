<?php
$arr = (array)$array;
var_dump($arr);



print(true);
exit;

$a = array();
$a = 1;
$a = 0;
$a = -1;
$a = '0';
$a = '1';
$a = '-1';
$a = '-2';
$a = NULL;
$a = PHP_INT_MAX;
$a = bcpow('2','1024');
var_dump($a);
var_dump($a+1);
var_dump($a+1<$a);exit;

class Solution {
    const USER_PHP_INT_MAX = 2147483647;
    const USER_PHP_INT_MIN = -2147483648;
    
    function reverse($x) {
        $rev =0;
        while($x !=0){
            $pop = $x % 10;
            if($x < 0) $x = ceil($x/10);
            if($x > 0) $x = floor($x/10);
var_export(array($pop,$x,$rev,self::USER_PHP_INT_MAX/10,self::USER_PHP_INT_MIN/10));
            if($rev > self::USER_PHP_INT_MAX/10 || ($rev == self::USER_PHP_INT_MAX/10 && $pop > 7))
                return 0;
            if($rev < self::USER_PHP_INT_MIN/10 || ($rev == self::USER_PHP_INT_MIN/10 && $pop < -8))
                return 0;
            $rev = $rev * 10 + $pop;
        }
print_r($rev);
        return $rev;
    }
}

$t = -1230;
$t = 1230;
//$s = new Solution();
//$t = $s->reverse($t);
print_r($t);


$arr = array(
    'a'=> 'a11',
    'b'=> 'b22',
    'c'=> 'c33',
);  
foreach ($arr as $k=>&$v){
    // Do somethind
}
foreach ($arr as $k=>$v){
    var_dump($v);
}



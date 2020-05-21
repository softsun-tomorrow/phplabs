<?php

hello_world();

#error_reporting(1);
#var_dump(0 == "a");
#$arr = array(0=>1,"aa"=>2, 3, 4);
#var_dump($arr);
#foreach($arr as $key=>$val){
#    print($key == "aa" ? 5 : $val);
#}
#exit;
#$a = count ("567")  + count(null) + count(false);
#echo $a;
#$first = array(1,2);
#$second = array(3,4);
#$third = $first+$second;
#print_r($third);exit;
# $A = 'PHPlinux';
#$B = 'PHPLinux';
#$C = strstr($A,'L');
#$D = stristr($B,'l');
#echo $C .' is '. $D;
#
#$array = array ('a', 'b', 'c', 'd');
#echo $array{1};
#var_dump(1 == [['1']]);
#echo 16 << 4 * 1.0;
#echo 20 % 20; exit;
#echo '2+5=' . 5+2;
#echo "和";
#echo "2+5=" , 5+2;
#     exit;
#   $a = 3;
#$b = 5;
#if($a = 5 || $b = 7) {
#    ++$a;
#    $b++;
#}
#echo $a . " " . $b;exit;
#
#$str = "qazwsxwsxrfv";
#$str['10'] = 'sijiaomao';
#echo $str;exit;
#
#$num='24linux'+6;
#echo $num;exit;
#
#$str='goodday';    
#echo $str{0};
#$str[0]='f';
#$str[1.6]='c';
#$str[10]='!';
#echo $str;exit;
#
#$a = 'abc';
#$a["$a[2]"+1] = 'd';
#echo $a;exit;
#
#
#$str = 'a';
#$str2 = 'b';
#//var_dump(array($str,$str2));
#//var_dump( 'e' || $str2 );
#if ($str = 'e' || $str2 = 'e') {
#//if ($str = 'e' and $str2 = 'e') {
#    $str[1] = 'c';
#    $str2[1] = 'c';
#}
#//var_dump(array($str,$str2));
#echo $str ." ".$str2;exit;
#$array = array ('a', 'b', 'c', 'd');
#//$array = array_flip($array);
#$array = array_reverse($array);
#var_dump($array);exit;
#$string = '\\$a';
#echo $string;
#$str = 'beijingsijiaomao';
#echo $str{'1'};exit;
#$a = array(0=> 1, "2");
#//$a[0] = 2;
#var_dump($a);
#$a = 3;
#echo "$a",'$a',"\\\$a","${a}","$a"."$a","$a"+"$a";exit;
# $a = 0.2+0.7;
#   $b = 0.9;
#var_dump(array($a,$b));
#   var_dump($a == $b);exit;
#$a = array(1,2,3,4,5);
#var_dump(reset($a));exit;
#list($q,$w) = $a;
# var_dump($q); exit;
#var_dump(array_slice($a,-3)); exit;
#var_dump(array_shift($a)); exit;
#$str = "123\n456"; echo ltrim($str,'123\n');echo substr($str, 4);echo substr($str, -3);echo str_replace("123\n", '', $str);
#$str = 'hello你好世界'; echo strlen($str);exit;
#   $a = 0.2+0.7;
#   $b = 0.9;
#   var_dump($a == $b);
#exit;
#
#$str = "sakjdfakdsfl;kae;jqmnwermnzxclkjv;oiawekr,mxcnv.mzxcwer,.\0sdfasdf";
#echo $str;
#
#$a = 'abc';
#$a["$a[2]"+1] = 'd';
#echo $a;
#exit;
#/*
#namespace sjmstudy;
#function date($str) { 
#echo "my date {$str}n";
#}
#echo date("Y-m-d"); //调的哪个？
#echo \date("Y-m-d"); //调的哪个？
#exit;
#*/
#
#switch (true) {
#    case 1:
#        echo('A');
#    case ['']:
#        echo('B');
#    case 'true':
#        echo('C');
#    default:
#        echo('D');
#}
#exit;
#
#
# $count = 5;
#    function get_count() {
#        static $count = 0;
#        return $count++;
#    }
#    ++$count;
#    get_count();
#    echo get_count();
#exit;
#
#$arr = array(0=>1,"aa"=>2, 3, 4);
#foreach($arr as $key=>$val){
#    print($key == "aa" ? 5 : $val);
#}
#
#
#var_dump(1 == [['1']]);
#$s = '12345';
#$s[$s[1]] = '2';
#echo $s;
#exit;
#
#$redis = new Redis();
#$result = $redis->connect('localhost',6379,600,0);
#var_dump($result);
#$result = $redis->set('test', "this is a test");
#var_dump($result);
#
#$memcache = new Memcached();
#//$memcache->addServers(array(
#//    array('localhost', 11211)
#//));
#$bool = $memcache->addServer('118.25.5.174',11211, 1);
#var_dump($bool);
#echo $memcache->getResultMessage(),"\n";
#$memcache->set('foo1', time());
#$memcache->set('foo2', 'a simple string');
#$memcache->set('foo3', new stdclass, time()+300);
#$memcache->set('foo4', 99);
#$memcache->set('foo5', array('test'));
#$memcache->set('foo6', phpinfo());
#$memcache->set('foo7', time());
#$keys = $memcache->get('foo');
#echo $memcache->getResultMessage(),"\n";
#echo $memcache->getResultCode(),"\n";
#var_dump($keys);
#var_dump($memcache);
#exit;
#//$memcache->connect('localhost', 11211) or die('Could not connect'); 
#//$memcache->set('key', 'test');
#//$get_value = $memcache->get('key');
#//echo $get_value;
#
#echo "<pre/>";
#$ref = new ReflectionClass('Memcached');
#
#$consts = $ref->getConstants(); //返回所有常量名和值
#$props = $ref->getDefaultProperties();
#$methods = $ref->getMethods();
#foreach($methods as $method){
#    echo $method->getName().PHP_EOL;
#}
#//$m = serialize($methods);
#var_dump($props);
#exit;
#
#
#function quickSort($arr){
#    if(count($arr) <=1){
#        return $arr;
#    }
#    $baseValue = $arr[0];
#    $leftArray = array();
#    $rightArray = array();
#    
#    array_shift($arr);
#    foreach($arr as $value){
#        if($value < $baseValue) {
#            $leftArray[] = $value;
#        } else {
#            $rightArray[] = $value;
#        }
#    }
#    $leftArray = quickSort($leftArray);
#    $rightArray = quickSort($rightArray);
#
#    return array_merge($leftArray, array($baseValue), $rightArray);
#}
#
#function bubbleSort($arr){
#    $len = count($arr);
#    for($i=1; $i<$len; $i++){
#        for($j=0; $j<$len-$i;$j++){
#            if($arr[$j]>$arr[$j+1]){
#                $tmp = $arr[$k+1];
#                $arr[$k+1] = $arr[$k];
#                $arr[$k] = $tmp;
#            }
#        }
#    }
#    return $arr;
#}
#
#
#exit;
#
#ini_set('memory_limit', '-1');
#function microtime_float(){
#    list($usec, $sec) = explode(" ", microtime());
#    return $usec + $sec;
#}
#$start_time = microtime_float();
#$file = './test.log2';
#/*
#$fp = fopen($file,'r');
#if($fp){
#    while(!feof($fp)){
#        $longarray[] = stream_get_line($fp, 65535, "\n");
#    }
#    $end_time = microtime_float();
#
#    echo $end_time - $start_time;
#}
#*/
#
#$file = escapeshellarg($file);
#$line = `tail -n 1 $file`;
#echo $line;
#exit;

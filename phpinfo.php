<?php


$wArr['pwa_update_time'] = '2018-03-25 13:25:16';
$date = date('Y-m-d H:i:s',strtotime($wArr['pwa_update_time']) - 8*3600);
var_dump($date);

$wRowTmp = array(
    'pv_id'            => 1,
    'pp_id'            => 2,
    //'warehouse_id'    => 3,
    'product_length'  => 4,
    'product_width'   => 5,
    'product_height'  => 6,
    'product_weight'  => 7,
    'product_package_type'    => 8,
    'pwa_update_time'    => 9,
    'pwa_modify_id'    => 10,
    'pwa_modify_count' => 11,
    'customer_code' => 12,
);


$wRowTmp1 = array(
    'pv_id'            => 1,
    'pp_id'            => 2,
    //'warehouse_id'    => 3,
    'product_length'  => 4,
    'product_width'   => 5,
    'product_height'  => 6,
    'product_weight'  => 7,
    'product_package_type'    => 8,
    'pwa_update_time'    => 9,
    'pwa_modify_id'    => 10,
    'pwa_modify_count' => 11,
    'customer_code' => 12,
);
var_dump(array_diff($wRowTmp, $wRowTmp1));

#多线程并发抓取函数mfetch：
function mfetch($params=array(), $method){
    $mh = curl_multi_init(); #初始化一个curl_multi句柄
    $handles = array();
    foreach($params as $key=>$param){
        $ch = curl_init(); #初始化一个curl句柄
        $url = $param["url"];
        $data = $param["params"];
        if(strtolower($method)==="get"){
           #根据method参数判断是post还是get方式提交数据
            $url = "$url?" . http_build_query( $data ); #get方式
        }else{
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data ); #post方式
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_multi_add_handle($mh, $ch);
        $handles[$ch] = $key;
        #handles数组用来记录curl句柄对应的key,供后面使用，以保证返回的数据不乱序。
    }
    $running = null;
    $curls = array(); #curl数组用来记录各个curl句柄的返回值
    do { #发起curl请求，并循环等等1/100秒，直到引用参数"$running"为0
        usleep(10000);
        curl_multi_exec($mh, $running);
        while( ( $ret = curl_multi_info_read( $mh ) ) !== false ){
            #循环读取curl返回，并根据其句柄对应的key一起记录到$curls数组中,保证返回的数据不乱序
            $curls[$handles[$ret["handle"]]] = $ret;
        }
    } while ( $running > 0 );
    foreach($curls as $key=>&$val){
        $val["content"] = curl_multi_getcontent($val["handle"]);
        curl_multi_remove_handle($mh, $val["handle"]); #移除curl句柄
    }
    curl_multi_close($mh); #关闭curl_multi句柄
    ksort($curls);
    return $curls;
}
 
#调用参数：
$keyword = "360";
$page = 1;
$params = array();
for($i=0;$i<10;$i++){
    $params[$i] = array(
        "url"=>"http://www.so.com/s",
        "params"=>array('q'=>$keyword.$i,'ie'=>"utf-8",'pn'=>($page-1)*10+$i+1)
    );
}
//$ret = mfetch($params, 'GET');
print_r($ret);
//exit;


$a = "hello";
$b = &$a;
unset($b);
$b = "world";
echo $a;//hello
echo "<br >";

$a = 'abcdef';
echo $a{0};
echo substr($a,0,1);
echo "<br >";

$x = NULL;
if ('0xFF' == 255) {  
  $x = (int)'0xFF';
}
var_dump((int)'0xFF');
var_dump('0xFF' == 255);
var_dump($x);
echo "<br >";

$text = 'John ';
$text[10] = 'Doe';
print_r($text);
echo "<br >";

$v = 1;
$m = 2;
$l = 3;
#实际结果是PHP 语法错误
/*
if($l > $m > $v){  
    echo "yes";
}else{
    echo "no";
}
echo "<br >";
*/

var_dump(0123 == 123);
var_dump('0123' == 123);
var_dump('0123' == 123);
echo "<br >";

$a = '1';
$b = &$a;
$b = "2$b";
print_r($a);
echo "<br >";
print_r($b);
echo "<br >";

$x = 5;
echo "<br >";  
echo $x;  
echo "<br >";  
echo $x+++$x++;  
echo "<br >";  
echo $x;  
echo "<br >";  
echo $x---$x--;  
echo "<br >";  
echo $x;
echo "<br >";

#写一个函数，尽可能高效的，从一个标准url里取出文件的扩展名例如: http://www.test.com.cn/abc/de/fg.php?id=1需要取出php或.php
$a = "http://www.test.com.cn/abc/de/fg.php?id=1";
$b = parse_url($a);
print_r($b);
print_r(strpos($b['path'],'.'));
echo substr($b['path'], strpos($b['path'], '.'));
echo end(explode('.', $b['path']));

phpinfo();


<?php

//$conn = mysqli_connect("qdm166276697.my3w.com","qdm166276697","ltf107834",true);//"qdm166276697_db");
//var_dump($conn);
//exit;

/*
 * 商城程序调用秒杀引擎
 */

// 脚本执行开始时间
//$now = explode(' ', microtime());
//$starttime = $now[1] + $now[0];

date_default_timezone_set('Asia/Shanghai');//配置地区
define('IN_SK', true);						// 定义文件合法包含常量
require_once 'seckill/seckill.inc.php';	// 包含秒杀引擎文件


// ========================= 秒杀活动管理测试 =========================
//$sk_admin = new sk_admin;
//var_dump($sk_admin->create_sk(13, 100, 5, ($sk_admin->timestamp), ($sk_admin->timestamp + 7800), ($sk_admin->timestamp + 1200), ($sk_admin->timestamp + 2400)));
//var_dump($sk_admin->create_sk(14, 100, 5, ($sk_admin->timestamp), ($sk_admin->timestamp + 7800), 0, 0));
//var_dump($sk_admin->create_sk(18, 100, 5, ($sk_admin->timestamp), ($sk_admin->timestamp + 7800), 0, 0));
//exit;
// ====================================================================

// ========================= 秒杀活动预约测试 =========================
//$skbook = new skbook;
//var_dump($skbook->book(12, 124));
// ====================================================================

// ========================= 秒杀活动秒杀测试 =========================
//$_REQUEST['user_id'] = rand(1,10000);
$sk_kill = new sk_kill;
var_dump($sk_kill->test());
//if(!empty($_REQUEST['user_id'])){
//    var_dump($sk_kill->kill(18, $_REQUEST['user_id']));
//}else{
//    echo "user_id cannot be empty";
//}
//exit;
// ====================================================================

// ========================= 订单处理测试 =========================
//$skorder = new skorder;
//var_dump($skorder->flush_orders());
// ================================================================

// 脚本执行结束时间
//$now = explode(' ', microtime());
//$endtime = $now[1] + $now[0];
//echo '<div>Proceeded in '.($endtime - $starttime).' second(s).</div>';


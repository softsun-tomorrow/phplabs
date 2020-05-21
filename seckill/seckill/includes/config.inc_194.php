<?php

/*
 * 秒杀引擎配置文件
 */

!defined('IN_SK') && exit('Access Denied');

// ========== MySQL 设置 ==========
$SK_CONFIG['mysql']['host'] = '10.10.10.205';		// MySQL 数据库服务器
$SK_CONFIG['mysql']['user'] = 'vivo';			// MySQL 数据库用户名
$SK_CONFIG['mysql']['passwd'] = 'vivostore';		// MySQL 数据库密码
$SK_CONFIG['mysql']['dbname'] = 'seckill';		// MySQL 数据库名
$SK_CONFIG['mysql']['tabpre'] = 'sk_';			// MySQL 数据库字符集
$SK_CONFIG['mysql']['charset'] = 'utf8';			// MySQL 数据库字符集
$SK_CONFIG['mysql']['pconn'] = 0;					// MySQL 数据库持久连接 0=关闭, 1=打开

// ========== Redis 设置 ==========
$SK_CONFIG['redis']['host'] = '10.10.10.205';		// Redis 服务器
$SK_CONFIG['redis']['port'] = '6379';				// Redis 服务器端口
$SK_CONFIG['redis']['timeout'] = 0;				// Redis 超时时间
$SK_CONFIG['redis']['dbid'] = '1';				// Redis 数据库编号
$SK_CONFIG['redis']['dbpwd'] = 'KBtAcGe2Q4W4hRDy';			// Redis 数据库密码
$SK_CONFIG['redis']['pconn'] = 0;					// Redis 持久连接 0=关闭, 1=打开

// ========== 秒杀设置 ==========
$SK_CONFIG['seckill']['clicklimit'] = 5;			// 最短点击时间, 单位秒
$SK_CONFIG['seckill']['paytimeout'] = 1800;		// 支付超时时间, 单位秒

?>

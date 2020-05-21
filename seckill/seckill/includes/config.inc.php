<?php

/*
 * 秒杀引擎配置文件
 */

!defined('IN_SK') && exit('Access Denied');

// ========== MySQL 设置 ==========
$SK_CONFIG['mysql']['host'] = 'qdm166276697.my3w.com';		// MySQL 数据库服务器
$SK_CONFIG['mysql']['user'] = 'qdm166276697';			// MySQL 数据库用户名
$SK_CONFIG['mysql']['passwd'] = 'ltf107834';		// MySQL 数据库密码
$SK_CONFIG['mysql']['dbname'] = 'qdm166276697_db';		// MySQL 数据库名
$SK_CONFIG['mysql']['tabpre'] = 'sk_';			// MySQL 数据库字符集
$SK_CONFIG['mysql']['charset'] = '';			// MySQL 数据库字符集
$SK_CONFIG['mysql']['pconn'] = 0;					// MySQL 数据库持久连接 0=关闭, 1=打开

// ========== Redis 设置 ==========
$SK_CONFIG['redis']['host'] = '127.0.0.1';		// Redis 服务器
$SK_CONFIG['redis']['port'] = '6379';				// Redis 服务器端口
$SK_CONFIG['redis']['timeout'] = 0;				// Redis 超时时间
$SK_CONFIG['redis']['dbid'] = '1';				// Redis 数据库编号
$SK_CONFIG['redis']['dbpwd'] = 'ltf107834';			// Redis 数据库密码
$SK_CONFIG['redis']['pconn'] = 0;					// Redis 持久连接 0=关闭, 1=打开

// ========== 秒杀设置 ==========
$SK_CONFIG['seckill']['clicklimit'] = 5;			// 最短点击时间, 单位秒
$SK_CONFIG['seckill']['paytimeout'] = 72*3600;		// 支付超时时间, 单位秒

?>

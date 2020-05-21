<?php

/*
 * 秒杀引擎包含文件
 */

!defined('IN_SK') && exit('Access Denied');					// 文件合法包含常量
define('SK_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);	// 秒杀引擎主目录
require_once SK_PATH.'includes/config.inc.php';				// 包含配置文件
require_once SK_PATH.'functions/global.func.php';			// 包含全局函数定义文件

// 自动加载对象
//if(function_exists('spl_autoload_register')) {
	spl_autoload_register('sk_load_class');
//} else {
//	function __autoload($classname) {
//		sk_load_class($classname);
//	}
//}

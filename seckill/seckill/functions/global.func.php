<?php

/*
 * 全局函数定义文件
 */

!defined('IN_SK') && exit('Access Denied');

/*
 * 自动加载对象
 */
function sk_load_class($classname) {
	$require_file = SK_PATH.'./classes/'.$classname.'.class.php';
	if(file_exists($require_file)) {
		require_once $require_file;
	}
}

/*
 * 写入日志文件
 */
function sk_write_log($file, $log) {

	$yearmonth = gmdate('Ym', time());
	$logdir = SK_PATH.'./logs/';
	$logfile = $logdir.$file.'_'.$yearmonth.'.php';

	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(!(strpos($entry, $file.'_'.$yearmonth) === false)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);

		$logfilebak = $logdir.$file.'_'.$yearmonth.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}

	if(!file_exists($logfile)) {
		$log = "<?php !defined('IN_SK')&&exit('Access Denied');?>\n".$log;
	}

	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, LOCK_EX);
		fwrite($fp, "$log\n");
		fclose($fp);
	}

}

?>
<?php

file_put_contents(dirname(__FILE__)."/".time().".log", time());
error_log(time().PHP_EOL, 3, dirname(__FILE__).'/'.time().'.log');
echo time();
exit;

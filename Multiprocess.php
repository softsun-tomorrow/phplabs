<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    exit('not enough argument');
}
$syncCommand = $argv[1];
$processCount = $argv[2];
if ($processCount < 1) {
    exit('processCount must more than 1');
}
$process = [];
$baseDir = dirname(__FILE__);
for ($i=0; $i<$processCount; $i++) {
    //$process[$i] = popen("php $baseDir/artisan $syncCommand $processCount $i", 'r');
    $process[$i] = popen("php -q $baseDir/MultiprocessTest.php", 'r');
    sleep(1);
}
$processPending = $processCount;
while ($processPending > 0) {
    $myPid = pcntl_waitpid(-1, $status, WNOHANG);
    $processPending--;
    usleep(100);
}

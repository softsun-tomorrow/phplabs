<?php


$serv = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr) 
or die("create server failed");

while(1){
    $conn = stream_socket_accept($serv);
    if(pcntl_fork() == 0){
        $request = fread($conn);
        $response = "hello world";
        fwrite($response);
        fclose($conn);
        exit(0);
    }
}

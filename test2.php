<?php


$serv = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr) 
or die("create server failed");

for($i=0; $i<32; $i++){
    if(pcntl_fork()==0){
        while(1){
            $conn = stream_socket_accept($serv);
            if($conn == false) continue;
            $request = fread($conn);
            $response = "hello world";
            fwrite($response);
            fclose($conn);
        } 
        exit(0);
    }
}

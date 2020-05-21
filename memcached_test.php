<?php

    $mem  = new Memcached();

     $mem->addServer('127.0.0.1',11211);
     if( $mem->add("mystr","this is a memcache test!",3600)){
         echo  '原始数据缓存成功!';
     }else{
         echo '数据已存在：'.$mem->get("mystr");
     }
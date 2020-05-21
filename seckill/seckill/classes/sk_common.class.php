<?php

/**
 * @desc 秒杀活动公用方法
 */
!defined('IN_SK') && exit('Access Denied');

class sk_common {

    /**
     * @desc 统一的返回数据格式
     * @return type 
     * @author sundy.zhang@magic-point.com 13-11-27 上午10:54
     */
    function format_return_data($status, $code, $msg, $data = null) {
        return array(
            'status' => $status,
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );
    }

}

?>
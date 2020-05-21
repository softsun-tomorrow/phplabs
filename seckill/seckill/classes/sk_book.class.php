<?php

/*
 * 秒杀活动预约类
 */
!defined('IN_SK') && exit('Access Denied');

class sk_book extends sk_base {
    /*
     * 构造方法
     */

    public function __construct() {
        parent::__construct();
    }

    /*
     * 秒杀活动预约
     * 说明：秒杀活动预约。
     *    状态码：
     *    -1 - 秒杀活动不存在
     *    -2 - 不需要预约
     *    -3 - 预约未开始
     *    -4 - 预约已结束
     *    -5 - 已经预约过
     *    -6 - 预约失败（数据库查询错误）
     */

    public function book($skid, $userid) {

        if (!$skdetail = $this->get_sk_detail($skid)) {
            return -1;
        } elseif ($skdetail['bookstarttime'] == 0 && $skdetail['bookendtime'] == 0) {
            return -2;
        } elseif ($skdetail['bookstarttime'] > $this->timestamp) {
            return -3;
        } elseif ($skdetail['bookendtime'] < $this->timestamp) {
            return -4;
        }

        $sql = "SELECT `bookid` FROM {$this->mysqldb->dbtabpre}books
			WHERE `skid`='{$skid}' AND `userid`='{$userid}'";
        if ($this->mysqldb->result_first($sql)) {
            return -5;
        } else {
            $sql = "INSERT INTO {$this->mysqldb->dbtabpre}books(`bookid`, `skid`, `userid`, `booktime`)
				VALUES(NULL, '{$skid}', '{$userid}', '{$this->timestamp}')";
            if ($this->mysqldb->query($sql)) {
                return $this->mysqldb->insert_id();
            } else {
                return -6;
            }
        }
    }

}

?>
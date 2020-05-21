<?php

/*
 * 秒杀活动秒杀类
 */
!defined('IN_SK') && exit('Access Denied');

class sk_kill extends sk_base {
    /*
     * 构造方法
     */

    public function __construct() {
        parent::__construct();
    }


    public function test() {
        $number = $this->redisdb->incr('cacheKey', 1);
        $orderId = '1'.str_pad($number, 15, 0, STR_PAD_LEFT);
        return $orderId;
    }

    /*
     * 秒杀活动预约
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
        } elseif ($this->_is_booked($skid, $userid)) {
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

    /*
     * 立即秒杀
     * 说明：立即秒杀。
     *  状态码：
     *  -1 - 秒杀活动不存在
     *  -2 - 秒杀活动未开始（未到开始时间）
     *  -3 - 秒杀活动已结束（已过结束时间）
     *  -4 - 需要预约的秒杀活动但没有成功预约过
     *  -5 - 两次点击时间间隔过短
     *  -6 - 已秒杀成功次数大于限额
     *  -7 - 秒杀库存已秒杀完
     *  -8 - 秒杀失败（数据库查询错误）
     */

    public function kill($skid, $userid) {
        $orderlist = $this->_get_order_id_list($skid, $userid);
        if (empty($userid)) {
            return $this->format_return_data(false, -9, '请先登录网站!');
        } else if (!$skdetail = $this->get_sk_detail($skid)) {
            return $this->format_return_data(false, -1, '秒杀活动不存在!');
        } else if ($skdetail['starttime'] > $this->timestamp) {
            return $this->format_return_data(false, -2, '秒杀活动未开始!');
        } else if ($skdetail['endtime'] < $this->timestamp) {
            return $this->format_return_data(false, -3, '秒杀活动已结束');
        } else if ($skdetail['bookstarttime'] != 0 && !$this->_is_booked($skid, $userid)) {
            return $this->format_return_data(false, -4, '需要预约的秒杀活动但没有成功预约过');
        } else if (!$this->_click($userid, $GLOBALS['SK_CONFIG']['seckill']['clicklimit'])) {
            return $this->format_return_data(false, -5, '两次点击时间间隔过短');
        } else if (count($orderlist) >= $skdetail['orderlimit']) {
            if ($orderid = $this->_get_order_not_synced($orderlist)) {
                return $this->format_return_data(true, '', '秒杀成功', array('engine_order_id' => $orderid));
            } else {
                return $this->format_return_data(false, -6, '已秒杀成功次数大于限额');
            }
        } else if ($this->get_stock($skid) <= 0) {
            return $this->format_return_data(false, -7, '已售完');
        } else {
            if ($orderid = $this->_get_order_not_synced($orderlist)) {
                return $this->format_return_data(true, '', '秒杀成功', array('engine_order_id' => $orderid));
            }

            $this->redisdb->plus("sk:{$skid}:stock:rested", -1);
            $this->redisdb->plus("sk:{$skid}:stock:locked", 1);
            if (!$orderid = $this->_order($skid, $userid)) {
                $this->redisdb->plus("sk:{$skid}:stock:rested", 1);
                $this->redisdb->plus("sk:{$skid}:stock:locked", -1);
                return $this->format_return_data(false, -8, '秒杀失败');
            } else {
                $this->redisdb->sadd("user:{$userid}:sk:{$skid}:orderids", $orderid);
                return $this->format_return_data(true, '', '秒杀成功', array('engine_order_id' => $orderid));
            }
        }
    }

    /**
     * @desc 检查引擎订单是否有效
     * @param type $skid
     * @param type $userid
     * @return type 
     * @author sundy.zhang@magic-point.com 13-11-29 下午5:39
     */
    public function check_kill($skid, $userid) {
        if (empty($userid)) {
            return $this->format_return_data(false, -9, '请先登录网站!');
        }

        $orderlist = $this->_get_order_id_list($skid, $userid);
        if ($orderid = $this->_get_order_not_synced($orderlist)) { // 如果已存在秒杀成功的订单 13-11-29 下午3:32
            return $this->format_return_data(true, '', '秒杀成功', array('engine_order_id' => $orderid));
        }
        return $this->format_return_data(false, -8, '秒杀失败,秒杀订单已过期!');
    }

    /*
     * 是否已预约
     */

    private function _is_booked($skid, $userid) {
        $sql = "SELECT `bookid` FROM {$this->mysqldb->dbtabpre}books
			WHERE `skid`='{$skid}' AND `userid`='{$userid}'";
        return $this->mysqldb->result_first($sql) ? true : false;
    }

    /*
     * 点击记录
     */

    private function _click($userid, $timelimit) {
        $lastclick = $this->redisdb->get("user:{$userid}:lastclick");
        if (!$lastclick || ($this->timestamp - $timelimit > $lastclick)) {
            $this->redisdb->set("user:{$userid}:lastclick", $this->timestamp);
            return true;
        } else {
            return false;
        }
    }

    /*
     * 获取订单号列表
     */

    private function _get_order_id_list($skid, $userid) {
        $order_id_list = $this->redisdb->smembers("user:{$userid}:sk:{$skid}:orderids");
        return !empty($order_id_list) ? $order_id_list : array();
    }

    /*
     * 生成订单
     */

    private function _order($skid, $userid) {
        $fields = "`orderid`, `skid`, `userid`, `create_time`, `finish_time`, `status`";
        $values = "VALUES(NULL, '{$skid}', '{$userid}', '{$this->timestamp}', '0', '0')";
        $sql = "INSERT INTO {$this->mysqldb->dbtabpre}orders(" . $fields . ")" . $values;
        if ($this->mysqldb->query($sql)) {
            return $this->mysqldb->insert_id();
        }
        return false;
    }

    /*
     * 获取未同步到商城的第一个订单
     */

    private function _get_order_not_synced($orderlist) {
        if (empty($orderlist)) {
            return false;
        }
        $orderlist = implode(',', $orderlist);
        $deadline = $this->timestamp - $GLOBALS['SK_CONFIG']['seckill']['paytimeout'];
        $orderid = $this->mysqldb->result_first("SELECT `orderid` FROM {$this->mysqldb->dbtabpre}orders
			WHERE `shop_order_id`='0' AND `orderid` IN({$orderlist}) AND `status`='0'
			AND `create_time`>='{$deadline}' LIMIT 0,1");
        return $orderid === false ? false : intval($orderid);
    }

	/**
     * @desc 获取当前会员的秒杀记录
     */
    public function get_member_oreders($member_id) {
        $sql = "SELECT * FROM {$this->mysqldb->dbtabpre}orders WHERE `userid`='{$member_id}' ORDER BY create_time DESC";
        $query = $this->mysqldb->query($sql);
        $orders = array();
        while ($order = $this->mysqldb->fetch_array($query)) {
            $orders[] = $order;
        }
        return $orders;
    }
    public function get_unexec_kill($data, $userid) {
        $skid = $data['rule_id'];
        $sql = "SELECT `orderid` FROM {$this->mysqldb->dbtabpre}orders WHERE 
        `userid`='{$userid}' AND `skid`='{$skid}' AND status=0 LIMIT 1";
        $order_id = $this->mysqldb->result_first($sql);
        return $order_id;
    }
}

?>

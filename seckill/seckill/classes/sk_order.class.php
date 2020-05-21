<?php

/*
 * 订单处理类
 * 订单状态：
 *   0-已秒杀成功未支付
 *   1-已秒杀成功已支付
 *   2-已秒杀成功但超时未支付
 *   3-处理过期订单的临时状态
 */
!defined('IN_SK') && exit('Access Denied');

class sk_order extends sk_base {
    /*
     * 构造方法
     */

    public function __construct() {
        parent::__construct();
    }

    /*
     * 处理过期未支付订单
     */

    public function flush_orders($skid = 0) {
        $order_ids = array();
        $deadline = $this->timestamp - $GLOBALS['SK_CONFIG']['seckill']['paytimeout'];
        $wheresql = $skid > 0 ? "`skid`='{$skid} AND '" : '';
        $wheresql .= "`status`='0' AND `create_time`<'{$deadline}'";
        $sql = "UPDATE {$this->mysqldb->dbtabpre}orders
			SET `finish_time`='{$this->timestamp}', `status`='3'
			WHERE {$wheresql}";
        $this->mysqldb->query($sql);

        $skids = array();
        if ($skid > 0) {
            $skids[] = $skid;
        } else {
            $sql = "SELECT DISTINCT `skid`,`shop_order_id` FROM {$this->mysqldb->dbtabpre}orders
				WHERE `status`='3'";
            $query = $this->mysqldb->query($sql);
            while ($row = $this->mysqldb->fetch_array($query)) {
                $skids[] = $row['skid'];
                if ($row['shop_order_id']) { // 如果
                    $order_ids[] = $row['shop_order_id'];
                }
            }
        }

        foreach ($skids as $skid) {
            $sql = "UPDATE {$this->mysqldb->dbtabpre}orders
				SET `status`='2' WHERE `skid`='{$skid}' AND `status`='3'";
            $query = $this->mysqldb->query($sql);
            if (($restorecnt = $this->mysqldb->affected_rows()) > 0) {
                $this->_restore_stock($skid, $restorecnt);
            }
        }

        return $this->format_return_data(true, '', '秒杀订单信息更新成功', $order_ids);
    }

    /*
     * 订单号同步
     */

    public function sync_shop_order_id($orderid, $userid, $shop_order_id) {
        $where = " `orderid`={$orderid} AND `shop_order_id`=0 AND `status`=0 AND `userid`={$userid}";
        $sql = "UPDATE {$this->mysqldb->dbtabpre}orders SET `finish_time`='{$this->timestamp}', `shop_order_id`={$shop_order_id} WHERE " . $where;
        if ($this->mysqldb->query($sql)) {
            return $this->format_return_data(true, '', '秒杀同步成功');
        }
        return $this->format_return_data(false, '', '秒杀同步失败');
    }

    /*
     * 订单支付成功
     */

    public function pay($shop_order_id) {
        $sql = "SELECT `orderid`, `skid`
			FROM {$this->mysqldb->dbtabpre}orders
			WHERE `shop_order_id`='{$shop_order_id}'";
        if ($order = $this->mysqldb->fetch_first($sql)) {
            $sql = "UPDATE {$this->mysqldb->dbtabpre}orders
				SET `finish_time`='{$this->timestamp}', `status`='1'
				WHERE `shop_order_id`='{$shop_order_id}'";
            $this->mysqldb->query($sql);
            $this->_unlock_stock($order['skid']);
            return $this->format_return_data(true, '', '秒杀同步成功');
        } else {
            return $this->format_return_data(false, '', '秒杀同步失败');
        }
    }

    /**
     * @desc 订单是否是可以支付
     */
    public function check_kill_pay($shop_order_id) {
        $sql = "SELECT `orderid`, `skid`, `create_time`, `status`
			FROM {$this->mysqldb->dbtabpre}orders
			WHERE `shop_order_id`='{$shop_order_id}' AND `status`='0'";
        if ($order = $this->mysqldb->fetch_first($sql)) {
            $deadline = $this->timestamp - $GLOBALS['SK_CONFIG']['seckill']['paytimeout'];
            if ($order['create_time'] < $deadline) {
                $sql = "UPDATE {$this->mysqldb->dbtabpre}orders
					SET `finish_time`='{$this->timestamp}', `status`='2'
					WHERE `shop_order_id`='{$shop_order_id}'";
                $this->mysqldb->query($sql);
                $this->_restore_stock($order['skid']);
                return $this->format_return_data(false, '', '秒杀订单已失效,不可支付');
            }
            return $this->format_return_data(true, '', '秒杀订单可支付', array('engine_order_id' => $order['orderid']));
        }
        return $this->format_return_data(false, '', '秒杀不存在相应订单信息');
    }

    /*
     * 恢复锁定库存至剩余库存
     */

    private function _restore_stock($skid, $stock = 1) {
        if (!$this->sk_exists($skid) || $stock < 1) {
            return false;
        } else {
            $stock_locked = $this->get_stock($skid, 'locked');
            if ($stock_locked < $stock) {
                $stock = $stock_locked;
            }
            if ($this->redisdb->plus("sk:{$skid}:stock:rested", $stock) !== false
                    && $this->redisdb->plus("sk:{$skid}:stock:locked", - $stock) !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * 解除一个锁定库存
     */

    private function _unlock_stock($skid) {
        if (!$this->sk_exists($skid) || $this->get_stock($skid, 'locked') < 1) {
            return false;
        } else {
            return $this->redisdb->plus("sk:{$skid}:stock:locked", -1);
        }
    }
    #获取所有秒杀记录
    #yan.kuai@magic-point.com 14-5-26 15:54
    public function get_all_orders($filter) {
        $where = 'WHERE 1=1';
        $where = $where . $filter;
        $sql = "SELECT `shop_order_id`,	`skid`, `userid`, `status`,`create_time` 
        FROM {$this->mysqldb->dbtabpre}orders {$where} 
        ORDER BY create_time DESC";
        
        $query = $this->mysqldb->query($sql);
        $orders = array();
        while ($order = $this->mysqldb->fetch_array($query)) {
            $orders[] = $order;
        }
        return $orders;
    }
    #取得该条件下的数量
    #yan.kuai@magic-point.com 14-5-26 14:55
    public function get_orders_count($filter) {
        $where = 'WHERE 1=1';
        $where = $where . $filter;
        $sql = "SELECT COUNT(`orderid`) AS count FROM {$this->mysqldb->dbtabpre}orders {$where} ";
        $count = $this->mysqldb->fetch_first($sql);
        
        return $count;
    }

}

?>
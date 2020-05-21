<?php

/*
 * 秒杀活动管理类
 */
!defined('IN_SK') && exit('Access Denied');

class sk_admin extends sk_base {
    /*
     * 构造方法
     */

    public function __construct() {
        parent::__construct();
    }

    /*
     * 创建或修改一个秒杀活动
     * 说明：创建一个秒杀活动。
     *   状态码：
     *     1 - 执行成功
     *     0 - 执行失败
     *   -1 - 秒杀活动在进行中，不可修改
     *   -2 - 库存不能为0或负数
     *   -3 - 每用户限秒杀商品数不能为0或负数
     *   -4 - 活动开始时间不能小于当前时间
     *   -5 - 活动结束时间不能小于活动开始时间
     *   -6 - 预约开始时间不能小于当前时间
     *   -7 - 预约结束时间不能小于预约开始时间
     *   -8 - 预约结束时间不能大于活动开始时间
     *   -9 - 预约开始时间和结束时间必须同时设置
     * 说明：
     * $skid为秒杀ID（以下同，略），
     * $stock为全部库存，
     * $orderlimit为每用户最多秒杀次数，
     * $starttime为秒杀开始时间（UNIX时间戳，以下涉及到时间的均为UNIX时间戳，略），
     * $endtime为秒杀结束时间，
     * $bookstarttime为预约开始时间（默认为0，即不需要预约），
     * $bookendtime为预约结束时间（必须和$bookstarttime同时设置）。
     * 参数合法性验证错误返回负数状态码，设置成功返回true，否则返回false。
     */

    public function create_sk($skid, $stock, $orderlimit, $starttime, $endtime, $bookstarttime = 0, $bookendtime = 0) {

        if ($skdetail = $this->get_sk_detail($skid)) {
            if ($skdetail['starttime'] < $this->timestamp ||
                    ($skdetail['bookstarttime'] != 0 &&
                    $skdetail['bookstarttime'] < $this->timestamp)) {
                return $this->format_return_data(false, -1, '秒杀引擎 - 秒杀活动在进行中，不可修改!');
            }
        }

        if ($stock <= 0) {
            return $this->format_return_data(false, -2, '秒杀引擎 - 库存不能为0或负数!');
        } elseif ($orderlimit <= 0) {
            return $this->format_return_data(false, -3, '秒杀引擎 - 每用户限秒杀商品数不能为0或负数!');
//		} elseif($starttime < $this->timestamp) {
//            return $this->format_return_data(false, -4, '秒杀引擎 - 活动开始时间不能小于当前时间!');
        } elseif ($endtime < $starttime) {
            return $this->format_return_data(false, -5, '秒杀引擎 - 活动结束时间不能小于活动开始时间!');
        } elseif ($bookstarttime != 0) {
            if ($bookstarttime < $this->timestamp) {
                return $this->format_return_data(false, -6, '秒杀引擎 - 预约开始时间不能小于当前时间!');
            } elseif ($bookendtime < $bookstarttime) {
                return $this->format_return_data(false, -7, '秒杀引擎 - 预约结束时间不能小于预约开始时间!');
            } elseif ($bookendtime > $starttime) {
                return $this->format_return_data(false, -8, '秒杀引擎 - 预约结束时间不能大于活动开始时间!');
            }
        } elseif ($bookendtime != 0) {
            return $this->format_return_data(false, -9, '秒杀引擎 - 预约开始时间和结束时间必须同时设置!');
        }

        $kvs = array(
            "sk:{$skid}:stock:all" => $stock,
            "sk:{$skid}:stock:rested" => $stock,
            "sk:{$skid}:stock:locked" => 0,
            "sk:{$skid}:orderlimit" => $orderlimit,
            "sk:{$skid}:starttime" => $starttime,
            "sk:{$skid}:endtime" => $endtime,
            "sk:{$skid}:bookstarttime" => $bookstarttime,
            "sk:{$skid}:bookendtime" => $bookendtime
        );

        if (!$skdetail) {
            $this->redisdb->sadd("sk:index", $skid);
        }

        $execute_result = $this->redisdb->mset($kvs);
        if ($execute_result) {
            return $this->format_return_data($execute_result, 1, '秒杀引擎 - 执行成功!');
        }
        return $this->format_return_data($execute_result, 0, '秒杀引擎 - 执行失败!');
    }

    /*
     * 立即停止一个秒杀活动
     */

    public function stop_sk($skid) {

        if (!$this->sk_exists($skid)) {
            return $this->format_return_data(false, 'no_exist', '不存在此秒杀活动');
        }

        $keys = array(
            "sk:{$skid}:stock:all",
            "sk:{$skid}:stock:rested",
            "sk:{$skid}:stock:locked",
            "sk:{$skid}:orderlimit",
            "sk:{$skid}:starttime",
            "sk:{$skid}:endtime",
            "sk:{$skid}:bookstarttime",
            "sk:{$skid}:bookendtime"
        );
        $this->redisdb->delete($keys);
        $this->redisdb->srem("sk:index", $skid);

        $keys = array();
        $sql = "SELECT DISTINCT `userid` FROM {$this->mysqldb->dbtabpre}orders
			WHERE `skid`='{$skid}'";
        $query = $this->mysqldb->query($sql);
        while ($row = $this->mysqldb->fetch_array($query)) {
            $keys[] = "user:{$row['userid']}:sk:{$skid}:orderids";
        }
        if (!empty($keys)) {
            $this->redisdb->delete($keys);
        }

        return $this->format_return_data(true, 'exist', '停止秒杀活动成功!');
    }

    /*
     * 追加秒杀活动商品库存
     */

    public function add_stock($skid, $stock) {
        if (!$this->sk_exists($skid) || $stock < 1) {
            return false;
        } else {
            return $this->redisdb->plus("sk:{$skid}:stock:all", $stock)
                    && $this->redisdb->plus("sk:{$skid}:stock:rested", $stock);
        }
    }

    /*
     * 延长秒杀活动时间
     */

    public function expand_time($skid, $time) {
        if (!$this->sk_exists($skid) || $time < 1) {
            return false;
        } else {
            return $this->redisdb->plus("sk:{$skid}:endtime", $time);
        }
    }

    /*
     * 清理 Redis 过期数据
     */

    public function flush_data($timepast) {
        if ($timepast < 0) {
            return false;
        } else {
            $deadline = $this->timestamp - $timepast;
            $skids = $this->redisdb->smembers("sk:index");
            foreach ($skids as $skid) {
                if ($this->redisdb->get("sk:{$skid}:endtime") < $deadline) {
                    $this->stop_sk($skid);
                }
            }
            $this->_flush_user_click($timepast);
            return true;
        }
    }

    /*
     * 清理用户最后点击时间数据
     */

    private function _flush_user_click($timepast) {

        if ($timepast < 0) {
            return false;
        }

        $delkeys = array();
        $deadline = $this->timestamp - $timepast - $GLOBALS['SK_CONFIG']['seckill']['clicklimit'];
        $keys = $this->redisdb->keys("user:*:lastclick");
        foreach ($keys as $key) {
            if ($this->redisdb->get($key) < $deadline) {
                $delkeys[] = $key;
            }
        }
        if (!empty($delkeys)) {
            $this->redisdb->delete($delkeys);
        }

        return true;
    }

}

?>
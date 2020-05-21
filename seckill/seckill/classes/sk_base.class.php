<?php

/*
 * 秒杀引擎基类
 */
!defined('IN_SK') && exit('Access Denied');

class sk_base extends sk_common {

    protected $mysqldb = null;  // MySQL 数据库访问对象
    protected $redisdb = null;  // Redis 数据库访问对象
    public $timestamp = null;  // UNIX 时间戳
    public $conn_status = null;

    /*
     * 构造方法
     */

    protected function __construct() {
        $this->timestamp = time();
        $this->mysqldb = new sk_mysql_db();
        $this->conn_status = $this->mysql_conn();
        $this->redisdb = new sk_redis_db();
        $this->conn_status = $this->redis_conn();
    }

    /*
     * 连接 MySQL 数据库
     */

    protected function mysql_conn() {
        $config = $GLOBALS['SK_CONFIG']['mysql'];
        return $this->mysqldb->connect($config['host'], $config['user'], $config['passwd'], $config['dbname'], $config['tabpre'], $config['charset'], $config['pconn']);
    }

    /*
     * 连接 Redis 数据库
     */

    protected function redis_conn() {
        $config = $GLOBALS['SK_CONFIG']['redis'];
        return $this->redisdb->connect($config['host'], $config['port'], $config['timeout'], $config['dbid'], $config['dbpwd'], $config['pconn']);
    }

    /*
     * 获取秒杀活动详细
     */

    public function get_sk_detail($skid) {
        if (!$this->sk_exists($skid)) {
            return false;
        } else {
            $getsk = $this->redisdb->mget(array(
                "sk:{$skid}:stock:all",
                "sk:{$skid}:stock:rested",
                "sk:{$skid}:stock:locked",
                "sk:{$skid}:orderlimit",
                "sk:{$skid}:starttime",
                "sk:{$skid}:endtime",
                "sk:{$skid}:bookstarttime",
                "sk:{$skid}:bookendtime"
                    ));
            $skdetail = array(
                'skid' => $skid,
                'stock_all' => $getsk[0],
                'stock_rested' => $getsk[1],
                'stock_locked' => $getsk[2],
                'orderlimit' => $getsk[3],
                'starttime' => $getsk[4],
                'endtime' => $getsk[5],
                'bookstarttime' => $getsk[6],
                'bookendtime' => $getsk[7]
            );
            return $skdetail;
        }
    }

    /*
     * 秒杀活动是否存在
     */

    public function sk_exists($skid) {
        return $this->redisdb->exists("sk:{$skid}:starttime");
    }

    /*
     * 获取秒杀活动状态
     * 说明：获取秒杀活动状态。
     *   状态码：
     *   1 - 秒杀活动进行中且库存充足
     *   2 - 秒杀活动预约中
     *   -1 - 秒杀活动进行中但已无库存
     *   -2 - 秒杀活动已过期
     *   -3 - 秒杀活动未开始
     */

    public function get_sk_status($skid) {
        if (!$skdetail = $this->get_sk_detail($skid)) {
            return false;
        } elseif ($skdetail['starttime'] <= $this->timestamp && $skdetail['endtime'] >= $this->timestamp) {
            if ($skdetail['stock_rested'] > 0) {
                return 1;
            } else {
                return -1;
            }
        } elseif ($skdetail['endtime'] < $this->timestamp) {
            return -2;
        } else {
            if ($skdetail['bookstarttime'] < $this->timestamp && $skdetail['bookendtime'] > $this->timestamp) {
                return 2;
            } else {
                return -3;
            }
        }
    }

    /*
     * 获取秒杀商品库存
     */

    public function get_stock($skid, $type = 'rested') {
        $type = in_array($type, array('all', 'rested', 'locked')) ? $type : 'rested';
        return $this->redisdb->get("sk:{$skid}:stock:{$type}");
    }

}

?>

<?php

/*
 * Redis 数据库操作类
 */
!defined('IN_SK') && exit('Access Denied');

class sk_redis_db extends sk_common {

    private $_redis = null;

    public function __construct() {
        if (!extension_loaded('redis')) {
            sk_write_log('redisError', date('Y-m-d H:i:s') . "\t" . 'Redis extension is not loaded!');
        }
    }

    public function connect($host, $port, $timeout, $dbid, $dbpwd, $dbpconn = 0) {
        if ($this->_redis) {
            return;
        } else {
            $this->_redis = new redis();
            $connfunc = $dbpconn ? 'pconnect' : 'connect';
            try {
                @$this->_redis->$connfunc($host, $port, $timeout, $dbid);
                if ($dbpwd) {
                    $this->_redis->auth($dbpwd);
                }
            } catch (Exception $e) {
                sk_write_log('redisError', date('Y-m-d H:i:s') . "\t" . 'Can\'t connect to Redis server! Please contact the webmaster, thank you!');
                return $this->format_return_data(false, '', 'Can\'t connect to Redis server! Please contact the webmaster, thank you!');
            }
        }
    }

    public function set($key, $value) {
        return $this->_redis->set($key, $value);
    }

    public function mset($kvs) {
        return $this->_redis->mSet($kvs);
    }

    public function get($key) {
        return $this->_redis->get($key);
    }

    public function mget($keys) {
        return $this->_redis->mGet($keys);
    }

    public function getset($key, $value) {
        return $this->_redis->getSet($key, $value);
    }

    public function delete($keys) {
        return $this->_redis->delete($keys);
    }

    public function sadd($key, $member) {
        return $this->_redis->sAdd($key, $member);
    }

    public function srem($key, $member) {
        return $this->_redis->sRem($key, $member);
    }

    public function smembers($key) {
        return $this->_redis->sMembers($key);
    }

    public function keys($pattern) {
        return $this->_redis->keys($pattern);
    }

    public function exists($key) {
        return $this->_redis->exists($key);
    }

    public function plus($key, $num = 1) {
        return $this->_redis->incrBy($key, $num);
    }

    public function info() {
        return $this->_redis->info();
    }

    public function incr($key, $num=1) {
        return $this->_redis->incr($key, $num);
    }

}

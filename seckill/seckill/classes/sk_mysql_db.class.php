<?php

/*
 * MySQL 数据库操作类
 */
!defined('IN_SK') && exit('Access Denied');

class sk_mysql_db extends sk_common {

    private $_link = null;
    public $dbtabpre = '';
    public $querynum = 0;

    public function __construct() {
        
    }

    public function connect($dbhost, $dbuser, $dbpwd, $dbname = '', $dbtabpre = '', $dbcharset = '', $dbpconn = 0) {
        if ($this->_link) {
            return;
        } else {
            $connfunc = $dbpconn ? 'mysqli_pconnect' : 'mysqli_connect';
            //if (!$this->_link = @$connfunc($dbhost, $dbuser, $dbpwd, true)) {
            if (!$this->_link = @$connfunc($dbhost, $dbuser, $dbpwd, $dbname)) {
                $this->_halt('Can\'t connect to MySQL server!');
                return $this->format_return_data(false, '', 'Can\'t connect to MySQL server!');
            }
            if ($dbcharset && !mysqli_query("SET NAMES {$dbcharset}", $this->_link)) {
                $this->_halt("Unknown character set '{$dbcharset}'!");
                return $this->format_return_data(false, '', "Unknown character set '{$dbcharset}'!");
            }
            //if ($dbname && !mysqli_select_db($dbname, $this->_link)) {
            //    $this->_halt("Unknown database '{$dbname}'!");
            //    return $this->format_return_data(false, '', "Unknown database '{$dbname}'!");
            //}
            $this->dbtabpre = $dbtabpre ? $dbtabpre : '';
        }
    }

    public function select_db($dbname) {
        return mysqli_select_db($dbname, $this->_link);
    }

    public function fetch_array($query, $result_type = MYSQL_ASSOC) {
        return mysqli_fetch_array($query, $result_type);
    }

    public function fetch_first($sql) {
        return $this->fetch_array($this->query($sql));
    }

    public function result_first($sql) {
        return $this->result($this->query($sql), 0);
    }

    public function query($sql, $type = '') {
        $func = $type == 'UNBUFFERED' && @function_exists('mysqli_unbuffered_query') ? 'mysqli_unbuffered_query' : 'mysqli_query';
        if (!($query = $func($this->_link,$sql)) && $type != 'SILENT') {
            $this->_halt('MySQL query error!'."\t {$sql}");
        }
        $this->querynum++;
        return $query;
    }

    public function affected_rows() {
        return mysqli_affected_rows($this->_link);
    }

    public function result($query, $row) {
//print_r($query);exit;
        $rows = @mysqli_fetch_array($query, MYSQLI_BOTH);
        return $rows[$row];
        return @mysqli_result($query, $row);
    }

    public function num_rows($query) {
        $query = mysqli_num_rows($query);
        return $query;
    }

    public function num_fields($query) {
        return mysqli_num_fields($query);
    }

    public function free_result($query) {
        return mysqli_free_result($query);
    }

    public function insert_id() {
        return ($id = mysqli_insert_id($this->_link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
    }

    public function fetch_row($query) {
        return mysqli_fetch_row($query);
    }

    public function fetch_fields($query) {
        return mysqli_fetch_field($query);
    }

    public function version() {
        return mysqli_get_server_info($this->_link);
    }

    public function close() {
        return mysqli_close($this->_link);
    }

    private function _error() {
        return (($this->_link) ? mysqli_error($this->_link) : 'false'); //mysqli_error());
    }

    private function _errno() {
        return intval(($this->_link) ? mysqli_errno($this->_link) : 'false'); //mysqli_errno());
    }

    private function _halt($message = '') {
        sk_write_log('mysqlerror', date('Y-m-d H:i:s') . "\t" . $this->_errno() . "\t" . $this->_error(). "\t". var_export($message,1));
    }

}



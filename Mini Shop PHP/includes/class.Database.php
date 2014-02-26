<?php

    class Database {
        
        public $db;
        
        public function __construct() {
            $this->db = mysql_connect(DB_HOST, DB_USER, DB_PASS);
            mysql_select_db(DB_NAME);
        }
        
        public function fetchAll($query, $start = '', $end = '') {
            if($start != '' && $end != '') {
                $add = " LIMIT ($start, $end)";
            } else {
                $add = '';
            }
            $rows = mysql_query($query.$add);
            echo mysql_error();
            if(mysql_num_rows($rows) == 0)
            {
                return false;
            }
            while($res = mysql_fetch_array($rows)) {
                $result[] = $res;
            }
            return $result;
        }
        
        public function fetchRow($query) {
            return @mysql_fetch_array(mysql_query($query));
        }
        
        public function insert($data, $table) {
            foreach($data as $k=>$v) {
                $colls[] = $k;
                $rows[] = '"'.$v.'"';
            }
            $line = "INSERT INTO $table (".implode(', ', $colls).") VALUES(".implode(', ', $rows).")";
            $lid = mysql_query($line);
            return mysql_insert_id();
        }
        
        public function update($data, $table, $usl) {
            foreach($data as $k=>$v) {
                $cols[] = "$k='$v'";
            }
            $col = implode(', ', $cols);
            
            foreach($usl as $k=>$v) {
                $uss[] = "$k='$v'";
            }
            $us = implode(' AND ', $uss);
            
            
            $sql = "UPDATE $table SET $col WHERE $us";
            //echo $sql;
            mysql_query($sql);
        }
        
        public function delete($table, $usl) {
            foreach($usl as $k=>$v) {
                $uss[] = "$k='$v'";
            }
            $us = implode(' AND ', $uss);
            $sql = "DELETE FROM $table WHERE $us";
            mysql_query($sql);
        }
        
        public function countRows($table, $usl) {
            foreach($usl as $k=>$v) {
                $uss[] = "$k='$v'";
            }
            $us = implode(' AND ', $uss);
            $sql = "SELECT count(*) FROM $table WHERE $us";
            $res = mysql_fetch_row(mysql_query($sql));
            return $res[0];
        }
        
    }
$gDb = new Database();

?>
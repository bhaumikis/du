<?php

define("MY_DB_DEBUGGER", true);

class MyDB {

    var $serverAddress;
    var $username;
    var $password;
    var $database;
    var $pConnect;
    var $link = null;
    var $DEBUG_ARRAY;

    function MyDB($DATABASE_CREDENTIALS = array()) {
        $this->DEBUG_ARRAY = array();
        $this->getCredentials($DATABASE_CREDENTIALS);
        $this->open();
    }

    function open() {

        try {
            $this->link = new PDO("mysql:host=" . $this->serverAddress . ";dbname=" . $this->database, $this->username, $this->password, array(PDO::ATTR_PERSISTENT => (boolean) $this->pConnect, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 'SET time_zone' => '+00:00'));
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->errorOutput('Connection failed: ' . $e->getMessage());
        }

        return $this->link;
    }

    function close() {
        return $this->link = null;
    }

    function getStatement($query = "") {
        return $this->link->prepare($query);
    }

    function interpolateQuery($query, $params) {
        $keys = array();

        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }
        }

        $query = preg_replace($keys, $params, $query, 1, $count);

        return $query;
    }

    function executeStatement($query = "", $params = array()) {
        if (MY_DB_DEBUGGER == true) {
            array_push($this->DEBUG_ARRAY, $this->interpolateQuery($query, $params));
        }

        $stmt = $this->getStatement($query);

        $result = $stmt->execute($params) or $this->errorOutput($this->interpolateQuery($query, $params));

        return $stmt;
    }

    function query($query, $params = array()) {
        return $this->executeStatement($query, $params);
    }

    function queryData($query = "", $params = array()) {
        $stmt = $this->executeStatement($query, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function queryOne($query, $params = array()) {
        $query = $query . " LIMIT 0,1";
        $stmt = $this->executeStatement($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function cleanParameter($param) {
        return mysql_real_escape_string($_REQUEST[$param]);
    }

    function clean($value) {
        return mysql_real_escape_string($value);
    }

    function nextRecord($stmt) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function recordCount($stmt) {
        return $stmt->rowCount();
    }

    function rowsAffected($stmt) {
        return $stmt->rowCount();
    }

    function describeTable($table = "") {
        return $this->queryData("DESCRIBE `" . $table . "`");
    }

    function insert($table, $data, $id = null) {
        reset($data);

        $data = $this->filterFields($table, $data);

        if ($id != null) {
            //local the table for writes
            $this->query("LOCK TABLES " . $table . " WRITE");
        }
        $query = 'INSERT INTO `' . $table . '` (';
        while (list ($columns, ) = each($data)) {
            $query .= "`" . $columns . "`" . ', ';
        }
        $query = substr($query, 0, -2) . ') VALUES (';
        reset($data);

        $params = array();
        while (list ($columns, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= 'now(), ';
                    break;
                case 'null' :
                    $query .= 'null, ';
                    break;
                default :
                    $query .= ":" . $columns . ", ";
                    break;
            }
            $params[":" . $columns] = $value;
        }
        $query = substr($query, 0, -2) . ')';

        $stmt = $this->executeStatement($query, $params);

        if ($id != null) {
            $this->query("UNLOCK TABLES");
        }

        return $this->link->lastInsertId();
    }

    function insertignore($table, $data, $id = null) {
        reset($data);

        $data = $this->filterFields($table, $data);

        if ($id != null) {
            //local the table for writes
            $this->query("LOCK TABLES " . $table . " WRITE");
        }
        $query = 'INSERT IGNORE INTO `' . $table . '` (';
        while (list ($columns, ) = each($data)) {
            $query .= "`" . $columns . "`" . ', ';
        }
        $query = substr($query, 0, -2) . ') VALUES (';
        reset($data);

        $params = array();
        while (list ($columns, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= 'now(), ';
                    break;
                case 'null' :
                    $query .= 'null, ';
                    break;
                default :
                    $query .= ":" . $columns . ", ";
                    break;
            }
            $params[":" . $columns] = $value;
        }
        $query = substr($query, 0, -2) . ')';

        $stmt = $this->executeStatement($query, $params);

        if ($id != null) {
            $this->query("UNLOCK TABLES");
        }

        return $this->link->lastInsertId();
    }

    function replace($table, $data, $id = null) {
        reset($data);

        $data = $this->filterFields($table, $data);

        if ($id != null) {
            //local the table for writes
            $this->query("LOCK TABLES " . $table . " WRITE");
        }
        $query = 'REPLACE INTO `' . $table . '` (';
        while (list ($columns, ) = each($data)) {
            $query .= "`" . $columns . "`" . ', ';
        }
        $query = substr($query, 0, -2) . ') VALUES (';
        reset($data);

        $params = array();
        while (list ($columns, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= 'now(), ';
                    break;
                case 'null' :
                    $query .= 'null, ';
                    break;
                default :
                    $query .= ":" . $columns . ", ";
                    break;
            }
            $params[":" . $columns] = $value;
        }
        $query = substr($query, 0, -2) . ')';

        $stmt = $this->executeStatement($query, $params);

        if ($id != null) {
            $this->query("UNLOCK TABLES");
        }

        return $this->link->lastInsertId();
    }

    function filterFields($table = "", $data = array()) {

        $text_datatypes = array("varchar", "char", "text", "tinytext", "mediumtext", "longtext", "binary",
            "varbinary", "tinyblob", "mediumblob", "blob", "longblog", "enum", "set");


        $d_table_result = $this->describeTable($table);

        $tmp_fileds = array();

        for ($i = 0; $i < count($d_table_result); $i++) {
            $d_table_result[$i]["Type"] = preg_replace("/\((.*)\)/i", "", $d_table_result[$i]["Type"]);
            $tmp_fileds[$d_table_result[$i]["Field"]] = $d_table_result[$i];
        }

        foreach ($data as $field => $value) {
            if (!in_array($field, array_keys($tmp_fileds))) {
                unset($data[$field]);
            }

//            $datatype_of_field = $tmp_fileds[$field]["Type"];
//            if (in_array(strtolower($datatype_of_field), $text_datatypes)) {
//                $data[$field] = $this->clean($data[$field]);
//            }
        }

        return $data;
    }

    function update($table, $data, $whereclause = '') {

        $params = array();
        $where = "";

        $data = $this->filterFields($table, $data);

        $query = 'UPDATE `' . $table . '` SET ';
        while (list ($columns, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= $columns . ' = now(), ';
                    break;
                case 'null' :
                    $query .= $columns .= ' = null, ';
                    break;
                default :
                    $query .= "`" . $columns . "`" . " = :" . $columns . ", ";
                    break;
            }
            $params[":" . $columns] = $value;
        }

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = array_merge($params, $whereclause["params"]);
            }
        } else {
            $where = $whereclause;
        }

        if (isset($where) and !empty($where)) {
            $query = substr($query, 0, -2) . ' WHERE ' . $where;
        }

        //This is only here to debug and track sql calls
        $stmt = $this->executeStatement($query, $params);

        return $this->rowsAffected($stmt);
    }

    function delete($table, $whereclause = '') {

        $params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        $query = 'DELETE FROM `' . $table . '`';

        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }

        $stmt = $this->executeStatement($query, $params);

        return $this->rowsAffected($stmt);
    }

    function select($table, $whereclause = '') {
        $params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        $query = 'SELECT * FROM `' . $table . '`';

        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }

        $stmt = $this->executeStatement($query, $params);

        return $stmt;
    }

    function selectField($fields = array(), $table, $whereclause = '', $order = '', $limit = '') {

        if (count($fields)) {
            $field_list = implode(",", $fields);
        } else {
            $field_list = "*";
        }

        $params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        $query = 'SELECT ' . $field_list . ' FROM `' . $table . '`';
        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }
        if ($order != '') {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit != '') {
            $query .= ' LIMIT ' . $limit;
        }

        $stmt = $this->executeStatement($query, $params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function selectData($table, $whereclause = '', $order = '', $limit = '') {

        $params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        $query = 'SELECT * FROM `' . $table . '`';
        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }
        if ($order != '') {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit != '') {
            $query .= ' LIMIT ' . $limit;
        }

        $stmt = $this->executeStatement($query, $params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function selectFieldOne($fields = array(), $table, $whereclause = '') {

        $params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        if (count($fields)) {
            $field_list = implode(",", $fields);
        } else {
            $field_list = "*";
        }

        $query = 'SELECT ' . $field_list . ' FROM `' . $table . '`';
        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }

        $query .= " LIMIT 0,1 ";

        $stmt = $this->executeStatement($query, $params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function selectOne($table, $whereclause = '') {

        $params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        $query = 'SELECT * FROM `' . $table . '`';
        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }
        $query .= " LIMIT 0,1 ";

        $stmt = $this->executeStatement($query, $params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function fetchFieldList($table = "") {
        $stmt = $this->query("SHOW COLUMNS FROM `" . $table . "`");

        $fields = array();
        while ($field = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fields[] = $field["Field"];
        }

        return $fields;
    }

    function outputHtml($string) {
        return stripslashes(htmlspecialchars($string));
    }

    function errorOutput($query) {
        die('<div style="margin:10px;border:1px solid #aaa"><font color="#000000"><b>' .
                $this->link->errorCode() . ' - ' . implode(" -> ", $this->link->errorInfo()) . '<br><br>' . $query .
                '<br><br><small><font color="#ff0000">[DATABASE ERROR STOP]</font></small><br><br></b></font></div>');
    }

    function getCredentials($value) {
        $this->serverAddress = $value["host"];
        $this->username = $value["username"];
        $this->password = $value["password"];
        $this->database = $value["database"];
        $this->pConnect = $value["pconnect"];
        return;
    }

    function getList($table = "", $params = array(), $whereclause = '', $order = '', $limit = '') {

        if (count($params)) {
            $field_list = implode(",", $params);
        } else {
            die("Invalid params");
        }

        $where_params = array();
        $where = "";

        if (is_array($whereclause)) {
            if (isset($whereclause["where"]) and !empty($whereclause["where"])) {
                $where = $whereclause["where"];
            }
            if (isset($whereclause["params"]) and !empty($whereclause["params"])) {
                $where_params = $whereclause["params"];
            }
        } else {
            $where = $whereclause;
        }

        $query = 'SELECT ' . $field_list . ' FROM `' . $table . '`';
        if (isset($where) and !empty($where)) {
            $query .= ' WHERE ' . $where;
        }
        if ($order != '') {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit != '') {
            $query .= ' LIMIT ' . $limit;
        }

        $stmt = $this->executeStatement($query, $where_params);

        $data = array();

        if ($stmt) {
            while ($d = $stmt->fetch(PDO::FETCH_BOTH)) {
                $data[$d[0]] = $d[1];
            }
        }

        return $data;
    }

    function fetchAssoc($stmp = null) {
        return $stmp->fetch(PDO::FETCH_ASSOC);
    }

    function getTotalFromQuery($query = "", $params = array()) {

        $stmt = $this->executeStatement($query, $params);

        $total = $stmt->fetch(PDO::FETCH_ASSOC);

        return $total["total"];
    }

    function getDBObject() {
        return $this->link;
    }

    function beginTransaction() {
        $this->link->beginTransaction();
    }

    function commit() {
        $this->link->commit();
    }

    function rollback() {
        $this->link->rollBack();
    }
    
    function inClauseEntityList($name = "", $items_count = 0) {
        $items = array();
        for ($i = 0; $i < $items_count; $i++) {
            $items[] = ":" . $name . $i;
        }
        return implode(',', $items);
    }

    function inClauseEntityParams($name = "", $values = 0) {

        for ($i = 0; $i < count($values); $i++) {
            $items[":" . $name . $i] = $values[$i];
        }

        return $items;
    }

}

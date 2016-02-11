<?php

define("MY_DB_DEBUGGER", true);

class MyDB {

    /**
     * Instance Variables for the class.
     */
    var $serverAddress;
    var $username;
    var $password;
    var $database;
    var $pConnect;
    var $link;
    var $DEBUG_ARRAY;

    /**
     * Constructor for the database class.
     */
    function MyDB($DATABASE_CREDENTIALS = array()) {
        $this->DEBUG_ARRAY = array();
        $this->getCredentials($DATABASE_CREDENTIALS);
        $this->open();
    }

    /**
     * Call this function to open the database. This function
     * must be called before any operation can be executed
     */
    function open() {

        //create database connection
        if ($this->pConnect == 'true') {
            $this->link = mysql_pconnect($this->serverAddress, $this->username, $this->password);
        } else {
            $this->link = mysql_connect($this->serverAddress, $this->username, $this->password);
        }

        if ($this->link)
            mysql_select_db($this->database);
        else
            $this->errorOutput("Connect to the database failed");

        return $this->link;
    }

    /**
     * Call this function to close the database. 
     * Need to close the database connection after all calls
     * are made.
     */
    function close() {
        return mysql_close($this->link);
    }

    /**
     * This function takes a query statement and returns a result set.
     * If there is an error it will execute die output the error.
     *
     * returns
     */
    function query($query) {

        //This is only here to debug and track sql calls
        if (MY_DB_DEBUGGER == true) {
            array_push($this->DEBUG_ARRAY, $query);
        }

        $results = mysql_query($query) or $this->errorOutput($query);

        return $results;
    }

    function queryData($query = "") {
        $resultSet = $this->query($query);
        $data = array();

        if ($resultSet) {
            while ($d = mysql_fetch_array($resultSet, MYSQL_ASSOC)) {
                $data[] = $d;
            }
        }

        return $data;
    }

    /**
     * Many times you need to just select the first row of data.  This method does
     * that and returns the record.
     *
     * This function takes a query statement and returns the first record returned.
     * If there is an error it will execute die output the error.
     *
     * Returns a database record
     */
    function queryOne($query) {
        $results = $this->query($query);
        return $this->nextRecord($results);
    }

    /*
     * This function cleans an HTTP input parameter to prevent SQL injection.
     * SQL injection BAD -> db_clean GOOD.
     * This should be used on any parameter that is added to the database.
     * SELECT INSERT DELETE UPDATE -> All of them
     */

    function cleanParameter($param) {
        return mysql_real_escape_string($_REQUEST[$param]);
    }

    /*
     * This function cleans the value parameter to prevent SQL injection.
     * SQL injection BAD -> db_clean GOOD.
     * This should be used on any parameter that is added to the database.
     * SELECT INSERT DELETE UPDATE -> All of them
     */

    function clean($value) {
        return mysql_real_escape_string($value);
    }

    /**
     * Returns the next record in the format of an Array. If no
     * more records are present, function returns null.
     *
     * Pass in the result set returned from the query function
     */
    function nextRecord($resultSet) {
        return mysql_fetch_array($resultSet, MYSQL_ASSOC);
    }

    /**
     * Returns the number of records.
     *
     * Pass in the result set returned from the query function
     */
    function recordCount($resultSet) {
        return mysql_num_rows($resultSet);
    }

    function describeTable($table = "") {
        return $this->queryData("DESCRIBE `" . $table . "`");
    }
    
    function describeTableFieldArray($table = "") {
        $table_fileds = $this->queryData("DESCRIBE `" . $table . "`");
        $fields = array();
        foreach ($table_fileds as $table_field) {
            $fields[] = $table_field["Field"];
        }

        return $fields;
    }

    /**
     * Call this function to insert a record into a database table.
     * An example of using this function is as follows.  Passing
     * a string as the last parameter return the id for the 
     * inserted data. The string is the column name of the id.
     *
     * $database->insert(
     * 		"product_table", 
     * 		array("name" => "Super Widget", "description" => $database->cleanParameter("description")), 
     * 		, "id" );
     */
    function insert($table, $data, $id = null) {
        reset($data);

        $data = $this->filterFields($table, $data);

        if ($id != null) {
            //local the table for writes
            $this->query("LOCK TABLES " . $table . " WRITE");
        }
        $query = 'insert into `' . $table . '` (';
        while (list ($columns, ) = each($data)) {
            $query .= "`" . $columns . "`" . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        while (list (, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= 'now(), ';
                    break;
                case 'null' :
                    $query .= 'null, ';
                    break;
                default :
                    $query .= "'" . $value . "', ";
                    break;
            }
        }
        $query = substr($query, 0, -2) . ')';
        
        $this->query($query);

        if ($id != null) {
            //Get the last inserted id and unlock the tables.
            $record = $this->queryOne("SELECT MAX(id) AS LAST_ID FROM `" . $table . "`");
            $this->query("UNLOCK TABLES");
            return $record["LAST_ID"];
        } else {
            //return null;
            $record = $this->queryOne("SELECT LAST_INSERT_ID() AS LAST_ID");
            return $record["LAST_ID"];
        }
    }

    function insertignore($table, $data, $id = null) {
        reset($data);

        $data = $this->filterFields($table, $data);

        if ($id != null) {
            //local the table for writes
            $this->query("LOCK TABLES " . $table . " WRITE");
        }
        $query = 'insert ignore into `' . $table . '` (';
        while (list ($columns, ) = each($data)) {
            $query .= "`" . $columns . "`" . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        while (list (, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= 'now(), ';
                    break;
                case 'null' :
                    $query .= 'null, ';
                    break;
                default :
                    $query .= "'" . $value . "', ";
                    break;
            }
        }
        $query = substr($query, 0, -2) . ')';
        $this->query($query);

        if ($id != null) {
            //Get the last inserted id and unlock the tables.
            $record = $this->queryOne("SELECT MAX(id) AS LAST_ID FROM `" . $table . "`");
            $this->query("UNLOCK TABLES");
            return $record["LAST_ID"];
        } else {
            //return null;
            $record = $this->queryOne("SELECT LAST_INSERT_ID() AS LAST_ID");
            return $record["LAST_ID"];
        }
    }

    function replace($table, $data, $id = null) {
        reset($data);

        $data = $this->filterFields($table, $data);

        if ($id != null) {
            //local the table for writes
            $this->query("LOCK TABLES " . $table . " WRITE");
        }
        $query = 'replace into `' . $table . '` (';
        while (list ($columns, ) = each($data)) {
            $query .= "`" . $columns . "`" . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        while (list (, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= 'now(), ';
                    break;
                case 'null' :
                    $query .= 'null, ';
                    break;
                default :
                    $query .= "'" . $value . "', ";
                    break;
            }
        }
        $query = substr($query, 0, -2) . ')';
        $this->query($query);

        if ($id != null) {
            //Get the last inserted id and unlock the tables.
            $record = $this->queryOne("SELECT MAX(id) AS LAST_ID FROM `" . $table . "`");
            $this->query("UNLOCK TABLES");
            return $record["LAST_ID"];
        } else {
            //return null;
            $record = $this->queryOne("SELECT LAST_INSERT_ID() AS LAST_ID");
            return $record["LAST_ID"];
        }
    }

    /**
     * Call this function to update a record in the database.  An example of this
     * call is the following.
     *
     * $database->update(
     * 		"product_table", 
     * 		array("name" => "Super Widget", "description" => $database->cleanParameter("description")), 
     * 		"id=5"
     * );
     */
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

            $datatype_of_field = $tmp_fileds[$field]["Type"];
            if (in_array(strtolower($datatype_of_field), $text_datatypes)) {
                $data[$field] = $this->clean($data[$field]);
            }
        }

        return $data;
    }

    function update($table, $data, $whereclause = '') {

        $data = $this->filterFields($table, $data);

        $query = 'update `' . $table . '` set ';
        while (list ($columns, $value) = each($data)) {
            switch ((string) $value) {
                case 'now()' :
                    $query .= $columns . ' = now(), ';
                    break;
                case 'null' :
                    $query .= $columns .= ' = null, ';
                    break;
                default :
                    $query .= "`" . $columns . "`" . " = '" . $value . "', ";
                    break;
            }
        }
        $query = substr($query, 0, -2) . ' where ' . $whereclause;
        
        return $this->query($query);
    }

    /**
     * Call this function to delete a record in the database.  An example of this
     * call is the following.
     *
     * $database->delete(
     * 		"product_table", 
     * 		"id=5"
     * );
     */
    function delete($table, $whereclause = '') {
        $query = 'DELETE FROM `' . $table . '` where ' . $whereclause;
        return $this->query($query);
    }

    /**
     * Call this function to select a record in the database.  An example of this
     * call is the following. Leave the where clause of to select all the records.
     * If your are doing more advanced selections ORDER BY, GROUP BY, OR JOINS
     * use query, queryOne.
     *
     * $database->select(
     * 		"product_table"
     * );
     */
    function select($table, $whereclause = '') {
        $query = 'SELECT * FROM `' . $table . '`';
        if ($whereclause != '') {
            $query .= ' where ' . $whereclause;
        }
        return $this->query($query);
    }

    function selectField($fields = array(), $table, $whereclause = '', $order = '', $limit = '') {

        if (count($fields)) {
            $field_list = implode(",", $fields);
        } else {
            $field_list = "*";
        }

        $query = 'SELECT ' . $field_list . ' FROM `' . $table . '`';
        if ($whereclause != '') {
            $query .= ' where ' . $whereclause;
        }
        if ($order != '') {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit != '') {
            $query .= ' LIMIT ' . $limit;
        }
echo $query;die;
        $resultSet = $this->query($query);

        $data = array();

        if ($resultSet) {
            while ($d = mysql_fetch_array($resultSet, MYSQL_ASSOC)) {
                $data[] = $d;
            }
        }

        return $data;
    }

    function selectData($table, $whereclause = '', $order = '', $limit = '') {
        $query = 'SELECT * FROM `' . $table . '`';
        if ($whereclause != '') {
            $query .= ' where ' . $whereclause;
        }
        if ($order != '') {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit != '') {
            $query .= ' LIMIT ' . $limit;
        }        
        
        $resultSet = $this->query($query);

        $data = array();

        if ($resultSet) {
            while ($d = mysql_fetch_array($resultSet, MYSQL_ASSOC)) {
                $data[] = $d;
            }
        }

        return $data;
    }

    function selectFieldOne($fields = array(), $table, $whereclause = '') {
        if (count($fields)) {
            $field_list = implode(",", $fields);
        } else {
            $field_list = "*";
        }

        $query = 'SELECT ' . $field_list . ' FROM `' . $table . '`';
        if ($whereclause != '') {
            $query .= ' where ' . $whereclause;
        }

        return $this->queryOne($query);
    }

    /**
     * Call this function to select a record in the database.  An example of this
     * call is the following. Leave the where clause of to select all the records.
     * If your are doing more advanced selections ORDER BY, GROUP BY, OR JOINS
     * use query, queryOne.
     *
     * $database->selectOne(
     * 		"product_table", 
     * 		"id=5"
     * );
     */
    function selectOne($table, $whereclause = '') {
        $query = 'SELECT * FROM `' . $table . '`';
        if ($whereclause != '') {
            $query .= ' where ' . $whereclause;
        }
        return $this->queryOne($query);
    }

    function fetchFieldList($table = "") {
        $r = mysql_query("SHOW COLUMNS FROM `" . $table . "`");

        $fields = array();
        while ($field = mysql_fetch_assoc($r)) {
            $fields[] = $field["Field"];
        }

        return $fields;
    }

    /**
     * Call this function to format the records value to be appropriate for displaying in HTML
     */
    function outputHtml($string) {
        return stripslashes(htmlspecialchars($string));
    }

    /**
     * PRIVATE METHODS
     */

    /**
     * This function outputs an error statement using the function parameters.
     * Use this on every database call to assist with debugging. 
     */
    function errorOutput($query) {
        die('<div style="margin:10px;border:1px solid #aaa"><font color="#000000"><b>' .
                mysql_errno() . ' - ' . mysql_error() . '<br><br>' . $query .
                '<br><br><small><font color="#ff0000">[DATABASE ERROR STOP]</font></small><br><br></b></font></div>');
    }

    /**
     * This is the only real private method
     */
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

        $query = 'SELECT ' . $field_list . ' FROM `' . $table . '`';
        if ($whereclause != '') {
            $query .= ' where ' . $whereclause;
        }
        if ($order != '') {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit != '') {
            $query .= ' LIMIT ' . $limit;
        }

        $resultSet = $this->query($query);

        $data = array();

        if ($resultSet) {
            while ($d = mysql_fetch_array($resultSet)) {
                $data[$d[0]] = $d[1];
            }
        }

        return $data;
    }

    function fetchAssoc($result = null) {
        return mysql_fetch_assoc($result);
    }

    function getTotalFromQuery($query = "") {

        $result = $this->queryOne($query);
        return $result["total"];
    }

}
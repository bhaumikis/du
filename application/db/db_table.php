<?php

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * DBTable Class
 * DBTable is database table's master class which use to perform the various database operation of the perticular database table.
 */
class DBTable {

	/**
	 * Database table name
	 * @var string
	 */
	protected $table_name;
	
	/**
	 * Database table primary key field name.
	 * @var array or string 
	 */
	protected $primary_keys;
	
    function __construct() {
        $this->database = configurations::getDBObject();
    }

    /**
     * Find the data from the database table
     * @param Array or String $keys
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >|Ambigous <multitype:, mixed>|boolean
     */
    function find($keys) {
        if (!is_array($this->primary_keys)) {

            if (is_array($keys)) {
                return $this->database->selectData($this->table_name, $this->primary_keys . " IN ('" . implode("','", $keys) . "')");
            } else {
                return $this->database->selectOne($this->table_name, array("where" => $this->primary_keys . " = :keys", "params" => array(":keys" => $keys)));
            }
        } else {

            if (!is_array($keys)) {
                return false;
            }

            if (isset($keys[0]) and is_array($keys[0])) {

                $where = array();
                foreach ($keys as $indkey) {
                    $sub_where[] = array();
                    foreach ($this->primary_keys as $pkf => $field) {
                        $sub_where[] = $field . " = '" . $this->database->clean($indkey[$pkf]) . "'";
                    }

                    $where[] = "(" . implode(" AND ", $sub_where) . ")";
                }

                return $this->database->selectData($this->table_name, "(" . implode(" OR ", $where) . ")");
            } else {
                $where[] = array();
                foreach ($this->primary_keys as $pkf => $field) {
                    $where[] = $field . " = '" . $this->database->clean($keys[$pkf]) . "'";
                }
                return $this->database->selectOne($this->table_name, "(" . implode(" AND ", $where) . ")");
            }
        }
    }

    /**
     * Find by fields
     * @param array $fields
     * @param array or string $keys
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >|Ambigous <multitype:, mixed>|boolean
     */
    function findByFields($fields = array(), $keys) {
        if (!is_array($this->primary_keys)) {

            if (is_array($keys)) {
                return $this->database->selectField($fields, $this->table_name, $this->primary_keys . " IN ('" . implode("','", $keys) . "')");
            } else {
                return $this->database->selectFieldOne($fields, $this->table_name, array("where" => $this->primary_keys . " = :keys", "params" => array(":keys" => $keys)));
            }
        } else {

            if (!is_array($keys)) {
                return false;
            }

            if (isset($keys[0]) and is_array($keys[0])) {

                $where = array();
                foreach ($keys as $indkey) {
                    $sub_where[] = array();
                    foreach ($this->primary_keys as $pkf => $field) {
                        $sub_where[] = $field . " = '" . $this->database->clean($indkey[$pkf]) . "'";
                    }

                    $where[] = "(" . implode(" AND ", $sub_where) . ")";
                }

                return $this->database->selectField($fields, $this->table_name, "(" . implode(" OR ", $where) . ")");
            } else {
                $where[] = array();
                foreach ($this->primary_keys as $pkf => $field) {
                    $where[] = $field . " = '" . $this->database->clean($keys[$pkf]) . "'";
                }
                return $this->database->selectFieldOne($fields, $this->table_name, "(" . implode(" AND ", $where) . ")");
            }
        }
    }

    /**
     * Fetch single row
     * @param string $whereclause
     * @return Ambigous <multitype:, mixed>
     */
    function fetchRow($whereclause = '') {
        return $this->database->selectOne($this->table_name, $whereclause);
    }

    /**
     * Fetch Single Row by fields  
     * @param array $fields
     * @param string $whereclause
     * @return Ambigous <multitype:, mixed>
     */
    function fetchRowByFields($fields = array(), $whereclause = '') {
        return $this->database->selectFieldOne($fields, $this->table_name, $whereclause);
    }

    /**
     * Fetch all rows of defined criteria 
     * @param string $whereclause
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >
     */
    function fetchAll($whereclause = '', $order = '', $limit = '') {
        return $this->database->selectData($this->table_name, $whereclause, $order, $limit);
    }

    /**
     * Fetch all rows of defined criteria by fields
     * @param array $fields
     * @param string $whereclause
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >
     */
    function fetchAllByFields($fields = array(), $whereclause = '', $order = '', $limit = '') {
        return $this->database->selectField($fields, $this->table_name, $whereclause, $order, $limit);
    }

    /**
     * Inert Data into the database table
     * @param unknown $data
     * @param string $id
     * @return Ambigous <unknown, string>
     */
    function insert($data, $id = null) {
        return $this->database->insert($this->table_name, $data, $id);
    }

    /**
     * Inert Data into the database table and ignore if data is already exist.
     * @param unknown $data
     * @param string $id
     * @return Ambigous <unknown, string>
     */
    function insertignore($data, $id = null) {
        return $this->database->insertignore($this->table_name, $data, $id);
    }

    /**
     * Inert Data into the database table and replace if data is already exist.
     * @param unknown $data
     * @param string $id
     * @return Ambigous <unknown, string>
     */
    function replace($data, $id = null) {
        return $this->database->replace($this->table_name, $data, $id);
    }

    /**
     * Update data into the database table as per the matched record in the where clause.
     * @param unknown $data
     * @param string $whereclause
     * @return mixed
     */
    function update($data, $whereclause = '') {
        return $this->database->update($this->table_name, $data, $whereclause);
    }

    /**
     * Delete Record(s) as per the matched record in the where clause.
     * @param string $whereclause
     * @return mixed
     */
    function delete($whereclause = '') {
        return $this->database->delete($this->table_name, $whereclause);
    }

}

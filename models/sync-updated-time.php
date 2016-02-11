<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief sync Updated Time Model updates database for last updated time so that we can track it for offline sync.
 */
class syncUpdatedTimeModel extends globalModel {

    /**
     * Update deleted Items
     * @param type $table
     * @param type $reference_key
     */
    function updateDeletedItems($table, $reference_key) {
        if (isset($reference_key) and !empty($reference_key)) {
            foreach ($reference_key as $id) {
                $data = array();
                $data['table'] = $table;
                $data['reference_id'] = $id;
                $data['reference_key_id'] = $_SESSION[$this->session_prefix]["user"]["user_id"];
                $data['deleted_on'] = date("Y-m-d H:i:s");
                $this->getDBTable("deleted-items")->insert($data);
            }
        }
    }

    /**
     * function to update deleted images
     * @param type $table
     * @param type $reference_key
     * @param type $extra_reference_keys
     */
    function updateDeletedImages($table, $reference_key, $extra_reference_keys = "") {
        if (isset($reference_key) and !empty($reference_key)) {
            foreach ($reference_key as $id) {
                $data = array();
                $data['table'] = $table;
                $data['reference_id'] = $id;
                $data['reference_key_id'] = $_SESSION[$this->session_prefix]["user"]["user_id"];
                $data['extra_reference_keys'] = $extra_reference_keys;
                $data['deleted_on'] = date("Y-m-d H:i:s");
                $this->getDBTable("deleted-items")->insert($data);
            }
        }
    }

}

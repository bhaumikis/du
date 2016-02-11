<?php

namespace model;


/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief permissions Model contains application logic for various functions and database operations of permissions Module.
*/
class permissionsModel extends globalModel {

    /**
     * This function is used to check the access based on module, controller and action parameters
     * @param $module
     * @param $option
     * @param $action
     * @return boolean
     */
    function hasAccessToResource($module = "", $option = "", $action = "") {

        $sql = "SELECT COUNT(*) AS total FROM `privileges` AS p INNER JOIN resources AS r ON (p.resource_id = r.resource_id AND p.usertype_id = '" . $_SESSION[$this->session_prefix]["user"]["usertype_id"] . "')
            WHERE r.module = '" . $module . "' AND r.option = '" . $option . "' AND r.action = '" . $action . "'";

        if ($total = $this->database->getTotalFromQuery($sql) and ($total != 0)) {
            return true;
        }

        return false;
    }

    /**
     * This function is used to check the permission for the logged in user for particular action and controller
     * @param $title
     * @return boolean
     */
    function checkPermission($title = "") {
        $sql = "SELECT COUNT(*) AS total FROM `privileges` AS p INNER JOIN resources AS r ON (p.resource_id = r.resource_id AND p.usertype_id = '" . $_SESSION[$this->session_prefix]["user"]["usertype_id"] . "')
            WHERE r.title = '" . $title . "'";

        if ($total = $this->database->getTotalFromQuery($sql) and ($total != 0)) {
            return true;
        }

        return false;
    }

}

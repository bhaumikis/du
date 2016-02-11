<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief User Types Model contains application logic for various functions and database operations of user types Module.
 */
class usertypesModel extends globalModel {

    /**
     * This function is used to get the list of user types
     * @return array
     */
    function getUserTypeList() {
        return $this->getDBTable("usertypes")->getList(array("usertype_id", "title"), "status = 1", "title ASC");
    }

    /**
     * This function is used to get list of user types which excludes customer_admin and customer_user
     * @return array
     */
    function getUserTypeListPlain() {

        return $this->getDBTable("usertypes")->getList(array("usertype_id", "title"), "status = 1 AND usertype_id != '" . ORDINARY_USER . "'", "title ASC");
    }

    /**
     * This function is used to get list of user types which excludes customer_admin and customer_user
     * @return array
     */
    function getUserTypeForCustomer() {
        return $this->getDBTable("usertypes")->fetchAll("status = 1 AND type IN ('2','3') AND usertype_id != '" . ORDINARY_USER . "'", "title ASC");
    }

    /**
     * This function is used to get list of user types which excludes customer_admin and customer_user
     * @return array
     */
    function getUserTypeCustomerUsers() {
        return $this->getDBTable("usertypes")->getList(array("usertype_id", "title"), "status = 1 AND type IN ('3') AND usertype_id != '" . ORDINARY_USER . "'", "title ASC");
    }

    /**
     * This function is used to get the user types for customer
     * @return array
     */
    function getUserTypeCustomerDetails() {
        return $this->getDBTable("usertypes")->fetchRow("title = 'Customers'");
    }

    /**
     * This function is used to get the list of all user type except ordinary user
     * @param string $order_by
     * @param sting $sortby
     * @return array
     */
    function listUserTypes($order_by, $sortby) {
        /*$sql = "SELECT * FROM `usertypes` WHERE usertype_id != '" . ORDINARY_USER . "' ORDER BY " . $order_by;
        $pager = new PS_Pagination($this->database->link, $sql, \generalFunctions::getConfValue("rows_per_page"), \generalFunctions::getConfValue("links_per_page"), "sortby=" . $sortby);
        $result = $pager->paginate();

        $usertypes = array();

        if ($result) {
            while ($row = $this->database->fetchAssoc($result)) {
                $usertypes[] = $row;
            }
        }

        return array($pager, $usertypes);*/
        
        $sql = "SELECT * FROM `usertypes` WHERE usertype_id != :usertype_id ORDER BY " . $order_by;
        $params = array("usertype_id"=>ORDINARY_USER);

        $pager = new \PS_Pagination($this->database->link, $sql, \generalFunctions :: getConfValue("rows_per_page"), \generalFunctions :: getConfValue("links_per_page"), "option=" . $_REQUEST["option"] . "&sortby=" . $sortby, $params);
        $stmt = $pager->paginate();

        $usertypes = array();
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $usertypes[] = $row;
            }
        }
    
        return array($pager, $usertypes);
    }

    /**
     * This function is used to get the list of all user type except ordinary user
     * @param string $order_by
     * @param string $sortby
     * @return array
     */
    function listUserTypesForSolution($order_by, $sortby) {
        $sql = "SELECT * FROM `usertypes` WHERE usertype_id NOT IN ('" . ORDINARY_USER . "','1') ORDER BY " . $order_by;
        $pager = new PS_Pagination($this->database->link, $sql, \generalFunctions::getConfValue("rows_per_page"), \generalFunctions::getConfValue("links_per_page"), "sortby=" . $sortby);
        $result = $pager->paginate();

        $usertypes = array();

        if ($result) {
            while ($row = $this->database->fetchAssoc($result)) {
                $usertypes[] = $row;
            }
        }

        return array($pager, $usertypes);
    }

    /**
     * This function is used to update the status of user type
     * @param int $usertype_id
     * @param int $status
     * @return boolean
     */
    function updateUserTypeStatus($usertype_id = 0, $status = 0) {
        $this->getDBTable("usertypes")->update(array("status" => $status), "usertype_id = '" . $usertype_id . "' AND usertype_id != 1");
        return true;
    }

    /**
     * This function is used to delete te user type if the user type is not Super Admin
     * @param int $usertype_id
     * @return void
     */
    function deleteUserType($usertype_id = 0) {
        //check if the any user exists for this user type, if yes then dont delete and return error message, if not exists then delete user type
        $arrUserCnt = $this->getDBTable("users")->fetchRow("usertype_id = '" . $usertype_id . "'");
        if (!empty($arrUserCnt) and count($arrUserCnt)) {
            return false;
        } else {
            $this->getDBTable("usertypes")->delete("usertype_id = '" . $usertype_id . "' AND usertype_id NOT IN ('" . SOLUTION_ADMIN . "','" . CUSTOMER_ADMIN . "','1')");
            return true;
        }
    }

    /**
     * This function is used to get user type details based on user_type_id
     * @param int $usertype_id
     * @return array
     */
    function getUserTypeDetails($usertype_id = 0) {
        return $this->getDBTable("usertypes")->fetchRow("usertype_id = '" . $usertype_id . "'");
    }

    /**
     * This function is used to save the privilasges based on the selected resource against priviledges
     * @return void
     */
    function savePreviledges() {
        $data = $_POST;

        if ($data["usertype_id"] == "1") {
            $_SESSION[$this->session_prefix]["action_message"] = _l("Super Administrator has all privileges.", "common");
            return false;
        }
        $this->getDBTable("privileges")->delete("usertype_id = '" . $data["usertype_id"] . "'");

        if (isset($data["chk"]) and is_array($data["chk"])) {
            foreach ($data["chk"] as $k => $v) {
                $this->getDBTable("privileges")->insert(array("usertype_id" => $data["usertype_id"], "resource_id" => $v));
            }
        }
        $_SESSION[$this->session_prefix]["action_message"] = _l("Privileges set successfully.", "common");
    }

    /**
     * This function is used to get the list of the resources based on user_type_id
     * @param int $usertype_id
     * @return $tmp_selected_modules_admin
     */
    function getSelectedResourceList($usertype_id = 0) {
        $selected_modules_admin = $this->getDBTable("privileges")->fetchAll("usertype_id = '" . $usertype_id . "'");

        $tmp_selected_modules_admin = array();
        foreach ($selected_modules_admin as $k => $v) {
            $tmp_selected_modules_admin[] = $v["resource_id"];
        }

        return $tmp_selected_modules_admin;
    }

    /**
     * This function is used to validate the parameters of the user type form, while performing the add/edit operation
     * @return boolean
     */
    function _validateUserTypeForm() {

        $errors = array();

        if (!isset($_POST["title"]) or !\generalFunctions::valueSet($_POST["title"])) {
            $errors[] = _l("Please enter title.", "usertypes");
        }
        if (!isset($_POST["status"]) or !\generalFunctions::valueSet($_POST["status"])) {

            $errors[] = _l("Please select status.", "usertypes");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * This function is used to perform the add and edit operation for the user types
     * @return void
     */
    function addEditUserType() {

        $data = $_POST;
        unset($data["submit"]);
        unset($data["cancel"]);

        if ($data["usertype_id"]) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("usertypes")->update($data, "usertype_id = '" . $data["usertype_id"] . "' AND usertype_id != 1");
            if ($data["usertype_id"] == 1) {
                $_SESSION[$this->session_prefix]["action_message"] = _l("Can not edit Super Administrator.", "common");
            } else {
                $_SESSION[$this->session_prefix]["action_message"] = _l("User type updated successfully.", "common");
            }
        } else { // Add Record
            unset($data["usertype_id"]);
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("usertypes")->insert($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("User type added successfully.", "common");
        }
    }

    /**
     * This function is used to get the all ediatble fields of the user Types in Admin
     * @param int $reference_id
     * @param int $usertype_id
     * @return int $fields
     */
    function getEditableFields($reference_id, $usertype_id) {

        $fields = array();
        $status = array();

        $status = $this->getFieldsStatus();

        $fields[] = array("reference" => "title", "type" => "textbox", "field_name" => "title", "text" => _l("Title", "usertypes"), "enc_key" => \generalFunctions::encrypt_decrypt("encrypt", "table=usertypes&field=title&reference_key=usertype_id&reference_id=" . $reference_id), "mode" => "inline");
        $fields[] = array("reference" => "description", "type" => "textarea", "field_name" => "description", "text" => _l("Description", "usertypes"), "showbuttons" => "bottom", "enc_key" => \generalFunctions::encrypt_decrypt("encrypt", "table=usertypes&field=description&reference_key=usertype_id&reference_id=" . $reference_id), "mode" => "inline");
        $fields[] = array("reference" => "status", "type" => "select", "field_name" => "status", "text" => _l("Select Status", "common"), "source" => $status, "value" => "1", "enc_key" => \generalFunctions::encrypt_decrypt("encrypt", "table=usertypes&field=status&reference_key=usertype_id&reference_id=" . $reference_id), "mode" => "inline");

        return $fields;
    }

    /**
     * This function is used to get the all ediatble fields of the user Types on Grid in Admin
     * @param array $rowdata
     * @return array $fields
     */
    function getEditableFieldsForHome($rowdata) {

        $fields = array();
        $status = array();

        $status = $this->getFieldsStatus();

        foreach ($rowdata as $k => $v) {

            $fields[] = array("reference" => "title" . ($k + 1), "type" => "textbox", "field_name" => "title", "text" => _l("Title", "usertypes"), "enc_key" => \generalFunctions::encrypt_decrypt("encrypt", "table=usertypes&field=title&reference_key=usertype_id&reference_id=" . $v['usertype_id'] . "&"), "mode" => "popup", "placement" => "right");
            $fields[] = array("reference" => "description" . ($k + 1), "type" => "textarea", "field_name" => "description", "text" => _l("Description", "usertypes"), "showbuttons" => "right", "enc_key" => \generalFunctions::encrypt_decrypt("encrypt", "table=usertypes&field=description&reference_key=usertype_id&reference_id=" . $v['usertype_id'] . "&"), "mode" => "popup", "placement" => "right");
            $fields[] = array("reference" => "status" . ($k + 1), "type" => "select2", "field_name" => "status", "text" => _l("Select Status", "common"), "source" => $status, "value" => "1", "enc_key" => \generalFunctions::encrypt_decrypt("encrypt", "table=usertypes&field=status&reference_key=usertype_id&reference_id=" . $v['usertype_id'] . "&"), "mode" => "popup", "placement" => "top");
        }
        return $fields;
    }

    /**
     * This function is used to get tree view structre of resources
     * @param array $resources
     * @return array $main_array
     */
    function getResourcesTreeView($resources) {

        $main_array = array();
        $child = array();
        $parent = array();

        foreach ($resources as $rskey => $rsvales) {

            if ($rsvales['action'] == "*" or $rsvales['action'] == "") {

                $parent = $rsvales;
                if (!array_key_exists($rsvales['option'], $main_array)) {
                    $child = array();
                }
            } else {
                if (array_key_exists($rsvales['option'], $main_array)) {
                    $child[] = $rsvales;
                } else {
                    $child = array();
                    $child[] = $rsvales;
                }
            }
            $main_array[$rsvales['option']] = array("parent" => $parent, "child" => $child, "module" => $rsvales['module']);
        }
        return $main_array;
    }

    /**
     * This function is used to get tree view structre in admin
     * @param array $resources
     * @return array $usertype
     */
    function getResourcesTreeViewAdmin($resources) {

        $main_array = array();
        $child = array();
        $parent = array();
        $usertype = array();

        foreach ($resources as $rskey => $rsvales) {
            if (empty($rsvales['module']) and empty($rsvales['option'])) {
                continue;
            }
            $main_array[$rsvales['module']][] = $rsvales;
        }

        foreach ($main_array as $key => $values) {
            $arrays = array();

            foreach ($values as $rskey => $rsvales) {
                if ($rsvales['action'] == "*") {
                    $parent = $rsvales;
                    if (!array_key_exists($rsvales['option'], $arrays)) {
                        $child = array();
                    }
                } else {
                    if (array_key_exists($rsvales['option'], $arrays)) {
                        $child[] = $rsvales;
                    } else {
                        $child = array();
                        $child[] = $rsvales;
                    }
                }
                $arrays[$rsvales['option']] = array("parent" => $parent, "child" => $child);
            }
            $usertype[$key] = $arrays;
        }
        return $usertype;
    }

}

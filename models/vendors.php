<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief User Types Model contains application logic for various functions and database operations of user types Module.
 */
class vendorsModel extends globalModel {

    /**
     * DU - This function is used to validate Vendor Form from the web
     * @param array $params
     * @param boolean $isRegister
     * @return type
     */
    function validateVendorForm($params = array(), $isRegister = true) {

        $errors = array();
        if (!isset($params["name"]) or !\generalFunctions::valueSet($params["name"])) {
            $errors[] = array("code" => "119", "message" => _l("Please enter name.", "services"));
        }

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to create the new registration of the user from the web services using the given parameters
     * @param array $data
     * @return $response
     */
    function addEditVendor($data) {

        unset($data["submit"], $data["cancel"]);

        if (isset($data["expense_vendor_id"]) and !empty($data["expense_vendor_id"])) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("expense-vendors")->update($data, array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $data['expense_vendor_id'])));
        } else { // Add Record
            unset($data["expense_vendor_id"], $data["user_application_id"]);

            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");

            if (isset($data["country"]) and !empty($data["country"])) {
                $data["country_id"] = $this->getModel("miscellaneous")->getCountryIdByName($data["country"]);
                unset($data["country"]);
            }

            $data["status"] = 1;
            $data["deleted"] = 0;
            $data["expense_vendor_id"] = $this->getDBTable("expense-vendors")->insert($data);
        }

        if (!empty($data["expense_vendor_id"])) {
            $response = array("server_id" => (int) $data["expense_vendor_id"], "LUID" => $data["LUID"]);
            return array(true, $response);
        } else {
            $response = array("code" => "1000", "message" => _l("Issue with Database operation.", "services"));
            return array(false, $response);
        }
    }

    /**
     * delete vendor for webservices
     * @param array $data
     * @return array
     */
    function deleteVendor($data) {
       // $strResponse = $this->getDBTable("expense-vendors")->delete(array("where" => "expense_vendor_id = :expense_vendor_id AND LUID = :LUID", "params" => array(":expense_vendor_id" => $data['expense_vendor_id'], ":LUID" => $data['LUID'])));
    	$strResponse = $this->getDBTable("expense-vendors")->delete(array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $data['expense_vendor_id'])));
        if ($strResponse) {
            return array(true, array("server_id" => (int) $data['expense_vendor_id'], "LUID" => (int) $data['LUID']));
        } else {
            return array(true, array("server_id" => (int) $data['expense_vendor_id'], "LUID" => (int) $data['LUID']));
        }
    }

    /** 
     * validate vendor id for webservice
     * @param array $params
     * @return array
     */
    function validateVendorId($params = array()) {

        $errors = array();
        if (!isset($params["expense_vendor_id"]) or !\generalFunctions::valueSet($params["expense_vendor_id"])) {
            $errors[] = array("code" => "101", "message" => _l("Please enter expense vendor id.", "services"));
        }
        /*
        if (!isset($params["LUID"]) or !\generalFunctions::valueSet($params["LUID"])) {
            $errors[] = array("code" => "102", "message" => _l("Please enter LUID.", "services"));
        }
        */

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * get all vendor of user
     * @return array
     */
    function getVendorListByUserId() {
        $rsResult = $this->getDBTable("expense-vendors")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));

        foreach ($rsResult as $arrData) {
            $arrFinal[$arrData['expense_vendor_id']] = $arrData['name'];
        }

        return $arrFinal;
    }

    /**
     * add edit vendor details
     * @return string
     */
    function addEditUserVendor() {
        $data = $_POST;

        unset($data["submit"], $data["cancel"], $data['hid_chk']);

        if ($data["expense_vendor_id"]) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("expense-vendors")->update($data, array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $data['expense_vendor_id'])));
            $_SESSION[$this->session_prefix]["action_message"] = _l("UPDATE_VENDOR_SUCCESS", "my-vendors");
        } else { // Add Record
            unset($data["expense_vendor_id"]);

            $data["user_id"] = $_SESSION[$this->session_prefix]['user']['user_id'];
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["status"] = 1;
            $data["deleted"] = 0;
            $data["expense_vendor_id"] = $this->getDBTable("expense-vendors")->insert($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_VENDOR_SUCCESS", "my-vendors");
        }

        if (!empty($data["expense_vendor_id"])) {

            return $data["expense_vendor_id"];
        } else {
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_VENDOR_FAIL", "my-vendors");
            return "";
        }
    }

    /**
     * get all vendor of a user
     * @param int $user_id
     * @param double $timestamp
     * @return array
     */
    function getVendorList($user_id, $timestamp) {
        $strWhere = "1";
        $arrParams = array();

        if (isset($timestamp) and !empty($timestamp)) {
            $strWhere .= " AND user_id = :user_id AND updated_date >= :updated_date";
            $arrParams[':updated_date'] = date("Y-m-d H:i:s", $timestamp);
        } else {
            $strWhere .= " AND user_id = :user_id AND status = :status";
            $arrParams[':status'] = 1;
        }

        $arrParams[':user_id'] = $user_id;

        $rsResult = $this->getDBTable("expense-vendors")->fetchAll(array("where" => $strWhere, "params" => $arrParams));

        $this->typeCastFields(array("int" => array('expense_vendor_id', 'user_id', 'LUID')), $rsResult, 2);

        foreach ($rsResult as $intKey => $arrDetail) {
            $rsResult[$intKey]['server_id'] = $arrDetail['expense_vendor_id'];
            foreach ($arrDetail as $strKey => $strdata) {
                if (empty($strdata)) {
                    $rsResult[$intKey][$strKey] = '';
                }
            }
            if (isset($arrDetail['LUID']) and empty($arrDetail['LUID'])) {
                $rsResult[$intKey]['LUID'] = (int) 0;
            }
        }



        return $rsResult;
    }

    /**
     * This function is used to get the deleted vendors based on timestamp
     * @param int $user_id
     * @return array $deleted_vendors
     */
    function getDeletedVendors($user_id, $timestamp = 0) {

        $strWhere = " reference_key_id = :reference_key_id AND deleted_on > :deleted_on AND `table` = :table";
        $arrParams[':reference_key_id'] = $user_id;
        $arrParams[':deleted_on'] = date("Y-m-d H:i:s", $timestamp);
        $arrParams[':table'] = 'expense_vendors';
        $arrResults = $this->getDBTable("deleted-items")->fetchAllByFields(array("deleted_item_id", "reference_id"), array("where" => $strWhere, "params" => $arrParams));
        foreach ($arrResults as $arrResult) {
            $arrData[] = $arrResult['reference_id'];
        }

        return array($arrData);
    }

    /**
     * This function is used to get my vendor list.
     * @return array
     */
    function getMyVendorList() {
        
        $strWhere = "1";
        $arrParams = array();

        $strWhere .= " AND user_id = :user_id AND status = :status AND deleted=:deleted";
        $arrParams[':status'] = 1;
        $arrParams[':deleted'] = 0;
        $arrParams[':user_id'] = $_SESSION[$this->session_prefix]['user']['user_id'];

        $rsResult = $this->getDBTable("expense-vendors")->fetchAll(array("where" => $strWhere, "params" => $arrParams));
        
        foreach($rsResult as $intKey => $arrData)
        {
            $rsResult[$intKey]['have_expense'] = ($this->getVendorExpenseCount($arrData['expense_vendor_id']))?"yes":"no";
        }

        return $rsResult;
    }
//    function getMyVendorList($order_by) {
//        $sql = "SELECT * FROM expense_vendors WHERE user_id = :user_id AND deleted = :deleted ORDER BY " . $order_by;
//        $params = array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":deleted" => "0");
//
//        $pager = new \PS_Pagination($this->database->link, $sql, \generalFunctions :: getConfValue("rows_per_page"), \generalFunctions :: getConfValue("links_per_page"), "&sortby=" . $sortby, $params);
//        $stmt = $pager->paginate();
//
//        $vendors = array();
//        if ($stmt) {
//            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
//                $vendors[] = $row;
//            }
//        }
//
//        return array($pager, $vendors);
//    }

    /**
     * This function is used to vendor details by id.
     * @return array
     */
    function getVendorDetailsId($expense_vendor_id = 0) {
        return $this->getDBTable("expense-vendors")->fetchRow(array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $expense_vendor_id)));
    }

    /**
     * delete vendor by vendor id
     * @param type $expense_vendor_id
     */
    function deleteVendorById($expense_vendor_id) {
        if ($this->getVendorExpenseCount($expense_vendor_id) == 0) {
            $arrayID = array("0" => $expense_vendor_id);
            $this->getModel("sync-updated-time")->updateDeletedItems("expense_vendors", $arrayID);
            $this->getDBTable("expense-vendors")->delete(array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $expense_vendor_id)));
            $_SESSION[$this->session_prefix]["action_message"] = _l("DELETE_VENDOR_SUCCESS", "my-vendors");
        } else {
            $_SESSION[$this->session_prefix]["error_message"] = _l("VENDOR_ASSIGN_EXPENSE", "my-vendors");
        }
    }
    
    /**
     * delete vendor by vendor id
     * @param type $expense_vendor_id
     */
    function deleteMultipleVendors($expense_vendor_ids) {
        if(is_array($expense_vendor_ids)){
            foreach($expense_vendor_ids as $expense_vendor_id){
                if ($this->getVendorExpenseCount($expense_vendor_id) == 0) {
                    $arrayID = array("0" => $expense_vendor_id);
                    $this->getModel("sync-updated-time")->updateDeletedItems("expense_vendors", $arrayID);
                    $this->getDBTable("expense-vendors")->delete(array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $expense_vendor_id)));
                    $_SESSION[$this->session_prefix]["action_message"] = _l("DELETE_VENDOR_SUCCESS", "my-vendors");
                } else {
                    $_SESSION[$this->session_prefix]["error_message"] = _l("VENDOR_ASSIGN_EXPENSE", "my-vendors");
                }                
            }
        }
    }    

    /**
     * get all vendor expense count
     * @param int $user_vendor_id
     * @return array
     */
    function getVendorExpenseCount($user_vendor_id) {
        $strSQL = "SELECT count(0) as count_row
                    FROM user_expenses
                    WHERE expense_vendor_id = :expense_vendor_id";
        $arrParams[':expense_vendor_id'] = $user_vendor_id;
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        return $arrResult['count_row'];
    }

    /**
     * function to check user access
     * @param int $intVendorId
     * @return boolean
     */
    function checkVendorAccess($intVendorId) {
        $strSQL = "SELECT COUNT(0) AS total FROM expense_vendors WHERE user_id = :user_id AND expense_vendor_id=:expense_vendor_id";
        $intTotal = $this->database->getTotalFromQuery($strSQL, array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":expense_vendor_id" => $intVendorId));
        if ($intTotal > 0) {
            return true;
        } else {
            return false;
        }
    }

}

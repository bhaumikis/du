<?php

namespace model;


/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief expenseCategoriesModel contains application logic for various functions and database operations of user types Module.
*/
class expenseCategoriesModel extends globalModel {

    /**
     * function to validate category form
     * @param type $params
     * @param type $isRegister
     * @return type
     */
    function validateCategoryForm($params = array(), $isRegister = true) {

        $errors = array();
        if (!isset($params["title"]) or !\generalFunctions::valueSet($params["title"])) {
            $errors[] = array("code" => "119", "message" => _l("Please enter title.", "services"));
        }

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to create the new category
     * @return $response
     */
    function addEditCategory($data) {

        unset($data["submit"], $data["cancel"]);
        if(isset($data["base_expense_type_id"])) {
            $data["base_type_id"] = $data["base_expense_type_id"];
            unset($data["base_expense_type_id"]);
        }
        if (!empty($data["expense_category_id"]) AND $data["expense_category_id"] != 0) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("expense-categories")->update($data, array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $data['expense_category_id'])));
        } else { // Add Record
            unset($data["expense_category_id"], $data["user_application_id"]);
            $data["is_default"] = 0;
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["expense_category_id"] = $this->getDBTable("expense-categories")->insert($data);
        }
 
        if (!empty($data["expense_category_id"])) {
            $response = array("server_id" => (int) $data["expense_category_id"], "LUID" => $data["LUID"]);
            return array(true, $response);
        } else {
            $response = array("code" => "1000", "message" => _l("Issue with Database operation.", "services"));
            return array(false, $response);
        }
    }

    /**
     * function to delete category
     * @param type $data
     * @return type
     */
    function deleteCategory($data) {
        $strResponse = $this->getDBTable("expense-categories")->delete(array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $data['expense_category_id'])));

        if ($strResponse) {
            return array(true, array("server_id" => (int) $data['expense_category_id'], "LUID" => (int) $data['LUID']));
        } else {
            return array(true, array("server_id" => (int) $data['expense_category_id'], "LUID" => (int) $data['LUID']));
        }
    }

    /**
     *  function to delete category
     * @return string
     */
    function deleteUserCategory() {
        $countExpenseRow = $this->getCategoryExpenseCount($_REQUEST['expense_category_id']);
        if ($countExpenseRow == 0) {
            $arrCategories = $this->getAllCategoryRecords($_REQUEST['expense_category_id']);
            foreach ($arrCategories as $arrCategory) {
                $arrCatId = array("0" => $arrCategory['expense_category_id']);
                $this->getModel("sync-updated-time")->updateDeletedItems("expense_categories", $arrCatId);
                $strResponse = $this->getDBTable("expense-categories")->delete(array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $arrCategory['expense_category_id'])));
            }
            if ($strResponse) {
                $_SESSION[$this->session_prefix]["action_message"] = _l("DELETE_CATEGORY_SUCCESS", "my-categories");
                return 'SUCCESS';
            } else {
                return 'FAIL';
            }
        } else {
            return "EXISTS";
        }
    }

    /**
     * function to get all categories
     * @param type $intCategoryId
     * @return type
     */
    function getAllCategoryRecords($intCategoryId) {
        $arrParams[':expense_category_id'] = $intCategoryId;
        $arrParams[':parent_expense_category_id'] = $intCategoryId;
        $arrResults = $this->getDBTable("expense-categories")->fetchAllByFields(array("expense_category_id"), array("where" => "expense_category_id = :expense_category_id OR parent_expense_category_id = :parent_expense_category_id", "params" => $arrParams));
        return $arrResults;
    }

    /**
     * function to count expense of category
     * @param type $expense_category_id
     * @return type
     */
    function getCategoryExpenseCount($expense_category_id) {
        $strSQL = "SELECT count(0) as count_row
                    FROM user_expenses
                    WHERE expense_category_id IN (
                    SELECT expense_category_id
                    FROM expense_categories
                    WHERE expense_category_id = :expense_category_id OR parent_expense_category_id = :parent_expense_category_id)";
        $arrParams[':expense_category_id'] = $expense_category_id;
        $arrParams[':parent_expense_category_id'] = $expense_category_id;
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        return $arrResult['count_row'];
    }

    /**
     * function to validate category
     * @param type $params
     * @return type
     */
    function validateCategoryId($params = array()) {

        $errors = array();
        if (!isset($params["expense_category_id"]) or !\generalFunctions::valueSet($params["expense_category_id"])) {
            $errors[] = array("code" => "101", "message" => _l("Please enter expense category id.", "services"));
        }
        if (!isset($params["LUID"]) or !\generalFunctions::valueSet($params["LUID"])) {
            $errors[] = array("code" => "102", "message" => _l("Please enter LUID.", "services"));
        }

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * function to get all categories of user
     * @return type
     */
    function getCategoryListForUser() {
        $arrData = array();
        $arrResult = $this->getCategoriesList();
        $arrDefaultCategories = $this->getDefaultExpenseCategories();
        foreach ($arrResult as $arrCategoriesData) {
            $arrData[$arrCategoriesData['base_type_id']]['base_name'] = $arrCategoriesData['base_expense_type_name'];
            if (!empty($arrCategoriesData['parent_expense_category_id'])) {
                $arrData[$arrCategoriesData['base_type_id']]['data'][$arrCategoriesData['parent_expense_category_id']]['data'][] = $arrCategoriesData;
                $arrData[$arrCategoriesData['base_type_id']]['data'][$arrCategoriesData['parent_expense_category_id']]['cat_name'] = $arrCategoriesData['parent_category'];
                unset($arrDefaultCategories[$arrCategoriesData['base_type_id']][$arrCategoriesData['parent_expense_category_id']]);
            }

            $arrData[$arrCategoriesData['base_type_id']]['cat_name'] = $arrDefaultCategories[$arrCategoriesData['base_type_id']];
        }
        return $arrData;
    }

    /**
     * function to get default category
     * @return type
     */
    function getDefaultCategories() {
        $arrData = array();
        $arrResults = $this->getDBTable("expense-categories")->fetchAllByFields(array("expense_category_id"), array("where" => "(parent_expense_category_id = :parent_expense_category_id OR parent_expense_category_id is NULL) AND user_id is null AND status=1", "params" => array(":parent_expense_category_id" => 0)));

        foreach ($arrResults as $arrResult) {
            $arrData[] = $arrResult['expense_category_id'];
        }
        return $arrData;
    }

    /**
     * function to get all default category
     * @return type
     */
    function getDefaultExpenseCategories() {
        $arrData = array();
        $arrResults = $this->getDBTable("expense-categories")->fetchAllByFields(array("expense_category_id,title,base_type_id"), array("where" => "(parent_expense_category_id = :parent_expense_category_id OR parent_expense_category_id is null) AND user_id is null AND status=1", "params" => array(":parent_expense_category_id" => 0)));

        foreach ($arrResults as $arrResult) {
            $arrData[$arrResult['base_type_id']][$arrResult['expense_category_id']] = $arrResult['title'];
        }

        return $arrData;
    }

    /**
     * function to get all category
     * @return type
     */
    function getCategoriesList() {
        $strSQL = "SELECT ec.expense_category_id,ec.base_type_id,parent_expense_category_id,title
                            ,bet.base_expense_type_name,
                            (select title from expense_categories where expense_category_id = ec.parent_expense_category_id) as parent_category
                    FROM expense_categories ec
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ec.base_type_id
                    where ec.user_id = :user_id OR ec.is_default = :is_default and ec.status =:status";
        $arrParams[':user_id'] = $_SESSION[$this->session_prefix]['user']['user_id'];
        $arrParams[':is_default'] = 1;
        $arrParams[':status'] = 1;
        $arrResult = $this->database->queryData($strSQL, $arrParams);
        return $arrResult;
    }

    /**
     * function to get user category
     * @return type
     */
    function getUserParentCategories() {
        $arrData = array();
        $arrResults = $this->getDBTable("expense-categories")->fetchAllByFields(array("expense_category_id,title,base_type_id"), array("where" => "(parent_expense_category_id = :parent_expense_category_id OR parent_expense_category_id is null) AND user_id=:user_id AND status=1", "params" => array(":parent_expense_category_id" => 0, ":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));

        foreach ($arrResults as $arrResult) {
            $arrData[$arrResult['base_type_id']][$arrResult['expense_category_id']] = $arrResult['title'];
        }

        return $arrData;
    }

    /**
     * function to get all category
     * @return type
     */
    function getAllCategories() {
        $arrData = array();
        $arrResult = $this->getCategoriesList();
        $arrDefaultCategories = $this->getDefaultExpenseCategories();
        $arrUserParentCategories = $this->getUserParentCategories();

        foreach ($arrResult as $arrCategoriesData) {
            $arrData[$arrCategoriesData['base_type_id']]['base_name'] = $arrCategoriesData['base_expense_type_name'];
            if (!empty($arrCategoriesData['parent_expense_category_id'])) {
                $arrData[$arrCategoriesData['base_type_id']]['data'][$arrCategoriesData['parent_expense_category_id']]['data'][] = $arrCategoriesData;
                $arrData[$arrCategoriesData['base_type_id']]['data'][$arrCategoriesData['parent_expense_category_id']]['cat_name'] = $arrCategoriesData['parent_category'];
            }


            $arrData[$arrCategoriesData['base_type_id']]['cat_name'] = $arrDefaultCategories[$arrCategoriesData['base_type_id']];
        }

        foreach ($arrUserParentCategories as $userBaseId => $userCategory) {
            foreach ($userCategory as $intUserCatId => $strUserCatName) {
                $arrData[$userBaseId]["cat_name"][$intUserCatId] = $strUserCatName;
            }
        }

        return $arrData;
    }

    /**
     * update categories
     */
    function updateExpenseCategories() {
        $arrExpenseId = explode(",", $_POST['user_expense_id']);
        foreach ($arrExpenseId as $IntExpenseId) {
            if (isset($_POST['sub_category_id']) and !empty($_POST['sub_category_id'])) {
                $intExpenseCategoryId = $_POST['sub_category_id'];
            } else {
                if (isset($_POST['category_id']) and !empty($_POST['category_id'])) {
                    $intExpenseCategoryId = $_POST['category_id'];
                } else {

                }
            }

            $arrData['expense_category_id'] = $intExpenseCategoryId;
            $arrData['base_type_id'] = $this->getBaseTypeByCategoryId($intExpenseCategoryId);
            $arrData["updated_date"] = date('Y-m-d H:i:s');

            $this->getDBTable("user-expenses")->update($arrData, array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $IntExpenseId)));
        }
    }

    /**
     * function to get base category
     * @return type
     */
    function getBaseCategoryList() {
        $arrFinal = array();

        $arrBaseData = $this->getDBTable("base-expense-types")->fetchAll();

        foreach ($arrBaseData as $arrBaseCategory) {
            $arrFinal[$arrBaseCategory['base_expense_type_id']] = $arrBaseCategory['base_expense_type_name'];
        }
        return $arrFinal;
    }

    /**
     * function to get base category by id
     * @param type $intCategoryId
     * @return type
     */
    function getBaseTypeByCategoryId($intCategoryId) {
        $arrResults = $this->getDBTable("expense-categories")->fetchRowByFields(array("base_type_id"), array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $intCategoryId)));
        return $arrResults['base_type_id'];
    }

    /**
     * function to get sub category of base category
     * @return type
     */
    function getSubCategoryOfBaseCategory() {
        $strSQL = "SELECT ec.expense_category_id, title
                    FROM expense_categories ec
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ec.base_type_id
                    where (ec.user_id = :user_id OR ec.is_default = :is_default) and ec.status =:status
                    and parent_expense_category_id=:parent_expense_category_id AND ec.base_type_id=:base_type_id";
        $arrParams[':user_id'] = $_SESSION[$this->session_prefix]['user']['user_id'];
        $arrParams[':is_default'] = 1;
        $arrParams[':status'] = 1;
        $arrParams[':parent_expense_category_id'] = 0;
        $arrParams[':base_type_id'] = $_POST['base_type_id'];

        $arrResult = $this->database->queryData($strSQL, $arrParams);
        return $arrResult;
    }

    /**
     * DU - This function is used to create the new registration of the user from the web services using the given parameters
     * @return $response
     */
    function addEditUserCategory() {

        $data = $_POST;

        unset($data["submit"], $data["cancel"]);
        if (!empty($data["expense_category_id"]) AND $data["expense_category_id"] != 0) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("expense-categories")->update($data, array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $data['expense_category_id'])));
            $_SESSION[$this->session_prefix]["action_message"] = _l("UPDATE_CATEGORY_SUCCESS", "my-categories");
        } else { // Add Record
            unset($data["expense_category_id"], $data["user_application_id"]);
            $data["is_default"] = 0;
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["user_id"] = $_SESSION[$this->session_prefix]['user']['user_id'];
            $data["status"] = 1;
            $data["expense_category_id"] = $this->getDBTable("expense-categories")->insert($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_CATEGORY_SUCCESS", "my-categories");
        }

        if (!empty($data["expense_category_id"])) {

        } else {
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_CATEGORY_FAIL", "my-categories");
        }
    }

    /**
     * get category data by id
     * @param type $intCategoryId
     * @return type
     */
    function getCategoryDataById($intCategoryId) {
        $arrResults = $this->getDBTable("expense-categories")->fetchRow(array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $intCategoryId)));
        return $arrResults;
    }

    /**
     * get user's category
     * @param type $user_id
     * @param type $timestamp
     * @return type
     */
    function getUserCategories($user_id, $timestamp) {
        $arrParams = array();
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $user_id;

        if (isset($timestamp) and !empty($timestamp)) {
            $strWhere .= " AND updated_date >= :updated_date";
            $arrParams[':updated_date'] = date("Y-m-d H:i:s", $timestamp);
        }

        $rsResult = $this->getDBTable("expense-categories")->fetchAll(array("where" => $strWhere, "params" => $arrParams));
        $arrIntType = array('is_default', 'expense_category_id', 'user_id', 'base_type_id', 'parent_expense_category_id', 'LUID', 'status');
        $this->typeCastFields(array("int" => $arrIntType), $rsResult, 2);

        foreach ($rsResult as $intKey => $arrDetail) {
            $rsResult[$intKey]['server_expense_category_id'] = $arrDetail['expense_category_id'];
            foreach ($arrDetail as $strKey => $strdata) {
                if (empty($strdata)) {
                    if (in_array($strKey, $arrIntType)) {
                        $rsResult[$intKey][$strKey] = 0;
                    } else {
                        $rsResult[$intKey][$strKey] = '';
                    }
                }
            }
            if (isset($arrDetail['LUID']) and empty($arrDetail['LUID'])) {
                $rsResult[$intKey]['LUID'] = (int) 0;
            }
            if (isset($arrDetail['LUID']) and empty($arrDetail['LUID'])) {
                $rsResult[$intKey]['LUID'] = (int) 0;
            }
        }

        return $rsResult;
    }

    /**
     * This function is used to get the deleted vendors based on timestamp
     * @param $user_id
     * @return $deleted_vendors
     */
    function getDeletedUserCategories($user_id, $timestamp = 0) {
        $arrData = array();
        $strWhere = " reference_key_id = :reference_key_id AND deleted_on > :deleted_on AND `table` = :table";
        $arrParams[':reference_key_id'] = $user_id;
        $arrParams[':deleted_on'] = date("Y-m-d H:i:s", $timestamp);
        $arrParams[':table'] = 'expense_categories';
        $arrResults = $this->getDBTable("deleted-items")->fetchAllByFields(array("deleted_item_id", "reference_id"), array("where" => $strWhere, "params" => $arrParams));
        foreach ($arrResults as $arrResult) {
            $arrData[] = $arrResult['reference_id'];
        }

        return array($arrData);
    }

    /**
     * get category name by id
     * @param type $intCategoryId
     * @return type
     */
    function getCategoryNameById($intCategoryId) {
        $arrParams = array();
        $strWhere = "expense_category_id = :expense_category_id";
        $arrParams[':expense_category_id'] = $intCategoryId;
        $rsResult = $this->getDBTable("expense-categories")->fetchRowByFields(array('title'), array("where" => $strWhere, "params" => $arrParams));
        return ($rsResult['title']) ? $rsResult['title'] : "";
    }

    /**
     * function to check user access
     * @param type $intCategoryId
     * @return boolean
     */
    function checkCategoryAccess($intCategoryId) {
        $strSQL = "SELECT COUNT(0) AS total FROM expense_categories WHERE user_id = :user_id AND expense_category_id=:expense_category_id";
        $intTotal = $this->database->getTotalFromQuery($strSQL, array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":expense_category_id" => $intCategoryId));
        if ($intTotal > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Function to add quick user category 
     * @return multitype:string unknown 
     */
    function AddQuickUserCategory(){
        $arrData = array();
        $data["title"] = ucwords($_POST['title']);
        $data["base_type_id"] = $_POST['base_id'];
        $data["parent_expense_category_id"] = ($_POST['parent_id'])?$_POST['parent_id']:0;
        $data["is_default"] = 0;
        $data["created_date"] = date("Y-m-d H:i:s");
        $data["updated_date"] = date("Y-m-d H:i:s");
        $data["user_id"] = $_SESSION[$this->session_prefix]['user']['user_id'];
        $data["status"] = 1;
        $data["expense_category_id"] = $this->getDBTable("expense-categories")->insert($data);        
        
        if(isset($data["expense_category_id"]) and !empty($data["expense_category_id"])){
            $arrData["expense_category_id"] = $data["expense_category_id"];
            $arrData["title"] = $data["title"];
            $arrData["message"] = "success";
        }else{
            $arrData["message"] = "failure";
        }
        return $arrData;
    }
    
    /**
     * Function to quick update user category
     * @return string
     */
    function quickUpdateUserCategoryOld(){
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["title"] = $_POST['title'];
            $strResponse = $this->getDBTable("expense-categories")->update($data, array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $_POST['expense_category_id'])));        
            if ($strResponse) {
                return 'SUCCESS';
            } else {
                return 'FAIL';
            }
    }
    
    /**
     * Function to quick update user category
     * @return string
     */
    function quickUpdateUserCategory(){
    	$data["updated_date"] = date("Y-m-d H:i:s");
        $data["title"] = $_POST['title'];
        $strResponse = $this->getDBTable("expense-categories")->update($data, array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $_POST['expense_category_id'])));
        if ($strResponse) {
    		return array("success"=>1,"id"=>$_POST['pk']);
    	} else {
    		return array("success"=>0);
    	}
    }

}



<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief Users Model contains application logic for various functions and database operations of users Module.
 */
class userExpensesModel extends globalModel {

    /**
     * This function is used to check all the validation for the users form,while performing add and edit operation from web service
     * @params $params
     * @params $isRegister
     * @return $errors
     */
    function validateExpenseForm($params = array()) {

        $errors = array();
        if (!isset($params["expense_amount"]) or !\generalFunctions::valueSet($params["expense_amount"])) {
            $errors[] = array("code" => "101", "message" => _l("Please enter amount.", "services"));
        }
        if (!isset($params["expense_date"]) or !\generalFunctions::valueSet($params["expense_date"])) {
            $errors[] = array("code" => "102", "message" => _l("Please enter date.", "services"));
        }
        
    	if (isset($params["expense_date"]) and !empty($params["expense_date"])) {
    		if (!(list($valid, $response) = validateTimeStampMS($params["expense_date"]) and $valid)) {
    			$errors[] = array("code" => "103", "message" => _l("Please enter valid timestamp of Expense Date.", "services"));
    		}
        }

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * Validate Expense Id
     * @param type $params
     * @return type
     */
    function validateExpenseId($params = array()) {

        $errors = array();
        if (!isset($params["expense_id"]) or !\generalFunctions::valueSet($params["expense_id"])) {
            $errors[] = array("code" => "101", "message" => _l("Please enter expense_id.", "services"));
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
     * add edit expense
     * @param type $data
     * @return multitype:boolean multitype:number Ambigous <>  
     */
    function addEditExpense($data) {
        $data['expense_vendor_id'] = $data['vendor_id'];
        $data["user_trip_id"] = $data["trip_id"];

        unset($data["cancel"], $data["submit"], $data["token"], $data['vendor_id']);
        if (isset($data['expense_date']) and strlen(trim($data['expense_date']))) {
            $data['exp_date_timestamp'] = $data['expense_date'];
            $data['expense_date'] = getTimestampMSFormat($data['exp_date_timestamp'], "MYSQL_DATE");
            $data['expense_time'] = getTimestampMSFormat($data['exp_date_timestamp'], "MYSQL_TIME");
        }
        if ($data["user_expense_id"]) { // Update Record
            $data["updated_date"] = getNow();
            $this->getDBTable("user-expenses")->update($data, array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $data['user_expense_id'])));
        } else { // Add Record
            unset($data["user_expense_id"]);
            $data["status"] = 1;
            $data["payment_mode"] = 2;
            $data["created_date"] = getNow();
            $data["updated_date"] = getNow();
            $data["user_expense_id"] = (int) $this->getDBTable("user-expenses")->insert($data);
        }

        if (!empty($data["user_expense_id"])) {
            $response = array("server_id" => (int) $data["user_expense_id"], "LUID" => $data["LUID"]);
            return array(true, $response);
        } else {
            $response = array("server_id" => 0, "LUID" => $data["LUID"]);
            return array(true, $response);
        }
    }

    /**
     * save expense raw image in images folder
     * @param type $data
     * @return type
     */
    function saveExpenseImage($arrImageData, $inputData) {
        $data["user_expense_id"] = $inputData['user_expense_id'];
        $data["expense_filetype"] = $arrImageData['extension'];
        $data["LUID"] = $arrImageData['LUID'];
        $user_expense_reference_id = $this->getDBTable("user-expenses-reference")->insert($data);
        $fileName = $user_expense_reference_id . "." . $arrImageData['extension'];
        $imagePath = APPLICATION_PATH . "/images/user_expenses/" . $fileName;
        $this->getModel("miscellaneous")->saveImages($imagePath, $arrImageData['binary_content']);
        $this->getDBTable("user-expenses-reference")->update(array("expense_filename" => $fileName), array("where" => "user_expense_reference_id = :user_expense_reference_id", "params" => array(":user_expense_reference_id" => $user_expense_reference_id)));

        $arrResponse['image_path'] = APPLICATION_URL . "/images/user_expenses/" . $fileName;
        $arrResponse['LUID'] = (int) $arrImageData['LUID'];
        $arrResponse['user_expense_reference_id'] = (int) $user_expense_reference_id;
        return array(true, $arrResponse);
    }

    /**
     * delete expense images from db as well physically
     * @param type $inputData
     * @return type
     */
    function deleteExpenseImages($inputData) {
        $arrImgInfo = pathinfo($inputData['image_path']);
        if ($arrImgInfo['filename']) {
            $this->getDBTable("user-expenses-reference")->delete(array("where" => "user_expense_reference_id = :user_expense_reference_id", "params" => array(":user_expense_reference_id" => $arrImgInfo['filename'])));
            @unlink(APPLICATION_PATH . "/images/user_expenses/" . $arrImgInfo['filename'] . "." . $arrImgInfo['extension']);
            return array(true, array("LUID" => (int) $inputData['LUID']));
        }
    }

    /**
     * function to get user expense
     * @param type $user_id
     * @return type
     */
    function getUserExpensesByUserId($user_id, $arrSearch) {
    	$strSQL = "SELECT
                            ".$this->getUserExpenseFieldByLocal().",
    						c.title,c.card_number,ut.trip_title,
                            if(bet.base_expense_type_name is not null,base_expense_type_name,'Uncategorized') as base_expense_type_name,
                            cs.currency_name AS base_currency_name,
                            cs.currency_symbol AS base_currency_symbol,
                            cs.currency_code AS base_currency_code,
                            csi.currency_name AS expense_currency_name,
                            csi.currency_symbol AS expense_currency_symbol,
                            csi.currency_code AS expense_currency_code,
                            ev.name,c.processor_type,ecp.title as parent_category, IF((bet.base_expense_type_name IS NOT NULL AND ec.title IS NULL), 'Uncategorized', ec.title) as category_title
                    FROM
                            user_expenses ue
                    LEFT JOIN cards c ON c.card_id = ue.card_id
                    LEFT JOIN expense_vendors ev ON ev.expense_vendor_id=ue.expense_vendor_id
                    LEFT JOIN user_trips ut ON ut.user_trip_id = ue.user_trip_id
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ue.base_type_id
                    LEFT JOIN expense_categories ec ON ec.expense_category_id = ue.expense_category_id
                    LEFT JOIN currencies cs ON cs.currency_id = ue.base_currency_id
                    LEFT JOIN currencies csi ON csi.currency_id = ue.expense_currency_id
                    LEFT JOIN expense_categories ecp ON ecp.expense_category_id = ec.parent_expense_category_id
                    WHERE 1";

        if ($arrSearch['user_trip_id'] != '' and $arrSearch['user_trip_id'] != '') {
            $arrParams[':user_trip_id'] = $arrSearch['user_trip_id'];
            $strSQL .= " AND ue.user_trip_id = :user_trip_id";
        }elseif ($arrSearch['expense_vendor_id'] != '' and $arrSearch['expense_vendor_id'] != '') {
            $arrParams[':expense_vendor_id'] = $arrSearch['expense_vendor_id'];
            $strSQL .= " AND ue.expense_vendor_id = :expense_vendor_id";
        } else {
            $arrParams[':user_id'] = $user_id;
            $strSQL .= " AND ue.user_id = :user_id";
            if ($arrSearch['fromDate'] != '' and $arrSearch['toDate'] != '') {
               // $strSQL .= " AND ue.expense_date BETWEEN :from_date AND :to_date  ";
            	$strSQL .= " AND ue.exp_date_timestamp BETWEEN :from_date AND :to_date  ";
                $arrParams[':to_date'] = $arrSearch['toDate']; ///strtotime($arrSearch['toDate']) * 1000;
                $arrParams[':from_date'] = $arrSearch['fromDate']; //strtotime($arrSearch['fromDate']) * 1000;
            }

            if ($arrSearch['expense_date'] != '') {
                $strSQL .= " AND ue.expense_date = :expense_date";
                $arrParams[':expense_date'] = $arrSearch['expense_date'];
            }
        }
        $strSQL .= " ORDER BY ue.expense_date DESC ";
       	//echo $strSQL; p($arrSearch,0); p($arrParams);
        if(isset($arrSearch['limit'])) $strSQL .= $arrSearch['limit'];
        	
        $_SESSION[$this->session_prefix]["user"]['user_expense_query'] = $strSQL;
        $_SESSION[$this->session_prefix]["user"]['user_expense_query_params'] = $arrParams;
        $arrResult = $this->database->queryData($strSQL, $arrParams);
		return $arrResult;
    }
    
    /**
     * Get last 5 expense transaction to display data on the dashboard.  
     */
    public function getLast5Expenses()
    {
    	if(isset($_SESSION[$this->session_prefix]["user"]["user_id"])) {
    		$user_id = $_SESSION[$this->session_prefix]["user"]["user_id"];
    		$arrSearch['limit'] = ' LIMIT 4 ';
    		return $this->getUserExpensesByUserId($user_id, $arrSearch);
    	}
    	return false;
    }

    /**
     * function to fetch total expenses and arrangeit in required format
     * @param type $arrSearch
     * @return type
     */
    function getUserExpenses() {

        $arrSearch = array();
        if ($_REQUEST['t']) {
            $arrSearch['user_trip_id'] = $_REQUEST['t'];
        }elseif ($_REQUEST['v']) {
            $arrSearch['expense_vendor_id'] = $_REQUEST['v'];
        } else {
            switch ($_REQUEST[range]) {
                case 'today':
                	$arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START"), "MYSQL_DATETIME");
                	$arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME");
                    //$arrSearch["expense_date"] = localToUtcMS(getNowLocalRange("START"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS"); //date('Y-m-d');
                    break;
                case 'yesterday':
                	$arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-1days"), "MYSQL_DATETIME");
                	$arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END", "-1days"), "MYSQL_DATETIME");
                    //$arrSearch["expense_date"] = localToUtcMS(getNowLocalRange("START", "-1days"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS", "-1days"); //date("Y-m-d", strtotime("-1day"));
                    break;
                case 'week':
                    $arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-7days"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS", "-7days"); //date("Y-m-d", strtotime("-7days"));
                    $arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME");//date("Y-m-d");
                    break;
                case 'month':
                    $arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-29days"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS", "-29days"); //date("Y-m-d", strtotime("-29days"));
                    $arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS");//date("Y-m-d");
                    break;
                default:
                    break;
            }

            if (isset($arrSearch) and !empty($arrSearch)) {

            } else {
                if (isset($_REQUEST['fromDate']) and strlen(trim($_REQUEST['fromDate']))) {
                    $arrSearch['fromDate'] = localToUtcMS($_REQUEST['from_date'], "MYSQL_DATETIME");
                } else {
                    $arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-1month"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS", "-1month"); //date('Y-m-d', strtotime('-1month'));
                }

                if (isset($_REQUEST['toDate']) and strlen(trim($_REQUEST['toDate']))) {	
                    $arrSearch['toDate'] = localToUtcMS($_REQUEST['toDate'], "MYSQL_DATETIME");
                } else {
                    $arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME"); //getNowUtc("MYSQL_TIMESTAMP_MS"); //date('Y-m-d');
                }
            }
        }
        $arrResult = array();

        $arrExpenses = $this->getUserExpensesByUserId($_SESSION[$this->session_prefix]["user"]["user_id"], $arrSearch);
        $arrFinalData['total'] = $this->getUserTotalExpenses($arrSearch);
        foreach ($arrExpenses as $strKey => $arrData) {
            switch ($arrData['expense_date']) {
                case getNowLocal('MYSQL_DATE'):
                    $strDate = 'Today';
                    break;
                case getNowLocal('MYSQL_DATE', "-1day"): ///date("Y-m-d", strtotime("yesterday")):
                    $strDate = 'Yesterday';
                    break;
                default:
                    $strDate = $arrData['expense_date'];
                    break;
            }

            $arrResult[$strDate]['data'][] = $arrData;
            $arrResult[$strDate]['total_amount'][] = $arrData['expense_base_currency_amount'];
            $arrResult[$strDate]['total_amount_symbol'] = $arrData['base_currency_symbol'];
        }
        //p($arrResult);
        $arrFinalData['data'] = $arrResult;

        return $arrFinalData;
    }

    /**
     * function to get total expense of a user
     * @param type $arrSearch
     * @return type
     */
    function getUserTotalExpenses($arrSearch) {
        $strSQL = "SELECT
                    SUM(ue.expense_base_currency_amount) as total_expense,csi.currency_symbol AS expense_currency_symbol
                    FROM
                     user_expenses ue
                    LEFT JOIN currencies csi ON csi.currency_id = ue.base_currency_id
                    WHERE 1";

        $arrParams[':user_id'] = $_SESSION[$this->session_prefix]["user"]["user_id"];

        if ($arrSearch['fromDate'] != '' and $arrSearch['toDate'] != '') {
            $strSQL .= " AND ue.exp_date_timestamp BETWEEN :from_date AND :to_date  ";
            $arrParams[':to_date'] = $arrSearch['toDate'];
            $arrParams[':from_date'] = $arrSearch['fromDate'];
        }

        if ($arrSearch['expense_date'] != '') {
            $strSQL .= " AND ue.exp_date_timestamp = :expense_date";
            $arrParams[':expense_date'] = $arrSearch['expense_date'];
        }


        $strSQL .= " AND ue.user_id = :user_id ORDER BY ue.expense_date DESC";
        //p($strSQL,0);         p($arrParams);
        $arrResult = $this->database->queryOne($strSQL, $arrParams);

        return $arrResult;
    }

    /**
     * function to gat dashboard data
     * @return type
     */
    function getUserDashboardExpenses() {
    	$arrExpense['today'] = $this->getUserTotalExpenses(array("fromDate" => localToUtcMS(getNowLocalRange("START"), "MYSQL_DATETIME"), "toDate" => localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME")));
        $arrExpense['yesterday'] = $this->getUserTotalExpenses(array("fromDate" => localToUtcMS(getNowLocalRange("START", "-1days"), "MYSQL_DATETIME"), "toDate" => localToUtcMS(getNowLocalRange("END", "-1days"), "MYSQL_DATETIME")));
        $arrExpense['last_week'] = $this->getUserTotalExpenses(array("fromDate" => localToUtcMS(getNowLocalRange("START", "-7days"), "MYSQL_DATETIME"), "toDate" => localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME"))); //$this->getUserTotalExpenses(array("fromDate" => date("Y-m-d", strtotime("-7days")), "toDate" => date('Y-m-d')));
        $arrExpense['last_month'] = $this->getUserTotalExpenses(array("fromDate" => localToUtcMS(getNowLocalRange("START", "-29days"), "MYSQL_DATETIME"), "toDate" => localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME")));
        return $arrExpense;
    }

    /**
     * functionexpense to delete
     * @param type $data
     * @return type
     */
    function deleteExpense($data) {
        $strResponse = $this->getDBTable("user-expenses")->delete(array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $data['user_expense_id'])));

        if ($strResponse) {
            $response = array("server_id" => (int) $data['user_expense_id'], "LUID" => $data["LUID"]);
            return array(true, $response);
        } else {
            $response = array("server_id" => (int) $data['user_expense_id'], "LUID" => $data["LUID"]);
            return array(true, $response);
        }
    }

    /**
     * add edit expense
     * @param type $data
     */
    function addEditUserExpense($data) {
        unset($data["submit"]);
        unset($data["cancel"]);
        
        if ($_SESSION[$this->session_prefix]['user']['base_currency_id'] != $this->getModel('users')->getUserBaseCurrency()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("BASE_CURRENCY_CHANGE", "common");
            return false;
        }

        $data["user_id"] = $_SESSION[$this->session_prefix]["user"]["user_id"];

        if (isset($data["base_type_id"]) and empty($data["base_type_id"])) {
            $data["base_type_id"] = $this->getBaseCategoryIdByCategory($data['expense_category_id']);
        }

        if (isset($data["expense_currency_id"]) and empty($data["expense_currency_id"])) {
            $data["expense_currency_id"] = $_SESSION[$this->session_prefix]["user"]['base_currency_id'];
        }

        if (isset($data["expense_date"]) and !empty($data["expense_date"]) and isset($data["expense_time"]) and !empty($data["expense_time"])) {
            //$strSQL = "SELECT UNIX_TIMESTAMP('".$data["expense_date"]." ".$data["expense_time"]."') as exp_date_timestamp";
            //$resTime = $this->database->queryOne($strSQL);
        	$data["expense_time"] = date("H:i:s", strtotime($data["expense_time"]));
        	$data["exp_date_timestamp"] = localToUtcMS($data["expense_date"]." ".$data["expense_time"], "MYSQL_DATETIME");
        	if(isset($data["exp_date_timestamp"])) {
                $data["expense_date"] = getTimestampMSFormat($data["exp_date_timestamp"],"MYSQL_DATE");
               	$data["expense_time"] = getTimestampMSFormat($data["exp_date_timestamp"],"MYSQL_TIME");
            }
        }
        
        $data['expense_base_currency_amount'] = $this->changeCurrencyToBaseCurrency($data['expense_amount'], $data['expense_currency_id'], $_SESSION[$this->session_prefix]["user"]['base_currency_id']);
        //$data["expense_currency_id"] = $this->getModel('miscellaneous')->getCurrencyIdByCode($data['expense_currency_id']);
        $data["base_currency_id"] = $_SESSION[$this->session_prefix]["user"]['base_currency_id'];

        if ($data["user_expense_id"]) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("user-expenses")->update($data, array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $data['user_expense_id'])));
            $_SESSION[$this->session_prefix]["action_message"] = _l("EDIT_EXPENSE_SUCCESS", "my-expenses");
        } else { // Add Record
            unset($data["user_expense_id"]);
            $data["status"] = 1;
            $data["payment_mode"] = 2;
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["user_expense_id"] = (int) $this->getDBTable("user-expenses")->insert($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_EXPENSE_SUCCESS", "my-expenses");
        }

        $path = APPLICATION_PATH . "/images/user_expenses/";

        if (is_array($_FILES)) {
            foreach ($_FILES as $arrMedia) {
                if (preg_match("/\.(png|jpg|gif|jpeg|bmp|PNG|JPG|GIF|JPEG|BMP)$/i", $arrMedia["name"])) {
                    $filetype_a = explode(".", $arrMedia["name"]);
                    $filetype = $filetype_a[count($filetype_a) - 1];
                    $imgData["user_expense_id"] = $data["user_expense_id"];
                    $imgData["expense_filetype"] = $filetype;
                    $imgData["created_date"] = date("Y-m-d H:i:s");
                    $imgData["updated_date"] = date("Y-m-d H:i:s");
                    $user_expense_reference_id = $this->getDBTable("user-expenses-reference")->insert($data);
                    $fileName = $user_expense_reference_id . "." . $filetype;
                    move_uploaded_file($arrMedia["tmp_name"], $path . $fileName);
                    $this->getDBTable("user-expenses-reference")->update(array("expense_filename" => $fileName), array("where" => "user_expense_reference_id = :user_expense_reference_id", "params" => array(":user_expense_reference_id" => $user_expense_reference_id)));
                }
            }
        }
        
          
        if (!empty($data["user_expense_id"])) {
            //$_SESSION[$this->session_prefix]["action_message"] = _l("ADD_EXPENSE_SUCCESS", "my-expenses");
        } else {
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_EXPENSE_FAIL", "my-expenses");
        }
    }

    /**
     * get base category by expense category
     * @param type $category_id
     * @return type
     */
    function getBaseCategoryIdByCategory($category_id) {
        $arrResults = $this->getDBTable("expense-categories")->fetchRowByFields(array("base_type_id"), array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $category_id)));
        return $arrResults['base_type_id'];
    }

    /**
     * change currency
     * @param type $amount
     * @param type $fromCurrency
     * @param type $toCurrency
     * @return type
     */
    function changeCurrencyToBaseCurrency($amount, $fromCurrency, $toCurrency) {
        $arrResult = $this->getDBTable("exchange-rates")->fetchAll();

        foreach ($arrResult as $arrData) {
            $arrRates[$arrData['currency']] = $arrData['rate'];
        }

        $fromCurrency = $this->getModel('miscellaneous')->getCurrencyCodeById($fromCurrency);
        $toCurrency = $this->getModel('miscellaneous')->getCurrencyCodeById($toCurrency);
        
        $converted_amount = ($amount / $arrRates[$fromCurrency]) * $arrRates[$toCurrency];
        $converted_amount = round($converted_amount, 2);
        return $converted_amount;
    }

    /**
     * Get Min and Max date field for the SQL query 
     * @return Array
     */
    function getMinMaxDateFieldArray()
    {
    	$array = array("MIN(DATE_FORMAT(FROM_UNIXTIME(ue.exp_date_timestamp/1000), '%Y-%m-%d')) as trip_date_from", "MAX(DATE_FORMAT(FROM_UNIXTIME(ue.exp_date_timestamp/1000), '%Y-%m-%d')) as trip_date_to");
    	if(isset($_SESSION[$this->session_prefix]['user']['client_locale']["timezone"])) {
    		$tz = $_SESSION[$this->session_prefix]['user']['client_locale']["timezone"];
    		$array = array("MIN(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d')) as trip_date_from",
    				"MAX(DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d')) as trip_date_to"
    		);
    	}
    	return $array;
    }
    
    /**
     * get user trip expense date
     * @return type
     */
    function getUserTripExpenseDates() {
        $arrParams[':user_trip_id'] = $_REQUEST['t'];
        $strWhere = " AND user_trip_id = :user_trip_id";
        $strSQL = "SELECT ue.*,".implode(",",$this->getMinMaxDateFieldArray())." FROM user_expenses as ue WHERE 1 ".$strWhere;
        $rsResult = $this->database->queryOne($strSQL, $arrParams);
        //$rsResult = $this->getDBTable("user-expenses")->fetchRowByFields($this->getMinMaxDateFieldArray(),array("where" => $strWhere, "params" => $arrParams));
        return $rsResult;
    }
    
    /**
     * get user trip expense date
     * @return type
     */
    function getUserVendorExpenseDates() {
        $arrParams[':expense_vendor_id'] = $_REQUEST['v'];
        $strWhere = " AND expense_vendor_id = :expense_vendor_id";
        $strSQL = "SELECT ue.*,".implode(",",$this->getMinMaxDateFieldArray())." FROM user_expenses as ue WHERE 1 ".$strWhere;
        $rsResult = $this->database->queryOne($strSQL, $arrParams);
        //$rsResult = $this->getDBTable("user-expenses")->fetchRowByFields($this->getMinMaxDateFieldArray(), array("where" => $strWhere, "params" => $arrParams));
        return $rsResult;
    }

    /**
     * delete expense
     * @return string
     */
    function deleteUserExpense() {
        if (is_array($_REQUEST['selected_expense'])) {
            $strExpenseIds = implode(",", $_REQUEST['selected_expense']);
            $this->getModel("sync-updated-time")->updateDeletedItems("user_expenses", $_REQUEST['selected_expense']);
            $strSQL = "DELETE FROM user_expenses WHERE payment_mode=2 AND user_expense_id IN (" . $strExpenseIds . ")";
            $this->database->query($strSQL);
            $_SESSION[$this->session_prefix]["action_message"] = _l("DELETE_EXPENSE_SUCCESS", "my-expenses");
            return 'SUCCESS';
        } else {
            return 'FAIL';
        }
    }

    /**
     * get user expense details
     * @param type $expenseId
     * @return type
     */
    function getUserExpenseById($expenseId) {
    	$strSQL = "SELECT
					    	".$this->getUserExpenseFieldByLocal().",
                            c.title,c.card_number,ut.trip_title,
                            bet.base_expense_type_name,
                            cs.currency_name AS base_currency_name,
                            cs.currency_symbol AS base_currency_symbol,
                            cs.currency_code AS base_currency_code,
                            csi.currency_name AS expense_currency_name,
                            csi.currency_symbol AS expense_currency_symbol,
                            csi.currency_code AS expense_currency_code,
                            ev.name as vendor,c.processor_type,ecp.title as parent_category,  IF((ec.title IS NULL), 'Uncategorized', ec.title) as category_title
                    FROM
                            user_expenses ue
                    LEFT JOIN cards c ON c.card_id = ue.card_id
                    LEFT JOIN expense_vendors ev ON ev.expense_vendor_id=ue.expense_vendor_id
                    LEFT JOIN user_trips ut ON ut.user_trip_id = ue.user_trip_id
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ue.base_type_id
                    LEFT JOIN expense_categories ec ON ec.expense_category_id = ue.expense_category_id
                    LEFT JOIN currencies cs ON cs.currency_id = ue.base_currency_id
                    LEFT JOIN currencies csi ON csi.currency_id = ue.expense_currency_id
                    LEFT JOIN expense_categories ecp ON ecp.expense_category_id = ec.parent_expense_category_id
                    WHERE 1";

        $arrParams[':user_expense_id'] = $expenseId;
        $strSQL .= " AND ue.user_expense_id = :user_expense_id";
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        return $arrResult;
    }

    /**
     * get expense images
     * @param type $expenseId
     * @return type
     */
    function getReferenceByExpenseId($expenseId) {
        $arrParams[':user_expense_id'] = $expenseId;
        $strWhere = "user_expense_id = :user_expense_id";
        $rsResult = $this->getDBTable("user-expenses-reference")->fetchAllByFields(array("expense_filename"), array("where" => $strWhere, "params" => $arrParams));
        return $rsResult;
    }

    /**
     * delete user expense bu id
     * @param type $expenseId
     * @return string
     */
    function deleteUserExpenseById($expenseId) {
        if ($expenseId != "") {
            $arrayID = array("0" => $expenseId);
            $this->getModel("sync-updated-time")->updateDeletedItems("user_expenses", $arrayID);
            $strSQL = "DELETE FROM user_expenses WHERE payment_mode=2 AND user_expense_id = '" . $expenseId . "'";
            $this->database->query($strSQL);
            return "SUCCESS";
        } else {
            return "FAIL";
        }
    }

    /**
     * export user expense bu expense id
     * @param type $expid
     */
    function exportUserExpenses($expid) {
        include(APPLICATION_PATH . "/lib/PHPExcel/PHPExcel.php");
        include(APPLICATION_PATH . "/lib/PHPExcel/PHPExcel/IOFactory.php");
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("User Expenses")
                ->setLastModifiedBy("User Expenses")
                ->setTitle("User Expenses Summary")
                ->setSubject("User Expenses Summary")
                ->setDescription("User Expenses Summary Report")
                ->setKeywords("User Expenses Summary")
                ->setCategory("User Expenses Summary");

        $sheet = $objPHPExcel->getActiveSheet();

        $this->createWorksheet($objPHPExcel, 0, 'User Expenses Summary', $expid);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="my-expenses-'.date("YmdHis").'.xls"');
        $objWriter->save('php://output');
    }

    /**
     * to cteare annd xls file
     * @param type $objPHPExcel
     * @param type $sheet
     * @param type $worksheetTitle
     * @param type $expid
     */
    function createWorksheet($objPHPExcel, $sheet, $worksheetTitle, $expid) {
        for ($j = 65; $j < 67; $j++) {
            for ($i = 65; $i < 91; $i++) {
                $arr[] = chr($j) . chr($i);
            }
        }
        $alphas = array_merge(range('A', 'Z'), $arr);
        $objWorkSheet = $objPHPExcel->createSheet($sheet);
        //$headers = array("Expense Date", "Base Category Type", "Base Currency Name", "Base Currency Code", "Expense Base Currency Amount", "Expense Currency Name", "Expense Currency Code", "Expense Amount", "Category Title");
        $headers = array("Summary",	"Vendor",	"Expense Currency",	"Amount",	"Base Currency", "Amount (in Base Currency)",	"Date",	"Type",	"Parent Category",	"Category",	"Trip",	"Description");

        for ($i = 0, $j = 1; $i < count($headers); $i++) {
            $objWorkSheet->setCellValue($alphas[$i] . $j, $headers[$i]);
            $objWorkSheet->getColumnDimension($alphas[$i])->setWidth(15);
            $objWorkSheet->getStyle($alphas[$i] . $j)->getFont()->setBold(true);
        }

        if ($_SESSION[$this->session_prefix]["user"]['user_expense_query'] != "") {
            if ($expid != "") {
            	$sql = "SELECT
	            			".$this->getUserExpenseFieldByLocal().",
            	            c.title,c.card_number,ut.trip_title,
                            if(bet.base_expense_type_name is not null,base_expense_type_name,'Uncategorized') as base_expense_type_name,
                            cs.currency_name AS base_currency_name,
                            cs.currency_symbol AS base_currency_symbol,
                            cs.currency_code AS base_currency_code,
                            csi.currency_name AS expense_currency_name,
                            csi.currency_symbol AS expense_currency_symbol,
                            csi.currency_code AS expense_currency_code,
                            ev.name,c.processor_type,ecp.title as parent_category, IF((bet.base_expense_type_name IS NOT NULL AND ec.title IS NULL), 'Uncategorized', ec.title) as category_title
                    FROM
                            user_expenses ue
                    LEFT JOIN cards c ON c.card_id = ue.card_id
                    LEFT JOIN expense_vendors ev ON ev.expense_vendor_id=ue.expense_vendor_id
                    LEFT JOIN user_trips ut ON ut.user_trip_id = ue.user_trip_id
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ue.base_type_id
                    LEFT JOIN expense_categories ec ON ec.expense_category_id = ue.expense_category_id
                    LEFT JOIN currencies cs ON cs.currency_id = ue.base_currency_id
                    LEFT JOIN currencies csi ON csi.currency_id = ue.expense_currency_id
                    LEFT JOIN expense_categories ecp ON ecp.expense_category_id = ec.parent_expense_category_id
                    WHERE 1 AND ue.user_expense_id IN (" . $expid . ") ORDER BY ue.exp_date_timestamp DESC";
                $arrResult = $this->database->queryData($sql, $_SESSION[$this->session_prefix]["user"]['user_expense_query_params']);
            } else {
                $arrResult = $this->database->queryData($_SESSION[$this->session_prefix]["user"]['user_expense_query'], $_SESSION[$this->session_prefix]["user"]['user_expense_query_params']);
            }
        }

        $i = 1;
        if (isset($arrResult) and !empty($arrResult)) {
            foreach ($arrResult as $key => $cdata) {
				/*
                $summary[$i] = array("Expense Date" => date(DATE_TIME_FORMAT, strtotime($cdata['expense_date'].' '.$cdata['expense_time'])), "Base Category Type" => $cdata['base_expense_type_name'], "Base Currency Name" => $cdata['base_currency_name'], "Base Currency Code" => $cdata['base_currency_code'],
                    "Expense Base Currency Amount" => $cdata['expense_base_currency_amount'], "Expense Currency Name" => $cdata['expense_currency_name'], "Expense Currency Code" => $cdata['expense_currency_code'], "Expense Amount" => $cdata['expense_amount'], "Category Title" => $cdata['category_title']
                );
                */
            	$summary[$i] = array("Summary"=>$cdata["expense_summary"],	"Vendor"=>$cdata["name"],	"Expense Currency"=>$cdata["expense_currency_code"],	"Amount"=>$cdata["expense_amount"],	"Base Currency"=>$cdata["base_currency_code"], "Amount (in Base Currency)"=>$cdata["expense_base_currency_amount"],	"Date"=>date(DATE_TIME_FORMAT, strtotime($cdata['expense_date'].' '.$cdata['expense_time'])),	"Type"=>$cdata["base_expense_type_name"],	"Parent Category"=>$cdata["parent_category"],	"Category"=>$cdata["category_title"],	"Trip"=>$cdata["trip_title"],	"Description"=>$cdata["expense_description"]);
                $i++;
            }

            for ($i = 0, $j = 1, $k = 2; $i < count($arrResult); $i++, $j++) {
                $l = 0;
                foreach ($summary[$j] as $value) {
                    $objWorkSheet->setCellValue($alphas[$l] . $k, $value);
                    $l++;
                }
                $k++;
            }
        }

        $objWorkSheet->setTitle($worksheetTitle);
    }

    /**
     * get alll user expenses
     * @param type $user_id
     * @param type $timestamp
     * @return type
     */
    function getAllUserExpenses($user_id, $timestamp) {
        $arrParams = array();
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $user_id;

        if (isset($timestamp) and !empty($timestamp)) {
            $strWhere .= " AND updated_date >= :updated_date";
            $arrParams[':updated_date'] = date("Y-m-d H:i:s", $timestamp);
        }

        $rsResult = $this->getDBTable("user-expenses")->fetchAll(array("where" => $strWhere, "params" => $arrParams));
        $arrIntType = array('status', 'payment_mode', 'expense_vendor_id', 'base_currency_id', 'user_expense_id', 'user_id', 'card_id', 'user_trip_id', 'base_type_id', 'expense_category_id', 'expense_currency_id', 'LUID');
        $arrFloatType = array('expense_amount', 'expense_base_currency_amount');
        $this->typeCastFields(array("int" => $arrIntType), $rsResult, 2);
        $this->typeCastFields(array("float" => $arrFloatType), $rsResult, 2);

        foreach ($rsResult as $intKey => $arrDetail) {

            $rsResult[$intKey]['server_id'] = $arrDetail['user_expense_id'];
            $rsResult[$intKey]['expense_id'] = $arrDetail['user_expense_id'];
            $rsResult[$intKey]['vendor_id'] = ($arrDetail['expense_vendor_id']) ? $arrDetail['expense_vendor_id'] : 0;
            $rsResult[$intKey]['trip_id'] = ($arrDetail['user_trip_id']) ? $arrDetail['user_trip_id'] : 0;
            $rsResult[$intKey]['parent_expense_category_title'] = $this->getModel('expense-categories')->getCategoryNameById($arrDetail['user_trip_id']);


            if (isset($arrDetail['expense_date']) and !empty($arrDetail['expense_date'])) {
                $rsResult[$intKey]['expense_date'] = (int) strtotime($arrDetail['expense_date']) * 1000;
                $rsResult[$intKey]['expense_date'] = $arrDetail['exp_date_timestamp'];
            }

            if (isset($arrDetail["expense_time"]) and !empty($arrDetail["expense_time"])) {
                //$rsResult[$intKey]["expense_time"] = (int) strtotime($arrDetail["expense_time"]) * 1000;
            }
            
            

            foreach ($arrDetail as $strKey => $strdata) {
                if (empty($strdata)) {
                    $rsResult[$intKey][$strKey] = '';
                }

                if (empty($strdata) and in_array($strKey, $arrIntType)) {
                    $rsResult[$intKey][$strKey] = 0;
                }

                if (empty($strdata) and in_array($strKey, $arrFloatType)) {
                    $rsResult[$intKey][$strKey] = 0;
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
     * @param $user_id
     * @return $deleted_vendors
     */
    function getDeletedUserExpenses($user_id, $timestamp = 0) {

        $strWhere = " reference_key_id = :reference_key_id AND deleted_on > :deleted_on AND `table` = :table";
        $arrParams[':reference_key_id'] = $user_id;
        $arrParams[':deleted_on'] = date("Y-m-d H:i:s", $timestamp);
        $arrParams[':table'] = 'user_expenses';
        $arrResults = $this->getDBTable("deleted-items")->fetchAllByFields(array("deleted_item_id", "reference_id"), array("where" => $strWhere, "params" => $arrParams));
        foreach ($arrResults as $arrResult) {
            $arrData[] = $arrResult['reference_id'];
        }

        return array($arrData);
    }

    /**
     * remove trip from expense
     * @return type
     */
    function removeExpenseTrip() {
        $data["updated_date"] = date("Y-m-d H:i:s");
        $data["user_trip_id"] = "";
        return $this->getDBTable("user-expenses")->update($data, array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $_REQUEST['id'])));
    }

    /**
     * get cat name by id
     * @param type $intExpenseCategoryId
     * @return type
     */
    function getCategoryNameByExpenseId($intExpenseCategoryId) {

        $arrData = array();
        $arrResults = $this->getCategoryDetailsById($intExpenseCategoryId);
        $arrData['cat_name'] = $arrResults['title'];

        if($arrData['cat_name']=='') $arrData['cat_name'] = 'Uncategorized';
        if (!empty($arrResults['parent_expense_category_id'])) {
            $arrParentResults = $this->getCategoryDetailsById($arrResults['parent_expense_category_id']);
            $arrData['parent_cat_name'] = $arrParentResults['title'];
        }

        return $arrData;
    }

    /**
     * get all Category
     * @param type $intExpenseCategoryId
     * @return type
     */
    function getCategoryDetailsById($intExpenseCategoryId) {
        $arrParams = array();
        $strWhere = "expense_category_id = :expense_category_id";
        $arrParams[':expense_category_id'] = $intExpenseCategoryId;
        $rsResult = $this->getDBTable("expense-categories")->fetchRowByFields(array('title', 'parent_expense_category_id'), array("where" => $strWhere, "params" => $arrParams));
        return $rsResult;
    }

    /**
     * funtion to get all user expense images
     * @param type $user_id
     * @param type $timestamp
     * @return type
     */
    function getAllUserExpensesImages($user_id, $timestamp) {
        $arrParams = array();
        $arrImgParams = $arrImages = $arrExpenseId = array();
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $user_id;

        $rsResult = $this->getDBTable("user-expenses")->fetchAllByFields(array('user_expense_id'), array("where" => $strWhere, "params" => $arrParams));

        foreach ($rsResult as $arrData) {
            $arrExpenseId[] = $arrData['user_expense_id'];
        }

        if (is_array($arrExpenseId) and !empty($arrExpenseId)) {
            $arrItems = $this->database->inClauseEntityList("item", count($arrExpenseId));
            $arrItemsParams = $this->database->inClauseEntityParams("item", $arrExpenseId);

            $strImgWhere = "user_expense_id in (" . $arrItems . ")";

            if (isset($timestamp) and !empty($timestamp)) {
                $strImgWhere .= " AND updated_date >= :updated_date";
                $arrImgParams[':updated_date'] = date("Y-m-d H:i:s", $timestamp);
            }
            $arrImgParams = array_merge($arrImgParams, $arrItemsParams);
            $arrResult = $this->getDBTable("user-expenses-reference")->fetchAll(array("where" => $strImgWhere, "params" => $arrImgParams));
            $this->typeCastFields(array("int" => array('user_expense_reference_id', 'user_expense_id', 'LUID')), $arrResult, 2);
            $path = APPLICATION_URL . "/images/user_expenses";
            foreach ($arrResult as $intKey => $arrImgData) {
                $arrImages[$intKey]['owner_key'] = $arrImgData['user_expense_id'];
                $arrImages[$intKey]['img']['server_id'] = $arrImgData['user_expense_reference_id'];
                $arrImages[$intKey]['img']['server_path'] = $path . "/" . $arrImgData['expense_filename'];
                $arrImages[$intKey]['img']['LUID'] = (int) (isset($arrImgData['LUID']) and !empty($arrImgData['LUID'])) ? $arrImgData['LUID'] : 0;
            }
            return $arrImages;
        } else {
            return array();
        }
    }

    /**
     * funtion to delete expense images
     * @return type
     */
    function deleteUserExpenseImages() {
        $arrParams[':user_expense_id'] = $_REQUEST['expense_id'];
        $arrParams[':expense_filename'] = $_REQUEST['filename'];
        $strWhere = "user_expense_id = :user_expense_id and expense_filename = :expense_filename";
        $rsResult = $this->getDBTable("user-expenses-reference")->fetchRowByFields(array("user_expense_reference_id"), array("where" => $strWhere, "params" => $arrParams));
        $this->getModel("sync-updated-time")->updateDeletedImages("user_expense_reference", array($rsResult['user_expense_reference_id']), $_REQUEST['filename']);
        @unlink(APPLICATION_PATH . "/images/user_expenses/" . $_REQUEST['filename']);
        return $this->getDBTable("user-expenses-reference")->delete(array("where" => "user_expense_reference_id = :user_expense_reference_id", "params" => array(":user_expense_reference_id" => $rsResult['user_expense_reference_id'])));
    }

    /**
     * This function is used to get user expenses by expense id from web service and generate pdf.
     */
    function getUserExpenseByExpenseIds($expenseIds) {
        $strSQL = "SELECT
                            ".$this->getUserExpenseFieldByLocal().",
                            c.title,c.card_number,ut.trip_title,
                            if(bet.base_expense_type_name is not null,base_expense_type_name,'Uncategorized') as base_expense_type_name,
                            cs.currency_name AS base_currency_name,
                            cs.currency_symbol AS base_currency_symbol,
                            cs.currency_code AS base_currency_code,
                            csi.currency_name AS expense_currency_name,
                            csi.currency_symbol AS expense_currency_symbol,
                            csi.currency_code AS expense_currency_code,
                            ev.name as vendor,c.processor_type,ecp.title as parent_category, IF((bet.base_expense_type_name IS NOT NULL AND ec.title IS NULL), 'Uncategorized', ec.title) as category_title
                    FROM
                            user_expenses ue
                    LEFT JOIN cards c ON c.card_id = ue.card_id
                    LEFT JOIN expense_vendors ev ON ev.expense_vendor_id=ue.expense_vendor_id
                    LEFT JOIN user_trips ut ON ut.user_trip_id = ue.user_trip_id
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ue.base_type_id
                    LEFT JOIN expense_categories ec ON ec.expense_category_id = ue.expense_category_id
                    LEFT JOIN currencies cs ON cs.currency_id = ue.base_currency_id
                    LEFT JOIN currencies csi ON csi.currency_id = ue.expense_currency_id
                    LEFT JOIN expense_categories ecp ON ecp.expense_category_id = ec.parent_expense_category_id";

        $arrParams[':user_expense_id'] = $expenseIds;
        $strSQL .= " WHERE ue.user_expense_id IN (" . $expenseIds . ")";
        $arrResult = $this->database->queryData($strSQL);

        return $arrResult;
    }

    /**
     * This function is used to send mail to user along with expense pdf as attachment.
     */
    function sendExpensePDFMail($token, $pdf_file_path) {
        $sql = "SELECT u.email, CONCAT_WS(' ',u.first_name,u.last_name) as uname FROM device_tokens AS dt
                INNER JOIN users AS u ON (u.user_id = dt.user_id)
                WHERE dt.token = :token";
        $userData = $this->database->queryOne($sql, array(":token" => $token));

        if (isset($userData) and !empty($userData)) {
            if (\generalFunctions::isValidEmail($userData['email'])) {
                $subject = "DailyUse - User Expenses";
                $body = "Hi " . $userData['uname'] . ",";
                $body .= "<br>Please find pdf file attached along with the mail.";
                $body .= "<br>Regards,";
                $body .= "<br>DailyUse Team";
                $attachment = array("0" => $pdf_file_path);

                $this->sendBulkEmail($userData['email'], $subject, $body, "support@dailyuse.com", $attachment);
            }
        }
    }

    /**
     * This function is used to get the deleted vendors based on timestamp
     * @param $user_id
     * @return $deleted_vendors
     */
    function getDeletedUserExpensesImages($user_id, $timestamp = 0) {

        $strWhere = " reference_key_id = :reference_key_id AND deleted_on > :deleted_on AND `table` = :table";
        $arrParams[':reference_key_id'] = $user_id;
        $arrParams[':deleted_on'] = date("Y-m-d H:i:s", $timestamp);
        $arrParams[':table'] = 'user_expense_reference';
        $arrResults = $this->getDBTable("deleted-items")->fetchAllByFields(array("deleted_item_id", "reference_id", "extra_reference_keys"), array("where" => $strWhere, "params" => $arrParams));
        foreach ($arrResults as $arrResult) {
            $arrData[] = APPLICATION_URL . "/images/user_expenses/" . $arrResult['extra_reference_keys'];
        }

        return $arrData;
    }

    /**
     *
     * @param type $arrData
     * @return string
     */
    function getDisableFileList($arrData) {
        $arrList = array();
        switch (count($arrData)) {
            case 3:
            case 2:
                $arrList[0] = "disabled";
                $arrList[1] = "disabled";
                break;
            case 1:
                $arrList[0] = "disabled";
                $arrList[1] = "";
                break;
            default:
                $arrList[0] = "";
                $arrList[1] = "";
                break;
        }
        return $arrList;
    }

    /**
     *
     * @param type $intExpenseId
     * @return type
     */
    function getAllCategoryByExpenseId($intExpenseId) {
        $strSQL = "SELECT
                    bet.base_expense_type_name,
                    ecp.title AS parent_category,ec.title AS category_title
                    FROM
                     user_expenses ue
                    LEFT JOIN base_expense_types bet ON bet.base_expense_type_id=ue.base_type_id
                    LEFT JOIN expense_categories ec ON ec.expense_category_id = ue.expense_category_id
                    LEFT JOIN expense_categories ecp ON ecp.expense_category_id = ec.parent_expense_category_id WHERE 1";


        $arrParams[':user_expense_id'] = $intExpenseId;
        $strSQL .= " AND ue.user_expense_id = :user_expense_id";
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        $arrDetails = array();
        foreach ($arrResult as $strData) {
            if (isset($strData) and !empty($strData)) {
                $arrDetails[] = ucfirst($strData);
            }
        }
		if(count($arrDetails)==1) $arrDetails[] = "Uncategorized";  
        if (is_array($arrDetails) and !empty($arrDetails)) {
            $data = implode(" > ", $arrDetails);
        }
		if(empty($data)) $data = "Uncategorized";
        return $data;
    }

    /**
     * This function is used to get generate expense pdf file.
     */
    function generateExpensePdf($viewSection, $module = '', $params = array()) {
        if ($module == 'default') {
            if (file_exists(HTML_FILE_PATH)) {
                @unlink(HTML_FILE_PATH);
            }
            if (file_exists(PDF_FILE_PATH)) {
                @unlink(PDF_FILE_PATH);
            }
            $fp = fopen(HTML_FILE_PATH, "a+");
            fwrite($fp, $viewSection);

            @chmod(HTML_FILE_PATH, 0777);
            @chmod(PDF_FILE_PATH, 0777);

            exec(PDF_CREATOR_PATH . ' "' . HTML_FILE_PATH . '" "' . PDF_FILE_PATH . '"');
        } else if ($module == 'services') {
        	$fileName = "my-expenses-".getNow("UNIQUE_DATETIME",SERVICE_LOCAL_TIMEZONE);
            $html_file_path = APPLICATION_PATH . "/images/pdf/".$fileName.".html";
            $pdf_file_path = APPLICATION_PATH . "/images/pdf/".$fileName.".pdf";

            if (file_exists($html_file_path)) {
                @unlink($html_file_path);
            }
            if (file_exists($pdf_file_path)) {
                @unlink($pdf_file_path);
            }
            $fp = fopen($html_file_path, "a+");
            fwrite($fp, $viewSection);

            @chmod($html_file_path, 0777);
            @chmod($pdf_file_path, 0777);

            exec(PDF_CREATOR_PATH . ' "' . $html_file_path . '" "' . $pdf_file_path . '"');

            if (file_exists($pdf_file_path)) {
                // Send mail to user with attachment.
                $this->sendExpensePDFMail($params['token'], $pdf_file_path);
                $response['expense_pdf_path'] = APPLICATION_URL . "/images/pdf/".$fileName.".pdf";
                $response['message'] = "success";
                //$this->generateResponse($response);
            } else {
                $response = array("code" => "163", "message" => _l("PDF not generated.", "services"));
                //$this->generateResponse($response, "error");
            }
            return $response;
        }
    }

    /**
     * function to check user access
     * @param type $intExpenseId
     * @return boolean
     */
    function checkExpenseAccess($intExpenseId) {
        $strSQL = "SELECT COUNT(0) AS total FROM user_expenses WHERE user_id = :user_id AND user_expense_id=:user_expense_id";
        $intTotal = $this->database->getTotalFromQuery($strSQL, array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":user_expense_id" => $intExpenseId));
        if ($intTotal > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get User Trip table sql field as per the user's local time
     * @return string
     */
    function getUserExpenseFieldByLocal($returnType = "string") {
    	$fields = "ue.`user_expense_id`,  ue.`user_id`,  ue.`card_id`,  ue.`user_trip_id`,  ue.`base_type_id`,  ue.`expense_category_id`,  ue.`expense_currency_id`,  ue.`expense_amount`,  ue.`base_currency_id`,  ue.`expense_base_currency_amount`,  ue.`expense_summary`,  ue.`expense_vendor_id`,  ue.`exp_date_timestamp`,  ue.`expense_description`,  ue.`expense_tags`,  ue.`status`,  ue.`payment_mode`,  ue.`created_date`,  ue.`updated_date`,  ue.`LUID`,";
    	if(isset($_SESSION[$this->session_prefix]['user']['client_locale']["timezone"])) {
    		$tz = $_SESSION[$this->session_prefix]['user']['client_locale']["timezone"];
    		$fields .= "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d') as expense_date,
    		DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%H:%i:%s') as expense_time ";
    	}else if(defined("SERVICE_LOCAL_TIMEZONE")) {
    		$tz = SERVICE_LOCAL_TIMEZONE;
    		$fields .= "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d') as expense_date,
    		DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%H:%i:%s') as expense_time ";
    	}else {
    		$fields .= "DATE_FORMAT(FROM_UNIXTIME(ue.exp_date_timestamp/1000),'%Y-%m-%d') as expense_date,
    				DATE_FORMAT(FROM_UNIXTIME(ut.exp_date_timestamp/1000),'%H:%i:%s') as expense_time ";
    	}
    	return $fields;
    }
}
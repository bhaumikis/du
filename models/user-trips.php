<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief User Types Model contains application logic for various functions and database operations of user types Module.
 */
class userTripsModel extends globalModel { 

    /**
     * validate trip form webservice
     * @param type $params
     * @param type $isRegister
     * @return type
     */
    function validateTripForm($params = array(), $isRegister = true) {

        $errors = array();
        if (!isset($params["trip_title"]) or !\generalFunctions::valueSet($params["trip_title"])) {
            $errors[] = array("code" => "119", "message" => _l("Please enter trip_title.", "services"));
        }
        if (isset($params["trip_date_from"]) and !empty($params["trip_date_from"])) {
        	if (!(list($valid, $response) = validateTimeStampMS($params["trip_date_from"]) and $valid)) {
        		$errors[] = array("code" => "120", "message" => _l("Please enter valid timestamp of Trip Date From.", "services"));
        	}
        }
    	if (isset($params["trip_date_to"]) and !empty($params["trip_date_to"])) {
        	if (!(list($valid, $response) = validateTimeStampMS($params["trip_date_to"]) and $valid)) {
        		$errors[] = array("code" => "121", "message" => _l("Please enter valid timestamp of Trip Date To.", "services"));
        	}
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
    function addEditTrip($data) {

        unset($data["submit"], $data["cancel"]);
        if (isset($data['trip_date_from']) and strlen(trim($data['trip_date_from']))) {
        	$data['trip_date_from_timestamp'] = $data['trip_date_from'];
            $data['trip_date_from'] = getTimestampMSFormat($data['trip_date_from'], "MYSQL_DATETIME");
        }

        if (isset($data['trip_date_to']) and strlen(trim($data['trip_date_to']))) {
        	$data['trip_date_to_timestamp'] = $data['trip_date_to'];
        	$data['trip_date_to'] = getTimestampMSFormat($data['trip_date_to'], "MYSQL_DATETIME");
        }

        if (isset($data["trip_destination"]) and !empty($data["trip_destination"])) {
            $data["trip_destination"] = $this->getModel("miscellaneous")->getCountryIdByName($data["trip_destination"]);
        }

        if (isset($data["base_type_id"]) and !empty($data["base_type_id"])) {
            $data["base_expense_type_id"] = $data["base_type_id"];
        }

        if (isset($data["base_currency_id"]) and !empty($data["base_currency_id"])) {
            $data["trip_currency"] = $data["base_currency_id"];
        }

        if ($data["user_trip_id"]) { // Update Record
            $data["updated_date"] = getNow();
            $this->getDBTable("user-trips")->update($data, array("where" => "user_trip_id = :user_trip_id", "params" => array(":user_trip_id" => $data['user_trip_id'])));
        } else { // Add Record
            unset($data["user_trip_id"], $data["user_application_id"]);

            $data["created_date"] = getNow();
            $data["updated_date"] = getNow();

            $data["user_trip_id"] = $this->getDBTable("user-trips")->insert($data);
        }

        if (!empty($data["user_trip_id"])) {
            $response = array("server_id" => (int) $data["user_trip_id"], "LUID" => $data["LUID"]);
            return array(true, $response);
        } else {
            $response = array("code" => "1000", "message" => _l("Issue with Database operation.", "services"));
            return array(false, $response);
        }
    }

    /**
     * delete trip id
     * @param $data
     * @return type
     */
    function deleteTrip($data) {
        $strResponse = $this->getDBTable("user-trips")->delete(array("where" => "user_trip_id = :user_trip_id", "params" => array(":user_trip_id" => $data['user_trip_id'])));
        $this->getDBTable("user-expenses")->update(array("user_trip_id" => 0), array("where" => "user_trip_id = :user_trip_id", "params" => array(":user_trip_id" => $data['user_trip_id'])));
        if ($strResponse) {
            return array(true, array("server_id" => (int) $data['user_trip_id'], "LUID" => (int) $data['LUID']));
        } else {
            return array(true, array("server_id" => (int) $data['user_trip_id'], "LUID" => (int) $data['LUID']));
        }
    }

    /**
     * validate trip data
     * @param type $params
     * @return type
     */
    function validateTripId($params = array()) {

        $errors = array();
        if (!isset($params["user_trip_id"]) or !\generalFunctions::valueSet($params["user_trip_id"])) {
            $errors[] = array("code" => "101", "message" => _l("Please enter user trip id.", "services"));
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
     * function to get trip by user id
     * @return type
     */
    function getTripListByUserId() {
        $rsResult = $this->getDBTable("user-trips")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));

        foreach ($rsResult as $arrData) {
            $arrFinal[$arrData['user_trip_id']] = $arrData['trip_title'];
        }

        return $arrFinal;
    }

    /**
     * save trip raw image in images folder
     * @param type $data
     * @return type
     */
    function saveTripImage($arrImageData, $inputData) {
        $data["user_trip_id"] = $inputData['user_trip_id'];
        $data["trip_filetype"] = $arrImageData['extension'];
        $data["LUID"] = $arrImageData['LUID'];
        $user_trip_reference_id = $this->getDBTable("user-trip-reference")->insert($data);
        $fileName = $user_trip_reference_id . "." . $arrImageData['extension'];
        $imagePath = APPLICATION_PATH . "/images/user_trips/" . $fileName;
        $this->getModel("miscellaneous")->saveImages($imagePath, $arrImageData['binary_content']);
        $this->getDBTable("user-trip-reference")->update(array("trip_filename" => $fileName), array("where" => "user_trip_reference_id = :user_trip_reference_id", "params" => array(":user_trip_reference_id" => $user_trip_reference_id)));

        $arrResponse['image_path'] = APPLICATION_URL . "/images/user_trips/" . $fileName;
        $arrResponse['LUID'] = (int) $arrImageData['LUID'];
        $arrResponse['user_trip_image_id'] = (int) $user_trip_reference_id;
        return array(true, $arrResponse);
    }

    /**
     * delete trip images from db as well physically
     * @param type $inputData
     * @return type
     */
    function deleteTripImages($inputData) {
        $arrImgInfo = pathinfo($inputData['image_path']);
        if ($arrImgInfo['filename']) {
            $this->getDBTable("user-trip-reference")->delete(array("where" => "user_trip_reference_id = :user_trip_reference_id", "params" => array(":user_trip_reference_id" => $arrImgInfo['filename'])));
            @unlink(APPLICATION_PATH . "/images/user_trips/" . $arrImgInfo['filename'] . "." . $arrImgInfo['extension']);
            return array(true, array("LUID" => (int) $inputData['LUID']));
        }
    }

    /**
     * DU - This function is used to create the new registration of the user from the web services using the given parameters
     * @return $response
     */
    function addEditUserTrip() {

        $data = $_POST;
        unset($data["submit"], $data["cancel"]);

        if ($_SESSION[$this->session_prefix]['user']['base_currency_id'] != $this->getModel('users')->getUserBaseCurrency()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("BASE_CURRENCY_CHANGE", "common");
            return false;
        }

        if (!isset($data['trip_currency']) and empty($data['trip_currency'])) {
            $data['trip_currency'] = $_SESSION[$this->session_prefix]['user']['base_currency_id'];
        }
        
		/*
        if (isset($data['trip_time_to']) and !empty($data['trip_time_to'])) {
            $data['trip_time_to'] = date("H:i:s", strtotime($data['trip_time_to']));
            $data['trip_date_from'] = $data['trip_date_from']." ".$data['trip_time_to'];
            unset($data['trip_time_to']);
            
        }

        if (isset($data['trip_time_from']) and !empty($data['trip_time_from'])) {
            $data['trip_time_from'] = date("H:i:s", strtotime($data['trip_time_from']));
            $data['trip_date_to'] = $data['trip_date_to']." ".$data['trip_time_from'];
            unset($data['trip_time_from']);
        }
        
        
        if (isset($data["trip_date_from"]) and !empty($data["trip_date_from"]) and isset($data["trip_date_to"]) and !empty($data["trip_date_to"])) {
        	$strSQL = "SELECT UNIX_TIMESTAMP('".$data["trip_date_from"]." ".$data["trip_time_from"]."') as from_timestamp, 
        			UNIX_TIMESTAMP('".$data["trip_date_to"]." ".$data["trip_time_to"]."') as to_timestamp";
        	$resTime = $this->database->queryOne($strSQL);
        	if(isset($resTime["from_timestamp"])) {
        		$data["trip_date_from_timestamp"] = $resTime["from_timestamp"];
        	}
        	if(isset($resTime["to_timestamp"])) {
        		$data["trip_date_to_timestamp"] = $resTime["to_timestamp"];
        	}
        }
        */
        if (isset($data["trip_date_from"]) and !empty($data["trip_date_from"]) and isset($data["trip_time_from"]) and !empty($data["trip_time_from"])) {
        	$data["trip_time_from"] = date("H:i:s", strtotime($data["trip_time_from"]));
        	$data["trip_date_from_timestamp"] = localToUtcMS($data["trip_date_from"]." ".$data["trip_time_from"], "MYSQL_DATETIME");
        	if(isset($data["trip_date_from_timestamp"])) {
        		$data["trip_date_from"] = getTimestampMSFormat($data["trip_date_from_timestamp"],"MYSQL_DATETIME");
        	}
        }
        if (isset($data["trip_date_to"]) and !empty($data["trip_date_to"]) and isset($data["trip_time_to"]) and !empty($data["trip_time_to"])) {
        	$data["trip_time_to"] = date("H:i:s", strtotime($data["trip_time_to"]));
        	$data["trip_date_to_timestamp"] = localToUtcMS($data["trip_date_to"]." ".$data["trip_time_to"], "MYSQL_DATETIME");
        	if(isset($data["trip_date_to_timestamp"])) {
        		$data["trip_date_to"] = getTimestampMSFormat($data["trip_date_to_timestamp"],"MYSQL_DATETIME");
        	}
        }
        
        //p($data);
        if ($data["user_trip_id"]) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("user-trips")->update($data, array("where" => "user_trip_id = :user_trip_id", "params" => array(":user_trip_id" => $data['user_trip_id'])));
            $_SESSION[$this->session_prefix]["action_message"] = _l("EDIT_TRIP_SUCCESS", "my-travel-plan");
        } else { // Add Record
            unset($data["user_trip_id"], $data["user_application_id"]);

            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["user_id"] = $_SESSION[$this->session_prefix]['user']['user_id'];

            $data["user_trip_id"] = $this->getDBTable("user-trips")->insert($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_TRIP_SUCCESS", "my-travel-plan");
        }

        $path = APPLICATION_PATH . "/images/user_trips/";

        if (is_array($_FILES)) {
            foreach ($_FILES as $arrMedia) {
                if (preg_match("/\.(png|jpg|gif|jpeg|bmp|PNG|JPG|GIF|JPEG|BMP)$/i", $arrMedia["name"])) {
                    $filetype_a = explode(".", $arrMedia["name"]);
                    $filetype = $filetype_a[count($filetype_a) - 1];
                    $imgData["user_trip_id"] = $data["user_trip_id"];
                    $imgData["trip_filetype"] = $filetype;
                    $imgData["created_date"] = date("Y-m-d H:i:s");
                    $imgData["updated_date"] = date("Y-m-d H:i:s");
                    $user_trip_reference_id = $this->getDBTable("user-trip-reference")->insert($data);
                    $fileName = $user_trip_reference_id . "." . $filetype;
                    move_uploaded_file($arrMedia["tmp_name"], $path . $fileName);
                    $this->getDBTable("user-trip-reference")->update(array("trip_filename" => $fileName), array("where" => "user_trip_reference_id = :user_trip_reference_id", "params" => array(":user_trip_reference_id" => $user_trip_reference_id)));
                }
            }
        }
        if (!empty($data["user_trip_id"])) {
            
        } else {
            $_SESSION[$this->session_prefix]["action_message"] = _l("ADD_TRIP_FAIL", "my-travel-plan");
        }
    }

    /**
     * get logged in user dta
     * @return array
     */
    function getUserTripDataById() {
        $rsResult = $this->getDBTable("user-trips")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
        $arrData = array();
        foreach ($rsResult as $arrTrip) {
            $arrData[$arrTrip['base_expense_type_id']][$arrTrip['user_trip_id']] = $arrTrip['trip_title'];
        }
        return $arrData;
    }

    /**
     * update expense trip by trip id
     * @return array
     */
    function updateExpenseForTripId() {
        $return['success'] = 0;
        $arrExpenseId = explode(",", $_POST['user_expense_id']);
        if(count($arrExpenseId)>0) {
           $strSQL = "SELECT count(ue.user_expense_id) AS total FROM user_expenses as ue WHERE 1
                   AND ue.user_id = :user_id AND ue.user_trip_id = :user_trip_id                    
                   AND ue.user_expense_id IN (%s)";
           $strSQL = sprintf($strSQL, $_POST['user_expense_id']);
           $intTotal = $this->database->getTotalFromQuery($strSQL,  array(  
                        ":user_id"          => $_SESSION[$this->session_prefix]['user']['user_id'], 
                        ":user_trip_id"     => $_POST['user_trip_id'],
           ));
           
           if($intTotal == count($arrExpenseId)) {
               // Error Trip id already assigned.
               $return['success'] = 0;
               $return['msg'] = "Selected Expenses are already assigned to This trip!";
               return $return;
           }
        }
        
        foreach ($arrExpenseId as $IntExpenseId) {  
            $this->getDBTable("user-expenses")->update(array("user_trip_id" => $_POST['user_trip_id'], "updated_date" => date('Y-m-d H:i:s')), array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $IntExpenseId)));
        }
        $return['success'] = 1;
        return $return;
    }

    /**
     * get all trip data
     * @return array
     */
    function getAllTripData() {
        $rsResult = $this->getDBTable("user-trips")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
        $arrFinal = array();
        foreach ($rsResult as $arrData) {
            if (($arrData['trip_date_from'] < date('Y-m-d')) and ($arrData['trip_date_to'] > date('Y-m-d'))) {
                $arrFinal["Ongoing"][] = $arrData;
            } elseif ($arrData['trip_date_from'] > date('Y-m-d')) {
                $arrFinal["Upcoming"][] = $arrData;
            } elseif ($arrData['trip_date_to'] < date('Y-m-d')) {
                $arrFinal["Previous"][] = $arrData;
            }
        }

        return $arrFinal;
    }

    /**
     * get user trip data
     * @return array
     */
    function getUserTripData() {
        $arrSearch = array();

        switch ($_REQUEST[range]) {
            case 'today':
            	$arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START"), "MYSQL_DATETIME");
            	$arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME");
                //$arrSearch["expense_date"] = localToUtcMS(getNowLocalRange("START"), "MYSQL_DATETIME");
                break;
            case 'yesterday':
            	$arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-1days"), "MYSQL_DATETIME");
            	$arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END", "-1days"), "MYSQL_DATETIME");
            	//$arrSearch["expense_date"] = localToUtcMS(getNowLocalRange("START", "-1days"), "MYSQL_DATETIME");
                break;
            case 'week':
                $arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-7days"), "MYSQL_DATETIME");
                $arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME");
                break;
            case 'month':
                $arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-29days"), "MYSQL_DATETIME"); 
                $arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END"), "MYSQL_DATETIME");
                break;
            default:
                break;
        }

        if (isset($arrSearch) and !empty($arrSearch)) {
            
        } else {
            if (isset($_REQUEST['fromDate']) and strlen(trim($_REQUEST['fromDate']))) {
                $arrSearch['fromDate'] = localToUtcMS($_REQUEST['from_date'], "MYSQL_DATETIME");
            } else {
                $arrSearch['fromDate'] = localToUtcMS(getNowLocalRange("START", "-3month"), "MYSQL_DATETIME"); 
            }

            if (isset($_REQUEST['toDate']) and strlen(trim($_REQUEST['toDate']))) {
                $arrSearch['toDate'] = localToUtcMS($_REQUEST['toDate'], "MYSQL_DATETIME");
            } else {
                $arrSearch['toDate'] = localToUtcMS(getNowLocalRange("END", "+3month"), "MYSQL_DATETIME"); 
            }
        }
        $arrResult = array();
		$arrTrips = $this->getUserTripByUserId($_SESSION[$this->session_prefix]["user"]["user_id"], $arrSearch);

        return $arrTrips;
    }

    /**
     * function to get user trip data
     * @param type $intUserId
     * @param type $arrSearch
     * @return array
     */
    function getUserTripByUserId($intUserId, $arrSearch = array()) {
        $strWhere = "1";

        if ($arrSearch['fromDate'] != '' and $arrSearch['toDate'] != '') {
           	/*
        	$strWhere .= " AND ( (trip_date_from_timestamp BETWEEN :trip_date_from_from AND :trip_date_from_to)  ";
            $arrParams[':trip_date_from_to'] = $arrSearch['toDate'];
            $arrParams[':trip_date_from_from'] = $arrSearch['fromDate'];

            $strWhere .= " OR (trip_date_to_timestamp BETWEEN :from_date_from AND :to_date_to)  )";
            $arrParams[':to_date_to'] = $arrSearch['toDate'];
            $arrParams[':from_date_from'] = $arrSearch['fromDate'];
            */
        	$strWhere .= " AND (( ( :from_date BETWEEN trip_date_from_timestamp AND trip_date_to_timestamp ) OR 
        					( :to_date BETWEEN trip_date_from_timestamp AND trip_date_to_timestamp ) ) 
        			OR (trip_date_from_timestamp BETWEEN :from_date AND :to_date) 
        				OR (trip_date_to_timestamp BETWEEN :from_date AND :to_date))";
        	$arrParams[':to_date'] = $arrSearch['toDate'];
        	$arrParams[':from_date'] = $arrSearch['fromDate'];
        }


        $arrParams[':user_id'] = $intUserId;
        $strWhere .= " AND ut.user_id = :user_id";

        $strSQL = "SELECT ".$this->getUserTripFieldByLocal('string')." FROM user_trips as ut WHERE $strWhere";
        $rsResult = $this->database->queryData($strSQL, $arrParams);
        //echo $strSQL; p($arrParams);
        $_SESSION[$this->session_prefix]["user"]['user_trips_array'] = $rsResult;
        $_SESSION[$this->session_prefix]["user"]['user_trips_where'] = $strWhere;
        $_SESSION[$this->session_prefix]["user"]['user_trips_params'] = $arrParams;

        foreach ($rsResult as $intKey => $arrResData) {
            $rsResult[$intKey]["trip_expense"] = $this->getTripTotalExpense($arrResData['user_trip_id']);
            $rsResult[$intKey]["trip_currency"] = $this->getCurrencySymbolById($arrResData['trip_currency']);
            $rsResult[$intKey]["have_expense"] = ($this->getTripExpenseCount($arrResData['user_trip_id']))?"yes":"no";
        }

        $arrFinal = array();
        $localNow = getNowLocal("MYSQL_TIMESTAMP_MS");
        foreach ($rsResult as $arrData) {
            if (($arrData['trip_date_from_timestamp'] <= $localNow) and ($arrData['trip_date_to_timestamp'] >= $localNow)) {
                $arrFinal[1][] = $arrData;
            } elseif ($arrData['trip_date_from_timestamp'] > $localNow) {
                $arrFinal[2][] = $arrData;
            } elseif ($arrData['trip_date_to_timestamp'] < $localNow) {
                $arrFinal[3][] = $arrData;
            }
        }
        ksort($arrFinal);
        $arrTrip = array();
        foreach ($arrFinal as $strKey => $arrValue) {
            switch ($strKey) {
                case 1:
                    $arrTrip['Ongoing'] = $arrValue;
                    break;
                case 2:
                    $arrTrip['Upcoming'] = $arrValue;
                    break;
                case 3:
                    $arrTrip['Previous'] = $arrValue;
                    break;
            }
        }
        return $arrTrip;
    }

    /**
     * get total trip expenses by provinding its type
     * @param type $arrData
     * @param type $strType
     * @return int $arrTotal
     */
    function getTotalTripExpense($arrData, $strType) {
        switch ($strType) {
            case 'PREVIOUS':
                $strTripIds = $this->extractTripIds($arrData['Previous']);
                break;
            case 'ONGOING':
                $strTripIds = $this->extractTripIds($arrData['Ongoing']);
                break;
            case 'UPCOMING':
                $strTripIds = $this->extractTripIds($arrData['Upcoming']);
                break;
        }
        if (!empty($strTripIds)) {
            $arrTotal = $this->getTripTotalExpense($strTripIds, true);
        }
        return $arrTotal;
    }

    /**
     * get extract from array
     * @param type $arrData
     * @return array
     */
    function extractTripIds($arrData) {
        $arrTripId = array();
        if (is_array($arrData)) {
            foreach ($arrData as $arrTrip) {
                array_push($arrTripId, $arrTrip['user_trip_id']);
            }
        }
        if (is_array($arrTripId)) {
            return implode(",", $arrTripId);
        } else {
            return $arrTripId;
        }
    }

    /**
     * get total trip expense
     * @param type $strTripId
     * @param type $blnMultiple
     * @return array 
     */
    function getTripTotalExpense($strTripId, $blnMultiple = false) {
        $strSQL = "SELECT
                        SUM(ue.expense_base_currency_amount) AS total_expense,
                        if(csi.currency_symbol is not null,csi.currency_symbol,csi.currency_code) AS expense_currency_symbol
                    FROM
                        user_expenses ue
                    LEFT JOIN
                        currencies csi ON csi.currency_id = ue.base_currency_id
                    WHERE 1";

        if ($blnMultiple) {
            $strSQL .= " AND ue.user_trip_id IN (" . $strTripId . ")";
        } else {
            $strSQL .= " AND ue.user_trip_id ='" . $strTripId . "'";
        }

        $rsResult = $this->database->queryOne($strSQL);

        return $rsResult;
    }

    /**
     * get currency symbol by currency id
     * @param type $currencyId
     * @return type
     * @return Ambigous <>
     */
    function getCurrencySymbolById($currencyId) {
        $strSQL = "SELECT if(currency_symbol is not null,currency_symbol,currency_code) as currency FROM currencies WHERE currency_id = :currency_id";
        $arrParams[':currency_id'] = $currencyId;
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        return $arrResult['currency'];
    }

    /**
     * get logged in user's trip details
     * @return Ambigous <>
     */
    function getUserTripDetails() {
        $arrData = array();

        $arrTrips = $this->getUserTripByUserId($_SESSION[$this->session_prefix]["user"]["user_id"]);

        if (is_array($arrTrips) and !empty($arrTrips)) {
            $arrData['Ongoing'] = ($arrTrips['Ongoing']) ? end($arrTrips['Ongoing']) : "";

            @usort($arrTrips['Upcoming'], function($a, $b) {
                                return $a['trip_date_from'] - $b['trip_date_from'];
                            });

            $arrData['Upcoming'] = @end($arrTrips['Upcoming']);

            @usort($arrTrips['Previous'], function($a, $b) {
                                return $a['trip_date_to'] - $b['trip_date_to'];
                            });

            $arrData['Previous'] = @end($arrTrips['Previous']);

            foreach ($arrData as $intKey => $arrDetails) {
                if (isset($arrDetails['trip_destination']) and !empty($arrDetails['trip_destination'])) {
                    $arrData[$intKey]['trip_destination'] = $this->getModel('miscellaneous')->getCountryNameById($arrDetails['trip_destination']);
                }
                if (isset($arrDetails['user_trip_id']) and !empty($arrDetails['user_trip_id'])) {
                    $arrData[$intKey]['trip_expense'] = $this->getTripTotalExpense($arrDetails['user_trip_id']);
                }
            }
        }
        return $arrData;
    }
    
    /**
     * function to get trip data by trip id
     * @param type $tripId
     * @return Ambigous <>
     */
    function getTripDataByTripId($tripId) {
        $strSQL = "SELECT ".$this->getUserTripFieldByLocal() .",c.name as destination,bet.base_expense_type_name
                    FROM user_trips ut
                    LEFT join countries c on c.country_id = ut.trip_destination
                    LEFT join base_expense_types bet on   bet.base_expense_type_id = ut.base_expense_type_id
                    WHERE 1";
        $arrParams[':user_trip_id'] = $tripId;
        $strSQL .= " AND ut.user_trip_id = :user_trip_id";
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        return $arrResult;
    }

    /**
     * get all images of a trip
     * @param type $tripId
     * @return array
     */
    function getReferenceByTripId($tripId) {
        $arrParams[':user_trip_id'] = $tripId;
        $strWhere = "user_trip_id = :user_trip_id";
        $rsResult = $this->getDBTable("user-trip-reference")->fetchAllByFields(array("trip_filename"), array("where" => $strWhere, "params" => $arrParams));
        return $rsResult;
    }

    /**
     * get all user trips
     * @param type $user_id
     * @param type $timestamp
     * @return array
     */
    function getUserTrips($user_id, $timestamp) {
        $arrParams = array();
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $user_id;

        if (isset($timestamp) and !empty($timestamp)) {
            $strWhere .= " AND updated_date >= :updated_date";
            $arrParams[':updated_date'] = date("Y-m-d H:i:s", $timestamp);
        }
        
        $rsResult = $this->getDBTable("user-trips")->fetchAll(array("where" => $strWhere, "params" => $arrParams));

        $this->typeCastFields(array("int" => array('user_trip_id', 'user_id', 'base_expense_type_id', 'trip_destination', 'LUID', 'trip_currency')), $rsResult, 2);
        $this->typeCastFields(array("float" => array('trip_budget')), $rsResult, 2);

        foreach ($rsResult as $intKey => $arrDetail) {

            $rsResult[$intKey]['server_id'] = $arrDetail['user_trip_id'];

            if (isset($arrDetail['trip_date_from']) and !empty($arrDetail['trip_date_from'])) {
                $rsResult[$intKey]['trip_date_from'] = $arrDetail['trip_date_from_timestamp']; //(int) strtotime($arrDetail['trip_date_from']) * 1000;
            }

            if (isset($arrDetail['trip_date_to']) and !empty($arrDetail['trip_date_to'])) {
                $rsResult[$intKey]['trip_date_to'] = $arrDetail['trip_date_to_timestamp']; //(int) strtotime($arrDetail['trip_date_to']) * 1000;
            }
            unset($rsResult[$intKey]['trip_date_from_timestamp']);
            unset($rsResult[$intKey]['trip_date_to_timestamp']);

            if (isset($arrDetail['created_date']) and !empty($arrDetail['created_date'])) {
                $rsResult[$intKey]['created_date'] = strtotime($arrDetail['created_date']) * 1000;
            }

            if (isset($arrDetail['updated_date']) and !empty($arrDetail['updated_date'])) {
                $rsResult[$intKey]['updated_date'] = strtotime($arrDetail['updated_date']) * 1000;
            }

            if (isset($arrDetail['trip_destination']) and !empty($arrDetail['trip_destination'])) {
                $rsResult[$intKey]['trip_destination'] = $this->getModel("miscellaneous")->getCountryNameById($arrDetail['trip_destination']);
            }

            $rsResult[$intKey]['base_type_id'] = ($arrDetail['base_expense_type_id']) ? $arrDetail['base_expense_type_id'] : "";
            $rsResult[$intKey]['base_currency_id'] = ($arrDetail['trip_currency']) ? $arrDetail['trip_currency'] : "";


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
     * @param unknown $user_id
     * @param number $timestamp
     * @return multitype:Ambigous 
     */
    function getDeletedUserTrips($user_id, $timestamp = 0) {

        $strWhere = " reference_key_id = :reference_key_id AND deleted_on > :deleted_on AND `table` = :table";
        $arrParams[':reference_key_id'] = $user_id;
        $arrParams[':deleted_on'] = date("Y-m-d H:i:s", $timestamp);
        $arrParams[':table'] = 'user_trips';
        $arrResults = $this->getDBTable("deleted-items")->fetchAllByFields(array("deleted_item_id", "reference_id"), array("where" => $strWhere, "params" => $arrParams));
        foreach ($arrResults as $arrResult) {
            $arrData[] = $arrResult['reference_id'];
        }

        return array($arrData);
    }

    /**
     * function to delete trip
     * @param type $travelId
     * @return string
     */
    function deleteUserTravelById($travelId) {
        if ($travelId != "") {
            $arrayID = array("0" => $travelId);
            $this->getModel("sync-updated-time")->updateDeletedItems("user_trips", $arrayID);
            $strResponse = $this->getDBTable("user-trips")->delete(array("where" => "user_trip_id = :user_trip_id", "params" => array(":user_trip_id" => $travelId)));
            $this->database->query($strSQL);
            return "SUCCESS";
        } else {
            return "FAIL";
        }
    }

    /**
     * function to delete trip
     * @return string
     */
    function deleteUserTrip() {
        if (is_array($_REQUEST['selected_trip'])) {
            $this->getModel("sync-updated-time")->updateDeletedItems("user_trips", $_REQUEST['selected_trip']);
            $arrItems = $this->database->inClauseEntityList("item", count($_REQUEST['selected_trip']));
            $arrItemsParams = $this->database->inClauseEntityParams("item", $_REQUEST['selected_trip']);
            $strSQL = "DELETE FROM user_trips WHERE user_trip_id IN (" . $arrItems . ")";
            $this->database->query($strSQL, $arrItemsParams);
            return 'SUCCESS';
        } else {
            return 'FAIL';
        }
    }

    /**
     * function to export data
     * @param type $tripid
     */
    function exportUserTrips($tripid) {
        include(APPLICATION_PATH . "/lib/PHPExcel/PHPExcel.php");
        include(APPLICATION_PATH . "/lib/PHPExcel/PHPExcel/IOFactory.php");
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("User Trips")
                ->setLastModifiedBy("User Trips")
                ->setTitle("User Trips Summary")
                ->setSubject("User Trips Summary")
                ->setDescription("User Trips Summary Report")
                ->setKeywords("User Trips Summary")
                ->setCategory("User Trips Summary");

        $sheet = $objPHPExcel->getActiveSheet();

        $this->createWorksheet($objPHPExcel, 0, 'User Trips Summary', $tripid);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="my-trips-'.getNowLocal("UNIQUE_DATETIME").'.xls"');
        $objWriter->save('php://output');
    }

    /**
     * create xls file for exporting
     * @param type $objPHPExcel
     * @param type $sheet
     * @param type $worksheetTitle
     * @param type $tripid
     */
    function createWorksheet($objPHPExcel, $sheet, $worksheetTitle, $tripid) {
        for ($j = 65; $j < 67; $j++) {
            for ($i = 65; $i < 91; $i++) {
                $arr[] = chr($j) . chr($i);
            }
        }
        $alphas = array_merge(range('A', 'Z'), $arr);
        $objWorkSheet = $objPHPExcel->createSheet($sheet);
        $headers = array("Title", "Destination", "Status", "Start Date", "End Date", "Type", "Base Currency Name", "Base Currency Code", "Budget Amount", "Trip Expense Amount", "Description",);
        for ($i = 0, $j = 1; $i < count($headers); $i++) {
            $objWorkSheet->setCellValue($alphas[$i] . $j, $headers[$i]);
            $objWorkSheet->getColumnDimension($alphas[$i])->setWidth(15);
            $objWorkSheet->getStyle($alphas[$i] . $j)->getFont()->setBold(true);
        }

        if (isset($_SESSION[$this->session_prefix]["user"]['user_trips_array']) and !empty($_SESSION[$this->session_prefix]["user"]['user_trips_array'])) {
            if ($tripid != "") {
                $sql = "SELECT ".$this->getUserTripFieldByLocal()." ,c.*, co.*, ue.*, bet.*, sum(ue.expense_amount) as total_expense_amount FROM `user_trips` AS ut
                    INNER JOIN currencies c ON c.currency_id = ut.trip_currency
                    INNER JOIN countries co ON co.country_id = ut.trip_destination
                    INNER JOIN base_expense_types bet ON bet.base_expense_type_id = ut.base_expense_type_id
                	LEFT JOIN user_expenses as ue ON ue.user_trip_id = ut.user_trip_id
                    WHERE 1 AND ut.user_trip_id IN (" . $tripid . ") GROUP BY ue.user_trip_id ORDER BY ut.trip_date_from DESC";
                $arrResult = $this->database->queryData($sql);
            } else {
                echo $sql = "SELECT ".$this->getUserTripFieldByLocal()." ,c.*, co.*, ue.*, bet.*, sum(ue.expense_amount) as total_expense_amount FROM `user_trips` AS ut
                    INNER JOIN currencies c ON c.currency_id = ut.trip_currency
                    INNER JOIN countries co ON co.country_id = ut.trip_destination
                    INNER JOIN base_expense_types bet ON bet.base_expense_type_id = ut.base_expense_type_id
                	LEFT JOIN user_expenses as ue ON ue.user_trip_id = ut.user_trip_id
                    WHERE 1 AND " . $_SESSION[$this->session_prefix]["user"]['user_trips_where']. " GROUP BY ue.user_trip_id ORDER BY ut.trip_date_from DESC";
                $arrResult = $this->database->queryData($sql, $_SESSION[$this->session_prefix]["user"]['user_trips_params']);
            }
        }

        $i = 1;
        if (isset($arrResult) and !empty($arrResult)) {
            foreach ($arrResult as $key => $cdata) {

                $summary[$i] = array("Title" => $cdata['trip_title'], "Destination" => $cdata['name'], "Status" => $cdata['trip_status'], "Start Date" => date(DATE_TIME_FORMAT, strtotime($cdata['trip_date_from'])), 
        		"End Date" => date(DATE_TIME_FORMAT, strtotime($cdata['trip_date_to'])), "Type" => $cdata['base_expense_type_name'], "Base Currency Name" => $cdata['currency_name'], "Base Currency Code" => $cdata['currency_code'],
        		"Budget Amount" => $cdata['trip_budget'], "Trip Expense Amount" => $cdata['total_expense_amount'],	"Description" => $cdata['trip_description']);
                
                $i++;
                $this->createTripEnpenseWorksheet($objPHPExcel, $j, 'Expense - ' . $cdata['trip_title'], $cdata['user_trip_id']);
            }

            for ($i = 0, $j = 1, $k = 2; $i < count($arrResult); $i++, $j++) {
                $l = 0;
                $tripTitle = 'Expense - ' . $arrResult[$i]['trip_title'];
                if (strlen($tripTitle) > 30) {
                    $newTripTitle = substr($tripTitle, 0, 29);
                } else {
                    $newTripTitle = $tripTitle;
                }

                foreach ($summary[$j] as $value) {
                    $objWorkSheet->setCellValue($alphas[$l] . $k, $value);
                    $objWorkSheet->getCell('A' . $k)->getHyperlink()->setUrl("sheet://'" . $newTripTitle . "'!A1");
                    $l++;
                }
                $k++;
            }
        }

        $objWorkSheet->setTitle($worksheetTitle);
    }

    /**
     * create xls file for trip related expenses
     */
    function createTripEnpenseWorksheet($objPHPExcel, $sheet, $worksheetTitle, $tripid) {
        for ($j = 65; $j < 67; $j++) {
            for ($i = 65; $i < 91; $i++) {
                $arr[] = chr($j) . chr($i);
            }
        }
        $alphas = array_merge(range('A', 'Z'), $arr);
        $objWorkSheet = $objPHPExcel->createSheet(1);

        if (strlen($worksheetTitle) > 30) {
            $newworksheetTitle = substr($worksheetTitle, 0, 29);
        } else {
            $newworksheetTitle = $worksheetTitle;
        }
        $objWorkSheet->setTitle($newworksheetTitle);
        $objWorkSheet->setCellValue("A1", $worksheetTitle);
        $objWorkSheet->mergeCells('A1:I1');
        $objWorkSheet->getStyle("A1")->getFont()->setBold(true);

        //$expense_headers = array("Expense Date", "Expense Summary", "Base Category Type", "Base Currency Name", "Base Currency Code", "Expense Base Currency Amount", "Expense Currency Name", "Expense Currency Code", "Expense Amount", "Category Title");
        $expense_headers = array("Summary",	"Vendor",	"Expense Currency",	"Amount",	"Base Currency", "Amount (in Base Currency)",	"Date",	"Type",	"Parent Category",	"Category",	"Trip",	"Description");

        for ($i = 0, $j = 2; $i < count($expense_headers); $i++) {
            $objWorkSheet->setCellValue($alphas[$i] . $j, $expense_headers[$i]);
            $objWorkSheet->getColumnDimension($alphas[$i])->setWidth(15);
            $objWorkSheet->getStyle($alphas[$i] . $j)->getFont()->setBold(true);
        }
		/*
        if(isset($_SESSION[$this->session_prefix]['user']['client_locale']["timezone"])) {
        	$tz = $_SESSION[$this->session_prefix]['user']['client_locale']["timezone"];
        	$selectDateTime = "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d %H:%i:%s') as expense_date,
        	DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ue.exp_date_timestamp/1000),@@session.time_zone, '$tz'),'%H:%i:%s') as expense_time,";
        }else {
        	$selectDateTime = "ue.`expense_date`,  ue.`expense_time`,";
        }
        */
        if (isset($tripid) and !empty($tripid)) {
                            // ue.`user_expense_id`,  ue.`user_id`,  ue.`card_id`,  ue.`user_trip_id`,  ue.`base_type_id`,  ue.`expense_category_id`,  ue.`expense_currency_id`,  ue.`expense_amount`,  ue.`base_currency_id`,  ue.`expense_base_currency_amount`,  ue.`expense_summary`,  ue.`expense_vendor_id`,  ue.`exp_date_timestamp`,  ue.`expense_description`,  ue.`expense_tags`,  ue.`status`,  ue.`payment_mode`,  ue.`created_date`,  ue.`updated_date`,  ue.`LUID`,
    						//$selectDateTime
            $sql_expense = "SELECT
            				".$this->getModel('user-expenses')->getUserExpenseFieldByLocal().",
                            c.title,c.card_number,ut.trip_title,
                            if(bet.base_expense_type_name is not null,base_expense_type_name,'Uncategorized') as base_expense_type_name,
                            cs.currency_name AS base_currency_name,
                            cs.currency_symbol AS base_currency_symbol,
                            cs.currency_code AS base_currency_code,
                            csi.currency_name AS expense_currency_name,
                            csi.currency_symbol AS expense_currency_symbol,
                            csi.currency_code AS expense_currency_code,
                            ev.name,c.processor_type,ecp.title as parent_category,
                            IF((bet.base_expense_type_name IS NOT NULL AND ec.title IS NULL), 'Uncategorized', ec.title) as category_title
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
                    WHERE 1 AND ut.user_trip_id = :user_trip_id";
            $arrTripExpenses = $this->database->queryData($sql_expense, array(":user_trip_id" => $tripid));
            
            $i = 1;
            if (isset($arrTripExpenses) and !empty($arrTripExpenses)) {
                foreach ($arrTripExpenses as $key => $cdata) {
                    //$summary[$i] = array("Expense Date" => $cdata['expense_date'], "Expense Summary" => $cdata['expense_summary'], "Base Category Type" => $cdata['base_expense_type_name'], "Base Currency Name" => $cdata['base_currency_name'], "Base Currency Code" => $cdata['base_currency_code'],
                    //    "Expense Base Currency Amount" => $cdata['expense_base_currency_amount'], "Expense Currency Name" => $cdata['expense_currency_name'], "Expense Currency Code" => $cdata['expense_currency_code'], "Expense Amount" => $cdata['expense_amount'], "Category Title" => $cdata['category_title']
                    //);
                	$summary[$i] = array("Summary"=>$cdata["expense_summary"],	"Vendor"=>$cdata["name"],	"Expense Currency"=>$cdata["expense_currency_code"],	"Amount"=>$cdata["expense_amount"],	"Base Currency"=>$cdata["base_currency_code"], "Amount (in Base Currency)"=>$cdata["expense_base_currency_amount"],	"Date"=>date(DATE_TIME_FORMAT, strtotime($cdata['expense_date'].' '.$cdata['expense_time'])),	"Type"=>$cdata["base_expense_type_name"],	"Parent Category"=>$cdata["parent_category"],	"Category"=>$cdata["category_title"],	"Trip"=>$cdata["trip_title"],	"Description"=>$cdata["expense_description"]);
                    $i++;
                }

                for ($i = 0, $j = 1, $k = 3; $i < count($arrTripExpenses); $i++, $j++) {
                    $l = 0;
                    foreach ($summary[$j] as $value) {
                        $objWorkSheet->setCellValue($alphas[$l] . $k, $value);
                        $l++;
                    }
                    $k++;
                }
            }
        }
    }

    /**
     * get all user trip images
     * @param type $user_id
     * @param type $timestamp
     * @return array
     */
    function getAllUserTripImages($user_id, $timestamp) {
        $arrParams = array();
        $arrImgParams = $arrImages = $arrTripId = array();
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $user_id;

        $rsResult = $this->getDBTable("user-trips")->fetchAllByFields(array('user_trip_id'), array("where" => $strWhere, "params" => $arrParams));

        foreach ($rsResult as $arrData) {
            $arrTripId[] = $arrData['user_trip_id'];
        }

        if (is_array($arrTripId) and !empty($arrTripId)) {
            $arrItems = $this->database->inClauseEntityList("item", count($arrTripId));
            $arrItemsParams = $this->database->inClauseEntityParams("item", $arrTripId);
            $strImgWhere = "user_trip_id in (" . $arrItems . ")";
            if (isset($timestamp) and !empty($timestamp)) {
                $strImgWhere .= " AND updated_date >= :updated_date";
                $arrImgParams[':updated_date'] = date("Y-m-d H:i:s", $timestamp);
            }
            $arrImgParams = array_merge($arrImgParams, $arrItemsParams);
            $arrResult = $this->getDBTable("user-trip-reference")->fetchAll(array("where" => $strImgWhere, "params" => $arrImgParams));
            $this->typeCastFields(array("int" => array('user_trip_reference_id', 'user_trip_id', 'LUID')), $arrResult, 2);
            $path = APPLICATION_URL . "/images/user_trips";
            foreach ($arrResult as $intKey => $arrImgData) {
                $arrImages[$intKey]['owner_key'] = $arrImgData['user_trip_id'];
                $arrImages[$intKey]['img']['server_id'] = $arrImgData['user_trip_reference_id'];
                $arrImages[$intKey]['img']['server_path'] = $path . "/" . $arrImgData['trip_filename'];
                $arrImages[$intKey]['img']['LUID'] = (isset($arrImgData['LUID']) and !empty($arrImgData['LUID'])) ? $arrImgData['LUID'] : 0;
            }
            return $arrImages;
        } else {
            return array();
        }
    }

    /**
     * delete user trip images
     * @return mixed
     */
    function deleteUserTripImages() {
        $arrParams[':user_trip_id'] = $_REQUEST['trip_id'];
        $arrParams[':trip_filename'] = $_REQUEST['filename'];
        $strWhere = "user_trip_id = :user_trip_id and trip_filename = :trip_filename";
        $rsResult = $this->getDBTable("user-trip-reference")->fetchRowByFields(array("user_trip_reference_id"), array("where" => $strWhere, "params" => $arrParams));
        $this->getModel("sync-updated-time")->updateDeletedImages("user_trip_reference", array($rsResult['user_trip_reference_id']), $_REQUEST['filename']);
        @unlink(APPLICATION_PATH . "/images/user_trips/" . $_REQUEST['filename']);
        return $this->getDBTable("user-trip-reference")->delete(array("where" => "user_trip_reference_id = :user_trip_reference_id", "params" => array(":user_trip_reference_id" => $rsResult['user_trip_reference_id'])));
    }

   /**
    * This function is used to get user trips by trip id from web service.
    * @param unknown $trip_ids
    * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >
    */
   function getUserTripsByTripIds($trip_ids) {
        $sql = "SELECT ".$this->getUserTripFieldByLocal().", c.*,co.*,bet.* FROM `user_trips` AS ut
                INNER JOIN currencies c ON c.currency_id = ut.trip_currency
                INNER JOIN countries co ON co.country_id = ut.trip_destination
                INNER JOIN base_expense_types bet ON bet.base_expense_type_id = ut.base_expense_type_id
                WHERE 1 AND ut.user_trip_id IN (" . $trip_ids . ") ";
        return $this->database->queryData($sql);
    }

    /**
     * This function is used to send mail to user along with trip pdf as attachment.
     * @param unknown $token
     * @param unknown $pdf_file_path
     */
    function sendTripPDFMail($token, $pdf_file_path) {
        $sql = "SELECT u.email, CONCAT_WS(' ',u.first_name,u.last_name) as uname FROM device_tokens AS dt
                INNER JOIN users AS u ON (u.user_id = dt.user_id)
                WHERE dt.token = :token";
        $userData = $this->database->queryOne($sql, array(":token" => $token));

        if (isset($userData) and !empty($userData)) {
            if (\generalFunctions::isValidEmail($userData['email'])) {
                $subject = "DailyUse - User Trips";
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
    function getDeletedUserTripImages($user_id, $timestamp = 0) {

        $strWhere = " reference_key_id = :reference_key_id AND deleted_on > :deleted_on AND `table` = :table";
        $arrParams[':reference_key_id'] = $user_id;
        $arrParams[':deleted_on'] = date("Y-m-d H:i:s", $timestamp);
        $arrParams[':table'] = 'user_trip_reference';
        $arrResults = $this->getDBTable("deleted-items")->fetchAllByFields(array("deleted_item_id", "reference_id", "extra_reference_keys"), array("where" => $strWhere, "params" => $arrParams));
        foreach ($arrResults as $arrResult) {
            $arrData[] = APPLICATION_URL . "/images/user_trips/" . $arrResult['extra_reference_keys'];
        }

        return $arrData;
    }

    /**
     * extract details from an array
     * @param type $arrUserTrips
     * @return array
     */
    function extractOngoingDetails($arrUserTrips) {
        $arrData = array();
        if (is_array($arrUserTrips['Ongoing'][0]) and !empty($arrUserTrips['Ongoing'][0])) {
            $arrData['trip_budget'] = $arrUserTrips['Ongoing'][0]['trip_budget'];
            $arrData['trip_currency'] = $arrUserTrips['Ongoing'][0]['trip_currency'];
        }
        return $arrData;
    }

    /**
     * get disable life list
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
     * Generate Trip pdf file
     * @param string $viewSection
     * @param string $module
     * @param array $params
     * @return Ambigous <string, multitype:string Ambigous <unknown, Ambigous> >
     */
    function generateTripPdf($viewSection, $module = '', $params = array()) {
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
        	$fileName = "my_trip_".getNow("UNIQUE_DATETIME",SERVICE_LOCAL_TIMEZONE);
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
                $this->sendTripPDFMail($params['token'], $pdf_file_path);
                $response['trip_pdf_path'] = APPLICATION_URL . "/images/pdf/".$fileName.".pdf";
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
     * @param type $intTripId
     * @return boolean
     */
    function checkTripAccess($intTripId) {
        $strSQL = "SELECT COUNT(0) AS total FROM user_trips WHERE user_id = :user_id AND user_trip_id=:user_trip_id";
        $intTotal = $this->database->getTotalFromQuery($strSQL, array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":user_trip_id" => $intTripId));
        if ($intTotal > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validate User Trips
     * @return boolean
     */
    function validateUserTrips() {
    	if (isset($_POST['trip_date_from']) and !empty($_POST['trip_date_from'])) {
            //$strFromDate = $_POST['trip_date_from']." ".date("H:i:s", strtotime($_POST['trip_time_from']));
    		$strFromDate = localToUtcMS($_POST['trip_date_from']." ".date("H:i:s", strtotime($_POST['trip_time_from'])), "MYSQL_DATETIME");
        }

        if (isset($_POST['trip_date_to']) and !empty($_POST['trip_date_to'])) {
            //$strToDate = $_POST['trip_date_to']." ".date("H:i:s", strtotime($_POST['trip_time_to']));
        	$strToDate = localToUtcMS($_POST['trip_date_to']." ".date("H:i:s", strtotime($_POST['trip_time_to'])), "MYSQL_DATETIME");
        }

        $strSQL = "select COUNT(0) AS total from user_trips where 
                        user_id = :user_id AND
                        (:from_date BETWEEN trip_date_from_timestamp AND trip_date_to_timestamp 
                        
                        OR 
                        :to_date BETWEEN trip_date_from_timestamp AND trip_date_to_timestamp 
                        )";
		if(isset($_REQUEST["user_trip_id"])) { //edit mode
			$strSQL .= " AND user_trip_id NOT IN ('".$_REQUEST["user_trip_id"]."')";
		}
        $intTotal = $this->database->getTotalFromQuery($strSQL, array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":from_date" => $strFromDate, ":to_date" => $strToDate));
		if ($intTotal > 0) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("DATE_CONFLICT", 'my-travel-plan');
            return false;
        } else {
            return true;
        }
    }

    /**
     * get all trips expense count
     * @param type $user_trip_id
     * @return type
     */
    function getTripExpenseCount($user_trip_id) {
        $strSQL = "SELECT count(0) as count_row
                    FROM user_expenses
                    WHERE user_trip_id = :user_trip_id";
        $arrParams[':user_trip_id'] = $user_trip_id;
        $arrResult = $this->database->queryOne($strSQL, $arrParams);
        return $arrResult['count_row'];
    }
    
    /**
     * get User Trip table sql field as per the user's local time
     * @return string
     */
    function getUserTripFieldByLocal($returnType = "string") {
    	$fields = "ut.`user_trip_id`,  ut.`user_id`,  ut.`base_expense_type_id`,  ut.`trip_title`,  ut.`trip_description`,  ut.`trip_destination`,  ut.`trip_currency`,  ut.`trip_date_from_timestamp`,  ut.`trip_date_to_timestamp`,  ut.`trip_budget`,  ut.`trip_status`,  ut.`created_date`,  ut.`updated_date`,  ut.`LUID`,";
    	if(isset($_SESSION[$this->session_prefix]['user']['client_locale']["timezone"])) {
    		$tz = $_SESSION[$this->session_prefix]['user']['client_locale']["timezone"];
    		$fields .= "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ut.trip_date_from_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d %H:%i:%s') as trip_date_from,
    		DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ut.trip_date_to_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d %H:%i:%s') as trip_date_to ";
    	}else if(defined("SERVICE_LOCAL_TIMEZONE")) {
    		$tz = SERVICE_LOCAL_TIMEZONE;
    		$fields .= "DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ut.trip_date_from_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d %H:%i:%s') as trip_date_from,
    		DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(ut.trip_date_to_timestamp/1000),@@session.time_zone, '$tz'),'%Y-%m-%d %H:%i:%s') as trip_date_to ";
    	}else {
    		$fields .= "DATE_FORMAT(FROM_UNIXTIME(ut.trip_date_from_timestamp/1000),'%Y-%m-%d %H:%i:%s') as trip_date_from,
    				DATE_FORMAT(FROM_UNIXTIME(ut.trip_date_to_timestamp/1000),'%Y-%m-%d %H:%i:%s') as trip_date_to ";
    	}
    	return $fields;
    }
}

<?php

namespace helper;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * User Class
 */
class user {

    function __construct($args = array()) {
        $this->dbobj = \configurations::getDBObject();
    }

    /**
     * DU - This function is used to get admin with ticket assignment rights.
     * @param number $user_id
     * @return Ambigous <multitype:, mixed>
     */
    function getUserImage($user_id = 0) {
        return $this->dbobj->selectFieldOne(array('user_image'), 'users', array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
    }

    /**
     * DU - This function is used to get admin with ticket assignment rights.
     * @param number $user_id
     * @return Ambigous <multitype:, mixed>
     */
    function getUserName($user_id = 0) {
        return $this->dbobj->selectFieldOne(array('first_name', 'last_name'), 'users', array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
    }

    /**
     * DU - This function is used to get user expenses by trip id.
     * @param number $tripid
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >
     */
    function getUserExpenseByTripId($tripid = 0) {
        $sql_expense = "SELECT
                            ".$this->getUserExpenseFieldByLocal().",
                            c.title,c.card_number,ut.trip_title,
                            if(bet.base_expense_type_name is not null,base_expense_type_name,'Uncategorized') as base_expense_type_name,
                            cs.currency_name AS base_currency_name,
                            cs.currency_symbol AS base_currency_symbol,
                            cs.currency_code AS base_currency_code,
                            csi.currency_name AS expense_currency_name,
                            csi.currency_symbol AS expense_currency_symbol,
                            csi.currency_code AS expense_currency_code,
                            ev.name,c.processor_type,ecp.title as parent_category,ec.title as category_title
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
        $arrTripExpenses = $this->dbobj->queryData($sql_expense, array(":user_trip_id" => $tripid));
        return $arrTripExpenses;
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
    				DATE_FORMAT(FROM_UNIXTIME(ue.exp_date_timestamp/1000),'%H:%i:%s') as expense_time ";
    	}
    	return $fields;
    }

}

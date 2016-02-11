<?php

namespace model;


/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief cron Model contains application logic for different cron features.
 */
class cronModel extends globalModel {

    /**
     * DU - this function is used to remove ticket assignment rights from Admins.
     */
    function updateTicketAssignmentRights() {
        $this->getDBTable("ticket-assignment-details")->update(array("is_deleted" => "1", "updated_date" => "DATE_FORMAT(now(),'%Y-%m-%d %T')"), array("where" => "CURDATE() > date_to", "params" => array()));
    }

    /**
     * DU - this function is used to remove ticket assignment rights from Admins.
     */
    function updateTicketAssignments() {

        $data = $this->getDBTable("ticket-assignments")->fetchAllByFields(array("ticket_id"), array("where" => "CURDATE() > date_to", "params" => array()));

        if (isset($data) and !empty($data)) {
            $ticketArr = array();
            foreach ($data as $d) {
                $ticketArr[] = $d['ticket_id'];
            }

            if (isset($ticketArr) and !empty($ticketArr)) {
                $arrItems = $this->database->inClauseEntityList("item", count($ticketArr));
                $arrItemsParams = $this->database->inClauseEntityParams("item", $ticketArr);

                $strWhere = "ticket_id IN (" . $arrItems . ") AND CURDATE() > date_to";
                $this->getDBTable("ticket-assignments")->update(array("is_deleted" => "1"), array("where" => $strWhere, "params" => $arrItemsParams));

                $strWhere1 = "ticket_id IN (" . $arrItems . ")";
                $this->getDBTable("tickets")->update(array("is_reassigned" => "0"), array("where" => $strWhere1, "params" => $arrItemsParams));
            }
        }

        exit;
    }
    
    /**
     * Function to get todays international currency exchange rate
     */
    function getTodaysCurrencyRate(){
        $strURL = "http://openexchangerates.org/api/latest.json?app_id=30ed12af3118411fb05eb3290f5f8781";

        $strData = \generalFunctions::getPageDataUsingCurl($strURL);

        $arrData = json_decode($strData);

        foreach($arrData->rates as $currency => $rates){
            $data = array();
            $data['currency'] = $currency;
            $data['rate'] = $rates;
            $data['date'] = date('Y-m-d');
            $this->getDBTable('exchange-rates')->insert($data);
        }        
    }

}

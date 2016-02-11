<?php

namespace helper;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * ticketAssignments Class
 */
class ticketAssignments {

    function __construct($args = array()) {
        $this->dbobj = \configurations::getDBObject();
    }

    /**
     * DU - This function is used to get admin with ticket assignment rights.
     */
    function isAdminAllowedForTicketAssignment($user_id = 0) {
        global $session_prefix;        
        $sql = "SELECT COUNT(*) AS total FROM ticket_assignment_details WHERE user_id = :user_id AND is_deleted=:is_deleted";
        return $total = $this->dbobj->getTotalFromQuery($sql,array(":user_id" => $user_id,":is_deleted"=>'0'));
    }
    
    /**
     * DU - This function is used to get date from on which admin has rights to access ticket assignment module.
     */
    function getTicketAssignmentDateForAdmin($user_id = 0) {
        return $this->dbobj->selectOne('ticket_assignment_details',array("where"=>"user_id = :user_id AND is_deleted=:is_deleted","params"=>array(":user_id" => $user_id,":is_deleted"=>'0')));
    }

}

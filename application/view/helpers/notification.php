<?php

namespace helper;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * Notification Class
 */
class notification {

    function __construct($args = array()) {
        $this->dbobj = \configurations::getDBObject();
    }

    /**
     * DU - This function is used to get unread ticket count for admin.
     */
    function getNotificationCount() {
        global $session_prefix;
        $sql = "SELECT COUNT(*) AS total FROM tickets WHERE (assigned_to = :assigned_to OR ticket_id IN (SELECT ticket_id FROM ticket_assignments 
                    WHERE reassigned_to=:reassigned_to AND CURDATE() between date_from and date_to)) AND is_read=:is_read";
        return $total = $this->dbobj->getTotalFromQuery($sql,array(":assigned_to" => $_SESSION[$session_prefix]['user']['user_id'],":is_read"=>'0',":reassigned_to"=>$_SESSION[$session_prefix]['user']['user_id']));
    }
    
    /**
     * DU - This function is used to get unread ticket count for admin.
     */
    function getAdminTickets() {
        global $session_prefix;
        $data = $this->dbobj->queryData("SELECT * FROM tickets AS t 
                        INNER JOIN users AS u ON (u.user_id = t.created_by)
                        WHERE (assigned_to = :assigned_to OR ticket_id IN (SELECT ticket_id FROM ticket_assignments 
                        WHERE reassigned_to=:reassigned_to AND CURDATE() between date_from and date_to)) AND is_read=:is_read 
                        ORDER BY t.created_date DESC LIMIT 3", array(':assigned_to' => $_SESSION[$session_prefix]['user']['user_id'],":is_read"=>'0',":reassigned_to"=>$_SESSION[$session_prefix]['user']['user_id']));
        
        return $data;
    }

}

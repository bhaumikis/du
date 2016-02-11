<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief tickets Model contains application logic for various functions and database operations of users tickets.
 */
class ticketsModel extends globalModel {

    /**
     * DU - This function is used to check all the validation for user ticket, while performing add ticket operation from web service
     * @param unknown $params
     * @return multitype:boolean multitype:multitype:string Ambigous <unknown, Ambigous>   |multitype:boolean multitype: 
     */
    function validateTicketForm($params = array()) {

        $errors = array();
        if (!isset($params["mobile_number"]) or !\generalFunctions::valueSet($params["mobile_number"])) {
            $errors[] = array("code" => "160", "message" => _l("Please enter mobile number.", "services"));
        }
        if (!isset($params["subject"]) or !\generalFunctions::valueSet($params["subject"])) {
            $errors[] = array("code" => "161", "message" => _l("Please enter subject.", "services"));
        }
        if (!isset($params["comment"]) or !\generalFunctions::valueSet($params["comment"])) {
            $errors[] = array("code" => "162", "message" => _l("Please enter comment.", "services"));
        }

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to add ticket through web service.
     * @param unknown $data
     * @return multitype:boolean multitype:Ambigous  |multitype:boolean multitype:string  
     */
    function addTicket($data) {
        unset($data["submit"]);

        $userDetail = $this->getDBTable("users")->fetchRowByFields(array("user_id", "first_name", "last_name"), array("where" => "mobile_number = :mobile_number AND deleted = :deleted", "params" => array(":mobile_number" => $data['mobile_number'], ":deleted" => '0')));

        if (isset($userDetail) and !empty($userDetail)) {
            $relationDetail = $this->getDBTable("user-admin-relations")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $userDetail['user_id'])));

            if (isset($relationDetail) and !empty($relationDetail)) {
                $adminDetail = $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $relationDetail['admin_id'])));
            } else {
                $adminDetail = $this->getDBTable("users")->fetchRow(array("where" => "usertype_id = :usertype_id", "params" => array(":usertype_id" => '1')));
            }

            $data["subject"] = $data['subject'];
            $data["comment"] = $data['comment'];
            $data["status"] = '0';
            $data["is_read"] = '0';
            $data["is_manual"] = '0';
            $data["query_template_id"] = $data['query_template_id'];
            $data["assigned_to"] = $adminDetail['user_id'];
            $data["created_by"] = $userDetail['user_id'];
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $ticket_id = $this->getDBTable("tickets")->insert($data);

            if ($adminDetail['email'] != '') {
                if (\generalFunctions::isValidEmail($adminDetail['email'])) {
                    $adminData = array();
                    $adminData["to_email"] = $adminDetail['email'];
                    $adminData["firstname"] = $adminDetail['first_name'];
                    $adminData["lastname"] = $adminDetail["last_name"];
                    $adminData["user_firstname"] = $userDetail['first_name'];
                    $adminData["user_lastname"] = $userDetail["last_name"];
                    $adminData["subject"] = $data['subject'];
                    $adminData["comments"] = $data['comment'];

                    $this->sendEmail("ticket_notification_mail", $adminData);
                }
            }

            $_SESSION[$this->session_prefix]["action_message"] = _l("Ticket added successfully.", "common");
        }

        if (!empty($ticket_id)) {
            return array(true, array("ticket_id" => $ticket_id));
        } else {
            $response = array("code" => "1000", "message" => "Issue with Database operation.");
            return array(false, $response);
        }
    }

    /**
     * DU - This function is used to get list of users tickets
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >
     */
    function getTickets() {
        if ($this->checkLoggedInAsSuperAdmin()) {
            return $this->database->queryData("SELECT t.*, t.status AS stat, CONCAT(u.first_name,' ',u.last_name) as uname, u.mobile_number as user_mobno, CONCAT(u1.first_name,' ',u1.last_name) as aname,u1.usertype_id FROM tickets AS t
                    INNER JOIN users AS u ON (u.user_id = t.created_by)
                    INNER JOIN users AS u1 ON (u1.user_id = t.assigned_to)
                    ORDER BY t.ticket_id DESC");
        } else {
            return $this->database->queryData("SELECT t.*, t.status AS stat, CONCAT(u.first_name,' ',u.last_name) as uname, u.mobile_number as user_mobno, CONCAT(u1.first_name,' ',u1.last_name) as aname FROM tickets AS t
                    INNER JOIN users AS u ON (u.user_id = t.created_by)
                    INNER JOIN users AS u1 ON (u1.user_id = t.assigned_to)
                    WHERE t.is_reassigned = :is_reassigned AND t.assigned_to = :assigned_to OR t.ticket_id IN (SELECT ticket_id FROM ticket_assignments
                        WHERE reassigned_to = :reassigned_to AND CURDATE() between date_from and date_to AND is_deleted=:is_deleted)
                    ORDER BY t.ticket_id DESC", array(":assigned_to" => $_SESSION[$this->session_prefix]['user']['user_id'], "is_reassigned" => '0', ":reassigned_to" => $_SESSION[$this->session_prefix]['user']['user_id'], ":is_deleted" => "0"));
        }
    }

    /**
     * DU - This function get the details of user ticket based on ticket_id
     * @param number $ticket_id
     * @return Ambigous <multitype:, mixed>
     */
    function getTicketDetails($ticket_id = 0) {

        $assignmentData = $this->getDBTable("tickets")->fetchRowByFields(array("assigned_to"), array("where" => "ticket_id = :ticket_id AND is_reassigned = :is_reassigned", "params" => array(":ticket_id" => $ticket_id, ":is_reassigned" => "0")));

        if (isset($assignmentData) and !empty($assignmentData)) {
            $assigned_id = $assignmentData['assigned_to'];
        } else {
            $reassignmentData = $this->getDBTable("ticket-assignments")->fetchRowByFields(array("reassigned_to"), array("where" => "ticket_id = :ticket_id AND is_deleted = :is_deleted AND CURDATE() between date_from and date_to", "params" => array(":ticket_id" => $ticket_id, ":is_deleted" => "0")));

            if (isset($reassignmentData) and !empty($reassignmentData)) {
                $assigned_id = $reassignmentData['reassigned_to'];
            }
        }

        if ($_SESSION[$this->session_prefix]['user']['user_id'] == $assigned_id) {
            $ticketOwner = $this->getDBTable("tickets")->fetchRowByFields(array("assigned_to"), array("where" => "ticket_id = :ticket_id", "params" => array(":ticket_id" => $ticket_id)));
            if (isset($ticketOwner) and !empty($ticketOwner)) {
                $ticketOwnerId = $ticketOwner['assigned_to'];
            }
            $updatedRows = $this->getDBTable("tickets")->update(array("is_read" => "1"), array("where" => "(ticket_id = :ticket_id) AND (assigned_to = :assigned_to OR assigned_to = :owner_id)", "params" => array(":ticket_id" => $ticket_id, ":assigned_to" => $assigned_id, ":owner_id" => $ticketOwnerId)));
            if ($updatedRows > 0) {
                $_SESSION[$this->session_prefix]['user']['notification_count'] = $_SESSION[$this->session_prefix]['user']['notification_count'] - 1;
            }
        }

        $sql = "SELECT t.*, u.first_name, u.last_name, u.mobile_number, u.email, u.address_line1, u.address_line2, u.birth_date, u.gender, u.country_id, c.name AS cname from tickets AS t
                    INNER JOIN users AS u ON (u.user_id = t.created_by)
                    LEFT JOIN countries AS c ON (c.country_id = u.country_id)
                    WHERE ticket_id = :ticket_id";
        return $this->database->queryOne($sql, array(":ticket_id" => $ticket_id));
    }

    /**
     * DU - This function get query templates.
     * @return Ambigous
     */
    function getQueryTemplates() {
        return $this->getDBTable("email-templates")->fetchAll(array("where" => "type = :type AND is_query_template = :is_query_template", "params" => array(":type" => "0", ":is_query_template" => "1")));
    }

    /**
     * DU - This function get the details of the user based on mobile number
     * @param number $mobile_number
     * @return multitype:string 
     */
    function getUserDetails($mobile_number = 0) {
        $sql = "SELECT u.*,c.name from users AS u INNER JOIN countries AS c ON (c.country_id = u.country_id) WHERE mobile_number = :mobile_number";
        $data = $this->database->queryOne($sql, array(":mobile_number" => $mobile_number));
        $arr = array();
        if (isset($data) and !empty($data)) {
            $arr['records'] = 'yes';
            $arr['data'] = '<div class="table-responsive">
                        <input type="hidden" name="hid_user_id" id="hid_user_id" value="' . $data['user_id'] . '" />
                        <table class="table table-bordered table-hover">
                            <tr><th width="20%">' . _l('Label_First_Name', 'tickets') . '</th><td>' . $data['first_name'] . '</td></tr>
                            <tr><th>' . _l('Label_Last_Name', 'tickets') . '</th><td>' . $data['last_name'] . '</td></tr>
                            <tr><th>' . _l('Label_Add1', 'tickets') . '</th><td>' . $data['address_line1'] . '</td></tr>
                            <tr><th>' . _l('Label_Add2', 'tickets') . '</th><td>' . $data['address_line2'] . '</td></tr>
                            <tr><th>' . _l('Label_DOB', 'tickets') . '</th><td>' . $data['birth_date'] . '</td></tr>
                            <tr><th>' . _l('Label_Gender', 'tickets') . '</th><td>' . $data['gender'] . '</td></tr>
                            <tr><th>' . _l('Label_Mobile', 'tickets') . '</th><td>' . $data['mobile_number'] . '</td></tr>
                            <tr><th>' . _l('Label_Email', 'tickets') . '</th><td>' . $data['email'] . '</td></tr>
                            <tr><th>' . _l('Label_Country', 'tickets') . '</th><td>' . $data['name'] . '</td></tr>
                        </table>
                    </div>';
        } else {
            $arr['records'] = 'no';
            $arr['data'] = '<div class="table-responsive">
                        <input type="hidden" name="hid_user_id" id="hid_user_id" value="" />
                        <table class="table table-bordered table-hover">
                            <tr><th width="20%">' . _l('Label_First_Name', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Last_Name', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Add1', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Add2', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_DOB', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Gender', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Mobile', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Email', 'tickets') . '</th><td></td></tr>
                            <tr><th>' . _l('Label_Country', 'tickets') . '</th><td></td></tr>
                        </table>
                    </div>';
        }
        return $arr;
    }

    /**
     * DU - This function get the details of template.
     * @param number $template_id
     * @return multitype:string unknown Ambigous 
     */
    function getTemplateDetails($template_id = 0) {
        $data = $this->getDBTable("email-templates")->fetchRow(array("where" => "email_template_id = :email_template_id", "params" => array(":email_template_id" => $template_id)));
        $arr = array();
        if (isset($data) and !empty($data)) {
            $arr['subject'] = $data['subject'];
            $arr['content'] = $data['htmltext'];
        } else {
            $arr['subject'] = '';
            $arr['content'] = '';
        }
        return $arr;
    }

    /**
     * DU - This function is used to add user tickets from admin. 
     */
    function addUserTickets() {
        $data = $_POST;
        unset($data["submit"], $data["hid_submit"]);

        $userAdminMappingData = $this->getDBTable("user-admin-relations")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['hid_user_id'])));

        $dataTicket = array();
        $dataTicket["subject"] = $data['subject'];
        $dataTicket["comment"] = $data['comments'];
        $dataTicket["status"] = $data['status'];
        $dataTicket["is_manual"] = '1';
        $dataTicket["query_template_id"] = $data['query_template_id'];
        $dataTicket["created_by"] = $data['hid_user_id'];
        $dataTicket["final_solution"] = $data['final_solution'];
        $dataTicket["created_date"] = date("Y-m-d H:i:s");
        $dataTicket["updated_date"] = date("Y-m-d H:i:s");
        if (isset($userAdminMappingData) and !empty($userAdminMappingData)) {
            $dataTicket["assigned_to"] = $userAdminMappingData["admin_id"];
            $dataTicket["is_read"] = '0';
        } else {
            $dataTicket["assigned_to"] = $_SESSION[$this->session_prefix]["user"]["user_id"];
            $dataTicket["is_read"] = '1';
        }
        $ticket_id = $this->getDBTable("tickets")->insert($dataTicket);
        $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Ticket_Add_Success", "tickets");

        $logdata = array();
        $logdata['ticket_id'] = $ticket_id;
        $logdata['status'] = $data['status'];
        $logdata["description"] = $data['final_solution'];
        $logdata["logged_by"] = $_SESSION[$this->session_prefix]['user']['user_id'];
        $logdata["log_date"] = date("Y-m-d H:i:s");
        $this->getDBTable("ticket-action-logs")->insert($logdata, array("where" => "ticket_id = :ticket_id", "params" => array(":ticket_id" => $ticket_id)));

        if ($_POST['hid_submit'] == '1') {
            $userDetail = $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['hid_user_id'])));
            if ($userDetail['email'] != '') {
                if (\generalFunctions::isValidEmail($userDetail['email'])) {
                    $this->sendBulkEmail($userDetail['email'], $data['subject'], $data['final_solution']);
                }
            }
        }
    }

    /**
     * DU - This function is used to reply to user ticket.
     */
    function replyUserTicket() {
        $data = $_POST;

        $udata = array();
        $udata['status'] = $data['status'];
        $udata["query_template_id"] = $data['query_template_id'];
        $udata["final_solution"] = $data['final_solution'];
        $udata["updated_date"] = date("Y-m-d H:i:s");
        $this->getDBTable("tickets")->update($udata, array("where" => "ticket_id = :ticket_id", "params" => array(":ticket_id" => $data["hid_ticket_id"])));

        $logdata = array();
        $logdata['ticket_id'] = $data['hid_ticket_id'];
        $logdata['status'] = $data['status'];
        $logdata["description"] = $data['final_solution'];
        $logdata["logged_by"] = $_SESSION[$this->session_prefix]['user']['user_id'];
        $logdata["log_date"] = date("Y-m-d H:i:s");
        $this->getDBTable("ticket-action-logs")->insert($logdata, array("where" => "ticket_id = :ticket_id", "params" => array(":ticket_id" => $data["hid_ticket_id"])));

        $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Reply_Success", "tickets");
        $userDetail = $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['hid_user_id'])));
        if ($userDetail['email'] != '') {
            if (\generalFunctions::isValidEmail($userDetail['email'])) {
                $this->sendBulkEmail($userDetail['email'], $data['subject'], $data['final_solution']);
            }
        }
    }

    /**
     * DU - This function is used to get list of pending tickets
     */
    function getPendingTickets() {
        if ($this->checkLoggedInAsSuperAdmin() || $_SESSION[$this->session_prefix]['user']['assign_tickets'] == "1") {
            return $this->database->queryData("SELECT t.ticket_id,t.assigned_to, t.subject, t.comment, t.query_template_id, t.final_solution, t.created_by, t.status AS stat, t.created_date,
                    u1.usertype_id, CONCAT_WS(' ',u.first_name,u.last_name) AS uname, u.mobile_number, CONCAT_WS(' ',u1.first_name,u1.last_name) AS aname, CONCAT_WS(' ',u2.first_name,u2.last_name) AS raname, u2.usertype_id AS rusertype_id
                    FROM tickets AS t
                    INNER JOIN users AS u ON (u.user_id = t.created_by)
                    INNER JOIN users AS u1 ON (u1.user_id = t.assigned_to)
                    LEFT JOIN ticket_assignments AS ta ON (ta.ticket_id = t.ticket_id AND ta.is_deleted='0')
                    LEFT JOIN users AS u2 ON (u2.user_id = ta.reassigned_to)
                    WHERE t.status = :status
                    ORDER BY t.ticket_id DESC", array(":status" => '0'));
        }
    }

    /** 
     * DU - This function is used to get admin list.
     * @return Ambigous
     */
    function getAdminList() {
        $userTypes = array('1', '3'); // 1 - Superadmin, 3 - Admin
        $arrItems = $this->database->inClauseEntityList("item", count($userTypes));
        $arrItemsParams = $this->database->inClauseEntityParams("item", $userTypes);
        $strWhere = "usertype_id IN (" . $arrItems . ") AND deleted = :deleted AND status = :status";
        $arrItemsParams[':deleted'] = '0';
        $arrItemsParams[':status'] = '1';
        //return $this->getDBTable("users")->fetchAll(array("where" => "usertype_id = :usertype_id", "params" => array(":usertype_id" => "3")));
        return $this->getDBTable("users")->fetchAll(array("where" => $strWhere, "params" => $arrItemsParams));
    }

    /**
     * DU - This function is used to reassign tickets to other admin.
     * @param number $template_id
     */
    function reassignTicket($template_id = 0) {
        $reassignedData = $this->getDBTable("tickets")->fetchAllByFields(array("ticket_id"), array("where" => "assigned_to = :assigned_to AND status = :status", "params" => array(":assigned_to" => $_POST['reassigned_from'], ":status" => "0")));

        if (isset($reassignedData) and !empty($reassignedData)) {
            $ticketArr = array();
            foreach ($reassignedData as $data) {
                $ticketArr[] = $data['ticket_id'];
            }
            // Update already assigned ticket entries from ticket_assignments table and reassign again.
            if (isset($ticketArr) and !empty($ticketArr)) {
                $arrItems = $this->database->inClauseEntityList("item", count($ticketArr));
                $arrItemsParams = $this->database->inClauseEntityParams("item", $ticketArr);
                $strWhere = "ticket_id in (" . $arrItems . ") AND is_deleted=:is_deleted0";
                $arrItemsParams[':is_deleted0'] = '0';
                $this->getDBTable("ticket-assignments")->update(array("is_deleted" => "1"), array("where" => $strWhere, "params" => $arrItemsParams));
            }

            $assignmentRightsData = $this->getDBTable("ticket-assignment-details")->fetchRowByFields(array("ticket_assignment_detail_id"), array("where" => "user_id = :user_id AND is_deleted = :is_deleted", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":is_deleted" => "0")));
            $ticket_assignment_detail_id = 0;
            if (isset($assignmentRightsData) and !empty($assignmentRightsData)) {
                $ticket_assignment_detail_id = $assignmentRightsData['ticket_assignment_detail_id'];
            }

            foreach ($reassignedData as $data) {
                $ticketdata = array();
                $ticketdata['ticket_id'] = $data['ticket_id'];
                $ticketdata['reassigned_to'] = $_POST['reassigned_to'];
                $ticketdata['date_from'] = date('Y-m-d', strtotime($_POST['date_from']));
                $ticketdata['date_to'] = date('Y-m-d', strtotime($_POST['date_to']));
                $ticketdata["assigned_by"] = $_SESSION[$this->session_prefix]['user']['user_id'];
                $ticketdata["is_deleted"] = '0';
                $ticketdata["ticket_assignment_detail_id"] = $ticket_assignment_detail_id;
                $ticketdata["assigned_date"] = date("Y-m-d H:i:s");
                $this->getDBTable("ticket-assignments")->insert($ticketdata);
                $this->getDBTable("tickets")->update(array("is_reassigned" => "1"), array("where" => "ticket_id = :ticket_id", "params" => array(":ticket_id" => $data['ticket_id'])));
            }
        }
        $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Ticket_Assigned_Success", "tickets");
    }

    /**
     * DU - This function is used to get list of admin who has been given ticket assignment rights.
     */
    function getAssignedAdminForTicket() {
        if ($this->checkLoggedInAsSuperAdmin()) {
            return $this->database->queryData("SELECT tad.*, CONCAT(u.first_name,' ',u.last_name) as adminname, CONCAT(u1.first_name,' ',u1.last_name) as assignedbyname, u1.usertype_id FROM ticket_assignment_details AS tad
                    INNER JOIN users AS u ON (u.user_id = tad.user_id)
                    INNER JOIN users AS u1 ON (u1.user_id = tad.assigned_by)
                    ORDER BY tad.ticket_assignment_detail_id DESC");
        }
    }

    /**
     * DU - This function is used to get ticket assignment activity after getting admin rights.
     */
    function getTicketAssignmentDetails($ticket_assignment_detail_id) {
        if ($this->checkLoggedInAsSuperAdmin()) {
            return $this->database->queryData("SELECT ta.*, CONCAT(u.first_name,' ',u.last_name) as assignedbyname, CONCAT(u1.first_name,' ',u1.last_name) as reassignedtoname, CONCAT(u2.first_name,' ',u2.last_name) as belongstoname FROM ticket_assignments AS ta
                    INNER JOIN ticket_assignment_details AS tad ON (tad.ticket_assignment_detail_id = ta.ticket_assignment_detail_id)
                    INNER JOIN tickets AS t ON (t.ticket_id = ta.ticket_id)
                    INNER JOIN users AS u ON (u.user_id = ta.assigned_by)
                    INNER JOIN users AS u1 ON (u1.user_id = ta.reassigned_to)
                    INNER JOIN users AS u2 ON (u2.user_id = t.assigned_to)
                    WHERE ta.ticket_assignment_detail_id = :ticket_assignment_detail_id AND (ta.assigned_date BETWEEN tad.date_from AND tad.date_to)
                    ORDER BY ta.ticket_assignment_id DESC", array(":ticket_assignment_detail_id" => $ticket_assignment_detail_id));
        } else {
            return $this->database->queryData("SELECT ta.*, CONCAT(u.first_name,' ',u.last_name) as assignedbyname, CONCAT(u1.first_name,' ',u1.last_name) as reassignedtoname, CONCAT(u2.first_name,' ',u2.last_name) as belongstoname FROM ticket_assignments AS ta
                    INNER JOIN ticket_assignment_details AS tad ON (tad.ticket_assignment_detail_id = ta.ticket_assignment_detail_id)
                    INNER JOIN tickets AS t ON (t.ticket_id = ta.ticket_id)
                    INNER JOIN users AS u ON (u.user_id = ta.assigned_by)
                    INNER JOIN users AS u1 ON (u1.user_id = ta.reassigned_to)
                    INNER JOIN users AS u2 ON (u2.user_id = t.assigned_to)
                    WHERE ta.assigned_by = :assigned_by AND (ta.assigned_date BETWEEN tad.date_from AND tad.date_to)
                    ORDER BY ta.ticket_assignment_id DESC", array(":assigned_by" => $_SESSION[$this->session_prefix]["user"]["user_id"]));
        }
    }

    /**
     * DU - This function is used to get ticket assignment activity after getting admin rights.
     * @param unknown $ticket_id
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >
     */
    function getTicketLogDetails($ticket_id) {
        return $this->database->queryData("SELECT tal.*, CONCAT(u.first_name,' ',u.last_name) as loggedby FROM ticket_action_logs AS tal
                    INNER JOIN users AS u ON (u.user_id = tal.logged_by)
                    WHERE tal.ticket_id = :ticket_id
                    ORDER BY tal.ticket_action_log_id DESC", array(":ticket_id" => $ticket_id));
    }

    /**
     * DU - This function is used to get ticket log descritpion.
     * @param number $ticket_action_log_id
     * @return Ambigous|string
     */
    function getTicketLogDesc($ticket_action_log_id = 0) {
        $data = $this->getDBTable("ticket-action-logs")->fetchRowByFields(array("description"), array("where" => "ticket_action_log_id = :ticket_action_log_id", "params" => array(":ticket_action_log_id" => $ticket_action_log_id)));
        if (isset($data) and !empty($data)) {
            return $data['description'];
        }
        return "";
    }

    /**
     * DU - This function is used to get unread ticket count for admin.
     * @return Ambigous <unknown, mixed>
     */
    function getHeaderNotificationCount() {
        $sql = "SELECT COUNT(*) AS total FROM tickets WHERE (assigned_to = :assigned_to OR ticket_id IN (SELECT ticket_id FROM ticket_assignments
                    WHERE reassigned_to=:reassigned_to AND CURDATE() between date_from and date_to)) AND is_read=:is_read";
        $total = $this->database->getTotalFromQuery($sql, array(":assigned_to" => $_SESSION[$this->session_prefix]['user']['user_id'], ":is_read" => '0', ":reassigned_to" => $_SESSION[$this->session_prefix]['user']['user_id']));
        return $total;
    }

    /**
     * DU - This function is used to check ticket access for admins.
     * @param number $ticket_id
     * @return boolean
     */
    function checkTicketAccess($ticket_id = 0) {
        $ticket_id = \generalFunctions::decryptURL($ticket_id);
        switch ($_SESSION[$this->session_prefix]['user']['usertype_id']) {
            case '1'://superadmin
                return true;
                break;
            case '3'://admin
                $sqlticket = "SELECT COUNT(*) AS total FROM tickets WHERE assigned_to = :assigned_to AND is_reassigned=:is_reassigned AND ticket_id=:ticket_id";
                $totalTickets = $this->database->getTotalFromQuery($sqlticket, array(":assigned_to" => $_SESSION[$this->session_prefix]['user']['user_id'], ":is_reassigned" => '0', ":ticket_id" => $ticket_id));
                if ($totalTickets > 0) {
                    return true;
                }
                $sqlreassigned = "SELECT COUNT(*) AS total FROM ticket_assignments WHERE reassigned_to=:reassigned_to AND CURDATE() between date_from and date_to AND is_deleted = :is_deleted AND ticket_id=:ticket_id";
                $totalReassigned = $this->database->getTotalFromQuery($sqlreassigned, array(":reassigned_to" => $_SESSION[$this->session_prefix]['user']['user_id'], ":is_deleted" => '0', ":ticket_id" => $ticket_id));
                if ($totalReassigned > 0) {
                    return true;
                }
                break;
        }
        return false;
    }

}

<?php

namespace admin\controllers;

include_once($this->module_path."/controllers/ticketbase.php");

class ticketsController extends ticketbaseController {

    /**
     * This action is used to check access.
     */
    function init() {
        if(isset($_REQUEST['ticket_id']) and !empty($_REQUEST['ticket_id'])) {
            if(!$this->getModel("tickets")->checkTicketAccess($_REQUEST['ticket_id'])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access",'common');
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            }
        }
    }
    /**
     * indexAction is used to display list of all end users tickets.
     */
    function indexAction() {
        $this->view->hideassignedtofield = '0';

        if (!$this->checkLoggedInAsSuperAdmin()) {
            $this->view->hideassignedtofield = '1';
        }
        $this->view->tickets = $this->getModel("tickets")->getTickets();
        $this->view->status = $this->getTicketStatus();
    }

    /**
     * viewAction is used to view user tickets.
     */
    function viewAction() {
        $ticket_id = \generalFunctions::decryptURL($_GET['ticket_id']);
        if ($_POST) {
            $this->getModel("tickets")->replyUserTicket();
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/tickets");
        }
        $this->view->ticket_id = $ticket_id;
        $this->view->ticketDetails = $this->getModel("tickets")->getTicketDetails($ticket_id);
        $this->view->queryTemplates = $this->getModel("tickets")->getQueryTemplates();
        $this->view->status = $this->getTicketStatus();
    }

    /**
     * addTicketAction is used to add user ticket.
     */
    function addTicketAction() {
        if ($_POST) {
            $this->getModel("tickets")->addUserTickets();
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/tickets");
        }
        $this->view->queryTemplates = $this->getModel("tickets")->getQueryTemplates();
        $this->view->status = $this->getTicketStatus();
    }

    /**
     * getUserDetailsAction is used to get user details.
     */
    function getUserDetailsAction() {
        $arr = array();
        $arr = $this->getModel("tickets")->getUserDetails($_POST['mobile_no']);
        echo json_encode($arr);
        exit;
    }

    /**
     * getTemplateDetailsAction is used to get template details.
     */
    function getTemplateDetailsAction() {
        $arr = array();
        $arr = $this->getModel("tickets")->getTemplateDetails($_POST['template_id']);
        echo json_encode($arr);
        exit;
    }
    
    /**
     * getHeaderNotificationCountAction is used to get notification count and display message to admin if there is any new notification.
     */
    function getHeaderNotificationCountAction() {
        $notificationCount = $this->getModel("tickets")->getHeaderNotificationCount();
        if($_SESSION[$this->session_prefix]['user']['notification_count'] != $notificationCount) {
            echo $_SESSION[$this->session_prefix]['user']['notification_count'] = $notificationCount;
        } else {
            echo 'na';
        }
        exit;
    }

}

<?php

namespace admin\controllers;

include_once($this->module_path."/controllers/ticketbase.php");

class ticketReportsController extends ticketbaseController {

    /**
     * indexAction is used to display log of all admin name who has been given ticket assignment rights.
     */
    function indexAction() {
        $this->view->assignedAdmin = $this->getModel("tickets")->getAssignedAdminForTicket();
    }

    /**
     * viewTicketAssignmentsAction is used to display ticket assignment activity after getting admin rights.
     */
    function viewTicketAssignmentsAction() {
        $this->view->ticket_detail_id = $_GET['tdi'];
        $this->view->ticketAssignmentDetails = $this->getModel("tickets")->getTicketAssignmentDetails($_GET['tdi']);
    }

    /**
     * viewTicketLogsAction is used to display individual ticket logs.
     */
    function viewTicketLogsAction() {
        $this->view->ticket_id = $_GET['ti'];
        $this->view->ticket_detail_id = $_GET['tdi'];
        $this->view->ticketLogDetails = $this->getModel("tickets")->getTicketLogDetails($_GET['ti']);
        $this->view->status = $this->getTicketStatus();
    }

    /**
     * getDescriptionAction is used to get description for ticket log.
     */
    function getDescriptionAction() {
        echo $this->getModel("tickets")->getTicketLogDesc($_GET['ticket_action_log_id']);
        exit;
    }

}

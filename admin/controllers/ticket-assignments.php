<?php

namespace admin\controllers;

include_once($this->module_path."/controllers/ticketbase.php");

class ticketAssignmentsController extends ticketbaseController {

    /**
     * indexAction is used to display list of all end users pending tickets.
     */
    function indexAction() {
        if ($_POST) {
            $this->getModel("tickets")->reassignTicket();
        }
        $this->view->hideassignedtofield = '1';
        if ($this->checkLoggedInAsSuperAdmin() || $_SESSION[$this->session_prefix]['user']['assign_tickets'] == "1") {
            $this->view->hideassignedtofield = '0';
        }
        $this->view->tickets = $this->getModel("tickets")->getPendingTickets();
        $this->view->adminlist = $this->getModel("tickets")->getAdminList();
        $this->view->status = $this->getTicketStatus();

        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.confirm.min.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-datepicker.js"));
    }

}

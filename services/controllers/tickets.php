<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the the database actions related to ticket table
 */
class ticketsController extends servicesGlobalController {

    /**
     * this action is to add tikets
     */
    function addTicketAction()
    {        
        if (list($valid, $response) = $this->getModel("tickets")->validateTicketForm($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        if (list($valid, $response) = $this->getModel("tickets")->addTicket($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }        
    }
    
}

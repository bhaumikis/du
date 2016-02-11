<?php

namespace admin\controllers;

class ticketbaseController extends adminGlobalController {

    function getTicketStatus() {
        return array('0' => _l("Text_Status_Pending",'tickets'), '1' => _l("Text_Status_In_Progress",'tickets'), '2' => _l("Text_Status_Hold",'tickets'), '3' => _l("Text_Status_Resolved",'tickets'), '4' => _l("Text_Status_Closed",'tickets'));
    }

}

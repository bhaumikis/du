<?php

namespace userportal\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * User Dashboard Controller Class
 */
class dashboardController extends globalController {

    /**
     * indexAction is login action check user credentials and redirect accordingly
     */
	function indexAction() {

        $this->view->arrExpense =  $this->getModel('user-expenses')->getUserDashboardExpenses();
        $this->view->baseCurrencySymbol = $this->getModel('miscellaneous')->getCurrencySymbolById($_SESSION[$this->session_prefix]['user']["base_currency_id"]);
        $this->view->baseCurrencySymbol = (isset($this->view->baseCurrencySymbol) and !empty($this->view->baseCurrencySymbol))?$this->view->baseCurrencySymbol:$_SESSION[$this->session_prefix]['user']["base_currency_code"];
        $this->view->arrTripData = $this->getModel('user-trips')->getUserTripDetails();
        $this->view->userBaseCurrency = $this->getModel('users')->getUserBaseCurrencySymbol();
        $arrUserExpenses = $this->getModel('user-expenses')->getLast5Expenses();
        $this->view->arrExpenses = $arrUserExpenses;
    }

}

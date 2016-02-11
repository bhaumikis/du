<?php

namespace userportal\controllers;

use \generalFunctions as generalFunctions;


/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * Index Controller Class
 */
class indexController extends globalController {

    /**
     * indexAction is login action check user credentials and redirect accordingly
     */
    function indexAction() {
        if ($this->checkUserIsLoggedIn()) {
            $userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]["user"]["user_id"]);
                
            if($userDetails['password_flag'] == '1') {
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/users/reset-password");
            } else {
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            }
        }

        if ($_POST) {
            if ($this->getModel("administrators")->checkValidLogin($this->getModule())) {
                $userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]["user"]["user_id"]);
                
                if($userDetails['password_flag'] == '1') {
                    \generalFunctions::redirectToLocation($this->getModuleURL() . "/users/reset-password");
                } else {
                    \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
                }
            } else {
                generalfunctions :: redirectToLocation($this->getModuleURL() . "/index/index");
            }
        }
        $this->setTemplate("login");
    }

    /**
     * logoutAction is uynset all user sessions and redirect accordingly
     */
    function logoutAction() {
        $this->getModel("administrators")->logout();
        \generalFunctions::redirectToLocation(APPLICATION_URL);
    }

}

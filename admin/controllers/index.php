<?php
namespace admin\controllers;
/**
  \brief Index controllers contains actions for home page.
 */
class indexController extends adminGlobalController {
    /**
     * index action is used to show the default action for home page.
     */
    function indexAction() {
        if ($this->checkUserIsLoggedIn()) {
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
        }

        if ($_POST) {
            if ($this->getModel("administrators")->checkValidLogin($this->getModule())) {
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            } else {
                \generalFunctions :: redirectToLocation($this->getModuleURL() . "/index/index");
            }
        }
        $this->setTemplate("login");
    }

    /**
     * logout Action is used to do the unset of the existing user from the session
     */
    function logoutAction() {
        $this->getModel("administrators")->logout();
        if (isset($_GET["email"]) and \generalFunctions::isValidEmail($_GET["email"])) {
            \generalFunctions::redirectToLocation($this->getModuleUrl() . "/index/index/email/" . $_GET["email"]);
        }
        \generalFunctions::redirectToLocation($this->getModuleUrl());
    }
    
}

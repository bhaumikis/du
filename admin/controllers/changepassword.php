<?php

namespace admin\controllers;

class changepasswordController extends adminGlobalController {

    function indexAction() {
        if ($_POST) {

            $this->getModel("users")->changeAdminPassword();


            \generalFunctions::redirectToLocation($this->getModuleURL() . "/changepassword");
        }
    }

}
<?php
namespace admin\controllers;
/*
 * Error Message Handler Controller Class
 */
class messageController extends adminGlobalController {

    /*
     * Handle error message
     */
    function indexAction() {
        $this->view->error_message = "";
        if (isset($_SESSION[$this->session_prefix]["error_message"]) and !empty($_SESSION[$this->session_prefix]["error_message"])) {
            $this->view->error_message = $_SESSION[$this->session_prefix]["error_message"];
            $_SESSION[$this->session_prefix]["error_message"] = "";
            unset($_SESSION[$this->session_prefix]["error_message"]);
        }
    }

}

// Handle error messages in session
switch ($action) {

    default:
        $error_message = "";
        if (isset($_SESSION[$session_prefix]["error_message"]) and !empty($_SESSION[$session_prefix]["error_message"])) {
            $error_message = $_SESSION[$session_prefix]["error_message"];
            $_SESSION[$session_prefix]["error_message"] = "";
            unset($_SESSION[$session_prefix]["error_message"]);
        }
        $action_message = "";
        if (isset($_SESSION[$session_prefix]["action_message"]) and !empty($_SESSION[$session_prefix]["action_message"])) {
            $action_message = $_SESSION[$session_prefix]["action_message"];
            $_SESSION[$session_prefix]["action_message"] = "";
            unset($_SESSION[$session_prefix]["action_message"]);
        }
        break;
}
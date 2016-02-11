<?php
namespace admin\controllers;

abstract class adminGlobalController extends \commonController {

    function preActionDispatch() {

       if (($this->getOption() != "index" or $this->getAction() != "index" ) and !$this->checkUserIsLoggedIn()) {
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/index/index");
        }

        $security_question_except_action = array("users#set-security-question", "index#logout");        
        if (isset($_SESSION[$this->session_prefix]['user']['is_security_question_set']) and !in_array($this->getOption() . "#" . $this->getAction(), $security_question_except_action)) {
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/users/set-security-question");
        }
        
        /*if ($this->checkUserIsLoggedIn() and !$this->checkPermissions()) {
            $_SESSION[$this->session_prefix]["error_message"] = "Permission denied.";
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
        }*/
        
    }

    function getOrderBy($option = "index", $default = "id DESC", $fields = array()) {

        if (isset($_GET["sortby"]) and \generalFunctions::valueSet($_GET["sortby"])) {
            $sortby = trim($_GET["sortby"]);
        } elseif (isset($_SESSION[$this->session_prefix]["sortby_{$option}"]) and \generalFunctions::valueSet($_SESSION[$this->session_prefix]["sortby_{$option}"])) {
            $sortby = $_SESSION[$this->session_prefix]["sortby_{$option}"];
        } else {
            $sortby = "";
        }

        $sortby_firstchar = (isset($sortby) and \generalFunctions::valueSet($sortby)) ? substr($sortby, 0, -1) : "";
        $sortby_secondchar = (isset($sortby) and \generalFunctions::valueSet($sortby)) ? substr($sortby, -1) : "";

        if ($sortby_firstchar and $sortby_secondchar and in_array($sortby_firstchar, array_keys($fields))) {
            if (strtolower($sortby_secondchar) == "d") {
                $order_by = $fields[$sortby_firstchar]["field"] . " DESC";
            } else {
                $order_by = $fields[$sortby_firstchar]["field"] . " ASC";
            }
            $_SESSION[$this->session_prefix]["sortby_{$option}"] = $sortby_firstchar . $sortby_secondchar;
        } else {
            $order_by = $default;
        }

        return array($sortby, $order_by);
    }

    function setPage($option = "index") {
        if (!isset($_GET["page"]) or !\generalFunctions::valueSet($_GET["page"]) and isset($_SESSION[$this->session_prefix]["page_{$option}"])) {
            $_GET["page"] = $_SESSION[$this->session_prefix]["page_{$option}"];
        } elseif (isset($_GET["page"]) and \generalFunctions::valueSet($_GET["page"])) {
            $_SESSION[$this->session_prefix]["page_{$option}"] = trim($_GET["page"]);
        }
    }

}

<?php

namespace userportal\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * Global Controller Class
 */
abstract class globalController extends \commonController {

    /*
     * Call Predisaptch event before action call
     */
    function preActionDispatch() {
    	
        define("DATE_FORMAT", $_SESSION[$this->session_prefix]['user']['user_date_format']);
        define("DATE_TIME_FORMAT", DATE_FORMAT." h:i:s A");
        
        if (($this->getOption() != "index" or $this->getAction() != "index" ) and !$this->checkUserIsLoggedIn()) {
        	if(isset($_POST["is_locale_set"]) && isset($_POST["client_date"]) && isset($_POST["client_timezone"])) {
        		$_SESSION[$this->session_prefix]['user']['client_locale'] = array ("date"=>$_POST["client_date"], "timezone"=>$_POST["client_timezone"]);
        	}
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/index/index");
        }


        /* if ($this->checkUserIsLoggedIn() and !$this->checkPermissions()) {
          $_SESSION[$this->session_prefix]["error_message"] = _l("PERMISSION_DENIED", "common");
          \generalFunctions::redirectToLocation($this->getModuleURL() . "/users/dashboard");
          } */

        /* if ($this->checkUserIsLoggedIn()) {
          $this->view->menu_applications = $this->getModel("applications")->getApplicationListForLeft();
          $this->view->app_modules = $this->getModel("modules")->listAppModulesForLeft();
          $this->view->xeditable_permissions = ($_SESSION[$this->session_prefix]['user']['usertype_id'] == SOLUTION_ADMIN) ? 1 : 0;
          } */

//        if (file_exists($this->getModulePath() . "/whitelablels/config/" . $_SESSION[$this->session_prefix]['user']['user_id'] . ".json")) {
//            $this->view->whiteLables = json_decode(file_get_contents($this->getModulePath() . "/whitelablels/config/" . $_SESSION[$this->session_prefix]['user']['user_id'] . ".json"));
//            $this->view->logo_file = $this->view->whiteLables->logo;
//            $this->view->configFolder = $_SESSION[$this->session_prefix]['user']['user_id'];
//        } else {
//            $this->view->whiteLables = json_decode(file_get_contents($this->getModulePath() . "/whitelablels/config/" . "default.json"));
//            $this->view->logo_file = $this->view->whiteLables->logo;
//            $this->view->configFolder = "default";
//        }
    }

    /*
     * Default order by on each page
     */
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

    /*
     * Set Page No.
     */
    function setPage($option = "index") {
        if (!isset($_GET["page"]) or !\generalFunctions::valueSet($_GET["page"]) and isset($_SESSION[$this->session_prefix]["page_{$option}"])) {
            $_GET["page"] = $_SESSION[$this->session_prefix]["page_{$option}"];
        } elseif (isset($_GET["page"]) and \generalFunctions::valueSet($_GET["page"])) {
            $_SESSION[$this->session_prefix]["page_{$option}"] = trim($_GET["page"]);
        }
    }

    /*
     * Method call after the action performed
     */
    function postActionDispatch() {
        $except_profile = array("users#my-profile", "users#change-password","users#change-security-question","users#change-base-currency","users#edit-profile","users#upload-temporary-image");
        if (isset($_SESSION[$this->session_prefix]['profile_access']) and $this->checkUserIsLoggedIn() and !in_array($this->getOption() . "#" . $this->getAction(), $except_profile)) {
            unset($_SESSION[$this->session_prefix]['profile_access']);
        }
    }

}

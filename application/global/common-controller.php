<?php

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * Common Controller
 */
abstract class commonController extends superGlobal {

    var $view;
    var $template = "default";
    private $module;
    private $option;
    private $action;
    private $module_path;
    private $module_url;

    function __construct() {
        parent::__construct();
    }

    /**
     * @param string $module_path
     */
    function setModulePath($module_path = "") {
        $this->module_path = $module_path;
    }

    /**
     * @param string $module_url
     */
    function setModuleURL($module_url = "") {
        $this->module_url = $module_url;
    }

    /**
     * @return string
     */
    function getModulePath() {
        return $this->module_path;
    }

    /**
     * @return string
     */
    function getModuleURL() {
        return $this->module_url;
    }

    /**
     * @param string $module
     */
    function setModule($module = "") {
        $this->module = $module;
    }

    /**
     * @return string
     */
    function getModule() {
        return $this->module;
    }

    /**
     * @param string $option
     */
    function setOption($option = "") {
        $this->option = $option;
    }

    /**
     * @return string
     */
    function getOption() {
        return $this->option;
    }

    /**
     * @param string $action
     */
    function setAction($action = "") {
        $this->action = $action;
    }

    /**
     * @return string
     */
    function getAction() {
        return $this->action;
    }

    /**
     * @param string $template
     */
    function setTemplate($template = "default") {
        $this->template = $template;
    }

    /**
     * Set View object
     * @param string $action
     * @param string $option
     * @param string $module
     */
    function setView($action = "", $option = "", $module = "default") {

        if ($this->view == null) {
            require_once(APPLICATION_PATH . "/application/view/view.php");
            $this->view = new view();
        }

        if ($action) {
            $this->view->action = $action;
        }

        if ($option) {
            $this->view->option = $option;
        }

        if ($module) {
            $this->view->module = $module;
        }
    }

    /**
     * Check permission of the request
     * @param array $data
     * @return boolean
     */
    function checkPermissions($data = array()) {

        if (!$this->checkUserIsLoggedIn()) {
            die("Permission check : User is not logged in.");
        }

        if ($this->checkLoggedInAsSuperAdmin()) {
            return true;
        }

        $module = (isset($data["module"]) and !empty($data["module"])) ? $data["module"] : $this->getModule();
        $option = (isset($data["option"]) and !empty($data["option"])) ? $data["option"] : $this->getOption();
        $action = (isset($data["action"]) and !empty($data["action"])) ? $data["action"] : $this->getAction();
        $title = (isset($data["title"]) and !empty($data["title"])) ? $data["title"] : "";

        $key_all_actions = "***";

        if (!empty($title)) {
            $key = md5($title);
        } else {
            $key = md5($module . $option . $action);
            $key_all_actions = md5($module . $option . "*");
        }

        if (in_array($key, unserialize($_SESSION[$this->session_prefix]["user"]["access"])) or
                in_array($key_all_actions, unserialize($_SESSION[$this->session_prefix]["user"]["access"]))) {
            return true;
        }

        return false;
    }

}

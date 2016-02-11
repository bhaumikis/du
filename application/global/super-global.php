<?php

/*
 * superGlobal Class
 * Global Declaration and utility of application
 */
abstract class superGlobal {

    var $used_models_by_request;
    var $session_prefix;

    function __construct() {
        $this->session_prefix = $GLOBALS["session_prefix"];
    }

    /**
     * @param string $key
     * @param string $value
     */
    function setSession($key = "key", $value = "") {
        $_SESSION[$this->session_prefix][$key] = $value;
    }

    /**
     * @param string $key
     */
    function getSession($key = "key") {
        return $_SESSION[$this->session_prefix][$key];
    }

    /**
     * @param string $key
     */
    function unsetSession($key = "key") {
        unset($_SESSION[$this->session_prefix][$key]);
    }

    /**
     * @param string $key
     * @return boolean
     */
    function checkSessionKeyExists($key = "key") {
        $exist = false;

        if (isset($_SESSION[$this->session_prefix][$key])) {
            $exist = true;
        }

        return $exist;
    }

    /**
     * Destroy Session
     */
    function destroySession() {
        session_destroy();
    }

    /**
     * getModel Object
     * @param string $model
     * @param string $require_new_object
     * @return modelObject
     */
    function getModel($model = "", $require_new_object = false) {

        if ($require_new_object == true or !isset($this->used_models_by_request[$model])) {
            include_once(APPLICATION_PATH . "/models/" . $model . ".php");
            $model_class_name = generalFunctions::convertToActionName($model) . "Model";
            $model_class_name = "model\\" . $model_class_name;
            $model_obj = new $model_class_name;
            $this->used_models_by_request[$model] = $model_obj;
        } else {
            $model_obj = $this->used_models_by_request[$model];
        }
        
        return $model_obj;
    }

    /**
     * Perform action from the rout, and render the view file
     * @param string $option
     * @param string $action
     * @param string $module
     * @param unknown $params
     * @return string
     */
    function action($option = "", $action = "", $module = "default", $params = array()) {

        if (isset($params) and is_array($params)) {
            foreach ($params as $k => $v) {
                $_REQUEST[$k] = $v;
                $_GET[$k] = $v;
            }
        }

        $frontcontroller = frontController::getNewInstance(array("option" => $option, "action" => $action, "module" => $module));

        $frontcontroller->run();

        list($view, $module, $option, $action, $module_path, $module_url, $option_class_name, $action_name, $obj) = $frontcontroller->getPropertiesDetails();

        $session_prefix = $module;

        ob_start();

        if ($middle = $module_path . "/view/middle/" . $view->option . "/" . $view->action . ".php" and !file_exists($middle)) {
            \generalFunctions::error404();
        }

        include($middle);

        $opt = ob_get_clean();

        unset($frontcontroller);

        return $opt;
    }

    /**
     * Perform action from the rout, and render the view template file
     * @param string $option
     * @param string $action
     * @param string $module
     * @param unknown $params
     * @param string $template
     * @return string
     */
    function actionWithTemplate($option = "", $action = "", $module = "default", $params = array(),$template = "") {

        if (isset($params) and is_array($params)) {
            foreach ($params as $k => $v) {
                $_REQUEST[$k] = $v;
                $_GET[$k] = $v;
            }
        }

        $frontcontroller = frontController::getNewInstance(array("option" => $option, "action" => $action, "module" => $module));

        $frontcontroller->run();

        list($view, $module, $option, $action, $module_path, $module_url, $option_class_name, $action_name, $obj) = $frontcontroller->getPropertiesDetails();

        $session_prefix = $module;

        ob_start();

        if ($middle = $module_path . "/view/middle/" . $view->option . "/" . $view->action . ".php" and !file_exists($middle)) {
            \generalFunctions::error404();
        }
        
        include($module_path."/view/templates/".$template.".php");

        $opt = ob_get_clean();

        unset($frontcontroller);

        return $opt;
    }
    
    /**
     * Forward the action
     * @param string $option
     * @param string $action
     * @param string $module
     * @param unknown $params
     */
    function forward($option = "", $action = "", $module = "default", $params = array()) {

        if (isset($params) and is_array($params)) {
            foreach ($params as $k => $v) {
                $_REQUEST[$k] = $v;
                $_GET[$k] = $v;
            }
        }

        frontController::$fc_obj = null;

        $frontcontroller = frontController::getInstance();

        $frontcontroller->setModule($module);
        $frontcontroller->setController($option);
        $frontcontroller->setAction($action);

        $frontcontroller->run();
    }

    /**
     * Check User is Logged in or not
     * @return boolean
     */
    function checkUserIsLoggedIn() {
        if (isset($_SESSION[$this->session_prefix]["user"]) and !empty($_SESSION[$this->session_prefix]["user"])) {
            return true;
        }
        return false;
    }

    /**
     * Check User is Logged in as super admin or not
     * @return boolean
     */
    function checkLoggedInAsSuperAdmin() {
        if ($_SESSION[$this->session_prefix]["user"]["usertype_id"] == 1) {
            return true;
        }
        return false;
    }

    /**
     * Check user is ordinary user or not
     * @return boolean
     */
    function isUserOrdinary() {
        if ($_SESSION[$this->session_prefix]['user']['usertype_id'] == ORDINARY_USER) {
            return true;
        }

        return false;
    }

}

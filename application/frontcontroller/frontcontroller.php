<?php

/** 
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * frontControllerInterface
 * define the pattern of the MVC
 */
interface frontControllerInterface {

    /**
     * Set Module Class
     * @param unknown $module
     */
    public function setModule($module);

    /**
     * Set Controller class
     * @param unknown $controller
     */
    public function setController($controller);

    /**
     * Set action
     * @param unknown $action
     */
    public function setAction($action);

    /**
     * Set Request Parameters
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * Process the Request 
     */
    public function run();
}

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * frontController Class
 * 
 */
class frontController implements frontControllerInterface {

    const DEFAULT_CONTROLLER = "index";
    const DEFAULT_ACTION = "index";
    const DEFAULT_MODULE = "default";

    protected $controller = self::DEFAULT_CONTROLLER;
    protected $action = self::DEFAULT_ACTION;
    protected $module = self::DEFAULT_MODULE;
    protected $module_url = "";
    protected $module_path = "";
    protected $contoller_obj = "";
    public static $fc_obj = null;

    /**
     * Get FrontController Class instance
     * @return frontController
     */
    static function getInstance() {
        if (self::$fc_obj == null) {
            self::$fc_obj = new frontController();
        }

        return self::$fc_obj;
    }

    /**
     * Get FrontController class instance with options parameters
     * @param unknown $options
     * @return frontController
     */
    static function getNewInstance($options = array()) {
        if (!isset($options) or empty($options)) {
            die("Options are not passed");
        }

        return new frontController($options);
    }

    private function __construct(array $options = array()) {
        if (empty($options)) {
            $this->parseUri($_SERVER["REQUEST_URI"]);
        } else {
            if (isset($options["module"])) {
                $this->setModule($options["module"]);
            }
            if (isset($options["option"])) {
                $this->setController($options["option"]);
            }
            if (isset($options["action"])) {
                $this->setAction($options["action"]);
            }
            if (isset($options["params"])) {
                $this->setParams($options["params"]);
            }
        }
    }

    /**
     * Parse Uri and process the request
     * @param string $request_uri
     * @return boolean
     */
    protected function parseUri($request_uri = "") {

        global $application_modules;

        $request_uri = str_replace(array("/" . PROJECT_ROOT . "/"), "", $request_uri);
        $request_uri = preg_replace("/^\//", "", $request_uri);
        $request_uri = preg_replace("/\?(.*)/", "", $request_uri);

        if (preg_match("/index\.php/i", $request_uri)) {

            if (isset($_REQUEST["module"]) and !empty($_REQUEST["module"])) {
                $this->setModule($_REQUEST["module"]);
            } else {
                $this->setModule(self::DEFAULT_MODULE);
                $_GET["module"] = $_REQUEST["module"] = self::DEFAULT_MODULE;
            }
            if (isset($_REQUEST["option"]) and !empty($_REQUEST["option"])) {
                $this->setController($_REQUEST["option"]);
            } else {
                $this->setController(self::DEFAULT_CONTROLLER);
                $_GET["option"] = $_REQUEST["option"] = self::DEFAULT_CONTROLLER;
            }
            if (isset($_REQUEST["action"]) and !empty($_REQUEST["action"])) {
                $this->setAction($_REQUEST["action"]);
            } else {
                $this->setAction(self::DEFAULT_ACTION);
                $_GET["action"] = $_REQUEST["action"] = self::DEFAULT_ACTION;
            }

            return true;
        }

        $request_uri = configurations::routers($request_uri);

        $urlparams = array();

        if (isset($request_uri) and strlen(trim($request_uri))) {
            $urlparams = explode("/", $request_uri);
        }

        if (isset($urlparams) and is_array($urlparams) and count($urlparams)) {
            if (!in_array($urlparams[0], $application_modules)) {
                $urlparams = array_merge(array("default"), $urlparams);
            }
        }

        foreach ($urlparams as $k => $v) {
            switch ($k) {
                case 0:
                    if (isset($v) and !empty($v)) {
                        $_GET["module"] = $v;
                        $_REQUEST["module"] = $v;
                        $this->module = $v;
                    }
                    break;
                case 1:
                    if (isset($v) and !empty($v)) {
                        $_GET["option"] = $v;
                        $_REQUEST["option"] = $v;
                        $this->controller = $v;
                    }
                    break;
                case 2:
                    if (isset($v) and !empty($v)) {
                        $_GET["action"] = $v;
                        $_REQUEST["action"] = $v;
                        $this->action = $v;
                    }
                    break;
                default:
                    if ($k % 2 == 0) {
                        $_GET[$urlparams[$k - 1]] = $v;
                        $_REQUEST[$urlparams[$k - 1]] = $v;
                    } else {
                        $_GET[$v] = "";
                        $_REQUEST[$v] = "";
                    }
                    break;
            }
        }

        $this->setModule($this->module);
        $this->setController($this->controller);
        $this->setAction($this->action);
    }

    /* (non-PHPdoc)
     * @see frontControllerInterface::setModule()
     */
    public function setModule($module) {
        $this->module = $module;

        if (isset($this->module) and !empty($this->module) and ($this->module != "default")) {
            $this->module_url = APPLICATION_URL . "/" . $this->module;
            $this->module_path = APPLICATION_PATH . "/" . $this->module;
        } else {
            $this->module_url = APPLICATION_URL;
            $this->module_path = APPLICATION_PATH;
        }
    }

    /* (non-PHPdoc)
     * @see frontControllerInterface::setController()
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /* (non-PHPdoc)
     * @see frontControllerInterface::setAction()
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /* (non-PHPdoc)
     * @see frontControllerInterface::setParams()
     */
    public function setParams(array $params) {
        $this->params = $params;
    }

    /**
     * Get Module
     * @return Ambigous <string, unknown, unknown>
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * Get controller
     * @return Ambigous <string, unknown, unknown>
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Get Action
     * @return Ambigous <string, unknown, unknown>
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Get Request Parameters
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Convert to Action name
     * @param string $keyword
     * @return Ambigous <string, unknown>
     */
    public static function convertToActionName($keyword = "") {
        $keyword = str_replace("_", "-", $keyword);
        $keywords = explode("-", $keyword);
        $keyword = "";
        for ($i = 0; $i < count($keywords); $i++) {
            if ($i == 0) {
                $keyword .= $keywords[$i];
            } else {
                $keyword .= ucfirst($keywords[$i]);
            }
        }

        return $keyword;
    }

    /**
     * Revert to action name
     * @param string $keyword
     * @return mixed
     */
    public static function revertToActionName($keyword = "") {
        $keyword = preg_replace_callback("/[A-Z]/", create_function(
                        '$matches', 'return "-".strtolower($matches[0]);'
                ), $keyword);

        return $keyword;
    }

    /* (non-PHPdoc)
     * @see frontControllerInterface::run()
     */
    public function run() {
        
        global $namespaces;    

        require_once(APPLICATION_PATH . "/application/global/super-global.php");
        require_once(APPLICATION_PATH . "/application/global/common-controller.php");

        if ($this->module == "default") {
            require_once($this->module_path . "/controllers/global-controller.php");
        } else {
            require_once($this->module_path . "/controllers/" . $this->module . "-global-controller.php");
        }

        require_once(APPLICATION_PATH . "/models/global-model.php");

        if (!file_exists($this->module_path . "/controllers/" . $this->controller . ".php")) {
            generalFunctions::error404();
        }

        require_once($this->module_path . "/controllers/" . $this->controller . ".php");

        $contoller_name = self::convertToActionName($this->controller) . "Controller";
        
        $contoller_name = $namespaces[$this->module]."\controllers\\".$contoller_name;
        
        $contoller_obj = new $contoller_name;
        $contoller_obj->setModule($this->module);
        $contoller_obj->setOption($this->controller);
        $contoller_obj->setAction($this->action);
        $contoller_obj->setModulePath($this->module_path);
        $contoller_obj->setModuleURL($this->module_url);
        $contoller_obj->setView($this->action, $this->controller, $this->module);

        $contoller_obj->session_prefix = $this->module;
        $GLOBALS["session_prefix"] = $this->module;

        if (method_exists($contoller_obj, "preActionDispatch")) {
            $contoller_obj->preActionDispatch();
        }
        if (method_exists($contoller_obj, "init")) {
            $contoller_obj->init();
        }

        $action_name = generalFunctions::convertToActionName($this->action) . "Action";

        if (!method_exists($contoller_obj, $action_name)) {
            generalFunctions::error404();
        }

        $contoller_obj->$action_name();

        if (method_exists($contoller_obj, "postActionDispatch")) {
            $contoller_obj->postActionDispatch();
        }

        $this->contoller_obj = $contoller_obj;
    }

    /**
     * Get class's all properties details
     * @return multitype:string NULL Ambigous <string, unknown, unknown> Ambigous <string, unknown, unknown> Ambigous <string, unknown> 
     */
    function getPropertiesDetails() {
        return array($this->contoller_obj->view,
            $this->module,
            $this->controller,
            $this->action,
            $this->module_path,
            $this->module_url,
            self::convertToActionName($this->controller) . "Controller",
            generalFunctions::convertToActionName($this->action) . "Action",
            $this->contoller_obj
        );
    }

}

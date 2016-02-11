<?php

require_once(APPLICATION_PATH . "/application/frontcontroller/frontcontroller.php");

frontController::getInstance()->run();

list($view, $module, $option, $action, $module_path, $module_url, $option_class_name, $action_name, $obj) = frontController::getInstance()->getPropertiesDetails();

$session_prefix = $module;

if ($middle = $module_path . "/view/middle/" . $view->option . "/" . $view->action . ".php" and !file_exists($middle)) {
    generalFunctions::error404();
}
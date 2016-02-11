<?php
require(dirname(__FILE__))."/application/config/configurations.php";
require(APPLICATION_PATH . "/application/config/config-operations.php");
require(APPLICATION_PATH . "/application/global/generalfunctions.php");
require(APPLICATION_PATH . "/application/global/super-global.php");
require(APPLICATION_PATH . "/application/db/db_table.php");
include(APPLICATION_PATH . "/models/global-model.php");

function loadModel($model = "") {
	include_once(APPLICATION_PATH . "/models/" . $model . ".php");
	$model_class_name = \generalFunctions::convertToActionName($model) . "Model";
	$model_class_name = "model\\" . $model_class_name;
	return new $model_class_name;
}
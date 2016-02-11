<?php
require("config/configurations.php");
require(APPLICATION_PATH . "/lib/easyDebug/general.php");
require(APPLICATION_PATH . "/application/config/config-operations.php");
require(APPLICATION_PATH . "/application/global/globalactions.php");
include(APPLICATION_PATH . "/application/global/generalfunctions.php");
require(APPLICATION_PATH . "/application/core/bootstrap.php");
require($module_path . "/view/templates/" . $obj->template . ".php");
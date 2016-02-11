<?php

session_start();

set_time_limit(0);

error_reporting(E_ALL & ~E_NOTICE);

//date_default_timezone_set("Asia/Kolkata");
date_default_timezone_set("UTC");

define("PROJECT_TITLE", "Dailyuse");
define("VERSION", "1.1");

define("PROTOCOL", ((isset($_SERVER["HTTPS"]) and strtolower($_SERVER["HTTPS"]) == "on") ? "https" : "http"));
define("DOMAIN_NAME", $_SERVER['HTTP_HOST']);
define("PROJECT_ROOT", "dujen");

define("APPLICATION_URL", PROTOCOL . "://" . DOMAIN_NAME . ((strlen(PROJECT_ROOT)) ? "/" . PROJECT_ROOT : ""));
define("APPLICATION_PATH", dirname(dirname(dirname(__FILE__))));

define("ORDINARY_USER", 2);
/*  // Original setting
define("DB_HOST", "10.12.43.198");
define("DB_USERNAME", "user");
define("DB_PASSWORD", "user");
*/
define("DB_HOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");


define("DB_NAME", "dailyuse");
define("DB_PORT", "3306");
define("DB_PCONNECT", "false");
define("DB_DRIVER", "pdo_mysql");

$application_modules = array("admin", "services");
$namespaces = array("default" => "userportal", "admin" => "admin", "services" => "services");

include(APPLICATION_PATH . "/application/lib/paging/ps_pagination.php");

define("HTML_FILE_PATH", APPLICATION_PATH . "/images/pdf/summarypdf.html");
define("PDF_FILE_PATH", APPLICATION_PATH . "/images/pdf/summarypdf.pdf");

/* HTML to PDF Conversion - wkhtmltopdf */
if (preg_match("/linux/i", PHP_OS)) {
    define("PDF_CREATOR_PATH", APPLICATION_PATH . '/lib/wkhtmltopdf/wkhtmltopdf'); //For Linux
} else {
    define("PDF_CREATOR_PATH", APPLICATION_PATH . '/lib/wkhtmltopdf/wkhtmltopdf.exe'); //For Windows(Local)
}


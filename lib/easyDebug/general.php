<?php

/*
 * Author: Bhaumik Patel | bhaumik.patel@infostretch.com
 * Easy and Quick Debug Function library
 */

/*
 * Print Object / Array
 */
function p($obj, $exit = 1) {
    print "<pre>";
    print_r($obj);
    print "</pre>";
    if ($exit)
        die;
}

/*
 * Print Class name of the object and its Methods
 */
function m($obj, $exit = 1) {
    print "<pre>";
    print get_class($obj);
    print_r(get_class_methods(get_class($obj)));
    print "</pre>";
    if ($exit)
        die;
}

/*
 * Print response in the json / xml
 */
function generateResponse($response_array = array(), $type = "response", $response_type = "json") {

    $response = array();

    //$response["response"]["#attributes"]["timestamp"] = (string) time();
    //$response["response"]["#attributes"]["error"] = 0;

    switch ($type) {
        case "error":
            // $response["response"]["#attributes"]["error"] = 1;
            $response["status"] = $response_array;
            break;
        default:
            //$response["response"]["results"] = array();
            $response = array();
            foreach ($response_array as $k => $v) {
                //$response["response"]['results'][$k] = $v;
                $response[$k] = $v;
            }

            //if ($service_static_parameters = $this->getModel("services")->getServiceStaticParams($this->method)) {
            //$response["response"]["extra_parameters"] = $service_static_parameters;
            //}

            break;
    }

    switch ($response_type) {
        case "text/xml":
            require_once (APPLICATION_PATH . "/lib/dom20/dom20.php");
            $response_content = DOM::arrayToXMLString($response["response"]);
            break;
        case "application/json":
        default:
            $response_content = json_encode($response);
            break;
    }

    header('Content-type: ' . $response_type);


    echo $response_content;
    exit;
}

/*
 * When server down / if any specific fatal error, it handles the error
 */
register_shutdown_function("fatal_handler");
function fatal_handler()
{
    $error = error_get_last();
    // fatal error, E_ERROR === 1
    if ($error['type'] === E_ERROR) { 
        $errors = array();
        if(stristr($_SERVER['REQUEST_URI'], 'services') || stristr($_SERVER['REQUEST_URI'], 'api')) {
            ob_clean();
            $errors[] = array("err_code" => "99", "err_msg" => _l("Internal Error.", "services"));
            generateResponse($errors, "error");
        }
    }
}

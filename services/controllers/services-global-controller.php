<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class contains routing logic and respective functions of the web service module 
 */
abstract class servicesGlobalController extends \commonController {

    private $request_headres = array();
    protected $params = array();
    private $response_content_type = "application/json";
    protected $user = array();
    protected $method = "";
    protected $timestamp = "";
    protected $service_headers = array();

    /**
     * Skip Token authentication as per the defined controller and action here. 
     * @return multitype:string 
     */
    function getSkipTokenCheckList() {
        $skip_token_checks = array(
            "users#login",
            "users#logout",
            "users#register",
            "miscellaneous#get-country-list",
            "miscellaneous#get-currencies-list",
            "miscellaneous#get-security-question",
            "miscellaneous#get-default-data",
            "miscellaneous#check-currency-rate-status",
            "users#forgotpassword",
            "tickets#add-ticket",
        	"wiki#index",
        	"wiki#dump-struct",
        );

        return $skip_token_checks;
    }

    /**
     * This function is called before the action perform. 
     */
    function preActionDispatch() {

        $this->method = $this->getAction();

        $this->request_headres = getallheaders();

        if (!isset($this->request_headres["Content-Type"]) or empty($this->request_headres["Content-Type"])) {
            $this->request_headres["Content-Type"] = "application/x-www-form-urlencoded";
        }

        
        if ($_REQUEST['response_type'] == "json") {
            $this->request_headres["Content-Type"] = "application/json";
        }

        switch ($this->request_headres["Content-Type"]) {
            case "application/json":
            case "application/json; charset=UTF-8":
                $this->setJSONParams();
                break;
            case "text/xml":
                $this->setXMLParams();
                break;
            default:
                $this->setPostParams();
                break;
        }
        
    	if(isset($this->params["timestamp"])) { // Globally Convert miliseconds to seconds
        	$this->params["timestamp"] = ($this->params["timestamp"] / 1000);
        }
        /*
        if(isset($this->params["expense_date"])) { // Globally Convert miliseconds to seconds
        	$this->params["expense_date"] = ($this->params["expense_date"] / 1000);
        }
        if(isset($this->params["trip_date_from"])) { // Globally Convert miliseconds to seconds
        	$this->params["trip_date_from"] = ($this->params["trip_date_from"] / 1000);
        }
        if(isset($this->params["trip_date_to"])) { // Globally Convert miliseconds to seconds
        	$this->params["trip_date_to"] = ($this->params["trip_date_to"] / 1000);
        }
        */
        
        
        if(isset($this->params["local_timezone"])) {
        	define("SERVICE_LOCAL_TIMEZONE", $this->params["local_timezone"]);
        }else {
        	define("SERVICE_LOCAL_TIMEZONE", '+00:00');
        }
        if (isset($this->params["response_content_type"]) and in_array($this->params["response_content_type"], array("text/xml", "application/json"))) {
            $this->response_content_type = $this->params["response_content_type"];
        }

        if (isset($this->params["response_content_type"])) {
            unset($this->params["response_content_type"]);
        }

        /* validate user token */
        $skip_token_checks = $this->getSkipTokenCheckList();

        $optaction = $this->getOption() . "#" . $this->getAction();

        $this->getModel('services')->flushExpireToken();

        if (!in_array($optaction, $skip_token_checks)) {
            if (!isset($this->params['token']) or empty($this->params['token'])) {
                $this->generateResponse(array(array("message" => _l("Please enter token.", "services"), "code" => 109)), "error");
            }

            if (list($valid, $response) = $this->getModel("services")->validateToken(trim($this->params['token'])) and !$valid) {
                $this->generateResponse($response, "error");
            }

            $this->user = $response;
            $this->params["user_application_id"] = $this->user["application_id"];
            
            define("DATE_FORMAT", $this->getModel("users")->getUserDateFormat($this->user['user_id'],"date_format"));
            define("DATE_TIME_FORMAT", DATE_FORMAT." h:i:s A");
            
        }
        /* End validate user token */

        /* set timestamp */

        if (isset($this->params["timestamp"]) and !empty($this->params["timestamp"])) {
            if (list($valid, $response) = $this->getModel("services")->validateTimeStamp($this->params["timestamp"]) and $valid) {
                $this->timestamp = $this->params["timestamp"];
            } else {
                $this->generateResponse($response, "error");
            }
        }

        /* end set timestamp */
    }

    /**
     * Set Post Parameters of the web service 
     */
    private function setPostParams() {

        foreach ($_POST as $k => $v) {
            $this->params[$k] = $v;
        }
    }

    /**
     * Set the requested XML parameters to the array 
     */
    private function setXMLParams() {

        $request_content = file_get_contents("php://input");
        /* $request_content = '<?xml version="1.0" encoding="utf-8"?><params><email>jitesh@yahoo.com</email><password>jitesh</password></params>'; */

        if ($doc = DOMDocument::loadXML($request_content)) {
            $params = $this->xmlToArray($doc);
            $this->params = $params["params"];
        }

        unset($doc);
    }

    /**
     * Set the requested Json parameters to the array 
     */
    private function setJSONParams() {
        $temp = file_get_contents("php://input", true);
        $params = json_decode(file_get_contents("php://input"), true);

        if (isset($params) and is_array($params)) {
            foreach ($params as $k => $v) {
                $this->params[$k] = $v;
            }
        }
    }

    /**
     * Convert the XML data to the array
     * @param DOMNode $node
     * @return Ambigous <NULL>|multitype:NULL |Ambigous <multitype:NULL multitype:Ambigous <multitype:>  , multitype:NULL >
     */
    public function xmlToArray(DOMNode $node = null) {
        $result = array();
        $group = array();
        $attrs = null;
        $children = null;

        if ($node->hasAttributes()) {
            $attrs = $node->attributes;
            foreach ($attrs as $k => $v) {
                $result[$v->name] = $v->value;
            }
        }

        $children = $node->childNodes;

        if (!empty($children)) {
            if ((int) $children->length === 1) {
                $child = $children->item(0);

                if ($child !== null && $child->nodeType === XML_TEXT_NODE) {
                    $result['#value'] = $child->nodeValue;
                    if (count($result) == 1) {
                        return $result['#value'];
                    } else {
                        return $result;
                    }
                }
            }

            for ($i = 0; $i < (int) $children->length; $i++) {
                $child = $children->item($i);

                if ($child !== null) {
                    if (!isset($result[$child->nodeName])) {
                        $result[$child->nodeName] = $this->xmlToArray($child);
                    } else {
                        if (!isset($group[$child->nodeName])) {
                            $result[$child->nodeName] = array($result[$child->nodeName]);
                            $group[$child->nodeName] = 1;
                        }
                        $result[$child->nodeName][] = $this->xmlToArray($child);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Generate the web service response in the requested format (XML / JSON)
     * @param unknown $response_array
     * @param string $type
     * @param string $response_type
     */
    protected function generateResponse($response_array = array(), $type = "response", $response_type = "") {

        $response = array();

        if (isset($this->service_headers) and !empty($this->service_headers)) {
            foreach ($this->service_headers as $header_key => $header_value) {
                $response["response"]["#attributes"][$header_key] = $header_value;
            }
        }

        $response["response"]["#attributes"]["timestamp"] = ((string) time()) * 1000;

        $response["response"]["#attributes"]["error"] = 0;

        switch ($type) {
            case "error":
                $response["response"]["#attributes"]["error"] = 1;
                $response["response"]["errors"] = $response_array;
                break;
            default:
                $response["response"]["results"] = array();
                foreach ($response_array as $k => $v) {
                    $response["response"]['results'][$k] = $v;
                }

                if ($service_static_parameters = $this->getModel("services")->getServiceStaticParams($this->method)) {
                    $response["response"]["extra_parameters"] = $service_static_parameters;
                }

                break;
        }
        if ($response_type != "") {
            $this->response_content_type = $response_type;
        }
        switch ($this->response_content_type) {
            case "text/xml":
                require_once (APPLICATION_PATH . "/lib/dom20/dom20.php");
                $response_content = DOM::arrayToXMLString($response["response"]);
                break;
            case "application/json":
            default:
                $response_content = json_encode($response);
                break;
        }

        header('Content-type: ' . $this->response_content_type);
        $this->logRequest($response_content);
        echo $response_content;
        exit;
    }

    /**
     * Set the service header in the request data
     * @param string $key
     * @param string $value
     */
    function setServiceHeader($key = "", $value = "") {
        $this->service_headers[$key] = $value;
    }

    /**
     * Log the Request data into the database.
     * @param unknown $output
     */
    function logRequest($output) {
        $this->getModel("miscellaneous")->logService($output, $this->params);
    }
}

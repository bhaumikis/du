<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * apiHelpModel Class
 */
class apiHelpModel extends globalModel {

    /**
     * get all Active web services
     * @return type
     */
    function getList() {
        $rsResult = $this->getDBTable("api-help")->fetchAllByFields(array("id","name","source"), array("where" => "status = 1"));

        foreach ($rsResult as $arrData) {
            $arrFinal[$arrData['id']] = $arrData;
        }
        return $arrFinal;
    }
    
    /**
     * Get Details of the web service
     * @return Ambigous
     */
    function getData($id) {
    	$arrData = $this->getDBTable("api-help")->find($id);
    	foreach($arrData as $k=>$v) {
    		$arrData[$k] = trim($v);
    	}
    	$arrData["request_data"] = str_ireplace("'","\'",$arrData["request_data"]);
    	$arrData["response_data"] = str_ireplace("'","\'",$arrData["response_data"]);
    	$arrData["request_data_other"] = str_ireplace("'","\'",$arrData["request_data_other"]);
    	$arrData["response_data_other"] = str_ireplace("'","\'",$arrData["response_data_other"]);
    	return $arrData;
    }
}

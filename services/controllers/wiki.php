<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * Wiki Controller Class
 */
class wikiController extends servicesGlobalController {

	/**
	 * List all the web services 
	 */
	public function indexAction()
	{
		//$json = '{"mobile_number":"9033235750","password":"Admin123#"}';		var_dump(json_decode($json)); die;
		$this->view->arrList = $this->getModel('api-help')->getList();
		if(isset($_REQUEST["id"])) {
			//$this->view->addExtraCSS(array("path"=>"../css/jjsonviewer.css"));
			$this->view->arrDetail = $this->getModel('api-help')->getData($_REQUEST["id"]);
			// Request and Response Structure data
			$this->view->arrStruct = $this->getModel('api-help-details')->getList($_REQUEST["id"]);
		}
	}
	
	public function dumpStructAction()
	{
		$this->getModel('api-help-details')->dumpStructFromSampleData();
		die;
	}
}

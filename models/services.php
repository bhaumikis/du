<?php

namespace model;

/**
 *
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief services Model contains application logic for various functions and database operations of services Module.
 */
class servicesModel extends globalModel {
	
	/**
	 * This function will remove the device token if they expires
	 */
	function flushExpireToken() {
		$this->getDBTable ( "device-tokens" )->delete ( "last_updated_date <= '" . date ( "Y-m-d H:i:s", time () - 24 * 3600 ) . "'" );
	}
	
	/**
	 * This function is used to validate the token value
	 * 
	 * @param
	 *        	$token
	 * @return array
	 */
	function validateToken($token = '') {
		$errors = array ();
		if ($device_token = $this->getDBTable ( "device-tokens" )->fetchRow ( "token = '" . $token . "'" )) {
			
			$this->getDBTable ( "device-tokens" )->update ( array (
					"last_updated_date" => date ( "Y-m-d H:i:s" ) 
			), "device_token_id = '" . $device_token ["device_token_id"] . "'" );
			$user = $this->getDBTable ( "users" )->fetchRow ( "user_id = '" . $device_token ["user_id"] . "'" );
			return array (
					true,
					$user 
			);
		} else {
			$errors = array (
					array (
							"message" => _l ( "Invalid token or token is expired.", "services" ),
							"code" => 125 
					) 
			);
			return array (
					false,
					$errors 
			);
		}
	}
	
	/**
	 * This function is used to get the static parameters for services based on methos name
	 * 
	 * @param
	 *        	$method
	 * @return $params
	 */
	function getServiceStaticParams($method = "") {
		$paramslist = $this->getDBTable ( "services-extra-parameters" )->fetchAll ( "method = '" . $method . "'" );
		$params = array ();
		foreach ( $paramslist as $param ) {
			if (! in_array ( $param ["type"], array_keys ( $param ) )) {
				$params [$param ["type"]] = array ();
			}
			$params [$param ["type"]] [$param ["parameter"]] = $param ["value"];
		}
		
		return $params;
	}
	
	/**
	 * Validate Timestamp value
	 * 
	 * @param number $timestamp        	
	 * @return multitype:boolean multitype:multitype:number Ambigous <unknown, Ambigous> |multitype:boolean multitype:
	 */
	function validateTimeStamp($timestamp = 0) {
		if (! \generalFunctions::checkIntPositive ( $timestamp )) {
			$errors = array (
					array (
							"message" => _l ( "Invalid Timestamp.", "services" ),
							"code" => 146 
					) 
			);
			return array (
					false,
					$errors 
			);
		}
		
		return array (
				true,
				array () 
		);
	}
}

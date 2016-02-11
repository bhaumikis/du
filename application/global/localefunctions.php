<?php

/**
 * Validate timestamp in miliseconds
 * @param number $timestamp
 * @return boolean[]
 */
function validateTimeStampMS($timestamp = 0) {
	$services = new model\servicesModel();
	return $services->validateTimeStamp( $timestamp / 1000 );
}

/**
 * Get Date / Time From Mili Second timestamp
 * @param number $timestamp
 * @param string $type
 */
function getTimestampMSFormat($timestamp = 0, $type = 'MYSQL_DATETIME') {
	$timestamp = $timestamp / 1000;
	switch ($type) {
		case "MYSQL_DATE" :
			return date("Y-m-d", $timestamp);
			break;
		case "MYSQL_TIME" :
			return date("H:i:s", $timestamp);
			break;
		case "MYSQL_DATETIME" :
			return date("Y-m-d H:i:s", $timestamp);
			break;
	}
}

/**
 * Get Current Date / Time as per the format requested
 * @param string $type
 * @return string
 */
function getNow($type = "MYSQL_DATETIME", $tz="", $param = "") {
	$date = new DateTime('NOW');
	if($param!='') $date->modify( $param );
	if($tz!="") {
		$tz = new DateTimeZone($tz);
		$date->setTimezone($tz);
	}
	switch ($type) {
		case "MYSQL_DATE" :
			return $date->format("Y-m-d");
			break;
		case "MYSQL_TIME" :
			return $date->format("H:i:s");
			break;
		case "MYSQL_DATETIME" :
			return $date->format("Y-m-d H:i:s");
			break;
		case "MYSQL_TIMESTAMP" :
			return $date->getTimestamp();
			break;
		case "MYSQL_TIMESTAMP_MS" :
			return $date->getTimestamp() * 1000;
			break;
		case "UNIQUE_DATETIME" :
			return $date->format("YmdHis");
			break;
	}
}

function getFormat($type="MYSQL_DATETIME") {
	switch ($type) {
		case "MYSQL_DATE" :
			return ("Y-m-d");
			break;
		case "MYSQL_TIME" :
			return ("H:i:s");
			break;
		case "MYSQL_DATETIME" :
			return ("Y-m-d H:i:s");
			break;
		case "MYSQL_TIMESTAMP" :
			return ("U");
			break;
		case "MYSQL_TIMESTAMP_MS" :
			return ("U")* 1000;
			break;
		case "UNIQUE_DATETIME" :
			return ("YmdHis");
			break;
	}
}

function getNowLocal($type = "MYSQL_DATETIME", $param = "") {
	$tz = $_SESSION[$GLOBALS["session_prefix"]]['user']['client_locale']['timezone'];
	return getNow($type, $tz, $param);
}

function getNowUtc($type = "MYSQL_DATETIME", $param = "") {
	return getNow($type, "+00:00", $param);
}


function getNowLocalRange($type="START", $param = "") {
	switch ($type) {
		case "START":
			return getNowLocal("MYSQL_DATE", $param)." 00:00:00";
			break;
		case "END":
			return getNowLocal("MYSQL_DATE", $param)." 23:59:00";
			break;
	}
}

function utcToLocal($date, $format) {

}
function localToUtcMS($date, $format) {
	$tzName = $_SESSION[$GLOBALS["session_prefix"]]['user']['client_locale']['timezone'];
	$tz = new DateTimeZone($tzName);
	$date = new DateTime($date.' '.$tzName);
	$date->setTimezone(new DateTimeZone('+00:00'));
	return $date->getTimestamp() * 1000;
}
function localToUtc($date, $format) {
	$tzName = $_SESSION[$GLOBALS["session_prefix"]]['user']['client_locale']['timezone'];
	$tz = new DateTimeZone($tzName);
	$date = new DateTime($date.' '.$tzName);
	$date->setTimezone(new DateTimeZone('+00:00'));
	return  $date->format(getFormat($format));
}

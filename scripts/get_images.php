<?php
include 'configure_modules.php';

$countries = $db->queryData("SELECT * FROM countries");
echo "<pre>";
foreach($countries as $country)
{
    $strSavePath = APPLICATION_PATH."/images/countries/";
    
    $strFileName = $country['flag'];
    
    $strFullSavePath = $strSavePath.$strFileName;
    
    $strImagePath = "http://www.sicherheitstacho.eu/static/images/country/";
    
    $strImageName = strtolower($country['flag']);
    
    $strFullImagePath = $strImagePath.$strImageName;
    
    save_image($strFullImagePath,$strFullSavePath);
}

die;

function save_image($img,$fullpath){
$ch = curl_init($img);
$fp = fopen($fullpath, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);
}
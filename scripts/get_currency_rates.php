<?php
include 'configure_modules.php';

$strURL = "http://openexchangerates.org/api/latest.json?app_id=30ed12af3118411fb05eb3290f5f8781";


$strData = getPageData($strURL);

$arrData = json_decode($strData);



foreach($arrData->rates as $currency => $rates){
    $data = array();
    $data['currency'] = $currency;
    $data['rate'] = $rates;
    $data['date'] = date('Y-m-d');
    $db->insert('exchange_rates_new', $data);    
}




echo "<pre>";print_r($arrData);die;

function getPageData($strURL)
{
    
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $strURL); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);      
        
        return $output;
}


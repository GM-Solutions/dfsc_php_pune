<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of REST_Common
 *
 * @author pavaningalkar
 */
class Common {
    public function __construct()
    {
//    parent::__construct();
        
    }
    public static function sendSMS($sms_dtl =  array()) {
        /*get country*/
        $CI = & get_instance();
        $country = !empty($CI->post('country')) ? $CI->post('country') : "india";        
        $all_group = $CI->config->item('db_group');
        $country = (array_key_exists($country, $all_group)) ? $country : "india";

        $sms_setting = $CI->config->item('sms');
        
        switch ($country) {
            case "india":
                $parameters = "aid=".$sms_setting[$country]['aid']."&pin=".$sms_setting[$country]['pin']."&signature=".$sms_setting[$country]['signature']."&mnumber=".$sms_dtl['mobile_no']."&message=".$sms_dtl['message'];
		$apiurl = $sms_setting[$country]['message_url'];
		$ch = curl_init($apiurl);		

		curl_setopt($ch, CURLOPT_POST,0);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);		

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_HEADER,0);
		// DO NOT RETURN HTTP HEADERS 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
		// RETURN THE CONTENTS OF THE CALL
		$return_val = curl_exec($ch);
                
                break;
            
            case "uganda":


                break;
        }
        
    }
    public static function get_country(){
        $CI = & get_instance();
        $country = !empty($CI->post('country')) ? $CI->post('country') : "india";        
        $all_group = $CI->config->item('db_group');
        $country = (array_key_exists($country, $all_group)) ? $country : "india";
        return $country;
    }
}

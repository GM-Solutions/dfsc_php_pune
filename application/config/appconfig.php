<?php

$setting_type = "local"; //  $setting_type=  local  /dev / qa/ production 
/* countries list */
$config['countries'] = array(
//    'india' => array('key' => 'india', 'lable' => 'India (+91)', 'mobile_validation' => 10, 'flag' => 'india.png', 'base_url' => 'india', 'code' => '91'),
    'uganda' => array('key' => 'uganda', 'lable' => 'Uganda (+256)', 'mobile_validation' => 9, 'flag' => 'uganda.png', 'base_url' => 'uganda', 'code' => '256'),
    'kenya' => array('key' => 'kenya', 'lable' => 'Kenya (+254)', 'mobile_validation' => 9, 'flag' => 'kenya.png', 'base_url' => 'kenya', 'code' => '254'),
    'bangladesh' => array('key' => 'bangladesh', 'lable' => 'Bangladesh (+880)', 'mobile_validation' => 10, 'flag' => 'bangladesh.png', 'base_url' => 'bangladesh', 'code' => '880'),
    'egypt' => array('key' => 'egypt', 'lable' => 'Egypt (+20)', 'mobile_validation' => 10, 'flag' => 'egypt.png', 'base_url' => 'egypt', 'code' => '20'),
);

/* language configuration */
$config['language'][0] = array('key' => 'en','lable' => 'English  English');
$config['language'][1] = array('key' => 'hi','lable' => 'Hindi    हिंदी');
$config['language'][2] = array('key' => 'ar','lable' => 'Arabic   عربى');

/* list of group available */
$config['db_group']['india'] = 'default';
$config['db_group']['uganda'] = 'bajajib';
$config['db_group']['kenya'] = 'kenya';
$config['db_group']['bangladesh'] = 'bangladesh';
$config['db_group']['egypt'] = 'egypt';

/*country role menu config*/
$config['role_menu']['india'][0] = array('key'=>'asc','value'=>'Authorise Service Center','username'=>TRUE);
$config['role_menu']['india'][1] = array('key'=>'dealer','value'=>'Dealers','username'=>TRUE);
$config['role_menu']['india'][2] = array('key'=>'service_advisor','value'=>'Service Advisors','username'=>FALSE);

$config['role_menu']['uganda'][0] = array('key'=>'main_country_dealer','value'=>'Main Country Dealer','username'=>TRUE);
$config['role_menu']['uganda'][1] = array('key'=>'dealer','value'=>'Dealers','username'=>TRUE);
$config['role_menu']['uganda'][2] = array('key'=>'service_advisor','value'=>'Service Advisors','username'=>FALSE);
$config['role_menu']['uganda'][3] = array('key'=>'sales_executive','value'=>'Sales Executive','username'=>FALSE);

$config['role_menu']['kenya'][0] = array('key'=>'main_country_dealer','value'=>'Main Country Dealer','username'=>TRUE);
$config['role_menu']['kenya'][1] = array('key'=>'dealer','value'=>'Dealers','username'=>TRUE);
$config['role_menu']['kenya'][2] = array('key'=>'service_advisor','value'=>'Service Advisors','username'=>FALSE);
$config['role_menu']['kenya'][3] = array('key'=>'sales_executive','value'=>'Sales Executive','username'=>FALSE);

$config['role_menu']['bangladesh'][0] = array('key'=>'main_country_dealer','value'=>'Distributor','username'=>TRUE);
$config['role_menu']['bangladesh'][1] = array('key'=>'dealer','value'=>'Dealers','username'=>TRUE);
$config['role_menu']['bangladesh'][2] = array('key'=>'service_advisor','value'=>'Service Advisors','username'=>FALSE);
$config['role_menu']['bangladesh'][3] = array('key'=>'sales_executive','value'=>'Sales Executive','username'=>FALSE);

$config['role_menu']['egypt'][0] = array('key'=>'main_country_dealer','value'=>'Distributor','username'=>TRUE);
$config['role_menu']['egypt'][1] = array('key'=>'dealer','value'=>'Dealers','username'=>TRUE);
$config['role_menu']['egypt'][2] = array('key'=>'service_advisor','value'=>'Service Advisors','username'=>FALSE);
$config['role_menu']['egypt'][3] = array('key'=>'sales_executive','value'=>'Sales Executive','username'=>FALSE);
/* sms configuration */
$config['sms']['india']['aid'] = '640811';
$config['sms']['india']['pin'] = 'ba124';
$config['sms']['india']['message_url'] = 'http://httpapi.zone:7501/failsafe/HttpLink';
$config['sms']['india']['signature'] = 'BJAJFS';

/* for india testing */
$config['sms']['kenya']['aid'] = '640811';
$config['sms']['kenya']['pin'] = 'ba124';
$config['sms']['kenya']['message_url'] = 'http://httpapi.zone:7501/failsafe/HttpLink';
$config['sms']['kenya']['signature'] = 'BJAJFS';

$config['sms']['bangladesh']['aid'] = '640811';
$config['sms']['bangladesh']['pin'] = 'ba124';
$config['sms']['bangladesh']['message_url'] = 'http://httpapi.zone:7501/failsafe/HttpLink';
$config['sms']['bangladesh']['signature'] = 'BJAJFS';

$config['sms']['uganda']['username'] = 'gladminds';
$config['sms']['uganda']['Apikey'] = '61a4c1083c72d913bc9481c91f610006147c51a759cc03a172e65bae92817c79';
$config['sms']['uganda']['message_url'] = 'https://api.africastalking.com/restless/send';
$config['sms']['uganda']['signature'] = 'BAJFSC';


/* $aid = "640810";
  $pin = "cvl@123";
  $signature = "CVLYTY";
  $message = "";
  $parameters = "aid=".$aid."&pin=".$pin."&signature=".$signature."&mnumber=".$mobile."&message=".$message; */
/* curl API Base URL */
if ($setting_type == "local") {
    $config['curl_api']['india'] = "http://local.bajaj.gladminds.co:8000/v1/messages";    
    $config['curl_api_custom']['india'] = "http://local.bajaj.gladminds.co:8000/aftersell/register/register_customer_api/";    
    $config['curl_api']['uganda'] = "http://127.0.0.1:8000/v1/messages";
    $config['curl_api']['kenya'] = "http://127.0.0.1:8000/v1/messages";
    $config['curl_api']['egypt'] = "http://54.158.156.249/v1/messages";
}

//if ($setting_type == "dev") {
//    $config['curl_api']['india'] = "http://local.bajaj.gladminds.co:8000/v1/messages";    
//    $config['curl_api_custom']['india'] = "http://local.bajaj.gladminds.co:8000/aftersell/register/register_customer_api/";    
//    $config['curl_api']['uganda'] = "http://127.0.0.1:8000/v1/messages";
//    $config['curl_api']['kenya'] = "http://127.0.0.1:8000/v1/messages";
//}

if ($setting_type == "qa") {
    $config['curl_api']['india'] = "http://qa.bajaj.gladminds.co/v1/messages";
    $config['curl_api_custom']['india'] = "http://qa.bajaj.gladminds.co/aftersell/register/register_customer_api/";
    $config['curl_api']['uganda'] = "http://qa.bajajib.gladminds.co/v1/messages";
    $config['curl_api']['kenya'] = "http://kfsc-qa-web.us-east-1.elasticbeanstalk.com/v1/messages";
    $config['curl_api']['bangladesh'] = "http://qabfsc.gladminds.co/v1/messages";
    $config['curl_api']['egypt'] = "http://54.158.156.249/v1/messages";
}

if ($setting_type == "production") {
    $config['curl_api']['india'] = "http://127.0.0.1:8000/v1/messages";
    $config['curl_api_custom']['india'] = "http://local.bajaj.gladminds.co:8000/aftersell/register/register_customer_api/";
    $config['curl_api']['uganda'] = "http://bajajib.gladminds.co/v1/messages";
    $config['curl_api']['kenya'] = "http://kfsc.gladminds.co/v1/messages";
}



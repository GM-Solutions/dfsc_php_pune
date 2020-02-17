<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* controler for india */

class Transaction extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->load->model("Users");
    }

    public function search_customer_post() {
        $data =  array();
        $search = $this->post('search');
        $filter = $this->post('filter');
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Kampala'));
        $filter_data = array();
        switch ($filter) {
            case 'engine_no': 
                $filter_data['engine'] = $search;
                
                break;
            case 'chassis': 
                $filter_data['product_id'] = $search;
                
                break;
            case 'veh_reg_no': 
                $filter_data['veh_reg_no'] = $search;
                
                break;
            case 'customer_id':
                
                $filter_data['customer_id'] = $search;
                $filter_data['customer_phone_number !='] = "";
                break;
            case 'mobile_no': 
                
                $filter_data['customer_id !='] = "";
                $filter_data['customer_phone_number'] = $search;
                break;
        }

        $product_info = $this->Users->select_info('gm_productdata', $filter_data);
        /*((1, 'Unused'), (2, 'Closed'),
         * ( 3, 'Expired'), (4, 'In Progress'), 
         * ( 5, 'Exceeds Limit'), (6, 'Closed Old Fsc'),(7,'Without UCN'))*/
        if($product_info){
            foreach ($product_info as $key => $value) {
                $info = array();
                $data['customer_details'][$key]['engine_no'] = $value['engine'];
//                $data['customer_details'][$key]['veh_reg_no'] = !empty($value['veh_reg_no']) ? $value['veh_reg_no'] :"";
                $data['customer_details'][$key]['customer_id'] = !empty($value['customer_id']) ? $value['customer_id'] :"";
                $data['customer_details'][$key]['mobile_no'] = !empty($value['customer_phone_number']) ? $value['customer_phone_number'] : "";
                $data['customer_details'][$key]['customer_name'] = !empty($value['customer_name']) ? $value['customer_name'] : "";
                $data['customer_details'][$key]['register_customer'] = !empty($value['customer_id']) ? FALSE :TRUE;
                $product_id = $value['id'];
                $coupon_info =$this->Users->select_info('gm_coupondata', array('product_id'=>$product_id));
               
                foreach ($coupon_info as $key1 => $value1) {
                    $info[$key1]['status']=$value1['status'];
                    $info[$key1]['closed_date']=!empty($value1['closed_date']) ? $value1['closed_date'] : "";
                    $info[$key1]['mark_expired_on']=!empty($value1['mark_expired_on']) ? $value1['mark_expired_on'] : "";
                    $info[$key1]['service_type']=!empty($value1['service_type']) ? $value1['service_type'] : "";
                }
                $send_false = TRUE;

                foreach ($info as $key1 => $value1) {
                    if((($value1['status'] == 1 ) && ($now->format('Y-m-d')<= date('Y-m-d',strtotime($value1['mark_expired_on']) ) ) &&  empty($value1['closed_date'])) || $value1['status'] == 4)
                    {
                        $data['customer_details'][$key]['service_detail']['service_status'] =  TRUE;
                        $data['customer_details'][$key]['service_detail']['label'] =  "Go For Service".$value1['service_type'];
                        $send_false=FALSE;
                        break;
                    }
                }
                
                if($send_false){
                    $data['customer_details'][$key]['service_detail']['service_status'] =  FALSE;
                    $data['customer_details'][$key]['service_detail']['label'] =  "No Service";
                }
            }
            $data['status']=TRUE;            
        } else{            
        $data['status']=FALSE;
        $data['message']="No customer details found";
        }

        $this->set_response($data, REST_Controller::HTTP_ACCEPTED); 
    }
    public function service_status_post() {
		$country_dtl = $this->config->item('countries');
        $data =  array();
		$country = $this->post('country');
        $search = $this->post('search');
        $filter = $this->post('filter');
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Africa/Kampala'));
        $filter_data = array();
        switch ($filter) {
            case 'chassis': 
                $filter_data['pd.product_id'] = $search;
                
                break;
            case 'engine_no': 
                $filter_data['pd.engine'] = $search;
                
                break;
            case 'veh_reg_no': 
                $filter_data['pd.veh_reg_no'] = $search;
                
                break;
            case 'customer_id':
                
                $filter_data['pd.customer_id'] = $search;
                $filter_data['pd.customer_phone_number !='] = "";
                break;
            case 'mobile_no': 
                $numlength = strlen((string)$search);
				if($numlength == $country_dtl[$country]['mobile_validation']){
					$search = $country_dtl[$country]['code']."".$search;
				}
				log_message('debug',print_r($search,TRUE));
				
                $filter_data['pd.customer_id !='] = "";
                $filter_data['pd.customer_phone_number'] = $search;
                break;
        }
        
        $product_info = $this->Users->service_status_info($filter_data);
//        print_r($product_info); 
        $op = $op_raw =  array();
        if($product_info){
        foreach ($product_info as $key => $value) {
            $op_raw[$value['engine']]['engine_no'] = $value['engine'];
            $op_raw[$value['engine']]['chassis'] = "";
            $op_raw[$value['engine']]['veh_reg_no'] = "";
            $op_raw[$value['engine']]['customer_id'] = $value['customer_id'];
            $op_raw[$value['engine']]['coupon'][$key]['unique_service_coupon'] = $value['unique_service_coupon'];
            $op_raw[$value['engine']]['coupon'][$key]['service_type'] = $value['service_type'];
            $op_raw[$value['engine']]['coupon'][$key]['status'] = $value['status'];
            
        }
        $i=0;
        foreach ($op_raw as $key => $value) {
            $op['service_status'][$i]['engine_no']=$value['engine_no'];
            $op['service_status'][$i]['chassis']=$value['chassis'];
            $op['service_status'][$i]['veh_reg_no']=$value['veh_reg_no'];
            $op['service_status'][$i]['customer_id']=$value['customer_id'];
            foreach ($value['coupon'] as $key_coupon => $value_coupon) {
                $op['service_status'][$i]['coupon'][$key_coupon]['unique_service_coupon']=$value_coupon['unique_service_coupon'];
                $op['service_status'][$i]['coupon'][$key_coupon]['service_type']=$value_coupon['service_type'];
                $status = $value_coupon['status'];
                switch ($value_coupon['status']){
                    case 1:   $status = 'Unused';  break;
                    case 2:   $status = 'Closed';  break;
                    case 3:   $status = 'Expired';  break;
                    case 4:   $status = 'In Progress';  break;
                    case 5:   $status = 'Exceeds Limit';  break;
                    case 6:   $status = 'Closed Old Fsc';  break;
                    case 7:   $status = 'Without UCN';  break;            
                }
                $op['service_status'][$i]['coupon'][$key_coupon]['status']=$status;
            }
            $i++;
        }
        }
        
        $data['service']= $op;
        $data['status']= (count($op) > 0) ? TRUE : FALSE;
        $data['message']=(!$data['status']) ? "No data Found":"";
        
        
        /*((1, 'Unused'), (2, 'Closed'),
         * ( 3, 'Expired'), (4, 'In Progress'), 
         * ( 5, 'Exceeds Limit'), (6, 'Closed Old Fsc'),(7,'Without UCN'))*/
        
        
       

        $this->set_response($data, REST_Controller::HTTP_ACCEPTED); 
    }
    
    public function checkCoupon_post() {
        
        $country = $this->post('country');
        $mobile_no = $this->post('mobile_no'); /* App User mobile No */
        $customer_id = $this->post('customer_id');
		$engine_no = $this->post('engine_no');
        $km = $this->post('km');
        log_message('debug',print_r($this->post(),TRUE));
		$vehical_no = "";
		$product_info = FALSE;
		
		if(!empty($engine_no)){
			$engine_no = $engine_no;
			$product_info = TRUE;
		} else if(!empty($customer_id)){
			$product_info = $this->Users->select_rows("*",'gm_productdata', array('customer_id'=>$customer_id));
			if(isset($product_info)){
			$engine_no = $product_info->engine;
			}else {
				$this->set_response([
                        'status'=> FALSE,
                        'message'=> "Please check input details"                    
                    ], REST_Controller::HTTP_ACCEPTED);
				return true;
			}
		}
        log_message('debug',print_r($product_info,TRUE));
		if(isset($product_info)){
            $engine_no = isset($product_info->engine) ? $product_info->engine : $engine_no ;
            $curl_url = $this->config->item('curl_api');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$curl_url[$country]."?format=json");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        "phoneNumber=".$mobile_no."&text=A ".$engine_no." ".$km);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);            
            $server_output = curl_exec ($ch);
            if($server_output == FALSE){                
                $this->set_response([
                    'status'=> FALSE,
                    'message'=>curl_error($ch)." Sorry We are processing your request"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            } else{
                $json = json_decode($server_output, true);
                
                if(array_key_exists('status', $json)){
                        $this->set_response([
                        'status'=> $json['status'],
                        'message'=>$json['message']                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                } else{
                        $this->set_response([
                        'status'=> FALSE,
                        'message'=>$json['message']                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                }                
            }            
            curl_close ($ch);
        }
        
    }
    public function sku_code_post() {
        $country_dtl = $this->config->item('countries');
        $country = $this->post('country');
        
        $sku_dtl = $this->Users->select_info('gm_skudetails');
        $op =  array();
        if($sku_dtl){
            foreach ($sku_dtl as $key => $value) {
              $data[$key]['sku_code'] =  $value['sku_code'];  
              $data[$key]['sku_description'] =  $value['sku_description'];  
            }
            $op['status']=  TRUE;
            $op['data']=  $data;
        }else{
            $op['status']=FALSE;
            $op['message']="No SKU data";
        }
        $this->set_response($op, REST_Controller::HTTP_ACCEPTED); 
    }
    public function customer_registration_post() {
		$country_dtl = $this->config->item('countries');
		
        $country = $this->post('country');
        $veh_reg_no = $this->post('veh_reg_no');
        $sku_code = $this->post('sku_code');
        $engine_no = $this->post('engine_no');
        $mobile_no = $this->post('mobile_no'); /* App User mobile No */
        $owner_mobile_no = $this->post('owner_mobile_no'); /* Customer mobile No */
        $owner_name = $this->post('owner_name');        
        $purchase_date = $this->post('purchase_date');		
		$purchase_date = str_replace('/','-',$purchase_date);
		
		$numlength = strlen((string)$owner_mobile_no);
		if($numlength == $country_dtl[$country]['mobile_validation']){
			$owner_mobile_no = $country_dtl[$country]['code']."".$owner_mobile_no;
		}
		

		log_message('debug',print_r($this->post(),TRUE));
        
        if(empty($sku_code) || empty($engine_no) || empty($owner_mobile_no) || empty($owner_name) || empty($purchase_date)){
            $this->set_response([
                    'status'=> FALSE,
                    'message'=>"please provide all details"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            return TRUE;  
        }
        /* register customer API */
        
            
            $curl_url = $this->config->item('curl_api');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$curl_url[$country]."?format=json");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        "phoneNumber=".$mobile_no."&text=O ".$engine_no." ".$owner_name." ".ltrim($owner_mobile_no, '880')." ".$purchase_date." 880 ".$sku_code);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);            
            $server_output = curl_exec ($ch);
            if($server_output == FALSE){                
                $this->set_response([
                    'status'=> FALSE,
                    'message'=>curl_error($ch)." Sorry We are processing your request"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            } else{
                $json = json_decode($server_output, true);
                
                if(array_key_exists('status', $json)){
/*if($json['message'] == "Invalid registration number or purchase date. Please check and try again. If unsuccessful, please contact customer support 0880300000"){
$json['message'] = "customer register sucessfully";
$json['status'] = TRUE;
}*/
                        $this->set_response([
                        'status'=> $json['status'],
                        'message'=>$json['message']                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                } else{
                        $this->set_response([
                        'status'=> FALSE,
                        'message'=>$json['message']                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                }                
            }            
            curl_close ($ch);
        
        
    }
    
    public function rider_registration_post() {
		$country_dtl = $this->config->item('countries');
		
        $country = $this->post('country');
        $veh_reg_no = $this->post('veh_reg_no');
        $mobile_no = $this->post('mobile_no'); /* App User mobile No */
        $rider_mobile_no = $this->post('rider_mobile_no'); /* Customer mobile No */
        $rider_name = $this->post('rider_name');
		
		$numlength = strlen((string)$rider_mobile_no);
		if($numlength == $country_dtl[$country]['mobile_validation']){
			$rider_mobile_no = $country_dtl[$country]['code']."".$rider_mobile_no;
		}
		
        
        
        if(empty($veh_reg_no) || empty($rider_mobile_no) || empty($rider_name)){
            $this->set_response([
                    'status'=> FALSE,
                    'message'=>"please provide all details"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            return TRUE;  
        }
        /* register customer API */
        
            
            $curl_url = $this->config->item('curl_api');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$curl_url[$country]."?format=json");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        "phoneNumber=".$mobile_no."&text=R ".$veh_reg_no." ".$rider_name." ".$rider_mobile_no);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);            
            $server_output = curl_exec ($ch);
            
            if($server_output == FALSE){                
                $this->set_response([
                    'status'=> FALSE,
                    'message'=>curl_error($ch)." Sorry We are processing your request"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            } else{
                $json = json_decode($server_output, true);
                
                if(array_key_exists('status', $json)){
                        $this->set_response([
                        'status'=> $json['status'],
                        'message'=>$json['message']                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                } else{
                        $this->set_response([
                        'status'=> FALSE,
                        'message'=>$json['message']                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                }                
            }            
            curl_close ($ch);        
    }
    
    public function close_coupon_post() {
        $country = $this->post('country');
        $ucn = $this->post('ucn');
        $mobile_no = $this->post('mobile_no');
        if(empty($ucn)){
            $this->set_response([
                    'status'=> FALSE,
                    'message'=>"please provide all details"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            return TRUE;  
        }
        /*coupon  close API */
        $curl_url = $this->config->item('curl_api');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$curl_url[$country]."?format=json");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        "phoneNumber=".$mobile_no."&text=C ".$ucn);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);            
            $server_output = curl_exec ($ch);
            if($server_output == FALSE){                
                $this->set_response([
                    'status'=> FALSE,
                    'message'=>curl_error($ch)." Sorry We are processing your request"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            } else{
                $json = json_decode($server_output, true);
                $this->set_response([
                    'status'=> $json['status'],
                    'message'=>$json['message']                    
                ], REST_Controller::HTTP_ACCEPTED); 
            }            
            curl_close ($ch);
        
    }
	public function report_service_count_post() {
        $country = $this->post('country');
        $user_id = $this->post('user_id');
        $group = $this->post('group');
        $date_from = $this->post('date_from');
        $date_to = $this->post('date_to');
        
            if(
                empty($country) || 
                empty($user_id) || 
                empty($group) || 
                empty($date_from) || 
                empty($date_to) || 
                empty($country) 
                ){
                    $this->set_response([
                           'status'=> FALSE,
                           'message'=>"please provide all details"                    
                       ], REST_Controller::HTTP_ACCEPTED);   
               return TRUE;
                }
                
                /* check  for user id is dealer or MCD*/
                /*for dealer*/
                $delaers_info = $this->Users->select_info('gm_dealer', array('user_id'=>$user_id));
                if($delaers_info){
                    $query = $this->db->query("(SELECT 
                        COUNT(*) AS count, 'register_customer_count' AS count_type
                    FROM
                        gm_productdata AS pd
                    WHERE
                        pd.customer_registration_by_user_id = ".$user_id."
                            AND customer_registration_date BETWEEN '".$date_from."' AND '".$date_to."') UNION (SELECT 
                        COUNT(*) AS count, 'check_coupon_count' AS count_type
                    FROM
                        gm_coupondata AS cd
                            LEFT JOIN
                        gm_serviceadvisor AS sa ON sa.user_id = cd.service_advisor_id
                    WHERE
                        cd.actual_service_date BETWEEN '".$date_from."' AND '".$date_to."'
                            AND sa.dealer_id = ".$user_id."
                            AND sa.main_country_dealer_id IS NULL) UNION (SELECT 
                        COUNT(*) AS count, 'close_coupon_count' AS count_type
                    FROM
                        gm_coupondata AS cd
                            LEFT JOIN
                        gm_serviceadvisor AS sa ON sa.user_id = cd.service_advisor_id
                    WHERE
                        cd.closed_date BETWEEN '".$date_from."' AND '".$date_to."'
                            AND sa.dealer_id = ".$user_id."
                            AND sa.main_country_dealer_id IS NULL);");
                     $dlr_info = $query->result_array();
                     if(count($dlr_info)){
                         $this->set_response([
                    'status'=> TRUE,
                    'data'=>$dlr_info                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                     }else{
                         $this->set_response([
                    'status'=> FALSE,
                    'message'=>"Invalid user"                    
                    ], REST_Controller::HTTP_ACCEPTED);
                     }
                     return TRUE;
                }
                /*for main country dealer */
                $mcdelaers_info = $this->Users->select_info('gm_maincountrydealer', array('user_id'=>$user_id));
                if($mcdelaers_info){
                    $query = $this->db->query("(SELECT 
                        COUNT(*) AS count, 'register_customer_count' AS count_type
                    FROM
                        gm_productdata AS pd
                    WHERE
                        pd.customer_registration_by_user_id = ".$user_id."
                            AND customer_registration_date BETWEEN '".$date_from."' AND '".$date_to."') UNION (SELECT 
                        COUNT(*) AS count, 'check_coupon_count' AS count_type
                    FROM
                        gm_coupondata AS cd
                            LEFT JOIN
                        gm_serviceadvisor AS sa ON sa.user_id = cd.service_advisor_id
                    WHERE
                        cd.actual_service_date BETWEEN '".$date_from."' AND '".$date_to."'
                            AND sa.main_country_dealer_id = ".$user_id."
                            AND sa.dealer_id IS NULL) UNION (SELECT 
                        COUNT(*) AS count, 'close_coupon_count' AS count_type
                    FROM
                        gm_coupondata AS cd
                            LEFT JOIN
                        gm_serviceadvisor AS sa ON sa.user_id = cd.service_advisor_id
                    WHERE
                        cd.closed_date BETWEEN '".$date_from."' AND '".$date_to."'
                            AND sa.main_country_dealer_id = ".$user_id."
                            AND sa.dealer_id IS NULL);");
                     $dlr_info = $query->result_array();
                     if(count($dlr_info)){
                         $this->set_response([
                    'status'=> TRUE,
                    'data'=>$dlr_info                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                     }
                }else{
                    
                    $this->set_response([
                    'status'=> FALSE,
                    'message'=>"Invalid user"                    
                    ], REST_Controller::HTTP_ACCEPTED); 
                     
                }
    }
}


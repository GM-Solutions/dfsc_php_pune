<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*controler for india */

class User extends REST_Controller  {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();        
        $this->load->model("Users");  
        $this->group =  array('AuthorisedServiceCenters','Dealers');
    }

    public function login_post()
    {
        $op =  array();
        $username = $this->post('username');
        $mobile_no = $this->post('mobile_no');
        $role = $this->post('role');
        $group = $group2 = "";
        switch ($role) {
            case "asc": $group = "AuthorisedServiceCenters"; break; /*2*/
            case "dealer": $group = "Dealers"; break;/*7*/
			case "service_advisor": $group = "ServiceAdvisors"; break;/*17*/

        }
            if(empty($mobile_no) || empty($mobile_no) || empty($role)){
                $op['status']= FALSE;
                $op['message']= "Please enter valid Username ,Mobile No & Role";
                echo json_encode($op);
                return TRUE;
            }
		/*execute data*/
		$this->db->select('*, auth_user.id AS usrid');
        $this->db->from('auth_user');
        $this->db->join('gm_userprofile','auth_user.id = gm_userprofile.user_id','left');
        $this->db->join('auth_user_groups','auth_user.id = auth_user_groups.user_id','left');
        $this->db->join('auth_group','auth_user_groups.group_id = auth_group.id','left');
        if(!empty($group)){
			$this->db->where('auth_group.name',  $group);
		}
        if(!empty($username)){
			$this->db->where('auth_user.username',$username);    
		}
		$this->db->like('gm_userprofile.phone_number',$mobile_no);        
        $user_dtl=  $this->db->get()->row();
		log_message('debug',print_r($user_dtl,TRUE));
		//log_message('debug', $this->db->last_query());
		/*execute data*/
		
		/*
         if(!empty($group)){
             
        $this->db->select('*, auth_user.id AS usrid');
        $this->db->from('auth_user');
        $this->db->join('gm_userprofile','auth_user.id = gm_userprofile.user_id','left');
        $this->db->join('auth_user_groups','auth_user.id = auth_user_groups.user_id','left');
        $this->db->join('auth_group','auth_user_groups.group_id = auth_group.id','left');
        
        $this->db->where('auth_group.name',  $group);
        $this->db->where('auth_user.username',$username);        
        $user_dtl=  $this->db->get()->row();
         }  elseif ($group2 == "ServiceAdvisors") {
           $this->db->select('*, auth_user.id AS usrid');
           $this->db->from('auth_user');
           $this->db->join('gm_userprofile','auth_user.id = gm_userprofile.user_id','left');
           $this->db->join('auth_user_groups','auth_user.id = auth_user_groups.user_id','left');
           $this->db->join('auth_group','auth_user_groups.group_id = auth_group.id','left');
           $this->db->like('gm_userprofile.phone_number',$mobile_no);
           $this->db->where('auth_group.name',  $group2);
           $user_dtl=  $this->db->get()->row();
           
        } 
         else {
            $op['status']= FALSE;
            $op['message']="Something Went Wrong";
            echo json_encode($op);
            return TRUE;
         }*/
            if (isset($user_dtl)){
//                echo $this->db->last_query(); die;
                if(substr($user_dtl->phone_number, -10) == $mobile_no){
                    /*send OTP */
                    $otp = "1111";//rand(111111,999999);
                    $message = "OTP to login in FSC program is:".$otp;
//                    Common::sendSMS(array('mobile_no'=>8983166667,'message'=>$message));
                    
                    $op['user_id'] = $user_dtl->usrid;
                    $op['firstname'] = !empty($user_dtl->first_name) ?  $user_dtl->first_name : "";
                    $op['lastname'] = !empty($user_dtl->last_name) ? $user_dtl->last_name :"";
                    $op['email'] = !empty($user_dtl->email) ? $user_dtl->email : "" ;                    
                    $op['group'] = !empty($user_dtl->name) ? $user_dtl->name : "" ;                    
                    $op['otp'] =  $otp;
                    
                    $op['menu'] = $this->menuList($op['group']);
                    
                    $op['status'] =  TRUE;
                }else{
                    $op['status'] =  FALSE;
                    $op['message'] = "Invalid User, Please check your mobile number details";
                }
            } else {
                    $op['status'] =  FALSE;
                    $op['message'] = "Invalid User, Please check your details";
                }
            echo json_encode($op);        
    }
    
    function menuList($group) {
        $menu =  array();
        switch ($group) {
            case 'Dealers':
                $s_menu[0]['key']='asc_registration';
                $s_menu[0]['value']='ASC Registration';
                
                $s_menu[1]['key']='service_adv_registration';
                $s_menu[1]['value']='Service Advisor Registration';                
                
                $menu['side_menu']=array();
                
                $d_menu[0]['key']='search_customer_detail';
                $d_menu[0]['value']='Search Customer';
                
                $d_menu[1]['key']='service_status';
                $d_menu[1]['value']='Service Status';
                
                $d_menu[2]['key']='check_free_services';
                $d_menu[2]['value']='Check Free Services';
                
                $d_menu[3]['key']='close_free_services';
                $d_menu[3]['value']='Close Free Services';
                
                $d_menu[4]['key']='customer_registration';
                $d_menu[4]['value']='Customer Registration';
                
                $menu['dashboard']=$d_menu;
                break;
            case 'AuthorisedServiceCenters':

                $s_menu[0]['key']='service_adv_registration';
                $s_menu[0]['value']='Service Advisor Registration';                
                
                $menu['side_menu']=array();
                
                $d_menu[0]['key']='search_customer_detail';
                $d_menu[0]['value']='Search Customer';
                
                $d_menu[1]['key']='service_status';
                $d_menu[1]['value']='Service Status';
                
                $d_menu[2]['key']='check_free_services';
                $d_menu[2]['value']='Check Free Services';
                
                $d_menu[3]['key']='close_free_services';
                $d_menu[3]['value']='Close Free Services';
                
                $d_menu[4]['key']='customer_registration';
                $d_menu[4]['value']='Customer Registration';
                
                $menu['dashboard']=$d_menu;
                break;
            case 'ServiceAdvisors':

                $s_menu[0]['key']='service_adv_registration';
                $s_menu[0]['value']='Service Advisor Registration';                
                
                $menu['side_menu']=array();
                
                $d_menu[0]['key']='search_customer_detail';
                $d_menu[0]['value']='Search Customer';
                
                $d_menu[1]['key']='service_status';
                $d_menu[1]['value']='Service Status';
                
                $d_menu[2]['key']='check_free_services';
                $d_menu[2]['value']='Check Free Services';
                
                $d_menu[3]['key']='close_free_services';
                $d_menu[3]['value']='Close Free Services';
                
                $d_menu[4]['key']='customer_registration';
                $d_menu[4]['value']='Customer Registration';
                
                $menu['dashboard']=$d_menu;
                break;

            default:
                break;
        }
        return $menu;
    }
    
     public function service_man_list_post() {
        $group = $this->post('group');
        
        $user_id = $this->post('user_id');
        $menu_name = $this->post('menu_name');
        if(empty($group)  || empty($user_id)){
            $this->set_response([
                    'status'=> FALSE,
                    'message'=>"Please provide all parameters"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            return TRUE;
        }
        
        $op =  array();
        switch ($group) {
            case "ServiceAdvisors":
                    $this->db->select('*, auth_user.id AS usrid');
                    $this->db->from('auth_user');
                    $this->db->join('gm_userprofile','auth_user.id = gm_userprofile.user_id','left');
                    $this->db->join('auth_user_groups','auth_user.id = auth_user_groups.user_id','left');
                    $this->db->where('auth_user.id',$user_id); 
                    $user_dtl=  $this->db->get()->row();
//                    print_r($user_dtl);  die;
                    $op['employee_dtl'][0]['firstname'] = !empty($user_dtl->first_name) ?  $user_dtl->first_name : "";
                    $op['employee_dtl'][0]['lastname'] = !empty($user_dtl->last_name) ? $user_dtl->last_name :"";
                    $op['employee_dtl'][0]['mobile_no'] = !empty($user_dtl->phone_number) ? $user_dtl->phone_number :"";
                    $op['employee_dtl'][0]['status'] = TRUE;
                break;
            case "Dealers":
                $all_employee =   array();
                $this->db->select('*');
                $this->db->from('gm_serviceadvisor AS sa');                
                $this->db->join('gm_userprofile AS profile_sa','sa.user_id=profile_sa.user_id','left');        
                $this->db->join('auth_user AS au','sa.user_id=au.id','left');        
                $this->db->where('sa.status','Y');
                $this->db->where('sa.dealer_id',$user_id);
                $query = $this->db->get();
		$all_asc = ($query->num_rows() > 0)? $query->result_array():FALSE;
                log_message('debug',print_r($all_asc,TRUE));
                //gm_salesexecutive
                
                $i=0;
                if($all_asc){
                foreach ($all_asc as $key => $value) {
                    $all_employee[$i]['firstname']=!empty($value['first_name']) ? $value['first_name'] :"";
                    $all_employee[$i]['lastname']=(!empty($value['last_name']) ? $value['last_name'] ."-" : "")."".$value['service_advisor_id'];
                    $all_employee[$i]['mobile_no']=!empty($value['phone_number']) ? $value['phone_number'] : "**********";
                    $all_employee[$i]['status'] = TRUE;
                    $i++;
                }}
                
                $op['employee_dtl']=$all_employee;
                
                break;
                case "AuthorisedServiceCenters":
                    $all_employee =   array();
                $this->db->select('*');
                $this->db->from('gm_serviceadvisor AS sa');                
                $this->db->join('gm_userprofile AS profile_sa','sa.user_id=profile_sa.user_id','left');        
                $this->db->join('auth_user AS au','sa.user_id=au.id','left');        
                $this->db->where('sa.status','Y');
                $this->db->where('sa.asc_id',$user_id);
                $query = $this->db->get();
		$all_asc = ($query->num_rows() > 0)? $query->result_array():FALSE;
                
                $i=0;
                if($all_asc){
                foreach ($all_asc as $key => $value) {
                    $all_employee[$i]['firstname']=!empty($value['first_name']) ? $value['first_name'] :"";
                    $all_employee[$i]['lastname']=(!empty($value['last_name']) ? $value['last_name'] ."-" : "")."".$value['service_advisor_id'];
                    $all_employee[$i]['mobile_no']=!empty($value['phone_number']) ? $value['phone_number'] : "**********";
                    $all_employee[$i]['status'] = TRUE;
                    $i++;
                }}
                
                $op['employee_dtl']=$all_employee;
                    break;
        } 
//        die;
        $op['count']= count($op['employee_dtl']);
        
        $this->set_response($op, REST_Controller::HTTP_ACCEPTED); 
    }
    
    
    public function user_profile_post() {
        $user_id = $this->post('user_id');
        $group = $this->post('group');
        if(empty($user_id) || empty($group)){
            $this->set_response([
                    'status'=> FALSE,
                    'message'=>"Please provide all parameters"                    
                ], REST_Controller::HTTP_ACCEPTED); 
            return TRUE;
        }
        /* send user Profile */
        $designation = "";
        switch ($group) {
            case "ServiceAdvisors":
                $designation = "Service Advisor";
                break;
            case "Dealers":
                $designation = "Dealers";
                break;
            case "MainCountryDealers":
                $designation = "Main Country Dealers";
                break;

            default:
                break;
        }
                $this->db->select('*');
                $this->db->from('auth_user');
                $this->db->join('gm_userprofile','auth_user.id = gm_userprofile.user_id','left');
                $this->db->where('auth_user.id',$user_id); 
                $user_dtl=  $this->db->get()->row();
                $op['username'] = $user_dtl->username;
                $op['first_name'] = $user_dtl->first_name;
                $op['email'] = $user_dtl->email;
                $op['phone_number'] = $user_dtl->phone_number;
                $op['designation'] = $designation;
                $op['status'] = TRUE;                                
                echo json_encode($op);            
        
    }
    
}

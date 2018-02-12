<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();        
//        $this->load->model("Common_model");        
    }

    public function countries_get()
    {
        $op =  array();
        $all_group = $this->config->item('countries');
        $i=0;
        foreach ($all_group as $key => $value) {
            $op['countries'][$i]['id'] = $value['key'];
            $op['countries'][$i]['value'] = $value['lable'];
            $op['countries'][$i]['mobile_validation'] = $value['mobile_validation'];
            $op['countries'][$i]['flag'] = base_url("assets/icons/".$value['flag']);
            $op['countries'][$i]['base_url'] = base_url("api/".$value['base_url']."/");
			$op['countries'][$i]['code'] = $value['code'];
            $i++;
        }
        $role_menu = $this->config->item('role_menu');
//        print_r($role_menu); 
        foreach ($role_menu as $key => $value) {
            foreach ($value as $key_sub => $value_sub) {
                $op['role_menu'][$key][$key_sub]['key'] = $value_sub['key'];
                $op['role_menu'][$key][$key_sub]['value'] = $value_sub['value'];
                $op['role_menu'][$key][$key_sub]['username'] = $value_sub['username'];
            }            
        }
        $op['status'] = TRUE;
        $op['message'] = "All Available Countries";
        $op['count'] = count($op['countries']);
        
        $this->set_response($op, REST_Controller::HTTP_ACCEPTED); 
    }
}

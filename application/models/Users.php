<?php 
require 'Common_model.php';
class Users extends Common_model {
    
    function service_status_info($cond=array()){
        $this->db->select('*,pd.product_id AS chessis');
        $this->db->from('gm_productdata AS pd');
        $this->db->join('gm_coupondata AS cd','pd.id=cd.product_id','left');        
        if(!empty($cond)){ foreach ($cond as $key => $value)$this->db->where($key,$value); }
        $query = $this->db->get();
        return ($query->num_rows() > 0)? $query->result_array():FALSE;
    }
}
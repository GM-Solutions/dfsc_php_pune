<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* controler for india */

class KTM extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        $this->load->model("Users");
        $this->load->database('default');
    }
    public function product_details_post() {
        $host =  "http://gladminds-connect.s3.amazonaws.com/";
        $op =  array();
        $phone_number = $this->post('phone_number');
        $phone_number = substr($phone_number, -10);
        $now = new DateTime();
        $now->setTimezone(new DateTimezone('Asia/Kolkata'));
        
        $this->db->select('*');
        $this->db->from('gm_productdata');
        $this->db->like('customer_phone_number',$phone_number);
        $this->db->where("(product_id like 'MD2JUCY%'
        or product_id like 'MD2JPEY%'
        or product_id like 'MD2JPJY%'
        or product_id like 'MD2JU%'
        or product_id like 'MD2JG%' 
        or product_id like 'VBK%')");
        
        $query = $this->db->get();
        $product_data =  ($query->num_rows() > 0)? $query->result_array():FALSE;
        
        if($product_data){
            $op['status'] = "1";
            $op['user_name'] = $product_data[0]['customer_name'];
            
            /*get duke_250,duke_250_version | duke_390,duke_390_version | duke_200,duke_200_version */
            
 
            $sql =$this->db->query('SELECT DISTINCT
    k200.manual_of_200,
    k200.version_of_200,
    k250.manual_of_250,
    k250.version_of_250,
    k390.manual_of_390,
    k390.version_of_390,
        k125.manual_of_125,
    k125.version_of_125
FROM
    gm_ktm_om AS k200
        JOIN
    gm_ktm_om AS k250 ON k250.id
        JOIN
    gm_ktm_om AS k390 ON k390.id
		JOIN
	gm_ktm_om AS k125 ON k125.id
WHERE
    k200.version_of_200 IS NOT NULL
        AND k250.version_of_250 IS NOT NULL
        AND k390.version_of_390 IS NOT NULL
        AND k125.version_of_125 IS NOT NULL
GROUP BY k200.version_of_200 , k250.version_of_250 , k390.version_of_390,k125.version_of_125
ORDER BY k200.version_of_200 DESC , k250.version_of_250 DESC , k390.version_of_390 DESC , k125.version_of_125 DESC
LIMIT 1;');
            $manuals = $sql->result();
            $op['duke_200'] = $host.$manuals[0]->manual_of_200;
            $op['duke_200_version'] = $manuals[0]->version_of_200;
            $op['duke_250'] = $host.$manuals[0]->manual_of_250;
            $op['duke_250_version'] = $manuals[0]->version_of_250;
            $op['duke_390'] = $host.$manuals[0]->manual_of_390;
            $op['duke_390_version'] = $manuals[0]->version_of_390;
	
	    $op['duke_125'] = $host.$manuals[0]->manual_of_125;
// $op['duke_125'] = $host.$manuals[0]->manual_of_200;
            $op['duke_125_version'] = $manuals[0]->version_of_125;
        }  else {
            $op['status'] = "0";
            $op['message'] = "No Products Available";
        }
        $this->set_response($op, REST_Controller::HTTP_ACCEPTED); 
    }
}

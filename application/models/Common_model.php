<?php 
class Common_model extends CI_Model {
    
        function select_info($table_name,$cond = array())
	{
		$this->db->select('*');
		$this->db->from($table_name);
		if(!empty($cond)){ foreach ($cond as $key => $value)$this->db->where($key,$value); }
		$query = $this->db->get();
		return ($query->num_rows() > 0)? $query->result_array():FALSE;
	}
	function insert_info($table_name,$data)
	{
		$this->db->insert($table_name, $data);
		return $this->db->insert_id();
	}
	function update_info($tbl_name,$data_array,$cond)
	{
		if(!empty($cond)){
			foreach ($cond as $key => $value) {
				$this->db->where($key,$value);
			}
		}
		$this->db->update($tbl_name,$data_array);
		return $this->db->affected_rows();
	}
	function insert_batch_record($table_name,$data)
	{
		$this->db->insert_batch($table_name, $data);
		return $this->db->insert_id();
	}
	
	function select_rows($selectstatement,$table_name,$cond = array(),$order_by=array())
	{
		$this->db->select($selectstatement);
		$this->db->from($table_name);
		if(!empty($cond)){ foreach ($cond as $key => $value)$this->db->where($key,$value); }
		if(!empty($order_by)){ foreach ($order_by as $key => $value)$this->db->order_by($key,$value); }     
		$query = $this->db->get();
		return $query->row();
	}
        function select_info_like($table_name,$cond = array())
	{
		$this->db->select('*');
		$this->db->from($table_name);
		if(!empty($cond)){ foreach ($cond as $key => $value)$this->db->like($key,$value); }
		$query = $this->db->get();
		return ($query->num_rows() > 0)?$query->result_array():FALSE;
	}
        
        
}
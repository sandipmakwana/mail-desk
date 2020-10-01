<?php

class Business_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function businessMaster(){
       return $this->db->select("vl.business_id,  vl.department_code, vl.department_name, vl.cost_center,vl.isactive")
			->from("vs_business as vl")			
			->order_by('vl.business_id','desc')		
			->get()
			->result();
    }
    public function insertBusinessMaster($businessarray){       
        $this->db->insert('vs_business', $businessarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updateBusinessMaster($businessarray, $businessid){

        return $this->db->where('business_id',$businessid)
                        ->update('vs_business', $businessarray);
       
    }
    public function deleteBusinessMaster($businessid){
        return $this->db->where('business_id',$businessid)
                        ->delete('vs_business');
    }
    public function activeBusinessMaster($businessid){

        return $this->db->where('business_id',$businessid)
                        ->update('vs_business', array('isactive' => '1' ));       
    }
     public function inactiveBusinessMaster($businessid){

        return $this->db->where('business_id',$businessid)
                        ->update('vs_business', array('isactive' => '0' ));       
    }
    public function getBusinessMaster($businessid){
        return $this->db->select("vl.business_id, vl.department_name, vl.department_code,vl.cost_center, vl.isactive")
            ->from("vs_business as vl")              
            ->where('vl.business_id',$businessid)
            ->get()
            ->row();
    }
    public function checkLocName($bizname){
        $this->db->select('vbs.business_id');
        $this->db->from('vs_business as vbs');
        $this->db->where('vbs.department_name', $bizname);
        $query = $this->db->get();
        return $query->result();  
    }
	
	public function checkDepartmentCode($department_code){
        $this->db->select('vca.business_id');
        $this->db->from('vs_business as vca');
        $this->db->where('vca.department_code', $department_code);
        $query = $this->db->get();
        return $query->result();
		//echo $this->db->last_query(); die;
    }
}	

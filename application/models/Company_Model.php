<?php

class Company_Model extends CI_Model {

    function __construct()

    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function companyMaster(){
    	return $this->db->select("vbs.bu_id, vbs.bu_code, vbs.bu_name,  vbs.isactive")
			->from("vs_businessunit as vbs")			
			->order_by('vbs.bu_id','desc')		
			->get()
			->result();
    }
    public function insertCompanyMaster($companyarray){       
        $this->db->insert('vs_businessunit', $companyarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updateCompanyMaster($locationarray, $compid){
        return $this->db->where('bu_id',$compid)
                        ->update('vs_businessunit', $locationarray);       
    }
    public function deleteCompanyMaster($compid){
        return $this->db->where('bu_id',$compid)
                        ->delete('vs_businessunit');
    }
    public function activeCompanyMaster($compid){

        return $this->db->where('bu_id',$compid)
                        ->update('vs_businessunit', array('isactive' => '1' ));       
    }
     public function inactiveCompanyMaster($compid){

        return $this->db->where('bu_id',$compid)
                        ->update('vs_businessunit', array('isactive' => '0' ));       
    }
    public function getCompanyMaster($compid){
        return $this->db->select("vbs.bu_id, vbs.bu_code, vbs.bu_name,  vbs.isactive")
            ->from("vs_businessunit as vbs")             
            ->where('vbs.bu_id',$compid)
            ->get()
            ->row();
    }
    public function checkCompCode($companycode){
        $this->db->select('vbs.bu_id');
        $this->db->from('vs_businessunit as vbs');
        $this->db->where('vbs.bu_code', $companycode);
        $query = $this->db->get();
        return $query->result();  
    }
    public function checkCompName($companyname){
        $this->db->select('vbs.bu_id');
        $this->db->from('vs_businessunit as vbs');
        $this->db->where('vbs.bu_name', $companyname);
        $query = $this->db->get();
        return $query->result();  
    }
}	

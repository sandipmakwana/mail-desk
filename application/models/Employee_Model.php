<?php

class Employee_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function empMaster(){
    	return $this->db->select("vl.emp_id, CONCAT_WS(' ', vl.emp_firstname,' ', vl.emp_lastname) as emp_name, vl.emp_username, vl.emp_email,CONCAT_WS(' ',lc.location_code,' - ',lc.location_name) as location,CONCAT_WS(' ',dp.department_code,' - ',dp.department_name) as department,vl.costcenter, emp_role, vl.emp_status")
			->from("vs_emp_details as vl")
            ->join("vs_location as lc","lc.location_id=vl.emp_locationid")
            ->join("vs_business as dp","dp.business_id=vl.emp_departmentid")           	
			->order_by('vl.emp_id','desc')		
			->get()
			->result();
    }
    public function insertEmpMaster($emparray){       
        $this->db->insert('vs_emp_details', $emparray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updateEmpMaster($emparray, $empid){
        return $this->db->where('emp_id',$empid)
                        ->update('vs_emp_details', $emparray);
           
    }
    public function deleteEmpMaster($empid){
        return $this->db->where('emp_id',$empid)
                        ->delete('vs_emp_details');
    }
    public function activeEmpMaster($empid){
        return $this->db->where('emp_id',$empid)
                        ->update('vs_emp_details', array('emp_status' => 'Active' ));       
    }
     public function inactiveEmpMaster($empid){
        return $this->db->where('emp_id',$empid)
                        ->update('vs_emp_details', array('emp_status' => 'Inactive' ));       
    }
    public function getEmpMaster($empid){
        return $this->db->select("vl.*")
            ->from("vs_emp_details as vl")              
            ->where('vl.emp_id',$empid)
            ->get()
            ->row();
    }
    public function getVendorList()
    {     
      $this->db->select('h.vendor_id,h.vendor_cmpname, h.location_id');
      $this->db->from('vs_vendor As h');
      $this->db->where('isactive=1');
      $query = $this->db->get();     
      foreach($query->result() as $row){
        $array[$row->vendor_id]=$row->vendor_cmpname. ' - '.$row->location_id;          
      }
      return $array;
    }
    public function checkEmptoken($id){
        $this->db->select('vbs.emp_id');
        $this->db->from('vs_emp_details as vbs');
        $this->db->where('vbs.emp_username', $id);
        $query = $this->db->get();
        return $query->result();  
    }
    public function checkEmpEmail($mail){
        $this->db->select('vbs.emp_id');
        $this->db->from('vs_emp_details as vbs');
        $this->db->where('vbs.emp_email', $mail);
        $query = $this->db->get();
        return $query->result();  
    }
    public function getlist($q) { 
        return $this->db->select("vl.*")
            ->from("vs_emp_details as vl")              
            ->like('vl.emp_username',$q)
            ->or_like('vl.emp_firstname',$q)
            ->or_like('vl.emp_lastname',$q)
            ->get()
            ->result();
    }
	public function getLocationMaster(){ 
        return $this->db->select("vl.location_id, vl.location_code, vl.location_name, vl.location_address, vl.isactive")
            ->from("vs_location as vl")              
            ->where('vl.isactive','1')
            ->get()
            ->result();
    }
	public function getDepartmentMaster(){ 
      
 	    return $this->db->select("vl.business_id,  vl.department_code, vl.department_name, vl.cost_center,vl.isactive")
			->from("vs_business as vl")
			->where('vl.isactive','1')			
			->order_by('vl.business_id','desc')		
			->get()
			->result();	
    }
}	

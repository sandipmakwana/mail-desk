<?php

class Location_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function locationMaster(){
    	return $this->db->select("vl.location_id,bu.bu_code,bu.bu_name,vl.location_type,vl.location_name, vl.location_code, vl.location_address,vl.location_city,vl.location_pincode, vl.isactive")
			->from("vs_location as vl")
            ->join("vs_businessunit as bu","bu.bu_id=vl.business_id") 	
			->order_by('vl.location_id','desc')		
			->get()
			->result();
    }
    public function insertLocationMaster($locationarray){       
        $this->db->insert('vs_location', $locationarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updateLocationMaster($locationarray, $locationid){

        return $this->db->where('location_id',$locationid)
                        ->update('vs_location', $locationarray);
       
    }
    public function deleteLocationMaster($locationid){
        return $this->db->where('location_id',$locationid)
                        ->delete('vs_location');
    }
    public function activeLocationMaster($locationid){

        return $this->db->where('location_id',$locationid)
                        ->update('vs_location', array('isactive' => '1' ));       
    }
     public function inactiveLocationMaster($locationid){

        return $this->db->where('location_id',$locationid)
                        ->update('vs_location', array('isactive' => '0' ));       
    }
    public function getLocationMaster($locationid){
        return $this->db->select("vl.location_id, vl.location_franking_balance, vl.business_id, vl.location_type, vl.location_name, vl.location_code, vl.location_address, vl.location_city, vl.location_pincode, vl.isactive")
            ->from("vs_location as vl")              
            ->where('vl.location_id',$locationid)
            ->get()
            ->row();
    }
    public function checkLocName($locname){
        $this->db->select('vbs.location_id');
        $this->db->from('vs_location as vbs');
        $this->db->where('vbs.location_name', $locname);
        $query = $this->db->get();
        return $query->result();  
    }
    //get business uer
    public function getBusinessList()
    {     
      $this->db->select('h.bu_id,h.bu_code, h.bu_name');
      $this->db->from('vs_businessunit As h');
      $this->db->where('isactive=1');
      $query = $this->db->get();    
      $bu_id = array('');
      $bu_name = array('Select Business');  
      foreach($query->result() as $row){        
        array_push($bu_id, $row->bu_id);
        array_push($bu_name, $row->bu_code. ' - '.$row->bu_name);           
      }
      $array_merge =array_combine($bu_id, $bu_name);  
      return $array_merge;
    }
}	

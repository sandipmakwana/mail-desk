<?php

class Pickuppoint_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function pickuppointMaster(){
    	return $this->db->select("vl.pickup_id, vl.p_locationid, vl.p_pickuppoint, vl.p_desc, vl.isactive, vloc.location_name")
			->from("vs_pickuppoint as vl")
			->join('vs_location as vloc', 'vloc.location_id = vl.p_locationid', 'left')
			->order_by('vl.pickup_id','desc')		
			->get()
			->result();
    }
    public function insertPickuppointMaster($pickuppointarray){       
        $this->db->insert('vs_pickuppoint', $pickuppointarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updatePickuppointMaster($pickuppointarray, $pickuppointid){

        return $this->db->where('pickup_id',$pickuppointid)
                        ->update('vs_pickuppoint', $pickuppointarray);
       
    }
    public function deletePickuppointMaster($pickuppointid){
        return $this->db->where('pickup_id',$pickuppointid)
                        ->delete('vs_pickuppoint');
    }
    public function activePickuppointMaster($pickuppointid){

        return $this->db->where('pickup_id',$pickuppointid)
                        ->update('vs_pickuppoint', array('isactive' => '1' ));       
    }
     public function inactivePickuppointMaster($pickuppointid){

        return $this->db->where('pickup_id',$pickuppointid)
                        ->update('vs_pickuppoint', array('isactive' => '0' ));       
    }
    public function getPickuppointMaster($pickuppointid){
        return $this->db->select("vl.pickup_id, vl.p_locationid, vl.p_pickuppoint, vl.p_desc, vl.isactive")
            ->from("vs_pickuppoint as vl")              
            ->where('vl.pickup_id',$pickuppointid)
            ->get()
            ->row();
    }

    public function getLocationMaster(){ 
        return $this->db->select("vl.location_id, vl.location_code, vl.location_name, vl.location_address, vl.isactive")
            ->from("vs_location as vl")              
            ->where('vl.isactive','1')
            ->get()
            ->result();
    }
    public function checkLocName($frvalue){
        $this->db->select('vbs.pickup_id');
        $this->db->from('vs_pickuppoint as vbs');
        $this->db->where('vbs.f_value', $frvalue);
        $query = $this->db->get();
        return $query->result();  
    }
}	

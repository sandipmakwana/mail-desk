<?php

class Agency_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }

    function agencyMaster(){
    	return $this->db->select("vca.*")
			->from("vs_courier_agency as vca")			
			->order_by('vca.agency_id','desc')		
			->get()
			->result();
    }

    public function insertAgencyMaster($agencyarray){       
        $this->db->insert('vs_courier_agency', $agencyarray);
        $id = $this->db->insert_id();
        return $id;    
    }

    public function updateAgencyMaster($agencyarray, $agencyid){
        return $this->db->where('agency_id',$agencyid)
                        ->update('vs_courier_agency', $agencyarray);
    }

    public function deleteAgencyMaster($agencyid){
        return $this->db->where('agency_id',$agencyid)
                        ->delete('vs_courier_agency');
    }

    public function activeAgencyMaster($agencyid){
        return $this->db->where('agency_id',$agencyid)
                        ->update('vs_courier_agency', array('agency_status' => '1' ));
    }

     public function inactiveAgencyMaster($agencyid){
        return $this->db->where('agency_id',$agencyid)
                        ->update('vs_courier_agency', array('agency_status' => '0' ));       
    }

    public function getAgencyMaster($agencyid){
        return $this->db->select("vca.*")
            ->from("vs_courier_agency as vca")              
            ->where('vca.agency_id',$agencyid)
            ->get()
            ->row();
    }

    public function checkAgencyName($agencyname){
        $this->db->select('vca.agency_id');
        $this->db->from('vs_courier_agency as vca');
        $this->db->where('vca.agency_name', $agencyname);
        $query = $this->db->get();
        return $query->result();
    }
	
	public function checkAgencyCode($agencycode){
        $this->db->select('vca.agency_id');
        $this->db->from('vs_courier_agency as vca');
        $this->db->where('vca.agency_code', $agencycode);
        $query = $this->db->get();
        return $query->result();
		//echo $this->db->last_query(); die;
    }
	
	public function getLocationMaster(){ 
        return $this->db->select("vl.location_id, vl.location_code, vl.location_name, vl.location_address, vl.isactive")
            ->from("vs_location as vl")              
            ->where('vl.isactive','1')
            ->get()
            ->result();
    }
	
}	

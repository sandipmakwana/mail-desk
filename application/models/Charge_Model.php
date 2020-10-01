<?php

class Charge_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }

    function chargeMaster(){
    	return $this->db->select("vc.charge_id, vc.charge_name, vc.agency_code, vc.charge_value, vc.charge_status")
			->from("vs_charge as vc")			
			->order_by('vc.charge_id','desc')		
			->get()
			->result();
    }

    public function insertChargeMaster($chargearray){       
        $this->db->insert('vs_charge', $chargearray);
        $id = $this->db->insert_id();
        return $id;    
    }

    public function updateChargeMaster($chargearray, $chargeid){
        return $this->db->where('charge_id',$chargeid)
                        ->update('vs_charge', $chargearray);
    }

    public function deleteChargeMaster($chargeid){
        return $this->db->where('charge_id',$chargeid)
                        ->delete('vs_charge');
    }

    public function activeChargeMaster($chargeid){
        return $this->db->where('charge_id',$chargeid)
                        ->update('vs_charge', array('charge_status' => '1' ));
    }

     public function inactiveChargeMaster($chargeid){
        return $this->db->where('charge_id',$chargeid)
                        ->update('vs_charge', array('charge_status' => '0' ));       
    }

    public function getChargeMaster($chargeid){
        return $this->db->select("vc.charge_id, vc.charge_name, vc.charge_code, vc.charge_value, vc.agency_code, vc.charge_status")
            ->from("vs_charge as vc")              
            ->where('vc.charge_id',$chargeid)
            ->get()
            ->row();
    }
	
	 public function getChargeCodes(){ 
       	return $this->db->select("vca.*")
			->from("vs_courier_agency as vca")			
			->order_by('vca.agency_id','desc')		
			->get()
			->result();
    }

    public function checkChargeName($chargename){
        $this->db->select('vc.charge_id');
        $this->db->from('vs_charge as vc');
        $this->db->where('vc.charge_name', $chargename);
        $query = $this->db->get();
        return $query->result();
    }
}	

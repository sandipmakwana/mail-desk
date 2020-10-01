<?php

class Franking_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function frankingMaster(){
    	return $this->db->select("vl.franking_id, vl.f_month, vl.f_year, vl.f_value, vl.transaction_dt, vl.reference_no, CONCAT(LEFT(vl.remark, 10), '...') as remark, vl.isactive, vloc.location_name")
			->from("vs_franking as vl")
			->join('vs_location as vloc', 'vloc.location_id = vl.f_locationids', 'left')
			->order_by('vl.franking_id','desc')
			->get()
			->result();
    }
    public function insertFrankingMaster($frankingarray){
        $this->db->insert('vs_franking', $frankingarray);
        $id = $this->db->insert_id();
       /* if ($id) {
           $amount = $this->db->select("bt.amount")
                    ->from("balance_table as bt")
                    ->where('bt.locationid',$frankingarray->f_locationids)
                    >get()
                    ->row();
            if($amount){
                  $this->db->insert('vs_franking', $frankingarray);
                 $id = $this->db->insert_id();
            }
            else
            {

            }

        }*/
        

        return $id;    
    }
    public function updateFrankingMaster($frankingarray, $frankingid){

        return $this->db->where('franking_id',$frankingid)
                        ->update('vs_franking', $frankingarray);
       
    }
    public function deleteFrankingMaster($frankingid){
        return $this->db->where('franking_id',$frankingid)
                        ->delete('vs_franking');
    }
    public function activeFrankingMaster($frankingid){

        return $this->db->where('franking_id',$frankingid)
                        ->update('vs_franking', array('isactive' => '1' ));       
    }
     public function inactiveFrankingMaster($frankingid){

        return $this->db->where('franking_id',$frankingid)
                        ->update('vs_franking', array('isactive' => '0' ));       
    }
    public function getFrankingMaster($frankingid){
        return $this->db->select("vl.franking_id, vl.f_month, vl.f_year, vl.f_value,vl.f_locationids, vl.reference_no, vl.remark, vl.isactive")
            ->from("vs_franking as vl")              
            ->where('vl.franking_id',$frankingid)
            ->get()
            ->row();
    }

    public function getLocationMaster(){ 
        return $this->db->select("vl.location_id, bu.bu_code,vl.location_code,vl.location_type, vl.location_name, vl.location_address, vl.isactive")
            ->from("vs_location as vl")
            ->join("vs_businessunit as bu","bu.bu_id=vl.business_id")            
            ->where('vl.isactive','1')
            ->get()
            ->result();
    }
	
    public function checkLocName($frvalue){
        $this->db->select('vbs.franking_id');
        $this->db->from('vs_franking as vbs');
        $this->db->where('vbs.f_value', $frvalue);
        $query = $this->db->get();
        return $query->result();  
    }
}	

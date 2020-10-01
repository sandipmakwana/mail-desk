<?php

class Esc_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function escMaster(){
    	return $this->db->select("vl.vs_escmstid, vl.vs_esc_emptoken, vl.vs_esc_empname, vl.vs_esc_empemail,vl.vs_esc_role, vl.isactive")
			->from("vs_escalation_mst as vl")			
			->order_by('vl.vs_escmstid','desc')		
			->get()
			->result();
    }
    public function insertEscMaster($escarray){       
        $this->db->insert('vs_escalation_mst', $escarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updateEscMaster($escarray, $escid){
        return $this->db->where('vs_escmstid',$escid)
                        ->update('vs_escalation_mst', $escarray);
           
    }
    public function deleteEscMaster($escid){
        return $this->db->where('vs_escmstid',$escid)
                        ->delete('vs_escalation_mst');
    }
    public function activeEscMaster($escid){
        return $this->db->where('vs_escmstid',$escid)
                        ->update('vs_escalation_mst', array('isactive' => '1' ));       
    }
     public function inactiveEscMaster($escid){
        return $this->db->where('vs_escmstid',$escid)
                        ->update('vs_escalation_mst', array('isactive' => '0' ));       
    }
    public function getEscMaster($escid){
        return $this->db->select("vl.vs_escmstid, vl.vs_esc_emptoken, vl.vs_esc_empname, vl.vs_esc_empemail,vl.vs_esc_role,  vl.isactive")
            ->from("vs_escalation_mst as vl")              
            ->where('vl.vs_escmstid',$escid)
            ->get()
            ->row();
    }
    public function checkEmptoken($id){
        $this->db->select('vbs.vs_escmstid');
        $this->db->from('vs_escalation_mst as vbs');
        $this->db->where('vbs.vs_esc_emptoken', $id);
        $query = $this->db->get();
        return $query->result();  
    }
    public function checkEmpEmail($mail){
        $this->db->select('vbs.vs_escmstid');
        $this->db->from('vs_escalation_mst as vbs');
        $this->db->where('vbs.vs_esc_empemail', $mail);
        $query = $this->db->get();
        return $query->result();  
    }
}	

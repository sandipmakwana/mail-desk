<?php

class Vendor_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function vendorMaster(){
    	return $this->db->select("vl.vendor_id, vl.vendor_cmpname, vl.vendor_name, vl.vendor_mobile,vl.vendor_email,vl.location_id, vl.isactive")
			->from("vs_vendor as vl")			
			->order_by('vl.vendor_id','desc')		
			->get()
			->result();
    }
    public function insertVendorMaster($vendorarray){       
        $this->db->insert('vs_vendor', $vendorarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function updateVendorMaster($vendorarray, $vendorid){
        return $this->db->where('vendor_id',$vendorid)
                        ->update('vs_vendor', $vendorarray);
    }
    public function deleteVendorMaster($vendorid){
        return $this->db->where('vendor_id',$vendorid)
                        ->delete('vs_vendor');
    }
    public function activeVendorMaster($vendorid){

        return $this->db->where('vendor_id',$vendorid)
                        ->update('vs_vendor', array('isactive' => '1' ));       
    }
     public function inactiveVendorMaster($vendorid){

        return $this->db->where('vendor_id',$vendorid)
                        ->update('vs_vendor', array('isactive' => '0' ));       
    }
    public function getVendorMaster($vendorid){
        return $this->db->select("vl.vendor_id, vl.vendor_cmpname, vl.vendor_name, vl.vendor_mobile,vl.vendor_email,vl.location_id,vl.vendor_address, vl.vendor_gst, vl.vendor_pan, vl.isactive")
            ->from("vs_vendor as vl")              
            ->where('vl.vendor_id',$vendorid)
            ->get()
            ->row();
    }
    public function checkVenmail($mail){
        $this->db->select('vbs.vendor_id');
        $this->db->from('vs_vendor as vbs');
        $this->db->where('vbs.vendor_email', $mail);
        $query = $this->db->get();
        return $query->result();  
    }
}	

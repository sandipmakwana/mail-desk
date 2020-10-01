<?php

class Request_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
 
    public function getEmpDetails($empid){
        return $this->db->select("vl.*")
            ->from("test_empmst as vl")              
            ->where('vl.emp_token',$empid)
            ->get()
            ->result();
    }
    public function getEmpDetail($empid){
        return $this->db->select("vl.*, ve.emp_username")
            ->from("test_empmst as vl")
			->join("vs_emp_details as ve", "ve.emp_username = vl.emp_token")
            ->where('vl.emp_token',$empid)
            ->get()
            ->row();
    }
    public function getLocationList(){
        $this->db->select('c.location_name');
        $this->db->from('vs_location As c');
        $this->db->where('isactive=1');
        $query = $this->db->get();
        $select =  array('' => 'Select Location');
        foreach($query->result() as $row){
            $array[$row->location_name]=$row->location_name;          
        }
        $array =array_merge($select, $array);
        return $array;
    }   
    public function getLocationAddress($location){
        $this->db->select('c.location_address');
        $this->db->from('vs_location As c');
        $this->db->where('c.location_name', $location);
        $query = $this->db->get();
        return $query->result();       
    } 
    public function userVerified($tokenid){
        return $this->db->select("vl.emp_id")
            ->from("vs_emp_details as vl")              
            ->where('vl.emp_username',$tokenid)
            ->get()
            ->row();
    }
    public function insertEmp($emparray){       
        $this->db->insert('vs_emp_details', $emparray);
        $id = $this->db->insert_id();
        return $id;    
    }
    public function insertRequest($reqarray){       
        $this->db->insert('vs_request', $reqarray);
        $id = $this->db->insert_id();
        return $id;    
    }
    /*function requestList(){
        return $this->db->select("vl.*,vv.vendor_cmpname")
            ->from("vs_request as vl") 
            ->join("vs_vendor as vv", "vv.vendor_id=vl.req_vendor_id")
            ->order_by('vl.req_id','desc')     
            ->get()
            ->result();
    }*/
	 function requestList(){
		  
		 if($this->session->userdata['logged_in']['role'] == 'DeskUser' or $this->session->userdata['logged_in']['role'] == 'Admin'){
			 
		   return $this->db->select("*")
            ->from("vs_courier_request")  
            ->where("req_status", "Submitted")
			->order_by('req_id','desc')              
            ->get()
            ->result();
			//echo $this->db->last_query(); die;
			
		 }else{

			return $this->db->select("vl.*")
            ->from("vs_courier_request as vl")
            ->where('vl.req_emp_token',$this->session->userdata['logged_in']['emp_username'])
			->order_by('vl.req_id','desc')
            ->get()
            ->result();
			 
		 }

    }

     function receiveList(){
          
         if($this->session->userdata['logged_in']['role'] == 'DeskUser' or $this->session->userdata['logged_in']['role'] == 'Admin'){
             
           return $this->db->select("*")
            ->from("vs_courier_request") 
            ->where("req_status", "Submitted") 
            ->order_by('req_id','desc')              
            ->get()
            ->result();
            //echo $this->db->last_query(); die;
            
         }else{
            
            return $this->db->select("vl.*")
            ->from("vs_courier_request as vl")              
            ->where('vl.emp_token',$this->session->userdata['logged_in']['username'])
            ->order_by('vl.req_id','desc')  
            ->get()
            ->result();         
             
         }  

    }
     function requestEmpList($empid){
        return $this->db->select("vl.*,vv.vendor_cmpname")
            ->from("vs_request as vl") 
            ->join("vs_vendor as vv", "vv.vendor_id=vl.req_vendor_id") 
            ->where('vl.req_emp_id',$empid)          
            ->order_by('vl.req_id','desc')     
            ->get()
            ->result();
    }
    function requestVendorList($vendorid){
        return $this->db->select("vl.*,vv.vendor_cmpname")
            ->from("vs_request as vl") 
            ->join("vs_vendor as vv", "vv.vendor_id=vl.req_vendor_id") 
            ->where('vl.req_vendor_id',$vendorid)  
            ->where("vl.req_status='Request Send to Vendor'")          
            ->order_by('vl.req_id','desc')     
            ->get()
            ->result();
    }
    //dispatch list
    function requestVenDispatchList($vendorid){
        return $this->db->select("vl.*,vv.vendor_cmpname")
            ->from("vs_request as vl") 
            ->join("vs_vendor as vv", "vv.vendor_id=vl.req_vendor_id") 
            ->where('vl.req_vendor_id',$vendorid)  
            ->where("vl.req_status='Dispatched'")          
            ->order_by('vl.req_id','desc')     
            ->get()
            ->result();
    }
     public function deleteRequest($reqid){
        return $this->db->where('req_id',$reqid)
                        ->delete('vs_request');
    }
    public function getRequestDetails($reqid){
         return $this->db->select("vl.*,vv.vendor_cmpname")
            ->from("vs_request as vl") 
             ->join("vs_vendor as vv", "vv.vendor_id=vl.req_vendor_id")
            ->where('vl.req_id',$reqid)
            ->get()
            ->row();
    }
    public function updateRequest($reqarray, $reqid){
        return $this->db->where('req_id',$reqid)
                        ->update('vs_request', $reqarray);
    }
    //report
    public function getRequestReport(){
        $first_day_this_month = date('Y-m-01');
        $todaytade = date('Y-m-d');
        $sql = "CALL getRequestReport(?,?,?)";
        $query = $this->db->query($sql,array('',$first_day_this_month,$todaytade));
        return $query->result();
    }
    //search report
    public function searchReport($location, $to_date, $from_date){
        $sql = "CALL getRequestReport(?,?,?)";
        $query = $this->db->query($sql,array($location,$to_date,$from_date));
        mysqli_next_result($this->db->conn_id);
       return $query->result();
        
    }
    //hr approval list
   
    public function hrRequestlist($uname){
        return $this->db->select('vl.*,vv.vendor_cmpname')
                    ->from("vs_request as vl")
                    ->join("vs_vendor as vv", "vv.vendor_id=vl.req_vendor_id")
                    ->where("vl.req_emp_hrmgr_token", $uname)
                    ->where("vl.req_status='Pending Approval from HR'")
                    ->get()      
                    ->result();
    }


    //Courier Add
    public function insertCourier($couarray){ 

        $this->db->insert('vs_courier_request', $couarray);
		$this->db->db_debug = TRUE; 
        $id = $this->db->insert_id();

        return $id;    
    }
    public function updateCourier($couarray, $reqid){ 
        return $this->db->where('req_id',$reqid)
                        ->update('vs_courier_request', $couarray);    
    }
	
	public function getAgencylist(){
       
	   return $this->db->select("c.agency_name,c.agency_id")
            ->from("vs_courier_agency as c")              
            ->where('c.agency_status','1')
            ->get()
            ->result();
	}	
	
	public function getFrankingstatus($emp_location_name){
		
		// get location id
        $this->db->select('c.location_id');
        $this->db->from('vs_location As c');
        $this->db->where('c.location_name', $emp_location_name);
        $query = $this->db->get();
        $locationid = 0;
		foreach($query->result() as $row){
		$locationid = $row->location_id;
		}
		
		// check location id in franking table
		$this->db->select('c1.*');
        $this->db->from('vs_franking As c1');
        $this->db->where('c1.f_year',date('Y'));
		
        $query2 = $this->db->get();
		$frankingvalues = 0;
        foreach($query2->result() as $row){
            $loc_ids = explode(',',$row->f_locationids);          
			
			if(in_array($locationid ,$loc_ids)){
				$frankingvalues += $row->f_value;
			}
        }
        
		if($frankingvalues > 1000){
			return 1;
		}else{
			return 0;
		}
		 
    }
    function getCourierRequestDetails($id) {
        $data = [];
        if(is_numeric($id) && $id>0) {
            $data = $this->db->select("vl.*")
                ->from("vs_courier_request as vl")
                ->where('vl.req_id',$id)
                ->order_by('vl.req_id','desc')  
                ->get()
                ->row();
        }
        return $data;
    }

    function getTrackingCode(){
        $bussinessCode='';
        $locationCode='';
        $tracking_code = '';
        if($this->session->userdata['logged_in']['emp_user_id']); {
            $id = $this->session->userdata['logged_in']['emp_user_id'];

            $bussiness = $this->db->select("b.department_code")
                ->from("vs_business as b")
                ->join("vs_emp_details as e", "e.emp_departmentid=b.business_id")
                ->where('e.emp_id',$id)
                ->get()
                ->row();
            if(!empty($bussiness)) {
                $bussinessCode = $bussiness->department_code;
            }

            $location = $this->db->select("l.location_code")
                ->from("vs_location as l")
                ->join("vs_emp_details as e", "e.emp_locationid=l.location_id")
                ->where('e.emp_id',$id) 
                ->get()
                ->row(); 
            if(!empty($location)) {
                $locationCode = $location->location_code;
            }

            $lastRequest = $this->db->select("tracking_code")
                ->from("vs_courier_request")
                ->where('YEAR(CAST(req_datetime AS DATE)) = YEAR(CURRENT_TIMESTAMP)') 
                ->order_by("req_id", 'desc')
                ->get()
                //->limit(1)
                ->row(); 

            if(!empty($lastRequest)) {
                $requestId = $lastRequest->tracking_code;
                $lastSeq = (int)substr($requestId, -7);
                $newSeq = $lastSeq+1;
            }
            else 
                $newSeq = 1;
            $formattedNumber = sprintf('%06d', $newSeq);
            $tracking_code = $bussinessCode.$locationCode.date("y").$formattedNumber;
       }
       return $tracking_code;
    }
	
	 public function getLocationMaster(){ 
        return $this->db->select("vl.location_id, bu.bu_code,vl.location_code,vl.location_type, vl.location_name, vl.location_address, vl.isactive")
            ->from("vs_location as vl")
            ->join("vs_businessunit as bu","bu.bu_id=vl.business_id")            
            ->where('vl.isactive','1')
            ->get()
            ->result();
    }
}
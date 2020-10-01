<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Request extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Request_Model');
		$this->load->model('Employee_Model');
		$this->load->model('Vendor_Model');
		$this->load->library('email');
		
		
	}	
	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'request'
			);
			$data['locationlist'] = $this->Request_Model->getLocationList();
			
			$data['vendorlist'] = $this->Employee_Model->getVendorList();		
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Request/raiserequest', $data);
			$this->load->view('templates/footer');
		}	
	}
	public function getEmployeeDetails(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$tokenid= $this->input->get('tokenid');	
			$empdtais=$this->Request_Model->getEmpDetails($tokenid);
			echo json_encode($empdtais);
			return;		
		}
	}
	public function locationAddress(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$location= $this->input->get('location');	
			$address=$this->Request_Model->getLocationAddress($location);
			echo json_encode($address);
			return;		
		}
	}	
	public function split_name($name) {
    	$parts = array();
    	while ( strlen( trim($name)) > 0 ) {
	        $name = trim($name);
	        $string = preg_replace('#.*\s([\w-]*)$#', '$1', $name);
	        $parts[] = $string;
	        $name = trim( preg_replace('#'.$string.'#', '', $name ) );
    	}
	    if (empty($parts)) {
	        return false;
	    }
	    $parts = array_reverse($parts);
	    $name = array();
	    $name['first_name'] = $parts[0];
	    $name['middle_name'] = (isset($parts[2])) ? $parts[1] : '';
	    $name['last_name'] = (isset($parts[2])) ? $parts[2] : ( isset($parts[1]) ? $parts[1] : '');

    	return $name;
	}	
	public function addRequest()
	{	
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			
			$designation =$this->input->post('hiddendesignation');
			$newdesignation =$this->input->post('designation');
			$department =$this->input->post('hiddendepartment');
			$newdepartment =$this->input->post('department');
	        $business_unit =$this->input->post('hiddenbusiness_unit');
			$newbusiness_unit =$this->input->post('business_unit');
			$location =$this->input->post('hiddenlocation');
			$newlocation =$this->input->post('location');
			$officeaddress =$this->input->post('hiddenofficeaddress');
			$newofficeaddress =$this->input->post('officeaddress');
			$telephone =$this->input->post('hiddentelephone');
			$newtelephone =$this->input->post('telephone');
			$mobile =$this->input->post('hiddenmobile');
			$newmobile =$this->input->post('mobile');
			$costcenter =$this->input->post('hiddencostcenter');
			$newcostcenter =$this->input->post('costcenter');
			$emailaddress=$this->input->post('emailaddress');
			$empname=$this->input->post('empname');
			$name = $this->split_name($empname);
			$tokenno =$this->input->post('hiddentokenid');
			$empid = $this->Request_Model->userVerified($tokenno);
			if($empid>0){
				$emp_id= $empid->emp_id;
			}
			else{
				$userarray = array('emp_username' => $tokenno,
									'emp_firstname'=>$name['first_name'],
									'emp_lastname'=>$name['last_name'],
									'emp_email'=>$emailaddress,
									'emp_role'=>'Employee',
									'emp_status'=>'Active',
									'is_ldap'=>'1',			
				);
				$emp_id=$this->Request_Model->insertEmp($userarray);
			}			
			if($this->input->post('buttondraft'))
			{
				$status='Draft';
			}
			if($this->input->post('buttonsubmit'))
			{				
				if($designation==$newdesignation && $department==$newdepartment && $business_unit==$newbusiness_unit /*&& $location==$newlocation && $officeaddress==$officeaddress && $telephone==$newtelephone*/ && $mobile==$newmobile && $costcenter==$newcostcenter)
				{
					$status='Request Send to Vendor';
				}
				else
				{
					$status='Pending Approval from HR';
				}
				$reqsubmitedby=$this->session->userdata['logged_in']['emp_user_id'];
				$reqsubmiteddate=date('Y-m-d H:i:s');
			}
			$requestarray = array('req_emp_id'=>$emp_id,
							'req_emp_token'=>$tokenno,
							'req_emp_name'=>$empname,
							'req_emp_org_desig'=>$designation,
							'req_emp_new_desig'=>$newdesignation,
							'req_emp_org_dept'=>$department,
							'req_emp_new_dept'=>$newdepartment,
							'req_emp_org_buss_unit'=>$business_unit,
							'req_emp_new_buss_unit'=>$newbusiness_unit,
							'req_emp_location_name'=>$location,
							'req_emp_new_location_name'=>$newlocation,
							'req_emp_address'=>$officeaddress,
							'req_emp_new_address'=>$newofficeaddress,
							'req_emp_stdcode'=>$this->input->post('stdcode'),
							'req_emp_landline'=>$telephone, 
							'req_emp_new_landline'=>$newtelephone,
							'req_emp_fax'=>$this->input->post('fax'),
							'req_emp_mobile'=>$mobile,
							'req_emp_new_mobile'=>$newmobile, 
							'req_emp_email'=>$emailaddress,
							'req_emp_costcenter'=>$costcenter,
							'req_emp_new_costcenter'=>$newcostcenter,
							'req_emp_wbs'=>$this->input->post('wbselement'), 
							'req_emp_mgr_token'=>$this->input->post('hiddenmgrtoken'), 
							'req_emp_mgr_name'=>$this->input->post('hiddenmgrname'),
							'req_emp_mgr_email'=>$this->input->post('hiddenmgremail'),
							'req_vendor_id'=>$this->input->post('vendor'),
							'req_emp_hrmgr_token'=>$this->input->post('hiddenhrmgrtoken'),
							'req_emp_hrmgr_name'=>$this->input->post('hiddenhrmgrname'),
							'req_emp_hrmgr_email'=>$this->input->post('hiddenhrmgremail'),
							'req_emp_sr_hrmgr_token'=>$this->input->post('hiddensrhrmgrtoken'),
							'req_emp_sr_hrmgr_name'=>$this->input->post('hiddensrhrmgrname'),
							'req_emp_sr_hrmgr_email'=>$this->input->post('hiddensrhrmgremail'),
							'req_status'=> $status,
							'req_createddate'=>date('Y-m-d H:i:s'),
							'req_createdby'=>$this->session->userdata['logged_in']['emp_user_id'],
							'req_submittedby'=>$reqsubmitedby,
							'req_submitteddate'=>$reqsubmiteddate);			

			$id = $this->Request_Model->insertRequest($requestarray);
			if($id > 0){
				
				if($status=="Request Send to Vendor") {
					$this->vendorMail($requestarray);
					$this->venEmpMail($empname, $emailaddress);
				}
				if($status == "Pending Approval from HR"){
					$this->hrMail($requestarray, $id);
					$this->empHrMail($requestarray);
				}        
			    $this->session->set_flashdata('message', 'save successfully');
			    redirect("Request/requestlist");
			} else {
			    #set exception message
			    $this->session->set_flashdata('exception', 'please try again');
			    redirect("Request/requestlist");
			}			
		}
	}
	public function editRequest()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$req_id =$this->input->post('req_id');
			if($req_id){
				$designation =$this->input->post('hiddendesignation');
				$newdesignation =$this->input->post('designation');
				$department =$this->input->post('hiddendepartment');
				$newdepartment =$this->input->post('department');
		        $business_unit =$this->input->post('hiddenbusiness_unit');
				$newbusiness_unit =$this->input->post('business_unit');
				$location =$this->input->post('hiddenlocation');
				$newlocation =$this->input->post('location');
				$officeaddress =$this->input->post('hiddenofficeaddress');
				$newofficeaddress =$this->input->post('officeaddress');
				$telephone =$this->input->post('hiddentelephone');
				$newtelephone =$this->input->post('telephone');
				$mobile =$this->input->post('hiddenmobile');
				$newmobile =$this->input->post('mobile');
				$costcenter =$this->input->post('hiddencostcenter');
				$newcostcenter =$this->input->post('costcenter');
				$emailaddress=$this->input->post('emailaddress');
				if($this->input->post('buttondraft'))
				{
					$status='Draft';
				}
				if($this->input->post('buttonsubmit'))
				{
					if($designation==$newdesignation && $department==$newdepartment && $business_unit==$newbusiness_unit /*&& $location==$newlocation && $officeaddress==$newofficeaddress && $telephone==$newtelephone*/ && $mobile==$newmobile && $costcenter==$newcostcenter)
						{
							$status='Request Send to Vendor';
						}
						else
						{
							$status='Pending Approval from HR';
						}
						$reqsubmitedby=$this->session->userdata['logged_in']['emp_user_id'];
						$reqsubmiteddate=date('Y-m-d H:i:s');
				}
				$requestarray = array(
								'req_emp_org_desig'=>$designation,
								'req_emp_new_desig'=>$newdesignation,
								'req_emp_org_dept'=>$department,
								'req_emp_new_dept'=>$newdepartment,
								'req_emp_org_buss_unit'=>$business_unit,
								'req_emp_new_buss_unit'=>$newbusiness_unit,
								'req_emp_location_name'=>$location,
								'req_emp_new_location_name'=>$newlocation,
								'req_emp_address'=>$officeaddress,
								'req_emp_new_address'=>$newofficeaddress,
								'req_emp_stdcode'=>$this->input->post('stdcode'),
								'req_emp_landline'=>$telephone, 
								'req_emp_new_landline'=>$newtelephone,
								'req_emp_fax'=>$this->input->post('fax'),
								'req_emp_mobile'=>$mobile,
								'req_emp_new_mobile'=>$newmobile, 
								'req_emp_email'=>$emailaddress,
								'req_emp_costcenter'=>$costcenter,
								'req_emp_new_costcenter'=>$newcostcenter,
								'req_emp_wbs'=>$this->input->post('wbselement'),
								'req_vendor_id'=>$this->input->post('vendor'),
								'req_status'=> $status,
								'req_modifieddate'=>date('Y-m-d H:i:s'),
								'req_createdby'=>$this->session->userdata['logged_in']['emp_user_id'],
								'req_modifiedby'=>$reqsubmitedby,
								'req_submitteddate'=>$reqsubmiteddate
							);					
				$id = $this->Request_Model->updateRequest($requestarray, $req_id);
				if($id > 0){ 
					if($status=="Request Send to Vendor") {
						$this->vendorMail($requestarray);
						$this->venEmpMail($empname, $emailaddress);
					}
					if($status == "Pending Approval from HR") {
						$this->hrMail($requestarray, $id);
						$this->empHrMail($requestarray);
					}          
				    $this->session->set_flashdata('message', 'update successfully');
				        redirect("Request/requestlist");
				} else {
				    #set exception message
				    $this->session->set_flashdata('exception', 'please try again');
				    redirect("Request/requestlist");
				}
			}
			else{
				#set exception message
				$this->session->set_flashdata('exception', 'please try again');
				redirect("Request/requestlist");
			}			
		}
	}
	public function requestlist(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'requestlist'
			);
			$data['reg_master'] = $this->Request_Model->requestList();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Request/requestlist',$data);
			$this->load->view('templates/footer');
		}	
	}

	public function receivelist(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'receivelist'
			);
			$data['reg_master'] = $this->Request_Model->receiveList();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Request/receivelist',$data);
			$this->load->view('templates/footer');
		}	
	}
	public function edit($req_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'requestlist'
			);
			if($req_id){
				$data['locationlist'] = $this->Request_Model->getLocationList();
				$data['vendorlist'] = $this->Employee_Model->getVendorList();
				$data['reg'] = $this->Request_Model->getRequestDetails($req_id);

				$this->load->view('templates/header', $pgdata);
				$this->load->view('Request/editrequest',$data);
				$this->load->view('templates/footer');
			}
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Request/requestlist");
		    }
		}
	}
	public function preview($req_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'requestlist'
			);
			if($req_id){				
				$data['reg'] = $this->Request_Model->getCourierRequestDetails($req_id);
				$this->load->view('templates/header', $pgdata);
				$this->load->view('Request/previewrequest',$data);
				$this->load->view('templates/footer');
			}
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Request/requestlist");
		    }
		}
	}
	public function delete($req_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($req_id){
				$reqid = $this->Request_Model->deleteRequest($req_id);
		        if($reqid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Request/requestlist");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Request/requestlist");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Request/requestlist");
		    }
		}
	}
	//send vendor email
	public function vendorMail($reqarray)
	{
		if($reqarray){
			$vendetails=$this->Vendor_Model->getVendorMaster($reqarray['req_vendor_id']);
			$ven_mail=$vendetails->vendor_email;
			$data['ven_name']=$vendetails->vendor_name;
			$data['reqarray']=$reqarray;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($ven_mail);
			//$this->email->cc($requester_email);
			$subject="Visiting Card Request for ". $reqarray['req_emp_name']."(".$reqarray['req_emp_token'] .")";
			$this->email->subject($subject);
			$this->email->message($this->load->view('Email/vendor_template',$data,true));
			$this->email->send();
		}
	}
	//vendor with emloyee
	public function venEmpMail($empname, $emailaddress)
	{
		if($emailaddress){
			$data['emp_name']=$empname;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($emailaddress);
			//$this->email->cc($requester_email);
			$this->email->subject('Visiting Card Request Approved');
			$this->email->message($this->load->view('Email/emp_template',$data,true));
			$this->email->send();
		}
	}
	// send hr email
	public function hrMail($reqarray, $id){
		if($reqarray){
			$hr_email=$reqarray['req_emp_hrmgr_email'];
			$data['reqarray']=$reqarray;
			$data['req_id']=$id;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($hr_email);
			$subject="Visiting Card Request for ". $reqarray['req_emp_name']."(".$reqarray['req_emp_token'] .")";
			$this->email->subject($subject);
			$this->email->message($this->load->view('Email/hr_template',$data,true));
			$this->email->send();
		}
	}
	public function empHrMail($reqarray){
		if($reqarray){
			$emp_email=$reqarray['req_emp_email'];
			$data['reqarray']=$reqarray;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($emp_email);
			//$this->email->cc($requester_email);
			$this->email->subject('Visiting Card Request');
			$this->email->message($this->load->view('Email/emp_hr_template',$data,true));
			$this->email->send();
		}
	}
	// hr approval vendro mail
	public function hrvendorMail($reqarray)
	{
		if($reqarray){
			$vendetails=$this->Vendor_Model->getVendorMaster($reqarray->req_vendor_id);
			$ven_mail=$vendetails->vendor_email;
			$data['ven_name']=$vendetails->vendor_name;
			$data['reqarray']=$reqarray;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($ven_mail);
			//$this->email->cc($requester_email);
			$subject="Visiting Card Request for ". $reqarray->req_emp_name."(".$reqarray->req_emp_token .")";
			$this->email->subject($subject);
			$this->email->message($this->load->view('Email/hr_vendor_template',$data,true));
			$this->email->send();
		}
	}
	//hr approval vendor with emloyee
	public function hrvenEmpMail($reqarr)
	{
		if($emailaddress){
			$data['emp_name']=$reqarr->req_emp_name;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($reqarr->req_emp_email);
			//$this->email->cc($requester_email);
			$this->email->subject('Visiting Card Request Approved');
			$this->email->message($this->load->view('Email/emp_template',$data,true));
			$this->email->send();
		}
	}
	//hr rejected mail to employee
	public function hrRejEmpMail($name, $email, $hrname, $reason)
	{
		if($email){
			$data['emp_name']= $name;
			$data['hr_name']= $hrname;
			$data['hr_reason']= $reason;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from(sendEmailID);
			$this->email->to($email);
			//$this->email->cc($requester_email);
			$this->email->subject('Visiting Card Request Rejected');
			$this->email->message($this->load->view('Email/hr_reject_emp_template',$data,true));
			$this->email->send();
		}
	}
	public function hrApproval($id, $hrtoken){
		if($id){
			$req_id= base64_decode($id);
			$hr_token = base64_decode($hrtoken);
			$req = $this->Request_Model->getRequestDetails($req_id);
			if($req->req_status=="Pending Approval from HR"){
				$arrhrapproval = array('req_status' => 'Request Send to Vendor',
				'req_hr_actionby'=>$hr_token,
				'req_hr_actiondate'=>date('Y-m-d H:i:s'));
				$id = $this->Request_Model->updateRequest($arrhrapproval, $req_id);
				if($id > 0){ 
					$data['reg'] = $this->Request_Model->getRequestDetails($req_id);
					$rearr=$data['reg'];
					$this->hrvendorMail($rearr);
					$this->hrvenEmpMail($rearr);
					$this->load->view('Email/hrapproval',$data);
				}
				else
				{
					exit;
				}
			}
			else{
				$this->load->view('Email/request');
			}
		}
	}
	public function hrRejected($id, $hrtoken){
		if($id){
			$data['req_id'] = base64_decode($id);
			$data['hr_token'] = base64_decode($hrtoken);
			$data['reg'] = $this->Request_Model->getRequestDetails($data['req_id']);
			if($data['reg']->req_status=="Pending Approval from HR"){		
				$this->load->view('Email/hrrejected',$data);
			}
			else{
				$this->load->view('Email/request');
			}
		}
	}
	public function hrSubmitReason(){
		$req_id=$this->input->post('reqid');
		$empname=$this->input->post('empname');
		$empmail=$this->input->post('empmail');
		$hrname=$this->input->post('hrname');
		$reason=$this->input->post('hrreason');
		$emptoken=$this->input->post('emptoken');
		$arrhrapproval = array('req_status' => 'Declined by HR',
			'req_hr_actionby'=>$this->input->post('hrtoken'),
			'req_hr_rejectremark'=>$reason,
			'req_hr_actiondate'=>date('Y-m-d H:i:s'));

		$id = $this->Request_Model->updateRequest($arrhrapproval, $req_id);
		if($id > 0){
			$this->hrRejEmpMail($empname, $empmail, $hrname, $reason);
			$response['emp_name']= $empname;
			$response['token'] = $emptoken;
			$this->load->view('Email/viewSuccessfully',$response);
		}
		else
		{
			exit;
		}
	}
	/*public function encrypt(){
		$encryption_key = openssl_random_pseudo_bytes(32);
                $iv = openssl_random_pseudo_bytes(16);
                $key = openssl_encrypt("12345", 'aes-256-cbc', $encryption_key, OPENSSL_RAW_DATA, $iv);
               
                $key=base64_encode($key);
                echo $key;
	}*/
	//reports
	public function requestReport(){

		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'requestreport'
			);
			$data['locationlist'] = $this->Request_Model->getLocationList();
			$data['reg_master'] = $this->Request_Model->getRequestReport();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Request/reportlist',$data);
			$this->load->view('templates/footer');
		}
	}
	//search report
	public function searchReport(){
	
			//$postdata = array();
			$location= $this->input->post('location');
			$to_date= $this->input->post('to_date');
			$from_date= $this->input->post('from_date');
			$to_date = date('Y-m-d H:i:s',strtotime($to_date));
			$from_date = date('Y-m-d H:i:s',strtotime($from_date));	
			$report=$this->Request_Model->searchReport($location, $from_date, $to_date);
			$rows=array();
			if (!empty($report)) { 
               foreach ($report as $req) {
                   $rows[] =  array($req->RequesterToken,$req->DisplayName,$req->Designation,$req->Department_Division,$req->Business_Unit,$req->Address,$req->Admin,$req->ISDCode,$req->Telephone,$req->Mobile,$req->Official_Email,$req->Cost_Center,$req->WBS,$req->Vendor_Name,$req->Created_Date,$req->HR_Approve_Date,$req->Current_Status,$req->HR_First_Escalation,$req->HR_First_Escalation_Date,$req->HR_Second_Escalation,$req->HR_Second_Escalation_Date,$req->Request_received_by_Vendor,$req->Dispatched_Date,$req->Close_Date,$req->Courie_details,$req->VENDOR_First_Escalation,$req->VENDOR_First_Escalation_Date,$req->VENDOR_Second_Escalation,$req->VENDOR_Second_Escalation_Date,$req->VENDOR_Third_Escalation,$req->VENDOR_Third_Escalation_Date,$req->VENDOR_Fourth_Escalation,$req->VENDOR_Third_Fourth_Date);
                } 
            }
			echo json_encode(array("data" => $rows));
			return;		
		
	}	



    //Courier
	function requestcourier($id=0)
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($id>0) {
				$data['reg'] = $this->Request_Model->getCourierRequestDetails($id);
				if(empty($data['reg'])) {
					redirect(base_url());		
				}
			}
			$pgdata = array(
				'pgdata' => 'request'
			);
			$data['locationlist'] = $this->Request_Model->getLocationList();
			$data['fr_locations'] = $this->Request_Model->getLocationMaster();
			$data['agencies'] = $this->Request_Model->getAgencylist();
			//print_r($data);exit;
			
			if($this->session->userdata['logged_in']['role'] == 'Employee'){
				
			$tokenid=$this->session->userdata['logged_in']['emp_username'];
			$data['empdtais']=$this->Request_Model->getEmpDetail($tokenid);
			if($data['empdtais']== null){
				 $this->session->set_flashdata('error', 'Your employee master data record not found. Please contact support!');
			}
			}
			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Request/raisecourier', $data);
			$this->load->view('templates/footer');
		}	
	}

	 //Courier
	function receivecourier($id=0)
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($id>0) {
				$data['reg'] = $this->Request_Model->getCourierRequestDetails($id);

				if(empty($data['reg'])) {
					redirect(base_url());		
				}
			}

			$pgdata = array(
				'pgdata' => 'request'
			);
			$data['locationlist'] = $this->Request_Model->getLocationList();
			$data['agencies'] = $this->Request_Model->getAgencylist();

			$this->load->view('templates/header', $pgdata);
			$this->load->view('Request/receivecourier', $data);
			$this->load->view('templates/footer');
		}	
	}
	
	public function addCourier()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//print_r($this->input->post());exit;
			if(!($this->input->post('buttondraft')))
				$data['tracking_code'] =$this->Request_Model->getTrackingCode();
			$data['from_id'] =$this->input->post('from_id');
			$data['req_emp_id'] =$this->input->post('req_emp_id');
			$data['req_emp_token'] =$this->input->post('req_emp_token');
			$data['req_mod_of_delivery'] =$this->input->post('mod_of_delivery');
			$data['req_courier'] =$this->input->post('courier');
			$data['req_emp_name'] =$this->input->post('empname');
			$data['req_emp_dept'] =$this->input->post('department');
			$data['req_emp_extension'] =$this->input->post('extension');
			$data['req_receiever_emp_token'] =$this->input->post('receiver_tokenno');
			$data['req_receiever_emp_name'] =$this->input->post('receiver_empname');
			$data['req_receiever_emp_address'] =$this->input->post('receiver_address');
			$data['req_receiever_emp_pincode'] =$this->input->post('receiver_pincode');
			$data['req_receiever_emp_city'] =$this->input->post('receiver_city');
			$data['req_receiever_type'] =$this->input->post('receiver_type');
			$data['req_receiever_telephone'] =$this->input->post('receiver_telephone');
			$data['req_receiever_remarks'] =$this->input->post('receiver_remark');
			$data['req_status'] ="Submitted";
			
			$data['req_unit'] =$this->input->post('unit_type');
			$data['req_weight'] =$this->input->post('req_weight');
			$data['req_agency'] =$this->input->post('req_agency');
			
			$data['req_fee'] =$this->input->post('req_fee');
			$data['req_datetime'] =date('Y-m-d H:i:s');
			
			
			$id = $this->Request_Model->insertCourier($data);
			
		    $this->session->set_flashdata('message', 'Courier Send Sucessfully');
		    redirect("Request/requestcourier");
		}
	}

	public function addReceivedCourier()
	{
		if(NULL==$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
		else {
			if(!($this->input->post('buttondraft')))
				$data['tracking_code'] =$this->Request_Model->getTrackingCode();

			$data['req_mod_of_delivery'] = $this->input->post('mod_of_delivery');
			$data['req_courier'] = ($this->input->post('courier'))? $this->input->post('courier') : NULL;
			$data['req_agency'] = ($this->input->post('req_agency'))? $this->input->post('req_agency') : NULL;
			$data['req_emp_name'] = ($this->input->post('req_emp_name'))? $this->input->post('req_emp_name') : NULL;
			$data['req_emp_city'] = ($this->input->post('req_emp_city'))? $this->input->post('req_emp_city') : NULL;
			$data['req_receiever_emp_token'] = $this->input->post('receiver_tokenno');
			$data['req_receiever_emp_name'] = $this->input->post('receiver_empname');
			$data['req_receiever_type'] = 'Internal';
			$data['req_receiever_telephone'] = $this->input->post('receiver_telephone');
			$data['req_receiever_remarks'] = $this->input->post('receiver_remark');
			$data['req_emp_address'] = $this->input->post('req_emp_address');
			$data['req_emp_pincode'] = $this->input->post('req_emp_pincode');
			$data['req_emp_type'] = 'External';
			$data['req_emp_telephone'] = $this->input->post('req_emp_telephone');
			$data['req_emp_remarks'] = $this->input->post('req_emp_remark');
			$data['req_status'] = "InwardGenerated";
			
			$data['req_datetime'] =date('Y-m-d H:i:s');
			$id = $this->Request_Model->insertCourier($data);
			if($id != '')
				$this->session->set_flashdata('message', 'Courier Received Sucessfully');
			else
				$this->session->set_flashdata('message', 'Something went wrong!');
		    redirect("Request/receivecourier");			
		}
	}

	public function editReceivedCourier()
	{print_r($_POST);die;
		if(NULL==$this->session->userdata('logged_in')) {
			redirect(base_url());
		}
		else {
			if(!($this->input->post('buttondraft')))
				$data['tracking_code'] =$this->Request_Model->getTrackingCode();
			
			$data['from_id'] =$this->input->post('from_id');
			$data['req_emp_id'] =$this->input->post('req_emp_id');
			$data['req_emp_token'] =$this->input->post('req_emp_token');
			$data['req_mod_of_delivery'] =$this->input->post('mod_of_delivery');
			$data['req_courier'] =$this->input->post('courier');
			$data['req_emp_name'] =$this->input->post('req_emp_name');
			$data['req_emp_dept'] =$this->input->post('req_emp_dept');
			$data['req_emp_extension'] =$this->input->post('req_emp');
			$data['req_receiever_emp_token'] =$this->input->post('receiver_tokenno');
			$data['req_receiever_emp_name'] =$this->input->post('receiver_empname');
			$data['req_receiever_emp_address'] =$this->input->post('receiver_address');
			$data['req_receiever_emp_city'] =$this->input->post('receiver_city');
			$data['req_receiever_emp_pincode'] =$this->input->post('receiver_pincode');
			$data['req_receiever_type'] =$this->input->post('receiver_type');
			$data['req_receiever_telephone'] =$this->input->post('receiver_telephone');
			$data['req_receiever_location'] =$this->input->post('req_receiever_location');
			$data['req_receiever_remarks'] =$this->input->post('receiver_remark');
			$data['req_emp_address'] =$this->input->post('req_emp_address');
			$data['req_emp_pincode'] =$this->input->post('req_emp_pincode');
			$data['req_emp_type'] =$this->input->post('req_emp_type');
			$data['req_emp_telephone'] =$this->input->post('req_emp_telephone');
			$data['req_emp_remarks'] =$this->input->post('req_emp_remark');
			$data['req_status'] ="ReceivedPending";
			$data['req_unit'] =$this->input->post('unit_type');
			$data['req_weight'] =$this->input->post('req_weight');
			$data['req_agency'] =$this->input->post('req_agency');
			$data['req_awb'] =$this->input->post('req_awb');
			$data['req_fee'] =$this->input->post('req_fee');
			$req_id =$this->input->post('req_id');
			$data['req_datetime'] =date('Y-m-d H:i:s');
			$id = $this->Request_Model->updateCourier($data, $req_id);
		    $this->session->set_flashdata('message', 'Courier Received Successfully');
		    redirect("Request/receivecourier");
		}
	}
	
	public function checkFrankingstatus()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$emp_location_name= $this->input->get('emp_location_name');	
			$franking=$this->Request_Model->getFrankingstatus($emp_location_name);
			if($franking == 0){
			$this->session->set_flashdata('message', 'Franking Cost has been reached, Please Contact Desk Person');
			redirect("Request/requestcourier");		
			}
			return $franking;
		}
	}

	public function editCourier()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$req_id =$this->input->post('req_id');
			if($req_id){
				if(!($this->input->post('buttondraft')))
					$data['tracking_code'] =$this->Request_Model->getTrackingCode();
				$data['from_id'] =$this->input->post('from_id');
				$data['req_emp_id'] =$this->input->post('req_emp_id');
				$data['req_emp_token'] =$this->input->post('req_emp_token');
				$data['req_mod_of_delivery'] =$this->input->post('mod_of_delivery');
				$data['req_courier'] =$this->input->post('courier');
				$data['req_emp_name'] =$this->input->post('empname');
				$data['req_emp_dept'] =$this->input->post('department');
				$data['req_emp_extension'] =$this->input->post('extension');
				$data['req_receiever_emp_token'] =$this->input->post('receiver_tokenno');
				$data['req_receiever_emp_name'] =$this->input->post('receiver_empname');
				$data['req_receiever_emp_address'] =$this->input->post('receiver_address');
				$data['req_receiever_emp_pincode'] =$this->input->post('receiver_pincode');
				$data['req_receiever_emp_city'] =$this->input->post('receiver_city');
				$data['req_receiever_location'] =$this->input->post('req_receiever_location');
				$data['req_receiever_type'] =$this->input->post('receiver_type');
				$data['req_emp_type'] =$this->input->post('req_emp_type');
				$data['req_receiever_telephone'] =$this->input->post('receiver_telephone');
				$data['req_receiever_remarks'] =$this->input->post('receiver_remark');
				$data['req_status'] ="Submitted";				
				$data['req_unit'] =$this->input->post('unit_type');
				$data['req_weight'] =$this->input->post('req_weight');
				$data['req_agency'] =$this->input->post('req_agency');

				$data['req_fee'] =$this->input->post('req_fee');
				$data['req_datetime'] =date('Y-m-d H:i:s');
				$id = $this->Request_Model->updateCourier($data, $req_id);

			    $this->session->set_flashdata('message', 'Courier update Sucessfully');
			    redirect("Request/requestlist");
			}			
		}
	}
}
?>
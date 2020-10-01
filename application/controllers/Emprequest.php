<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Emprequest extends CI_Controller {
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
				'pgdata' => 'emprequest'
			);
			$data['locationlist'] = $this->Request_Model->getLocationList();
			$data['vendorlist'] = $this->Employee_Model->getVendorList();
			$tokenid=$this->session->userdata['logged_in']['emp_username'];
			$data['empdtais']=$this->Request_Model->getEmpDetail($tokenid);
			if($data['empdtais']== null){
				 $this->session->set_flashdata('error', 'Your employee master data record not found. Please contact support!');
			}
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Emprequest/raiserequest', $data);
			$this->load->view('templates/footer');
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
			    redirect("Emprequest/requestlist");
			} else {
			    #set exception message
			    $this->session->set_flashdata('exception', 'please try again');
			    redirect("Emprequest/requestlist");
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
				$emailaddress=$this->input->post('emailaddress');
				$newcostcenter =$this->input->post('costcenter');
				$empname=$this->input->post('empname');
				$tokenno =$this->input->post('hiddentokenid');
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
				$requestarray = array(
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
				       redirect("Emprequest/requestlist");
				} else {
				    #set exception message
				    $this->session->set_flashdata('exception', 'please try again');
				    redirect("Emprequest/requestlist");
				}
			}
			else{
				#set exception message
				$this->session->set_flashdata('exception', 'please try again');
				redirect("Emprequest/requestlist");
			}			
		}
	}
	public function requestlist(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'emprequestlist'
			);
			$empid=$this->session->userdata['logged_in']['emp_user_id'];

			$data['reg_master'] = $this->Request_Model->requestEmpList($empid);
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Emprequest/requestlist',$data);
			$this->load->view('templates/footer');
		}	
	}
	//hr approve & rejected list
	public function approveReqlist(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'approverequestlist'
			);
			$empid=$this->session->userdata['logged_in']['emp_username'];
			$data['reg_master'] = $this->Request_Model->hrRequestlist($empid);
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Emprequest/approvallist',$data);
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
				$this->load->view('Emprequest/editrequest',$data);
				$this->load->view('templates/footer');
			}
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Emprequest/requestlist");
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
				$data['locationlist'] = $this->Request_Model->getLocationList();
				$data['vendorlist'] = $this->Employee_Model->getVendorList();
				$data['reg'] = $this->Request_Model->getRequestDetails($req_id);
				$this->load->view('templates/header', $pgdata);
				$this->load->view('Emprequest/previewrequest',$data);
				$this->load->view('templates/footer');
			}
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Emprequest/requestlist");
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
		           redirect("Emprequest/requestlist");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Emprequest/requestlist");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Emprequest/requestlist");
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
	//hr link approval
	public function hrlinkApproval(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$req_id=$this->input->get($id);
			if($req_id)
			{			
				$arrhrapproval = array('req_status' => 'Request Send to Vendor',
										'req_hr_actionby'=>$this->session->userdata['logged_in']['emp_username'],
										'req_hr_actiondate'=>date('Y-m-d H:i:s'));
				$id = $this->Request_Model->updateRequest($arrhrapproval, $req_id['id']);
				if($id > 0){ 
					$data['reg'] = $this->Request_Model->getRequestDetails($req_id['id']);
					$rearr=$data['reg'];
					$this->hrvendorMail($rearr);
					$this->hrvenEmpMail($rearr);
					echo json_encode($rearr);
					return;
				}
			}
		}
	}
	public function hrlinkRejected(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$req_id=$this->input->post('reqid');
			$reason=$this->input->post('reason');
			$empname=$this->input->post('empname');
			$empmail=$this->input->post('empmail');
			$emptoken=$this->input->post('emptoken');
			$hrname=$this->input->post('hrname');
			$arrhrapproval = array('req_status' => 'Request Send to Vendor',
			'req_hr_actionby'=>$this->session->userdata['logged_in']['emp_username'],
			'req_hr_rejectremark'=>$reason,
			'req_hr_actiondate'=>date('Y-m-d H:i:s'));
			$id = $this->Request_Model->updateRequest($arrhrapproval, $req_id);
			if($id > 0){
				$this->hrRejEmpMail($empname, $empmail, $hrname, $reason);
				$response['emp_name']= $empname;
				$response['token'] = $emptoken;
				echo json_encode($response);
				return;
			}
		}
	}
	
}
?>
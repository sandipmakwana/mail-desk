<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendorreq extends CI_Controller {
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
	}
	public function index(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'vendorrequestlist'
			);
			$vendorid=$this->session->userdata['logged_in']['vendorid'];
			$data['reg_master'] = $this->Request_Model->requestVendorList($vendorid);
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Vendorrequest/requestlist',$data);
			$this->load->view('templates/footer');
		}	
	}
	public function dispatch(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'dispatchrequestlist'
			);
			$vendorid=$this->session->userdata['logged_in']['vendorid'];
			$data['reg_master'] = $this->Request_Model->requestVenDispatchList($vendorid);
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Vendorrequest/dispatchlist',$data);
			$this->load->view('templates/footer');
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
				$this->load->view('Vendorrequest/previewrequest',$data);
				$this->load->view('templates/footer');
			}
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Vendorrequest/requestlist");
		    }
		}
	}	
	public function dispatchRequest(){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$req_id=$this->input->post('reqid');
			$empname=$this->input->post('empname');
			$empmail=$this->input->post('empmail');
			$dispatcharr = array('req_vendor_dispatched_actiondate' => date('Y-m-d H:i:s'),
								 'req_vendor_dispatchdate'=>date('Y-d-m', strtotime($this->input->post('dispatch_date'))),
								  'req_vendor_remark'=>$this->input->post('dispatch_details'),
								  'req_status'=>'Dispatched');
			$id = $this->Request_Model->updateRequest($dispatcharr, $req_id);
			if($id > 0){ 
				$this->empMail($empname,$empmail);
				echo json_encode(array("status" => TRUE));
			}
		}
	}
	public function empMail($name, $email){
		if($email){
			$data['emp_name']=$name;
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->from('noreply@intellectsoftsol.com');
			$this->email->to($email);
			//$this->email->cc($requester_email);
			$this->email->subject('Visiting Card Request');
			$this->email->message($this->load->view('Email/dispatch_emp_template',$data,true));
			$this->email->send();
		}
	}
}
?>
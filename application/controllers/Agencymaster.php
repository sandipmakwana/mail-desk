<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agencymaster extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Agency_Model', 'agency_model');
	}

	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			$data['pgdata'] = 'agencymaster';
			$data['agency_master'] = $this->agency_model->agencyMaster();
			$this->template->view('Agency/agencymasterlist',$data);
		}	
	}

	public function agencyForm($agency_id=null){
		if(NULL == $this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			if($agency_id){
				$data['buttonname'] = "Update";
				$data['agency_id'] = $agency_id;
        		$data['agency_master'] = $this->agency_model->getAgencyMaster($agency_id);
			}else{
				$data['buttonname'] = "Save";
			}
			$data['fr_locations'] = $this->agency_model->getLocationMaster();
			$data['pgdata'] = 'agencymaster';
			$this->template->view('Agency/agencymasterform',$data);
		}	
	}

	public function addAgencyMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
		    $agency_id = $this->input->post('agency_id');
//			$locationids = implode(',', $this->input->post('agency_delivery_locations'));
		    if($agency_id){
				$agencyarray = array( //update array
					'agency_name'				=> strip_tags($this->input->post('agency_name')),
					'agency_code'				=> strip_tags($this->input->post('agency_code')),
					'agency_sap_code'			=> strip_tags($this->input->post('agency_sap_code')),
					'agency_address'			=> strip_tags($this->input->post('agency_address')),
					'agency_person_name'		=> strip_tags($this->input->post('agency_person_name')),
					'agency_mobile_number'		=> strip_tags($this->input->post('agency_mobile_number')),
					'agency_email_address'		=> strip_tags($this->input->post('agency_email_address')),
					'agency_tracking_url'		=> strip_tags($this->input->post('agency_tracking_url')),
					'agency_delivery_locations'	=> strip_tags($this->input->post('agency_delivery_location')),
				);

				$agency_id = $this->agency_model->updateAgencyMaster($agencyarray, $agency_id);
				if($agency_id>0){
				  $this->session->set_flashdata('message', 'update successfully');
				   redirect("Agencymaster");
				} else {
				  #set exception message
				  $this->session->set_flashdata('exception', 'Please try again');
				  redirect("Agencymaster");
				}
			}else{
				$agencyarray = array( //insert array
					'agency_name'				=> strip_tags($this->input->post('agency_name')),
					'agency_code'				=> strip_tags($this->input->post('agency_code')),
					'agency_sap_code'			=> strip_tags($this->input->post('agency_sap_code')),
					'agency_address'			=> strip_tags($this->input->post('agency_address')),
					'agency_person_name'		=> strip_tags($this->input->post('agency_person_name')),
					'agency_mobile_number'		=> strip_tags($this->input->post('agency_mobile_number')),
					'agency_email_address'		=> strip_tags($this->input->post('agency_email_address')),
					'agency_tracking_url'		=> strip_tags($this->input->post('agency_tracking_url')),
					'agency_delivery_locations'	=> strip_tags($this->input->post('agency_delivery_locations')),
					'created_at'				=> date('Y-m-d H:i:s'),
				);
				$new_agency_id = $this->agency_model->insertAgencyMaster($agencyarray);
				if($new_agency_id>0){
					$this->session->set_flashdata('message', 'save successfully');
					redirect("Agencymaster");
				} else {
					#set exception message
					$this->session->set_flashdata('exception', 'please try again');
					redirect("Agencymaster");
				}
			}
		}
	}

	public function delete($agencyid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($agencyid){
				$deleted_agency = $this->agency_model->deleteAgencyMaster($agencyid);
		        if($deleted_agency){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Agencymaster");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Agencymaster");
		        }
		    } else {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Agencmaster");
		    }
		}
	}

	public function active($agency_id = null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			if($agency_id){
				$updated_agency = $this->agency_model->activeAgencyMaster($agency_id);
		        if($updated_agency){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Agencymaster");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Agencymaster");
		        }
		    }else{
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Agencymaster/index");
		    }
		}
	}

	public function inactive($agencyid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($agencyid){
				$updated_agency = $this->agency_model->inactiveAgencyMaster($agencyid);
		        if($updated_agency){
		            $this->session->set_flashdata('message', 'Deactivated successfully');
		           redirect("Agencymaster");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Agencymaster");
		        }
		    }else{
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Agencymaster");
		    }
		}
	}

	//checkAgencyName
	public function checkAgencyName()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$agency_name= $this->input->get('agency_name');
			$agency_id= $this->agency_model->checkAgencyName($agency_name);
			echo json_encode($agency_id);
			return;		
		}
	}
	
	//checkAgencyCode
	public function checkAgencyCode()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$agency_code= $this->input->get('agency_code');
			$agency_id= $this->agency_model->checkAgencyCode($agency_code);
			echo json_encode($agency_id);
			return;		
		}
	}
}
?>
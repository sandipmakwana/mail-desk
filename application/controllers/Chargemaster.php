<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chargemaster extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Charge_Model');
	}

	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			$data['pgdata'] = 'chargemaster';
			$data['charge_master'] = $this->Charge_Model->chargeMaster();
			$this->template->view('Charge/chargemasterlist',$data);
		}	
	}

	public function chargeForm($charge_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			if($charge_id){
				$data['buttonname'] = "Update";
				$data['charge_id'] = $charge_id;
        		$data['charge_master'] = $this->Charge_Model->getChargeMaster($charge_id);
			}else{
				$data['buttonname'] = "Save";
			}
			$data['chargecodes'] = $this->Charge_Model->getChargeCodes();
			$data['pgdata'] = 'chargemaster';
			$this->template->view('Charge/chargemasterform',$data);
		}	
	}

	public function addChargeMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
		    $charge_id = $this->input->post('charge_id');
		    if($charge_id){
				$chargearray = array( //update array
					'charge_name' => strip_tags($this->input->post('charge_name')),
					'charge_code' => strip_tags($this->input->post('charge_code')),
					'agency_code' => strip_tags($this->input->post('agency_code')),
					'charge_value' => strip_tags($this->input->post('charge_value')),
					'charge_status' => strip_tags($this->input->post('charge_status')),
					'updated_at' =>date('Y-m-d H:i:s'),
					'created_at' =>date('Y-m-d H:i:s'),
				);

				$charge_id = $this->Charge_Model->updateChargeMaster($chargearray, $charge_id);
				if($charge_id>0){          
				  $this->session->set_flashdata('message', 'update successfully');
				   redirect("Chargemaster");
				} else {
				  #set exception message
				  $this->session->set_flashdata('exception', 'Please try again');
				  redirect("Chargemaster");
				}
			}else{
				$chargearray = array( //insert array
					'charge_name' => strip_tags($this->input->post('charge_name')),
					'charge_code' => strip_tags($this->input->post('charge_code')),
					'agency_code' => strip_tags($this->input->post('agency_code')),
					'charge_value' => strip_tags($this->input->post('charge_value')),
					'updated_at' => date('Y-m-d H:i:s'),
					'created_at' => date('Y-m-d H:i:s')
				);
				$new_charge_id = $this->Charge_Model->insertChargeMaster($chargearray);
				if($new_charge_id>0){
					$this->session->set_flashdata('message', 'save successfully');
					redirect("Chargemaster");
				} else {
					#set exception message
					$this->session->set_flashdata('exception', 'please try again');
					redirect("Chargemaster/index");
				}
			}
		}
	}

	public function delete($chargeid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($chargeid){
				$charge_id = $this->Charge_Model->deleteChargeMaster($chargeid);
		        if($charge_id){
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Chargemaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Chargemaster");
		        }
		    } else {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Chargemaster");
		    }
		}
	}

	public function active($charge_id = null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			if($charge_id){
				$chargeid = $this->Charge_Model->activeChargeMaster($charge_id);
		        if($chargeid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Chargemaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Chargemaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Chargemaster/index");
		    }
		}
	}

	public function inactive($chargeid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($chargeid){
				$new_charge_id = $this->Charge_Model->inactiveChargeMaster($chargeid);
		        if($new_charge_id){
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Chargemaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Chargemaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Chargemaster/index");
		    }
		}
	}

	//check ChargeName
	public function checkChargeName()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$charge_name= $this->input->get('charge_name');
			$charge_id= $this->Charge_Model->checkChargeName($charge_name);
			echo json_encode($charge_id);
			return;		
		}
	}
}
?>
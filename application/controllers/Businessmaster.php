<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Businessmaster extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Business_Model');
	}

	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			$pgdata = array(
				'pgdata' => 'businessmaster'
			);
			$data['biz_master'] = $this->Business_Model->businessMaster();
			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Business/businessmasterlist',$data);
			$this->load->view('templates/footer');
		}	
	}

	public function BusinessForm($businessid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($businessid){
				$data['buttonname'] = "Update";
				$data['business_id'] = $businessid; 
        		$data['biz_master'] = $this->Business_Model->getBusinessMaster($businessid);
			}else{
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'businessmaster'
			);			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Business/businessmasterform', $data);
			$this->load->view('templates/footer');			
		}	
	}

	public function addBusinessMaster()
	{ 
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{ 
		    $business_id = $this->input->post('business_id');
		    if($business_id){
				$businessarray = array( //update array
					'department_name' =>strip_tags($this->input->post('department_name')),
					'department_code' =>strip_tags($this->input->post('department_code')),
					'cost_center' =>strip_tags($this->input->post('cost_center')),
					'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'modifieddate' =>date('Y-m-d H:i:s')
				);

				$bizid = $this->Business_Model->updateBusinessMaster($businessarray, $business_id); 
				if($bizid>0){          
				  $this->session->set_flashdata('message', 'update successfully');
				   redirect("Businessmaster/index");
				} else {
				  #set exception message
				  $this->session->set_flashdata('exception', 'Please try again');
				  redirect("Businessmaster/index");
				}
			}else{
				
				$businessarray = array( //insert array
					'department_name' =>strip_tags($this->input->post('department_name')),
					'department_code' =>strip_tags($this->input->post('department_code')),
					'cost_center' =>strip_tags($this->input->post('cost_center')),
					'createdby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'createddate' =>date('Y-m-d H:i:s'),
					'isactive' =>'1',                              
				);
				$bizid = $this->Business_Model->insertBusinessMaster($businessarray);
				if($bizid>0){          
					$this->session->set_flashdata('message', 'save successfully');
					redirect("Businessmaster/index");
				} else {
					#set exception message
					$this->session->set_flashdata('exception', 'please try again');
					redirect("Businessmaster/index");
				}
			}
		}
	}

	public function delete($businessid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($businessid){
				$bizid = $this->Business_Model->deleteBusinessMaster($businessid); 
		        if($bizid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Businessmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Businessmaster/index");
		        }
		    } else {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Businessmaster/index");
		    }
		}
	}

	public function active($businessid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($businessid){
				$bizid = $this->Business_Model->activeBusinessMaster($businessid); 
		        if($bizid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Businessmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Businessmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Businessmaster/index");
		    }
		}
	}

	public function inactive($businessid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($businessid){
				$bizid = $this->Business_Model->inactiveBusinessMaster($businessid); 
		        if($bizid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Businessmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Businessmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Businessmaster/index");
		    }
		}
	}

	//check businessname
	public function checkBusinessName()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$businessname= $this->input->get('businessname');	
			$bizid= $this->Business_Model->checkLocName($businessname);
			echo json_encode($bizid);
			return;		
		}
	}
	
	//checkDepartmentCode
	public function checkDepartmentCode()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$department_code= $this->input->get('department_code');
			$bizid= $this->Business_Model->checkDepartmentCode($department_code);
			echo json_encode($bizid);
			return;		
		}
	}
}
?>
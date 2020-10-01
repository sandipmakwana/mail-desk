<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Locationmaster extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Location_Model');
	}

	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			$pgdata = array(
				'pgdata' => 'locationmaster'
			);
			$data['loc_master'] = $this->Location_Model->locationMaster();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Location/locationmasterlist',$data);
			$this->load->view('templates/footer');
		}	
	}

	public function locationForm($locationid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($locationid){
				$data['buttonname'] = "Update";
				$data['location_id'] = $locationid; 
        		$data['loc_master'] = $this->Location_Model->getLocationMaster($locationid);
			}else{
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'locationmaster'
			);
			$data['businesslist'] = $this->Location_Model->getBusinessList();			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Location/locationmasterform', $data);
			$this->load->view('templates/footer');			
		}	
	}

	public function addLocationMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
		    $location_id = $this->input->post('location_id');
		    if($location_id){
				$locationarray = array( //update array
					'business_id' =>strip_tags($this->input->post('business')),
					'location_type' =>strip_tags($this->input->post('locationtype')),
					'location_name' =>strip_tags($this->input->post('locationname')),
					'location_code' =>strip_tags($this->input->post('location_code')),
					'location_address' =>strip_tags($this->input->post('address')),
					'location_city' =>strip_tags($this->input->post('location_city')),
					'location_pincode' =>strip_tags($this->input->post('location_pincode')),
					'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'modifieddate' =>date('Y-m-d H:i:s')
				);

				$locid = $this->Location_Model->updateLocationMaster($locationarray, $location_id);
				if($locid>0){          
				  $this->session->set_flashdata('message', 'update successfully');
				   redirect("Locationmaster/index");
				} else {
				  #set exception message
				  $this->session->set_flashdata('exception', 'Please try again');
				  redirect("Locationmaster/index");
				}
			}else{
				$locationarray = array( //insert array
					'business_id' =>strip_tags($this->input->post('business')),
					'location_type' =>strip_tags($this->input->post('locationtype')),
					'location_name' =>strip_tags($this->input->post('locationname')),
					'location_code' =>strip_tags($this->input->post('location_code')),
					'location_address' =>strip_tags($this->input->post('address')),
					'location_city' =>strip_tags($this->input->post('location_city')),
					'location_pincode' =>strip_tags($this->input->post('location_pincode')),
					'createdby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'createddate' =>date('Y-m-d H:i:s'),
					'isactive' =>'1',                              
				);				
				$locid = $this->Location_Model->insertLocationMaster($locationarray);
				if($locid>0){          
					$this->session->set_flashdata('message', 'save successfully');
					redirect("Locationmaster/index");
				} else {
					#set exception message
					$this->session->set_flashdata('exception', 'please try again');
					redirect("Locationmaster/index");
				}
			}
		}
	}

	public function delete($locationid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($locationid){
				$locid = $this->Location_Model->deleteLocationMaster($locationid); 
		        if($locid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Locationmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Locationmaster/index");
		        }
		    } else {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Locationmaster/index");
		    }
		}
	}

	public function active($locationid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($locationid){
				$locid = $this->Location_Model->activeLocationMaster($locationid); 
		        if($locid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Locationmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Locationmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Locationmaster/index");
		    }
		}
	}

	public function inactive($locationid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($locationid){
				$locid = $this->Location_Model->inactiveLocationMaster($locationid); 
		        if($locid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Locationmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Locationmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Locationmaster/index");
		    }
		}
	}

	//check locationName
	public function checkLocationName()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$locationname= $this->input->get('locationname');	
			$locid= $this->Location_Model->checkLocName($locationname);
			echo json_encode($locid);
			return;		
		}
	}
}
?>
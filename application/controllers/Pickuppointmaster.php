<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pickuppointmaster extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Pickuppoint_Model');
	}

	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			$pgdata = array(
				'pgdata' => 'pickuppointmaster'
			);
			$data['fr_master'] = $this->Pickuppoint_Model->pickuppointMaster();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Pickuppoint/pickuppointmasterlist',$data);
			$this->load->view('templates/footer');
		}	
	}

	public function pickuppointForm($pickuppointid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($pickuppointid){ 
				$data['buttonname'] = "Update";
				$data['pickup_id'] = $pickuppointid; 
				$data['fr_master'] = $this->Pickuppoint_Model->getPickuppointMaster($pickuppointid);
				$data['fr_locations'] = $this->Pickuppoint_Model->getLocationMaster();
				
			}else{
				$data['fr_locations'] = $this->Pickuppoint_Model->getLocationMaster();
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'pickuppointmaster'
			);			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Pickuppoint/pickuppointmasterform', $data);
			$this->load->view('templates/footer');			
		}	
	}

	public function addPickuppointMaster()
	{  
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{

				
				
		    $pickup_id = $this->input->post('pickup_id');
		    if($pickup_id){
				$pickuppointarray = array( //update array
					'p_pickuppoint' =>strip_tags($this->input->post('p_pickuppoint')),
					'p_desc' =>strip_tags($this->input->post('p_desc')),
					'p_locationid' =>strip_tags($this->input->post('p_locationid')),
					'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'modifieddate' =>date('Y-m-d H:i:s')
				);

				$pickid = $this->Pickuppoint_Model->updatePickuppointMaster($pickuppointarray, $pickup_id); 
				if($pickid>0){          
				  $this->session->set_flashdata('message', 'update successfully');
				   redirect("Pickuppointmaster/index");
				} else {
				  #set exception message
				  $this->session->set_flashdata('exception', 'Please try again');
				  redirect("Pickuppointmaster/index");
				}
			}else{
				
				$pickuppointarray = array( //insert array
					'p_pickuppoint' =>strip_tags($this->input->post('p_pickuppoint')),
					'p_desc' =>strip_tags($this->input->post('p_desc')),
					'p_locationid' =>strip_tags($this->input->post('p_locationid')),
					'createdby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'createddate' =>date('Y-m-d H:i:s'),
					'isactive' =>'1',                              
				);

				$pickid = $this->Pickuppoint_Model->insertPickuppointMaster($pickuppointarray);
				if($pickid>0){          
					$this->session->set_flashdata('message', 'save successfully');
					redirect("Pickuppointmaster/index");
				} else {
					#set exception message
					$this->session->set_flashdata('exception', 'please try again');
					redirect("Pickuppointmaster/index");
				}
			}
		}
	}

	public function delete($pickuppointid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($pickuppointid){
				$pickid = $this->Pickuppoint_Model->deletePickuppointMaster($pickuppointid); 
		        if($pickid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Pickuppointmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Pickuppointmaster/index");
		        }
		    } else {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Pickuppointmaster/index");
		    }
		}
	}

	public function active($pickuppointid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($pickuppointid){
				$pickid = $this->Pickuppoint_Model->activePickuppointMaster($pickuppointid); 
		        if($pickid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Pickuppointmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Pickuppointmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Pickuppointmaster/index");
		    }
		}
	}

	public function inactive($pickuppointid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($pickuppointid){
				$pickid = $this->Pickuppoint_Model->inactivePickuppointMaster($pickuppointid); 
		        if($pickid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Pickuppointmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Pickuppointmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Pickuppointmaster/index");
		    }
		}
	}

	//check pickuppointName
	public function checkPickuppointName()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$f_value= $this->input->get('f_value');	
			$pickid= $this->Pickuppoint_Model->checkLocName($f_value);
			echo json_encode($pickid);
			return;		
		}
	}
}
?>
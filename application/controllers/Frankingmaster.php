<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frankingmaster extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Franking_Model');
		$this->load->model('Location_Model');
	}

	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}else{
			$pgdata = array(
				'pgdata' => 'frankingmaster'
			);
			$data['fr_master'] = $this->Franking_Model->frankingMaster();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Franking/frankingmasterlist',$data);
			$this->load->view('templates/footer');
		}	
	}

	public function frankingForm($frankingid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($frankingid){
				$data['buttonname'] = "Update";
				$data['franking_id'] = $frankingid; 
				$data['fr_master'] = $this->Franking_Model->getFrankingMaster($frankingid);
				$data['fr_locations'] = $this->Franking_Model->getLocationMaster();
				
			}else{
				$data['fr_locations'] = $this->Franking_Model->getLocationMaster();
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'frankingmaster'
			);			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Franking/frankingmasterform', $data);
			$this->load->view('templates/footer');			
		}	
	}

	public function addFrankingMaster()
	{  
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$location_id = $this->input->post('f_locationid');
				
				
		    $franking_id = $this->input->post('franking_id');
		    if($franking_id){
				$frankingarray = array( //update array
					'transaction_dt' => date('Y-m-d H:i:s', strtotime(strip_tags($this->input->post('transaction_dt')))),
					'reference_no' =>strip_tags($this->input->post('referenceno')),
					'f_value' =>strip_tags($this->input->post('f_value')),
					'remark' =>strip_tags($this->input->post('remark')),
					'f_locationids' =>$location_id,
					'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
					'modifieddate' =>date('Y-m-d H:i:s')
				);

				$locid = $this->Franking_Model->updateFrankingMaster($frankingarray, $franking_id); 
				if($locid>0){          
				  $this->session->set_flashdata('message', 'update successfully');
				   redirect("Frankingmaster/index");
				} else {
				  #set exception message
				  $this->session->set_flashdata('exception', 'Please try again');
				  redirect("Frankingmaster/index");
				}
			}else{
				
				$frankingarray = array( //insert array
					'transaction_dt'	=> date('Y-m-d H:i:s', strtotime(strip_tags($this->input->post('transaction_dt')))),
					'reference_no'		=> strip_tags($this->input->post('referenceno')),
					'f_value'			=> strip_tags($this->input->post('f_value')),
					'remark'			=> strip_tags($this->input->post('remark')),
					'f_locationids'		=> $location_id,
					'createdby'			=> $this->session->userdata['logged_in']['emp_user_id'],
					'createddate'		=> date('Y-m-d H:i:s'),
					'isactive'			=> '1',
				);

				$locid = $this->Franking_Model->insertFrankingMaster($frankingarray);
				if($locid>0){
					// Fetch selected location franking balance
					$location_data = $this->Location_Model->getLocationMaster($location_id);
					// Add new franking value for that location
					$new_value = (float)$location_data->location_franking_balance + (float) strip_tags($this->input->post('f_value'));

					// Update the new value for that location id in the vs_location table.
					$locationarray['location_franking_balance'] = $new_value;
					$this->Location_Model->updateLocationMaster($locationarray, $location_id);

					$this->session->set_flashdata('message', 'save successfully');
					redirect("Frankingmaster/index");
				} else {
					#set exception message
					$this->session->set_flashdata('exception', 'please try again');
					redirect("Frankingmaster/index");
				}
			}
		}
	}

	public function delete($frankingid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($frankingid){
				$locid = $this->Franking_Model->deleteFrankingMaster($frankingid); 
		        if($locid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Frankingmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Frankingmaster/index");
		        }
		    } else {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Frankingmaster/index");
		    }
		}
	}

	public function active($frankingid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($frankingid){
				$locid = $this->Franking_Model->activeFrankingMaster($frankingid); 
		        if($locid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Frankingmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Frankingmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Frankingmaster/index");
		    }
		}
	}

	public function inactive($frankingid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($frankingid){
				$locid = $this->Franking_Model->inactiveFrankingMaster($frankingid); 
		        if($locid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Frankingmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Frankingmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Frankingmaster/index");
		    }
		}
	}

	//check frankingName
	public function checkFrankingName()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$f_value= $this->input->get('f_value');	
			$locid= $this->Franking_Model->checkLocName($f_value);
			echo json_encode($locid);
			return;		
		}
	}
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Escmaster extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Esc_Model');
	}	
	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'escmaster'
			);
			$data['esc_master'] = $this->Esc_Model->escMaster();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Escalation/escalationuserlist',$data);
			$this->load->view('templates/footer');
		}	
	}
	public function escForm($escid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($escid){
				$data['buttonname'] = "Update";
				$data['vs_escmstid'] = $escid; 
        		$data['esc_master'] = $this->Esc_Model->getEscMaster($escid);
			}
			else
			{
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'escmaster'
			);			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Escalation/escalationuserform', $data);
			$this->load->view('templates/footer');			
		}	
	}	
	public function addEscMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{		
		      	$emp_id = $this->input->post('emp_id');
		      	if($emp_id){
		      		$emparray = array( //update array
			            'vs_esc_emptoken' =>strip_tags($this->input->post('tokenid')),
			            'vs_esc_empname' =>strip_tags($this->input->post('name')),
			            'vs_esc_empemail' =>strip_tags($this->input->post('email')),
			            'vs_esc_role' =>strip_tags($this->input->post('role')),
			            'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
			            'modifieddate' =>date('Y-m-d H:i:s')
			                                          
		        	);	        	
		        	$escid = $this->Esc_Model->updateEscMaster($escarray, $vs_escmstid); 	        	
		         	if($escid>0){          
		              $this->session->set_flashdata('message', 'update successfully');
		               redirect("Escmaster/index");
		            } else {
		              #set exception message
		              $this->session->set_flashdata('exception', 'Please try again');
		              redirect("Escmaster/index");
		            }
		      	}
		      else{
			      	$escarray = array( //insert array
			            'vs_esc_emptoken' =>strip_tags($this->input->post('tokenid')),
			            'vs_esc_empname' =>strip_tags($this->input->post('name')),
			            'vs_esc_empemail' =>strip_tags($this->input->post('email')),
			            'vs_esc_role' =>strip_tags($this->input->post('role')),	
			            'createdby' =>$this->session->userdata['logged_in']['emp_user_id'],
			            'createddate' =>date('Y-m-d H:i:s'),
			            'isactive' =>'1',                              
			        );
		       		$escid = $this->Esc_Model->insertEscMaster($escarray);
			        if($escid>0){          
			            $this->session->set_flashdata('message', 'save successfully');
			            redirect("Escmaster/index");
			        } else {
			            #set exception message
			            $this->session->set_flashdata('exception', 'please try again');
			            redirect("Escmaster/index");
			        }
		  		 }
		}
	}

	public function delete($esc_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($esc_id){
				$escid = $this->Esc_Model->deleteEscMaster($esc_id);
		        if($escid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Escmaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Escmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Escmaster/index");
		    }
		}
	}
	public function active($esc_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($esc_id){
				$escid = $this->Esc_Model->activeEscMaster($esc_id); 
		        if($escid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Escmaster/index");
		        } else {
		            #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Escmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Escmaster/index");
		    }
		}
	}
	public function inactive($esc_id=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($esc_id){
				$escid = $this->Esc_Model->inactiveEscMaster($esc_id); 
		        if($escid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Escmaster/index");
		        } else {
		            #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Escmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Escmaster/index");
		    }
		}
	}
	//check employee token
	public function checkEmptoken()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$tokenid= $this->input->get('tokenid');	
			$empid= $this->Esc_Model->checkEmptoken($tokenid);
			echo json_encode($empid);
			return;		
		}
	}

	//check emp mail
	public function checkEmpEmail()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$email= $this->input->get('email');	
			$empid= $this->Esc_Model->checkEmpEmail($email);
			echo json_encode($empid);
			return;		
		}
	}
}
?>
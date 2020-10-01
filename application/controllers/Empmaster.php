<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empmaster extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Employee_Model');
		if(NULL==$this->session->userdata('logged_in'))
			redirect(base_url());
	}	
	public function index()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			$pgdata = array(
				'pgdata' => 'empmaster'
			);
			$data['emp_master'] = $this->Employee_Model->empMaster();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Employee/emplist',$data);
			$this->load->view('templates/footer');
		}	
	}
	public function empForm($empid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($empid){
				$data['buttonname'] = "Update";
				$data['emp_id'] = $empid; 
        		$data['emp_master'] = $this->Employee_Model->getEmpMaster($empid);
			}
			else
			{
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'empmaster'
			);
			$data['vendorlist'] = $this->Employee_Model->getVendorList();		
			$data['fr_locations'] = $this->Employee_Model->getLocationMaster();
			$data['fr_departments'] = $this->Employee_Model->getDepartmentMaster();				
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Employee/empform', $data);
			$this->load->view('templates/footer');
		}	
	}	
	public function addEmpMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){redirect(base_url());
		}
		else{				
	      		$emp_id = $this->input->post('emp_id');
		      	if($emp_id){
		      		if($this->input->post('role')=="Vendor"){
		      			$vedorid= strip_tags($this->input->post('vendor'));
		      		}
		      		else{
		      			$vedorid=null;
		      		}
		      		$emparray = array( //update array
			            'emp_username' =>strip_tags($this->input->post('uname')),
			            'emp_firstname' =>strip_tags($this->input->post('firstname')),
			            'emp_lastname' =>strip_tags($this->input->post('lastname')),
			            'emp_email' =>strip_tags($this->input->post('email')),
			            'costcenter' =>strip_tags($this->input->post('costcenter')),
			            'emp_role' =>strip_tags($this->input->post('role')),
						'emp_locationid' =>strip_tags($this->input->post('emp_locationid')),	
						'emp_departmentid' =>strip_tags($this->input->post('emp_departmentid')),						
			            'is_ldap' =>strip_tags($this->input->post('ldap')),	
			            'vendorid' =>$vedorid,			            
		        	);	        	
		        	$empid = $this->Employee_Model->updateEmpMaster($emparray, $emp_id); 	        	
		         	if($empid>0){          
		              $this->session->set_flashdata('message', 'update successfully');
		               redirect("Empmaster/index");
		            } else {
		              #set exception message
		              $this->session->set_flashdata('exception', 'Please try again');
		              redirect("Empmaster/index");
		            }
		      	}
		      else{
		      		if($this->input->post('role')=="Vendor"){
		      			$vedorid= strip_tags($this->input->post('vendor'));
		      		}
		      		else{
		      			$vedorid=null;
		      		}
		      		if($this->input->post('password')){
		      			$emppasswd = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
		      		}
		      		else{
		      			$emppasswd=null;
		      		}
			      	$emparray = array( //insert array		            
			            'emp_username' =>strip_tags($this->input->post('uname')),
			            'emp_firstname' =>strip_tags($this->input->post('firstname')),
			            'emp_lastname' =>strip_tags($this->input->post('lastname')),
			            'emp_email' =>strip_tags($this->input->post('email')),
			            'costcenter' =>strip_tags($this->input->post('costcenter')),
			            'emp_role' =>strip_tags($this->input->post('role')),
						'emp_locationid' =>strip_tags($this->input->post('emp_locationid')),	
						'emp_departmentid' =>strip_tags($this->input->post('emp_departmentid')), 
			             'is_ldap' =>strip_tags($this->input->post('ldap')),	
			             'vendorid' =>$vedorid,
			             'emp_password' =>$emppasswd,		          
			            'emp_status' =>'Active',  
			        );
		       		$empid = $this->Employee_Model->insertEmpMaster($emparray);
			        if($empid>0){          
			            $this->session->set_flashdata('message', 'save successfully');
			            redirect("Empmaster/index");
			        } else {
			            #set exception message
			            $this->session->set_flashdata('exception', 'please try again');
			            redirect("Empmaster/index");
			        }
		  		}
		  
		}
	}
	public function active($emp_id=null){
		if(NULL==$this->session->userdata('logged_in')){redirect(base_url());
		}
		else{
			if($emp_id){
				$empid = $this->Employee_Model->activeEmpMaster($emp_id); 
		        if($empid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Empmaster/index");
		        } else {
		            #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Empmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Empmaster/index");
		    }
		}
	}
	public function inactive($emp_id=null){
		if(NULL==$this->session->userdata('logged_in')){redirect(base_url());
		}
		else{
			if($emp_id){
				$empid = $this->Employee_Model->inactiveEmpMaster($emp_id); 
		        if($empid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Empmaster/index");
		        } else {
		            #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Empmaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Empmaster/index");
		    }
		}
	}
	//check employee token
	public function checkEmptoken()
	{
		if(NULL==$this->session->userdata('logged_in')){redirect(base_url());
		}
		else{
			//$postdata = array();
			$tokenid= $this->input->get('tokenid');	
			$empid= $this->Employee_Model->checkEmptoken($tokenid);
			echo json_encode($empid);
			return;		
		}
	}
	//check emp mail
	public function checkEmpEmail()
	{
		if(NULL==$this->session->userdata('logged_in')){redirect(base_url());
		}
		else{
			//$postdata = array();
			$email= $this->input->get('email');	
			$empid= $this->Employee_Model->checkEmpEmail($email);
			echo json_encode($empid);
			return;		
		}
	}
	//get the employee list
	public function list() {
		//$postdata = array();
		$q = $this->input->get('q');	
		$emp = $this->Employee_Model->getlist($q);
		echo json_encode($emp);
		return;
	}
}
?>
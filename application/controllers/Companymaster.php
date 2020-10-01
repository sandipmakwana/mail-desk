<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Companymaster extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Company_Model');
	}	
	public function index()
	{	
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			
			$data['comp_master'] = $this->Company_Model->companyMaster();
			$pgdata = array(
				'pgdata' => 'companymaster'
			);
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Company/companymasterlist',$data);
			$this->load->view('templates/footer');
		}	
	}
	public function companyForm($companyid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($companyid)
			{
				$data['buttonname'] = "Update";
				$data['bu_id'] = $companyid; 
        		$data['com_master'] = $this->Company_Model->getCompanyMaster($companyid);
			}
			else{
				$data['buttonname'] = "Save";
			}
			
			$pgdata = array(
				'pgdata' => 'companymaster'
			);		
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Company/companyform', $data);
			$this->load->view('templates/footer');
		}	
	}	
	public function addCompanyMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
	      		$bu_id = $this->input->post('bu_id');
		      	if($bu_id){
		      		$companyarray = array( //insert array
			            'bu_code' =>strip_tags($this->input->post('companycode')),
			            'bu_name' =>strip_tags($this->input->post('companyname')),
			            'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
			            'modifieddate' =>date('Y-m-d H:i:s'),
			            'isactive' =>'1',                              
		        	);
		        	$compid = $this->Company_Model->updateCompanyMaster($companyarray, $bu_id);     
		         	if($compid>0){          
		              $this->session->set_flashdata('message', 'update successfully');
		               redirect("Companymaster/index");
		            } else {
		              #set exception message
		              $this->session->set_flashdata('exception', 'please try again');
		              redirect("Companymaster/index");
		            }
		      	}
		      else{
			      	$companyarray = array( //insert array
			            'bu_code' =>strip_tags($this->input->post('companycode')),
			            'bu_name' =>strip_tags($this->input->post('companyname')),
			            'createdby' =>$this->session->userdata['logged_in']['emp_user_id'],
			            'createddate' =>date('Y-m-d H:i:s'),
			            'isactive' =>'1',                              
			        );
		       		$compid = $this->Company_Model->insertCompanyMaster($companyarray);
			        if($compid>0){          
			            $this->session->set_flashdata('message', 'save successfully');
			            redirect("Companymaster/index");
			        } else {
			            #set exception message
			            $this->session->set_flashdata('exception', 'please try again');
		              	redirect("Companymaster/index");
			        }
		  		}
		  	
		}
	}
	public function delete($compid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($compid){
				$id = $this->Company_Model->deleteCompanyMaster($compid); 
		        if($id){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Companymaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Companymaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Companymaster/index");
		    }
		}
	}
	public function active($compid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($compid){
				$id = $this->Company_Model->activeCompanyMaster($compid); 
		        if($id){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Companymaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Companymaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Companymaster/index");
		    }
		}
	}
	public function inactive($compid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($compid){
				$id = $this->Company_Model->inactiveCompanyMaster($compid); 
		        if($id){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Companymaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Companymaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Companymaster/index");
		    }
		}
	}
	//check companycode
	public function checkCompanyCode()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$id= $this->input->get('companyid');	
			if($id){
				$companycode= strip_tags($this->input->get('companycode'));	
				$compid= $this->Company_Model->checkCompCodewithid($companycode, $id);
				echo json_encode($compid);
				return;	
			}
			else{
				$companycode= $this->input->get('companycode');	
				$compid= $this->Company_Model->checkCompCode($companycode);
				echo json_encode($compid);
				return;	
			}	
		}
	}
	//check companyName
	public function checkCompanyname()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$id= $this->input->get('companyid');	
			if($id){
				$companyname= strip_tags($this->input->get('companyname'));	
				$compid= $this->Company_Model->checkCompNamewithid($companyname, $id);
				echo json_encode($compid);
				return;	
			}
			else{
				$companyname= $this->input->get('companyname');	
				$compid= $this->Company_Model->checkCompName($companyname);
				echo json_encode($compid);
				return;	
			}	
		}
	}
}
?>
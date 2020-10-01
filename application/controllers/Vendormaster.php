<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendormaster extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');		
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Vendor_Model');
	}	
	public function index()
	{
		/*if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{*/
			$pgdata = array(
				'pgdata' => 'vendormaster'
			);
			$data['ven_master'] = $this->Vendor_Model->vendorMaster();
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Vendor/vendormasterlist',$data);
			$this->load->view('templates/footer');
		//}	
	}
	public function vendorForm($vendorid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($vendorid){
				$data['buttonname'] = "Update";
				$data['vendor_id'] = $vendorid; 
        		$data['ved_master'] = $this->Vendor_Model->getVendorMaster($vendorid);
			}
			else
			{
				$data['buttonname'] = "Save";
			}
			$pgdata = array(
				'pgdata' => 'vendormaster'
			);			
			$this->load->view('templates/header', $pgdata);
			$this->load->view('Vendor/vendormasterform', $data);
			$this->load->view('templates/footer');			
		}	
	}	
	public function addVendorMaster()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			
		      	$vendor_id = $this->input->post('vendor_id');
		      	if($vendor_id){
		      		$vendorarray = array( //update array
			            'vendor_cmpname' =>strip_tags($this->input->post('companyname')),
			            'vendor_name' =>strip_tags($this->input->post('vendorname')),
			            'vendor_mobile' =>strip_tags($this->input->post('contact')),
			            'vendor_email' =>strip_tags($this->input->post('email')),
			            'vendor_address' =>strip_tags($this->input->post('address')),
			            'vendor_gst' =>strip_tags($this->input->post('gst')),
			            'vendor_pan' =>strip_tags($this->input->post('pan')),
			             'location_id' =>strip_tags($this->input->post('location')),
			            'modifiedby' =>$this->session->userdata['logged_in']['emp_user_id'],
			            'modifieddate' =>date('Y-m-d H:i:s')
			                                          
		        	);
		        	
		        	$venid = $this->Vendor_Model->updateVendorMaster($vendorarray, $vendor_id); 
		         	if($venid>0){          
		              $this->session->set_flashdata('message', 'update successfully');
		               redirect("Vendormaster/index");
		            } else {
		              #set exception message
		              $this->session->set_flashdata('exception', 'Please try again');
		              redirect("Vendormaster/index");
		            }
		      	}
		      else{
			      	$vendorarray = array( //insert array
			            'vendor_cmpname' =>strip_tags($this->input->post('companyname')),
			            'vendor_name' =>strip_tags($this->input->post('vendorname')),
			            'vendor_mobile' =>strip_tags($this->input->post('contact')),
			            'vendor_email' =>strip_tags($this->input->post('email')),
			            'vendor_address' =>strip_tags($this->input->post('address')),
			            'vendor_gst' =>strip_tags($this->input->post('gst')),
			            'vendor_pan' =>strip_tags($this->input->post('pan')),
			             'location_id' =>strip_tags($this->input->post('location')),
			            'createdby' =>$this->session->userdata['logged_in']['emp_user_id'],
			            'createddate' =>date('Y-m-d H:i:s'),
			            'isactive' =>'1',                              
			        );
		       		$venid = $this->Vendor_Model->insertVendorMaster($vendorarray);
			        if($venid>0){          
			            $this->session->set_flashdata('message', 'save successfully');
			            redirect("Vendormaster/index");
			        } else {
			            #set exception message
			            $this->session->set_flashdata('exception', 'please try again');
			            redirect("Vendormaster/index");
			        }
		  		}
		  
		}
	}

	public function delete($vendorid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($vendorid){
				$vedid = $this->Vendor_Model->deleteVendorMaster($vendorid); 
		        if($vedid){          
		            $this->session->set_flashdata('message', 'delete successfully');
		           redirect("Vendormaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Vendormaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Vendormaster/index");
		    }
		}
	}
	public function active($vendorid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($vendorid){
				$vedid = $this->Vendor_Model->activeVendorMaster($vendorid); 
		        if($vedid){          
		            $this->session->set_flashdata('message', 'active successfully');
		           redirect("Vendormaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Vendormaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Vendormaster/index");
		    }
		}
	}
	public function inactive($vendorid=null){
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			if($vendorid){
				$vedid = $this->Vendor_Model->inactiveVendorMaster($vendorid); 
		        if($vedid){          
		            $this->session->set_flashdata('message', 'inactive successfully');
		           redirect("Vendormaster/index");
		        } else {
		              #set exception message
		            $this->session->set_flashdata('exception', 'Please try again');
		            redirect("Vendormaster/index");
		        }
		    }
		    else
		    {
		    	$this->session->set_flashdata('exception', 'Please try again');
		    	redirect("Vendormaster/index");
		    }
		}
	}
	//check vendor mail
	public function checkVenEmail()
	{
		if(NULL==$this->session->userdata('logged_in')){	
			redirect(base_url());
		}
		else{
			//$postdata = array();
			$email= $this->input->get('email');	
			$venid= $this->Vendor_Model->checkVenmail($email);
			echo json_encode($venid);
			return;		
		}
	}
}
?>
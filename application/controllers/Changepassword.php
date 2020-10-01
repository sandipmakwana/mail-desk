<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changepassword extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->data = array('root'=>base_url(),'assets'=>base_url('assets/'));
		$this->load->model('Login_model');
	}	
	public function index()
	{
		$pgdata = array(
				'pgdata' => 'changepassword'
			);
		$this->load->view('templates/header', $pgdata);
		$this->load->view('newpassword');
		$this->load->view('templates/footer');
	}
	function passwordupdate_action()
	{		
		$this->form_validation->set_rules('adm_password', 'Current Password', 'required|trim|xss_clean');
		$this->form_validation->set_rules('adm_newpassword', 'New Password', 'required|trim|xss_clean');
		$this->form_validation->set_rules('adm_cpassword', 'Confirm Password', 'required|matches[adm_newpassword]|trim|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			//$data['adm_id']=$this->input->post('adm_id');
			$this->load->view('templates/header');
			$this->load->view('newpassword',$data);
			$this->load->view('templates/footer');
		}
		else
		{
			$empid=$this->session->userdata['logged_in']['emp_user_id'];
			$emp_user=$this->session->userdata['logged_in']['emp_username'];
			$empass=$this->input->post('adm_password');
			$empnewpass=$this->input->post('adm_newpassword');
			$result=$this->Login_model->validate_user($emp_user,$empass);
			if(is_array($result))
			{	
				//print_r($adm_newpassword);
				$newpass=strip_tags(password_hash($this->input->post('adm_newpassword'), PASSWORD_DEFAULT));
				//print_r($edit_data);
				//exit;
				$this->Login_model->update_record($empid,$newpass);
				//$data['success']='Password change successfully';
				?>
				<script type="text/javascript">
					window.alert("Password change successfully");
					 window.location.href='../Login/logout';
				</script>
				<?php
				//redirect('Login/logout');
			}
			else
			{
				$pgdata = array(
				'pgdata' => 'changepassword'
				);
				$this->session->set_flashdata('exception', 'Current password mismatch!');
				$this->load->view('templates/header',$pgdata);
				$this->load->view('newpassword',$data);
				$this->load->view('templates/footer');
			}
		
		}
		
	}
}
?>
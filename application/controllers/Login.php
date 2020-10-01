<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	public $data = array();

	function __construct(){
		parent::__construct();
		// Load form helper library
		$this->load->helper('form');
		// Load form validation library
		$this->load->library('form_validation');
		// Load session library
		$this->load->library('session');
		//@session_start();  
				
		// Load database
//		$this->load->model('Login_database');
		$this->load->model('Login_model');
		$this->load->model('User_model');
		$this->load->model('Business_model');
		$this->load->helper('url');
		$this->load->helper('captcha');
	}	
    private function _generateCaptcha()
    {
        $vals = array(
            'img_path' => './captcha_images/',
            'img_url' => base_url('captcha_images/'),
            'img_width' => '150',
            'img_height' => '30',
            //'expiration' => 7200,
			'font_size'  => 30,
			'word_length'   => 4,
			'pool'   => '1223456789',
        );
        /* Generate the captcha */
        return create_captcha($vals);
    }
	public function index()
	{
		if(isset($this->session->userdata['logged_in']))
		{
			if($this->session->userdata['logged_in']['role'] == "Employee")
				redirect(base_url().'Emprequest/index');
			else if($this->session->userdata['logged_in']['role'] == "Admin")
				redirect(base_url().'Request/index');
			else if($this->session->userdata['logged_in']['role'] == "Vendor")
				redirect(base_url().'Vendorreq/index');
			else if($this->session->userdata['logged_in']['role'] == "DeskUser")
				redirect(base_url().'Request/index');
		}
		else{				
			$this->load->view('emp_login');
		}
	}

	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}


	/**
	* **********Admin /EMPLOYEE / MODERATOR LOGIN
	*/
	
	public function login()
	{
		$emp_id=0;
		$this->load->view('emp_login');
	}
	public function logout() 
	{
		$uemail=$this->session->userdata('logged_in');
		$userId=$uemail['log_id'];
		$this->Login_model->update_session($userId);
		// Removing session data
		$sess_array = array(
			'emp_user_id' => '',
			'emp_username' => '',
			'emp_email' => '',
			'role' => '',
			'role' => '',
			'log_id' => '',
			'vendorid' => '',
			'hrlogin' =>'',
		);
		$this->session->unset_userdata('logged_in', $sess_array);
		$this->session->sess_destroy();
		$data['message_display'] = 'Successfully Logout';
		$chk_ldap = $this->Login_model->get_ldap_settings();
		if($chk_ldap[0]->is_active == '1'){
			redirect('login');
		}
		else
		{
			redirect('login');
		
		}
	}
	public function do_login()
	{
		if(NULL==$this->session->userdata('logged_in'))
		{
			$this->form_validation->set_rules('user_name', 'User Name', 'required|trim|xss_clean');
			$this->form_validation->set_rules('user_password', 'Password', 'required|trim|xss_clean');		
			$this->form_validation->set_error_delimiters('', ''); 
			if ($this->form_validation->run() == FALSE)
			{
				echo validation_errors();
			}
			else
			{
				$uid = $this->input->post('user_name', TRUE);
				$upw = $this->input->post('user_password', TRUE);
				$uid = $this->security->xss_clean($uid);
				$upw = $this->security->xss_clean($upw);
				$uid=strip_tags($uid);
				$upw=strip_tags($upw);				
				//Check user exist

				$ans=$this->Login_model->user_exist($uid);
				if(!is_array($ans))
				{
					echo "Email id is incorrect.";
					return false;
				}
				else
				{
					$hrvalue=$this->hrlogin($ans[0]->emp_username);
					if($ans[0]->is_ldap==0)
					{
						$ip = $_SERVER["REMOTE_ADDR"];				
						//Check user valid or not
						$User = $this->Login_model->validate_user($uid,$upw);
						//print_r($User);
						//exit;
						if(is_array($User))
						{
							$userId=$User[0]->emp_id;						
							$chk_attempt=$this->check_login_attempt($ip);
							$chk_last_attemtp=$this->Login_model->check_last_attempt($ip);
							if(!empty($chk_last_attemtp))
							{
								if(isset($chk_attempt[0]['attmpt']) && $chk_attempt[0]['attmpt'] >= 5)
								{
									echo "Your account block for 20 minutes";
									return false;
								}	
							}
							$this->add_login_attempt($userId,'Yes',$ip);
							//Check user OTP verification
							$verfied=$this->check_verified($userId);
							if(!is_array($verfied))
							{
								//unverified user
								$userEmail=$User[0]->emp_email;
								$this->session->set_userdata('log_email',$userEmail);
								$send=$this->send_otp($userEmail);
								//echo 1;	
								
								return false;
							}
							$u_data=array(
								'uid'=> $User[0]->emp_id,
								'is_logged_in'=>TRUE,
							);
							$this->session->set_userdata('u_data',$u_data);
							//session_regenerate_id();
							$this->session->set_userdata('vs_ci_session',session_regenerate_id());
							$sessionId=$this->session->session_id;
							$this->Login_model->setSession($User[0]->emp_id,$sessionId);
							$logid=$this->Login_model->update_access($userId);
							$dept='';
							if(isset($User[0]->emp_departmentid) && $User[0]->emp_departmentid > 0) {
								$deptdata=$this->Business_model->getBusinessMaster($User[0]->emp_departmentid);
								if(!empty($deptdata) && isset($deptdata->department_name))
									$dept = $deptdata->department_name;
							}

							$session_data = array(
										'emp_user_id' => $User[0]->emp_id,
										'emp_username' => $User[0]->emp_username,
										'emp_email' => $User[0]->emp_email,
										'emp_firstname' => $User[0]->emp_firstname,
										'emp_lastname' => $User[0]->emp_lastname,
										'role' => $User[0]->emp_role,
										'log_id' => $logid,
										'vendorid' => $User[0]->vendorid,
										'hrlogin' =>$hrvalue,
										'is_ldap'=>$User[0]->is_ldap,
										'dept' => $dept,
										'extension' => $User[0]->emp_mobile
										//'ci_session' => session_id()
									);
								$this->session->set_userdata('logged_in', $session_data);
								echo $this->session->userdata('sess_url') == NULL ? $User[0]->emp_role : $this->session->userdata('sess_url');
							
						}
						else
						{
							//Get user data by email id;
							$userId=$ans[0]->emp_id;

							$this->add_login_attempt($userId,'No',$ip);
							$chk_attempt=$this->check_login_attempt($ip);
							
							if($chk_attempt[0]['attmpt'] >= 5)
							{
								echo "Due to more than 5 incorrect attempts your account is blocked. Please try after 5 minutes";
								return false;
							}	
							echo "Token No. or Password is Incorrect";
							return false;
						}
					}
					else
					{
						$chk_ldap = $this->Login_model->get_ldap_settings();
						$ldapserver = $chk_ldap[0]->server;
						$ldapusernm = strstr($uid, 'mahindra/', true);			
						$ldapuser      = 'mahindra\\'.$uid;
						$ldappass     = $upw;
						$ldaptree    = $chk_ldap[0]->tree;
						$search = "samaccountname=".$uid;
						// connect
						$ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
						//$ldapconn = 1;
						if($ldapconn) 
						{
							ldap_set_option ($ldapconn, LDAP_OPT_REFERRALS, 0);
							ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
							// binding to ldap server
							$ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);
							//or die ("Error trying to bind: ".ldap_error($ldapconn));
							//$ldapbind= 1;
							$ip=$_SERVER['REMOTE_ADDR'];
							// verify binding
							if ($ldapbind) 
							{
								$result = ldap_search($ldapconn,$ldaptree, $search) or die ("Error in search query: ".ldap_error($ldapconn));
								$data = ldap_get_entries($ldapconn, $result);
								if($data)
								{
									$chk_attempt=$this->check_login_attempt($ip);
									$chk_last_attemtp=$this->Login_model->check_last_attempt($ip);						
									if(!empty($chk_last_attemtp))
									{
										if($chk_attempt[0]['attmpt'] >= 5)
										{
											echo "Due to more than 5 incorrect attempts your account is blocked. Please try after 5 minutes";
											return false;
										}
									}
									$user = $data[0]["cn"][0];
									$get_name = explode(' ', $user);
									$fname = strtolower($get_name[1]);
									$lname = strtolower($get_name[0]);
									$email = $data[0]["mail"][0];
									$is_employee_exist = $this->User_model->is_employee_exist($email);
									$dept='';
									if(isset($is_employee_exist[0]['emp_departmentid']) && $is_employee_exist[0]['emp_departmentid'] > 0) {
										$deptdata=$this->Business_model->getBusinessMaster($is_employee_exist[0]['emp_departmentid']);
										if(!empty($deptdata) && isset($deptdata->department_name))
									$dept = $deptdata->department_name;
									}
									if(count($is_employee_exist) >= 1){
										$session_data = array(
											'emp_user_id' => $is_employee_exist[0]['emp_id'],
											'emp_username' => $is_employee_exist[0]['emp_username'],
											'emp_p' => base64_encode($ldappass),
											'emp_email' => $is_employee_exist[0]['emp_email'],
											'role' => $is_employee_exist[0]['emp_role'],
											'emp_firstname' => $is_employee_exist[0]['emp_firstname'],
											'emp_lastname' => $is_employee_exist[0]['emp_lastname'],
											'log_id' => $logid,
											'hrlogin' =>$hrvalue,
											'is_ldap'=>$is_employee_exist[0]['is_ldap'],
											'dept'=>$dept,
											'extension'=>$is_employee_exist[0]['emp_mobile'],
										);
							
									}
									else
									{
										$emp_data = array('emp_username' => $uid, 
													'emp_password' => '', 
													'emp_firstname' => $fname,
													'emp_lastname' => $lname,
													'emp_email' => $email, 
													'emp_role' => 'Employee', 
													'emp_status' => 'Active', 
													'is_ldap' => '1', 
													'emp_verify' => '0');
							
										$emp_id = $this->User_model->insert_data($emp_data);
										$session_data = array(
											'emp_user_id' => $emp_id,
											'emp_username' => $user,
											'emp_p' => base64_encode($ldappass),
											'emp_email' => $email,
											'role' => 'Employee',
											'emp_firstname' =>$fname,
											'dept' => 'tetst',
											'extension'=>'34234234234'
										);
									}
									$upw='';
									$uid=$is_employee_exist[0]['emp_id'];
								$userId=$is_employee_exist[0]['emp_id'];
								$this->add_login_attempt($userId,'Yes',$ip);	
										
									//Check user OTP verification
									$verfied=$this->check_verified($userId);			
									if(!is_array($verfied))
									{
										//unverified user
										$userEmail=$is_employee_exist[0]['emp_email'];
										$this->session->set_userdata('log_email',$userEmail);
										$send=$this->send_otp($userEmail);
										//echo 1;
										return false;
									}
									$u_data=array(
										'uid'=> $userId,
										'is_logged_in'=>TRUE,
									);
									$this->session->set_userdata('u_data',$u_data);
									//session_regenerate_id();
									$this->session->set_userdata('vs_ci_session',session_regenerate_id());
									$sessionId=$this->session->session_id;
									$this->Login_model->setSession($userId,$sessionId);
									$logid=$this->Login_model->update_access($userId);		
									$this->session->set_userdata('logged_in', $session_data);
									echo 'success';					
								}
								else
								{
									//Get user data by email id;					
									$chk_attempt=$this->check_login_attempt($ip);
									if($chk_attempt[0]['attmpt'] >= 5)
									{
										echo "Due to more than 5 incorrect attempts your account is blocked. Please try after 5 minutes";
										return false;
									}
									$this->add_login_attempt(0,'No',$ip);				
									echo "Token No. or Password is Incorrect";
									return false;
								}
							}
							else 
							{
								$chk_attempt=$this->check_login_attempt($ip);
								if($chk_attempt[0]['attmpt'] >= 5)
								{
									echo "Due to more than 5 incorrect attempts your account is blocked. Please try after 5 minutes";
									return false;
								}
								$this->add_login_attempt(0,'No',$ip);						
								echo "Token No. or Password is Incorrect";
							}	
						}
						// all done? clean up
						ldap_close($ldapconn);	
					}
				}
			}
		}
		else{ 
			print_r($User);die;
			echo $User[0]->emp_role;
		}
	}
	//hr login
	public function hrlogin($username){
		$value= $this->Login_model->check_hrlogin($username);
		if($value){
			return 1;
		}
		else
		{
			return 0;
		}

	}

	//Check user OTP verification.
	private function check_verified($userId)
	{
		return $check_verify = $this->Login_model->check_verify($userId);
		
	}
	//Check user loged in or NOT from another systeam.
	private function check_logedin($userId)
	{
		$now = date('Y-m-d H:i:s'); 
		$check_logedin = $this->Login_model->check_auth($userId);
		$log_intime=$check_logedin[0]->log_intime;
		$log_outtime=$check_logedin[0]->log_outtime;
		
		$start_date = new DateTime($log_intime);
		$since_start = $start_date->diff(new DateTime($now));
		if(($log_outtime =='0000-00-00 00:00:00' AND $since_start->i < 5))
		{
			return 2;
		}		
		else
		{
			return 1;
		}	
		
	}
	//Login Attempt
	private function add_login_attempt($userId,$flag,$ip)
	{
		$attempt['attmpt_empid']=$userId;
		$attempt['attmpt_status']=$flag;
		$attempt['attmpt_ip']=$ip;
		
		$this->Login_model->loginattempt($attempt);
	}
	//Check Login Attempt
	private function check_login_attempt($ip)
	{
		
		date_default_timezone_set('Asia/Kolkata');
		return $this->Login_model->checkAttempt($ip);
		//print_r($ans)
	}
	public function otp()
	{
		$this->load->view('emp_otp');
	}
	public function otp_verified()
	{
		$data=$this->input->post('emp_otp', TRUE);
		$uid=$this->session->userdata('log_email');
		$ans=$this->Login_model->verified_otp($uid,$data);
		
		if($ans=='1')
		{
			echo '2';
		}	
		else
		{
			echo '3';
		}
	}
	public function otp_reset()
	{
		$userEmail=$this->session->userdata('log_email');
		$send=$this->send_otp($userEmail);
		/* if($send)
		{
			echo 1;
		}	
		else
		{
			echo 2;
		} */	
	}
	public function send_otp($userEmail)
	{
		
		$otp=mt_rand(100000, 999999);
		$userdata=$this->Login_model->user_exist($userEmail);
		$userid=$userdata[0]->emp_id;
		$data=array(
				'emp_otp' => $otp,
				'emp_id' =>$userid
		);
		
		
				$ans=$this->Login_model->update_otp($userid,$data);
				$empname = ucfirst($userdata[0]->emp_firstname)." ".ucfirst($userdata[0]->emp_lastname);
	
				$this->email->set_mailtype("html");
				$this->email->set_newline("\r\n");
				$this->email->from('MiScout@mahindra.com');
				$this->email->to($userEmail);
				//$this->email->cc($requester_email);
				$this->email->subject('One Time Password');
				$emaildata['empname']=$empname;
				$emaildata['otp']=$otp;
				$this->email->message($this->load->view('emailtemplate/otp',$emaildata,true));
				$this->email->send();
				echo 1;
	}
	/*forgot password irfan*/
	function forgot()
	{
		$data = array(
						'error_message' => ''
		);
		$data['data']=$data;
		$this->load->view('forgot',$data);
	}
	public function forgot_password()
	{
		$data=$this->input->post();
		$this->form_validation->set_rules('uname', 'uname', 'required|trim|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$data = array(
					'error_message' => ''
					);
				$data['data']=$data;
				$this->load->view('forgot',$data);
		}
		else
		{
			$uname=$data['uname'];
			$ans=$this->Login_model->check_user($uname);
			if(is_array($ans))
			{	
				$name =	$ans[0]->emp_firstname. " " .$ans[0]->emp_lastname;		
				$this->forgot_passmail($ans[0]->emp_email, $name, $ans[0]->emp_id);
			}
			else
			{
				$data = array(
					'error_message' => 'Invalid Username please try again!'
					);
				$data['data']=$data;
				$this->load->view('forgot',$data);
			}	
		}
	}
	public function makeRandomPassword() 
	{
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); 
	    $alphaLength = strlen($alphabet) - 1; 
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); 
	}
	public function forgot_passmail($useremail, $name, $id)
	{
		$pass = $this->makeRandomPassword();
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		$this->email->from($sendEmailID);
		$this->email->to($useremail);
		$emaildata['empname']=$name;
		$this->email->subject('Reset your password');
		$this->email->message($this->load->view('Email/resetpassword',$emaildata,true));
		$this->email->send();
		$pass=password_hash($pass, PASSWORD_DEFAULT);
		$this->Login_model->update_record($id,$pass);
		redirect('Login/index');
	}
}

?>
<?php
class Login_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('UTC');
    }
    function get_ldap_settings(){
       // $usr=$this->db->get_where('empl_details',array('emp_username'=>$uname));
       $this->db->from('vs_ldap_settings');
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			$result = $query->result();

			return $result;
		} else {
			return false;
		}
	}
	function check_auth($userId)
	{
		$query=$this->db->query("SELECT log_id,log_empid,log_intime,log_outtime
                                 FROM  vs_log
                                 WHERE log_empid = '$userId' ORDER BY log_id DESC LIMIT 1");
								 
		if ($query->num_rows() == 1) 
		{
			return $result = $query->result();
		}
		else
		{
			return 'No record found';
		}
	}
	function check_verify($userId)
	{
		$query =$this->db->get_where('vs_emp_details',array('emp_verify' => '1','emp_id' => $userId));
		
		if ($query->num_rows() == 1) 
		{
			return $result = $query->result();
		}
		else
		{
			return 'No record found';
		}	
			
	}
	function user_exist($emp_mail)
	{
		$sql = "SELECT * FROM vs_emp_details WHERE (emp_email = ? OR emp_username=?)";
		$this->db->limit(1);
		$query =  $this->db->query($sql, array($emp_mail, $emp_mail));
		
		if ($query->num_rows() == 1) 
		{
			return $result = $query->result();
		}
		else
		{
			return 'No record found';
		}	
			
	}
    function validate_user($uname,$upw)
	{
		$sql = "SELECT * FROM vs_emp_details WHERE (emp_email = ? OR emp_username=?)AND emp_status = ? AND (emp_role= ? OR emp_role= ? OR emp_role= ? OR emp_role= ?)";
		$this->db->limit(1);
		$query =  $this->db->query($sql, array($uname, $uname,'Active', 'Admin','Employee', 'Vendor','DeskUser'));
		/*echo $this->db->last_query();
		exit*/;
		if ($query->num_rows() == 1) {
			$result = $query->result();	
			//print_r($result);				
			if(true === password_verify($upw, $result[0]->emp_password)){			
				return $result;
			}
			else{
				return false;
			}
		} else {
			return false;
		}
    }
	
	function verified_otp($uid,$data)
	{
		$ans['emp_email']=$uid;
		$ans['emp_otp']=$data;
		$this->db->select("emp_otp");
		$record = $this->db->get_where("vs_emp_details",$ans);
		$fans = $record->result();
		if($fans[0]->emp_otp==$data)
		{
			$fdata = array(
					'emp_verify' => '1',
			);
			$this->db->where('emp_email', $uid);
			$this->db->update('vs_emp_details', $fdata);
			return 1;
		}
		else
		{
			return "Invalid OTP ";
		}
		
	}
	function update_session($userId)
	{
		date_default_timezone_set('Asia/kolkata');
		
		$now = date('Y-m-d H:i:s');
		
		$data = array(
						'log_outtime' => $now,
				);
		
		$this->db->where('log_id', $userId);
		$this->db->update('vs_log', $data);
	}
	function update_access($userId)
	{
		$sessionId=$this->session->userdata('vs_ci_session');
		$now = date('Y-m-d H:i:s');
		//date_default_timezone_set('Asia/kolkata');
		$data = array(
						'log_intime' => $now,
						'log_empid'	=> $userId,
						'log_session' => $sessionId,
						
				);
				
		$this->db->insert('vs_log',$data);
		return $log_id=$this->db->insert_id();
	   //return TRUE;

	}
	/*update otp*/
	function update_otp($username,$data)
	{
		$this->db->where('emp_id', $username);
		$this->db->update('vs_emp_details', $data);
	}
	
	function loginattempt($attempt)
	{
		$this->db->insert('vs_login_attempt',$attempt);
        return TRUE;
    }
	
	function checkAttempt($ip)
	{	
		
		
		$query=$this->db->query("SELECT count(attmpt_empid) as attmpt,attmpt_time
                                 FROM  vs_login_attempt
								 WHERE attmpt_ip = '$ip' AND attmpt_time > (NOW() - INTERVAL 5 MINUTE) AND attmpt_status='No'
								 GROUP BY attmpt_time");
       $ans=$query->result_array();
		//print_r($query);	
		return $ans;						 
		
	}
	function check_last_attempt($ip)
	{
		$query=$this->db->query("SELECT attmpt_id,attmpt_empid,attmpt_time
                                 FROM  vs_login_attempt
                                 WHERE attmpt_ip = '$ip' AND attmpt_status='Yes' ORDER BY attmpt_id DESC LIMIT 1");
		if ($query->num_rows() == 1) 
		{
			return $query->result_array();
		}
		else
		{
			return 0;
		}	
							 
	}
	
	function get_empid($uid)
	{	
	
		$query=$this->db->query("SELECT emp_id,emp_username,emp_firstname,emp_lastname
                                 FROM  vs_emp_details
                                 WHERE emp_email = '$uid'");
        return $query->result_array();
	}
	function del_accesslog($delTime)
	{
		$this->db->where('attmpt_id < ', $delTime);
		$this->db->delete('login_attempt');
	}
	public function setSession($uid,$sessionid)
	{
		# code...
		$query=$this->db->query("SELECT log_session
                                 FROM  vs_log
                                 WHERE log_empid = '$uid'");
       $oldsession=$query->row();;
//$oldsession['log_session'];
 $oldsession=$oldsession->log_session;
    
		/*$oldsession=$this->db->select('log_session');
					$this->db->where(array('log_empid'=>$uid));
					$this->db->get("emp_log");
					$oldsession->row();*/
		
		$this->db->where('id',$oldsession);
		$this->db->delete('vs_ci_sessions');		
		$this->db->where('log_empid',$uid);
		$this->db->update('vs_log',array('log_session'=>$sessionid));	



	}
	//hr login
	public function check_hrlogin($uname){
		return $this->db->select('vl.emp_id')
					->from("vs_emp_details as vl")
					->join("vs_request as req", "req.req_emp_hrmgr_token=vl.emp_username")
					->where("vl.emp_username", $uname)
					->get()		 
					->result();
	}
	//check user
	public function check_user($uname)
	{	
		$query =$this->db->select('vs_emp_details.*')
						->from('vs_emp_details')
						->where('emp_username',$uname)
						->where('is_ldap','0')
						->get();
		if ($query->num_rows() == 1) 
		{
			return $result = $query->result();
		}
		else
		{
			return 'No record found';
		}
	}
	//reset password
	function update_record($id,$pass)
	{
		$this->db->where('emp_id',$id);
		$this->db->update('vs_emp_details',array('emp_password' => $pass ));
		//return $this->db->last_query();
	}
	

}	

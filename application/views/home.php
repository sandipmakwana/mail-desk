<?php
if (isset($this->session->userdata['logged_in']['role']))
{
	if(isset($this->session->userdata['logged_in']['adm_username']))
	{
		$username = ($this->session->userdata['logged_in']['adm_username']);
		$email = ($this->session->userdata['logged_in']['adm_email']);
		$role = "Admin";
	}else
	{
		$username = ($this->session->userdata['logged_in']['emp_username']);
		$email = ($this->session->userdata['logged_in']['emp_email']);
		$role = "Employee";
	}
} else {
$url = base_url();
redirect($url);
}
?>
  <img src="<?php echo base_url().'img/loginLogo.png';?>" class="mb-4" />

<div class="datalisting  animated fadeIn">

                     <br><br><br><br><br><br>
                   <p class="h5 text-center mb-4 red-text"> Welcome to <?php echo $role;?> Section</p>
                  <br><br><br><br><br><br><br>

                </div>


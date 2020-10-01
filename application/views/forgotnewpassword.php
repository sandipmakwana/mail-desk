
<div style="height: 100vh">
        <div class="flex-center flex-column">
            <div class="login-box  animated fadeIn">
                <div class="login-box-body">

                <?php
				if (isset($logout_message)) {
				echo "<div class='message' align='center'>";
				echo $logout_message;
				echo "</div><br>";
				}
				?>
				<?php
				if (isset($message_display)) {
				echo "<div class='message' align='center'>";
				echo $message_display;
				echo "</div><br>";
				}
				?>

				<?php
				echo "<div class='error_msg' align='center'>";
				if (isset($error_message)) {
				echo $error_message;
				}
				echo validation_errors();
				echo "</div><br>";
				
				
				
?>
                    <!-- Form login 
                    <form action="<?php echo base_url().'login/forgotpasswordupdate_action';?>" method="post" autocomplete="off">-->
					<?php echo form_open(base_url('login/passwordupdate_action'),array('id'=>'frm_login','autocomple'=>'off','method' => 'post','accept-charset'=> 'UTF-8'));?>

					<input type='hidden' value="<?php echo $adm_id;?>" name="adm_id">
                        <p class="h5 text-center mb-4 red-text">Change Password</p>
						<label class="ml-4 pl-3 mb-0">Current password</label>
                        <div class="md-form form-sm">
                            <i class="fa fa-envelope prefix red-text"></i>
                            
                            <input type="password" name="adm_password" placeholder="" id="adm_password" class="form-control"/>
						</div>
						<label class="ml-4 pl-3 mb-0">New password</label>
						<div class="md-form form-sm">
                            <i class="fa fa-envelope prefix red-text"></i>
                            
                            <input type="password" name="adm_newpassword" placeholder="" id="adm_newpassword" class="form-control"/>
						</div>
						<label class="ml-4 pl-3 mb-0">Confirm password</label>
						<div class="md-form form-sm">
                            <i class="fa fa-envelope prefix red-text"></i>
                            
                            <input type="password"  name="adm_cpassword" id="adm_cpassword" class="form-control"/>
						</div>
                                      
                      <!--<div class="md-form form-sm">
                      </div>-->
 <!-- this line is added for csrf protection cross site scripting -->
					<input type="submit" value="Submit" class="btn btn-danger" style="width:100%">
                        </div>
                    </form>
                    <!-- Form login -->
                </div>
            </div>

	         </div>
    </div>

<script type="text/javascript">
    $(document).ready(function ()
   {
	   $("#forgot").click(function(){
		  window.location.href=baseurl+'login/forgot';
		   
	   });
	   
    });
    </script>

<div style="height: 100vh;margin-top:-80px;margin-bottom:-80px;">
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
  
					<?php echo form_open(base_url('Changepassword/passwordupdate_action'),array('id'=>'frm_login','autocomple'=>'off','method' => 'post','accept-charset'=> 'UTF-8'));?>

					
                        <p class="h5 text-center mb-4 red-text">Change Password</p>
						<label class="mb-0">Current password</label>
                        <div class="md-form form-sm">
                            <input type="password" name="adm_password" placeholder="" id="adm_password" class="form-control"/>
						</div>
						<label class="mb-0">New password</label>
						<div class="md-form form-sm">
                            
                            <input type="password" name="adm_newpassword" placeholder="" id="adm_newpassword" class="form-control"/>
						</div>
						<label class=" mb-0">Confirm password</label>
						<div class="md-form form-sm">
                            <input type="password"  name="adm_cpassword" id="adm_cpassword" class="form-control"/>
						</div>
                                      
                      <!--<div class="md-form form-sm">
                      </div>-->
 <!-- this line is added for csrf protection cross site scripting -->
                  <div class="text-right"  style="width:100%">
					<input type="submit" value="Submit" class="btn btn-danger d-block"/>
                        </div>
                    <!--</form>-->
                    <!-- Form login -->
                </div>
            </div>

	         </div>
    </div>

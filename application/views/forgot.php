	<?php
if (isset($this->session->userdata['logged_in']['emp_username']))
{
	$url = base_url()."Login/index";
	redirect($url);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>img/favicon.ico" type="image/x-icon" />
    <title>Mahindra</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
	<!-- Material Design Bootstrap -->
    <link href="<?php echo base_url(); ?>css/mdb.min.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
     <!-- MDB core JavaScript -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/mdb.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js?V=1"></script>
</head>
<body class="login-bg">
<div style="height: 100vh">
        <div class="flex-center flex-column">
        	<img src="<?php echo base_url(); ?>img/loginLogo.png" class="mb-4" />
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
				if (isset($data['error_message'])) {
				echo $data['error_message'];
				}
				echo validation_errors();
				echo "</div><br>";			
				
?>
 
				<?php echo form_open(base_url('Login/forgot_password'),array('id'=>'frm_login','autocomple'=>'off','method'=>'post'));?>
                        <p class="h5 text-center mb-4 red-text">Password Reset</p>
						<label class="ml-4 pl-3 mb-0">Enter your username</label>
                        <div class="md-form form-sm">
                            <i class="fa fa-envelope prefix red-text"></i>
                            <input type="text" name="uname" id="uname"  class="form-control"/>
						</div>
						<div  class="text-center">
							<input type="submit" value="Submit" class="btn btn-danger">
                            <input type="button" name="cancel" value="Cancel" onclick="history.back();" class="btn btn-outline-danger" />
                        </div>
                    </form>
                
                </div>
            </div>

	         </div>
    </div>


   </body>
   	</html>
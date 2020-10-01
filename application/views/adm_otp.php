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
</head>

<body class="login-bg">

    <!-- Start your project here-->
    <div style="height: 100vh">
		<div class="flex-center flex-column">
			<img src="<?php echo base_url(); ?>img/loginLogo.png" class="mb-4" />
			<div class="login-box  animated fadeIn">
				<div class="login-box-body">
				<?php
				if (isset($message_display)) {
				echo "<div class='message' align='center'>";
				echo $message_display;
				echo "</div><br>";
				}
				
				
				?>




					<!-- Form login 
					<form action="#" id="frm_login" type="post" autocomple="off">-->
					<?php echo form_open('',array('id'=>'frm_login','autocomple'=>'off'));?>
					<div class="form-group has-error"><span class="help-block err" id="err_lgn"></span></div>
						<p class="h5 text-center mb-4 red-text">ENTER OTP</p>
						<div class="md-form form-sm">
							<i class="fa fa-envelope prefix red-text"></i>
							<input type="text" id="txt_otp" name="txt_otp" class="form-control" />
							
							<div class="form-group has-error"><span class="help-block err" id="err_txt_otp"></span></div>
						</div>
						<div id="resentotp" onclick="return resentotp();">Resend OTP? </div>
						<div class="text-center">
						<input type="button" value="Submit" class="btn btn-danger" onclick="return admotp_verification();" style="width:100%">
						</div>
					</form>
					<!-- Form login -->
				</div>
			</div>
		</div>
	</div>
    <!-- /Start your project here-->
    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/mdb.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js?V=1"></script>
<script>
$(function($) {

    // this script needs to be loaded on every page where an ajax POST may happen
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
 
 
  // now write your ajax script 
 
});
</script>
</body>

</html>

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
<script>
var baseurl="<?php echo base_url();?>";

</script>
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
				<!-- Form login -->
					<form action="#" id="frm_login" type="post" autocomple="off">
					<div class="form-group has-error"><span class="help-block err" id="err_lgn"></span></div>
						<p class="h5 text-center mb-4 red-text">SIGN IN</p>
						<label class="ml-4 pl-3 mb-0">Token No</label>
						<div class="md-form form-sm">
							<i class="fa fa-user prefix red-text"></i>
							<!--<label for="txt_email">TOKEN NO</label>-->
							<input type="text" id="txt_email" name="txt_email" class="form-control"/>
							<div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_email" ></span></div>
						</div>
						<label class="ml-4 pl-3 mb-0">Password</label>
						<div class="md-form form-sm">
							<i class="fa fa-lock prefix red-text"></i>
							<input type="password" id="txt_pass" name="txt_pass" class="form-control">
							<!--<label for="txt_pass">PASSWORD</label>-->
							<div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_pass"></span></div>                       			</div>
<div class="mr-3" style="margin-left: 2.2rem !important;">
  
<div class="form-row">
<div class="col-12"><label>Enter your answer</label></div>
<div class="col-3">
<div class="md-form form-sm">
<!--<label for="captcha">Captcha:</label>-->
	<div class="cap_val mt-2">
	<span id="firtval">
		</span> +
	<span id="secval">
	</span > =
	</div>
	
  </div>
  
  </div>

  <div class="col">
  <input id="first" readonly name="captcha" id="captcha" type="hidden"  class="form-control mt-0" placeholder="Captcha"/> 
	<input id="second" readonly  name="captcha" id="captcha" type="hidden"  class="form-control mt-0" placeholder="Captcha"/>
    <input id="total_email" name="captcha" type="text"  class="form-control mt-0 "/>
<div class="form-group has-error"><span class="help-block err" id="err_txt_captcha"></span></div>  
</div>	</div>
</div>

</div>
					<div class="text-center">
						<button class="btn btn-danger" id="do_loginbtn" onclick="return do_login();" style="width:100%">Login</button>
						
						
						</div>
						<div class="text-right">
						<small><a href="#" id="forgot" class="text-center dark-grey-text mt-2 ">Forgot Password?</a></small>
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
    
<script>
$(function($) {

    // this script needs to be loaded on every page where an ajax POST may happen
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });

 //alert(first);
  //alert(second);
 
  var min = 1;
  var max = 10;
			

	// and the formula is:
	var first = Math.floor(Math.random() * (max - min + 1)) + min;
	var second = Math.floor(Math.random() * (max - min + 1)) + min;
  // now write your ajax script 
   var ans=first+second
  $("#first").val(first);
  $("#second").val(second);
   $("#firtval").html(first);
  $("#secval").html(second);
 
});


    $(document).ready(function ()
   {
	   $("#forgot").click(function(){
		  window.location.href=baseurl+'Login/forgot';
		   
	   });
	   
    });
    </script>
</body>

</html>

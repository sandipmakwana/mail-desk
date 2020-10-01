<?php

 function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
$serverIp=explode(".",getUserIpAddr() );

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="<?php echo base_url().'img/favicon.ico';?>" type="image/x-icon" />
    <title>Mahindra</title> 
    <link href="<?php echo base_url().'css/font-awesome.min.css';?>" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url().'css/bootstrap.min.css';?>" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="<?php echo base_url().'css/mdb.min.css';?>" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="<?php echo base_url().'css/style.css';?>" rel="stylesheet">
    <link href="<?php echo base_url().'css/pagination.css';?>" rel="stylesheet">
    <link href="<?php echo base_url().'css/ionicons.min.css';?>" rel="stylesheet" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url().'jquery/jquery-ui.css'; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/datatables/css/jquery.dataTables.min.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/datatables/css/buttons.dataTables.min.css" />
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="<?php echo base_url()?>js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="<?php echo base_url()?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/bootstrap-typeahead.min.js"></script>
	<script type="text/javascript">
		var baseurl = "<?php echo base_url(); ?>";
	</script>
    <script src="<?php echo base_url().'js/custom.js?V=1' ?>"></script>
  <!--   <script type="text/javascript" src="<?php echo base_url()?>assets/datatables/js/jquery.dataTables.min.js"></script> -->
     <script type="text/javascript" src="<?php echo base_url()?>assets/datatables/js/datatables.min.js"></script>

     
   <script src="<?php echo base_url().'assets/ckeditor/ckeditor.js';?>">
   </script>
   <?php  echo link_tag('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');?>
    <script src="<?= base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery.blockUI.min.js"></script>
    <!----sweet allert -->
     <?php  echo link_tag('css/sweetalert.min.css');?>
    <script src="<?= base_url('js/sweetalert.min.js');?>"></script>
</head>
<?php
if(NULL==$this->session->userdata('logged_in')){
	redirect(base_url());
}								
?>
<body>
    <!--Main Navigation-->
    <header>
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-pink p-0">
            <a class="navbar-brand mr-5" href="#">
                
                <img src="<?php echo base_url().'img/logo.png';?>" />
                <strong class="ml-3 dark-grey-text">
                  <small >Mail Management System</small>
                </strong>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
  			<?php
			if (isset($this->session->userdata['logged_in']))
			{?>
            	<div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mr-5">
					<?php if($this->session->userdata['logged_in']['role'] == 'Admin')
					{?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" id="masterDropdownMenuLink" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#"><i class="fa fa-list-alt fa-fw"></i> Masters<span class="caret"></span></a>
							<div class="dropdown-menu dropdown-danger" aria-labelledby="masterDropdownMenuLink">
                                 <a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "companymaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Companymaster/index';?>">Business</a>
								<a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "locationmaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Locationmaster/index';?>">Location</a>
								<!--<a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "businessmaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Businessmaster/index';?>">Department</a>-->
								<a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "agencymaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Agencymaster/index';?>">Courier Agency</a>
							<!-- 	<a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "chargemaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Chargemaster/index';?>">Courier Charges</a> -->
                                <a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "frankingmaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Frankingmaster/index';?>">Franking Payment</a>
								 <a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "pickuppointmaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Pickuppointmaster/index';?>">Pickup Point</a>
								<a class="dropdown-item <?php if(isset($pgdata) && $pgdata == "empmaster"){ echo 'active'; } ?>" href="<?php echo base_url().'Empmaster/index';?>">Users</a>
							</div>
						</li>

                        <li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "courier"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/requestcourier';?>"><i class="fa fa-edit fa-fw"></i> New Outward </a>
                        </li>

						<!--<li class="nav-item ">
							<a class="nav-link <?php if(isset($pgdata) && $pgdata == "request"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/index';?>"><i class="fa fa-edit fa-fw"></i> Raise Request</a>
						</li>-->	
						<li class="nav-item ">
							<a class="nav-link <?php if(isset($pgdata) && $pgdata == "requestlist"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/requestlist';?>"><i class="fa fa-files-o fa-fw"></i> Outward List</a>
						</li>
						<li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "courier"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/receivecourier';?>"><i class="fa fa-edit fa-fw"></i> New Inward </a>
                        </li>
						<li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "receivelist"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/receivelist';?>"><i class="fa fa-files-o fa-fw"></i> Inward List</a>
                        </li>
						<li class="nav-item ">
							<a class="nav-link <?php if(isset($pgdata) && $pgdata == "requestreport"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/requestReport';?>"><i class="fa fa-file-text-o fa-fw"></i> Reports</a>
						</li>
					<?php
					}
                    if($this->session->userdata['logged_in']['role'] == 'Employee')
                    {?>
						<li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "courier"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/requestcourier';?>"><i class="fa fa-edit fa-fw"></i> Raise New Request</a>
                        </li>
                           
                        <li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "emprequestlist"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/requestlist';?>"><i class="fa fa-files-o fa-fw"></i> Request List</a>
                        </li>
                        
                    <?php  }
                    if($this->session->userdata['logged_in']['role'] == 'DeskUser')
                    {?>
                        <li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "courier"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/receivecourier';?>"><i class="fa fa-edit fa-fw"></i> Receive Courier</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "receivelist"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/receivelist';?>"><i class="fa fa-files-o fa-fw"></i> Received Mail</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "vendorrequestlist"){ echo 'active'; } ?>" href="<?php echo base_url().'Request/receivelist';?>"><i class="fa fa-files-o fa-fw"></i> Outgoing Mail</a>
                        </li>
                    <?php }
					if($this->session->userdata['logged_in']['is_ldap'] == '1')
                     {
						 ?>
                        <li class="nav-item ">
                            <a class="nav-link <?php if(isset($pgdata) && $pgdata == "changepassword"){ echo 'active'; } ?>" href="<?php echo base_url().'Changepassword/index';?>"><i class="fa fa-lock fa-fw"></i> Change Password</a>
                        </li>
                    <?php }?>
                </ul>
				<ul class="navbar-nav nav-flex-icons">
                    <li class="nav-item">
						<a class="nav-link" href="#"><i class="fa fa-user-o fa-fw"></i> <?php echo $this->session->userdata['logged_in']['emp_firstname'];?> <?php echo $this->session->userdata['logged_in']['emp_lastname'];?> </a>
					</li>
				    <li class="nav-item">
		              <a id="aLogOut" class="nav-link" title="Sign Out" href="<?php echo base_url().'login/logout';?>"><i class="fa fa-sign-in fa-fw"></i> Logout</a>
					</li>
                </ul>
            </div>
          <?php }  ?>
        </nav>
    </header>
    <!--Main Navigation-->
    <div style="padding-top: 80px;padding-bottom: 80px;">
    <div class="container">
    	<div class="row">
    		<div class="col-sm-12" id="flashmsg">
    			<!-- alert message -->
                <?php if ($this->session->flashdata('message') != null) {  ?>
                    <div class="alert alert-info alert-dismissable">
                        <?= $this->session->flashdata('message'); ?>
                    </div> 
                <?php } ?>                    
                <?php if ($this->session->flashdata('exception') != null) {  ?>
                    <div class="alert alert-danger alert-dismissable">    
                        <?= $this->session->flashdata('exception'); ?>
                    </div>
                <?php } ?> 
                <?php if (validation_errors()) {  ?>
                    <div class="alert alert-danger alert-dismissable">
                        <?= validation_errors(); ?>
                    </div> 
                <?php } ?> 
    		</div>
    	</div>
    </div>
    <?php

	$idletime=900;//after 60 seconds the user gets logged out
	 if (  isset($_SESSION['timestamp']) &&((time()- $_SESSION['timestamp']) > $idletime)){
		 
		redirect(base_url().'login/logout');
		

	}else{
	    $_SESSION['timestamp']=time();
		$issessionout = 0;
	} 
	//on session creation
	$_SESSION['timestamp']=time();


    ?>
    <script type="text/javascript">
$(function() {
$("#flashmsg").fadeIn().delay('slow').fadeOut(3000);
});
</script>

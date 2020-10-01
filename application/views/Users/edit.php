<?php
if (!isset($this->session->userdata['adm_login']['adm_username'])) {
$url = base_url()."login/adm_login";
redirect($url);
}
?>
<br><br>
<div class="datalisting  animated fadeIn">
<div class="container">
<div class="card">
<div class="card-body">


<?php echo validation_errors();?>

  <p class="h5 text-left mb-4 red-text px-4">Update User</p>
<p id="head"></p>

 <?php
      if(isset($this->data['edit_data']) && is_array($this->data['edit_data']) && count($this->data['edit_data'])): $i=1;
      foreach ($edit_data as $key => $this->data) {

    ?>

 <div class="col-sm-8 text-left">
    <!--<form class="mx-2 mt-4 form-sm wow fadeInRight" method="post" action="<?php echo site_url('routes/update_user/'.$this->data['emp_id'].''); ?>" name="data_register" autocomple="off">-->
	
	<?php echo form_open('routes/update_user/'.$this->data['emp_id'],array('class'=>'mx-2 mt-4 form-sm wow fadeInRight','id'=>'data_register','autocomple'=>'off','name'=>'data_register','method' => 'post'));?>
	
    <label for="empusername">Username</label>
    <input type="input" id="empusername" name="empusername" class="form-control" value="<?php echo $this->data['emp_username']; ?>" required />
    <p id="p1"></p>
    
    <label for="empfirstname">Firstname</label>
    <input type="input" id="empfirstname" name="empfirstname" class="form-control" value="<?php echo $this->data['emp_firstname']; ?>" required />
     <p id="p3"></p>
    <label for="emplastname">Lastname</label>
    <input type="input" id="emplastname" name="emplastname" class="form-control" value="<?php echo $this->data['emp_lastname']; ?>" required />
    <p id="p4"></p>
    <label for="empemail">Email</label>
    <input type="input" id="empemail" name="empemail" class="form-control" value="<?php echo $this->data['emp_email']; ?>" required />
	<p id="p5"></p>
	<?php
	    $selemp = $selmod = $seladm = "";

		    if(($this->data['emp_role']) == 'Employee')
		    {
		    	$selemp = "selected";
				$selmod = "";
				$seladm = "";
			}
			else if(($this->data['emp_role']) == 'Moderator')
			{
				$selmod = "selected";
				$selemp = "";
				$seladm = "";
			}
			else if(($this->data['emp_role']) == 'Admin')
			{
				$selmod = "";
				$selemp = "";
				$seladm = "selected";
   		    }
	?>
	  <label for="emprole">Role</label>
   <select class="form-control mdb-select" id="emprole" name="emprole">
	<option value="0" >--Select--</option>
 	<option value="Employee" <?php echo $selemp;?>>Employee</option>
  	<option value="Moderator" <?php echo $selmod;?>>Moderator</option>
  	<option value="Admin" <?php echo $seladm;?>>Admin</option>
	</select>
  	<p id="p6"></p>

	<?php

			$emparea = $this->data['emp_area'];
			if(isset($view_area) && is_array($view_area) && count($view_area))
			{

	?>
		<div id="showarea">
		<label for="empareas">Areas</label>
        <select class="form-control mdb-select" id="empareas" name="empareas[]"  multiple="multiple" size="10">
          <option value="0">--Select--</option>
		  <?php
		  foreach ($view_area as $ardata)
		  {
				if(strstr($emparea, $ardata['area_id']))
					$sl = "selected";
				else
					$sl = "";
		  ?>
          <option value="<?php echo $ardata['area_id'];?>" <?php echo $sl;?>><?php echo $ardata['area_title'];?> </option>
		  <?php
		  }
		  ?>

        </select>
        <p id="p7"></p>
		</div>
		<?php
			}

		?>
		<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

    <input type="submit" id="submit" name="submit" value="Edit User" class="btn btn-danger" />
    <input type="button" name="cancel" value="Cancel" onclick="history.back();" class="btn btn-outline-danger" />

</form>
<?php
}endif;
?>
</div>
</div></div>
</div>
</div>
 <script type="text/javascript">


    $(document).ready(function () {
   if($("#emprole").val() == "Moderator")
	   document.getElementById('showarea').style.display = "block";
  else
	  document.getElementById('showarea').style.display = "none";

	$("#emprole").change(function(e) {

		if($("#emprole").val() == "Employee" || $("#emprole").val() == "Admin")
			document.getElementById('showarea').style.display = "none";
		else
			document.getElementById('showarea').style.display = "block";
	});

 $('#submit').click(function(e) {



    var empusername = $("#empusername").val();
    	empusername = jQuery.trim(empusername);

    /*var emppassword = $("#emppassword").val();
    	emppassword = jQuery.trim(emppassword);*/

    var empfirstname = $("#empfirstname").val();
    	empfirstname = jQuery.trim(empfirstname);

    var emplastname = $("#emplastname").val();
    	emplastname = jQuery.trim(emplastname);

    var empemail = $("#empemail").val();
    	empemail = jQuery.trim(empemail);

	var emprole = $("#emprole").val();
	var selectedValues = $("#empareas").val();
			



	  var name_regex = /^[a-zA-Z]+$/;
	  var email_regex = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
	  var add_regex = /^[0-9a-zA-Z]+$/;
	  var zip_regex = /^[0-9]+$/;
//alert(emprole);	
	if (emprole == "Moderator")
		{

			
			if(empty(selectedValues))
			{
				$('#p7').text("* Please Choose Areas for Moderators "); // This Segment Displays The Validation Rule For Selection
				$("#empareas").focus();
				return false;
			}
			if(selectedValues=='0')
			{
				$('#p7').text("* Please Choose Areas for Moderators"); // This Segment Displays The Validation Rule For Selection
				$("#empareas").focus();
				return false;
			}
		}
		else
		{
			return true;
		}
	  

	if (empusername.length == 0) {
	$('#head').text("* All fields are mandatory *"); // This Segment Displays The Validation Rule For All Fields
	$("#empusername").focus();
	return false;
	}
	else if ((empusername.length > 150 )||(empusername.length == 0)) {
	$('#p1').text("*Username cannot be empty or should be less than 150 characters"); // This Segment Displays The Validation Rule For Username
	$("#empusername").focus();
	return false;
	}
	else if ((emppassword.length > 150 )||(emppassword.length == 0)) {
	$('#p2').text("*Password cannot be empty or should be less than 150 characters"); // This Segment Displays The Validation Rule For Name
	$("#emppassword").focus();
	return false;
	}
	else if (!empfirstname.match(name_regex) || empfirstname.length == 0 || empfirstname.length > 150) {
	$('#p3').text("* For Firstname please use alphabets only, maximum 150 characters are allowed"); // This Segment Displays The Validation Rule For Name
	$("#empfirstname").focus();
	return false;
	}
	else if (!emplastname.match(name_regex) || emplastname.length == 0 || emplastname.length > 150) {
	$('#p4').text("* For Lastname please use alphabets only, maximum 150 characters are allowed"); // This Segment Displays The Validation Rule For Name
	$("#emplastname").focus();
	return false;
	}
	else if (!empemail.match(email_regex) || empemail.length == 0 || empemail.length > 150) {
	$('#p5').text("* Please enter a valid email address, Emailid cannot be more than 150 characters"); // This Segment Displays The Validation Rule For Email
	$("#empemail").focus();
	return false;
	}
	// Validating Select Field.
	else if (emprole == "0") {
	$('#p6').text("* Please Choose employee role"); // This Segment Displays The Validation Rule For Selection
	$("#emprole").focus();
	return false;
	}
	else 
	{
		
	
	

	if (emprole == "Moderator")
		{

			
			if(!selectedValues)
			{
				$('#p7').text("* Please Choose Areas for Moderators "); // This Segment Displays The Validation Rule For Selection
				$("#empareas").focus();
				return false;
			}
			if(selectedValues=='0')
			{
				$('#p7').text("* Please Choose Areas for Moderators"); // This Segment Displays The Validation Rule For Selection
				$("#empareas").focus();
				return false;
			}
		}
		else
		{
			return true;
		}
	}

});
});


</script>
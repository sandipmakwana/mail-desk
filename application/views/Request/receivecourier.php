    <div class="container">
        <div class="row">           
			<div class="col-sm-12">
				<!-- Default form login -->
				<div class="card">
					<div class="card-body">
						<h4 class="pink-text"> New Inward Mail </h4><hr>
						<?php echo form_open_multipart('Request/'.(isset($reg->req_id) ? 'editReceivedCourier' : 'addReceivedCourier'),'class="form-inner" id="courierform"','autocomplete="off"'); ?>
						<input type="hidden" id="req_id" class="form-control mb-4" placeholder="" name="req_id" value="<?php echo isset($reg->req_id) ? $reg->req_id : ''; ?>">
						<input type="hidden" id="from_id" class="form-control mb-4" placeholder="" name="from_id" value="<?php echo $this->session->userdata['logged_in']['emp_user_id']; ?>">

						<div class="row "> <label class="text-left col-sm-4"><strong>Sender Details</strong></label></div> 
						<div class="row">
							<div class="col-sm-6">
								<label>Mail Received Mode</label>
								<select class="form-control" id="mod_of_delivery" name="mod_of_delivery">
									<option value="O" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'O') ? 'selected' : ''; ?>>Ordinary Mail</option>
									<option value="R" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'R') ? 'selected' : ''; ?>>Registered /A.D.</option>
									<option value="U" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'U') ? 'selected' : ''; ?>>Under Certificate of Posting</option>
									<option value="S" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'S') ? 'selected' : ''; ?>>Speed Post</option>
									<option value="AM" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'AM') ? 'selected' : ''; ?>>Air Mail</option>
									<option value="C" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'C') ? 'selected' : ''; ?>>Courier Company</option>
									<option value="Internal" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery == 'Internal') ? 'selected' : ''; ?>>Internal</option>
								</select>
							</div>
							<div class="col-sm-6" id="courier-block">
								<label>Courier</label>
								<select class="form-control" name="courier" id="courier" disabled >
									<option value="Domestic">Domestic</option>
									<option value="International">International</option>
								</select>
							</div>
						</div>
						<div class="row">     
							<div class="col-sm-6" id="agency-block">
								<label>Agency</label>
								<select class="form-control" name="req_agency" id="req_agency">
									<option value="0"> Select Agency </option>
									<?php if (!empty($agencies)) { ?>
										<?php foreach ($agencies as $agency) { ?>
											<option  value="<?php echo $agency->agency_id; ?>"><?php echo $agency->agency_name; ?></option>
										<?php } ?> 
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label>Senders Name</label>
								<input type="text" id="sender_name" class="form-control mb-4"  value="<?php if(isset($reg->req_emp_name)) echo $reg->req_emp_name; elseif(isset($empdtais->emp_name)) echo $empdtais->emp_name; ?>" name="req_emp_name">
							</div>
							<div class="col-sm-4">
								<label>Address</label>
								<input type="text" id="sender_address" class="form-control mb-4"  placeholder="" name="req_emp_address" value="<?php echo isset($reg->req_emp_address) ? $reg->req_emp_address : '' ?>">
							</div>
							<div class="col-sm-4">
								<label>City</label>
								<input type="text" id="sender_city" class="form-control mb-4"  value="<?php if(isset($reg->req_emp_city)) echo $reg->req_emp_city; elseif(isset($empdtais->emp_city)) echo $empdtais->emp_city; ?>" name="req_emp_city">
							</div>
							<div class="col-sm-4">
								<label>Pin Code</label>
								<input type="text" id="sender_pincode" class="form-control mb-4" placeholder="" name="req_emp_pincode" value="<?php echo isset($reg->req_emp_pincode) ? $reg->req_emp_pincode : '' ?>">
							</div>
							<div class="col-sm-4">
								<label>Telephone/Mobile no.</label>
								<input type="text" id="sender_telephone" class="form-control mb-4" placeholder="" name="req_emp_telephone" value="<?php echo isset($reg->req_emp_telephone) ? $reg->req_emp_telephone : '' ?>">
							</div>
							<div class="col-sm-4">
								<label>Remarks</label>
								<textarea class="form-control mb-4" name="req_emp_remark" id="sender_remark" ><?php echo isset($reg->vs_emp_remarks) ? $reg->vs_emp_remarks : '' ?></textarea>
							</div>
						</div>
						<hr>
						<hr>
						<div class="row ">
							<label class="text-left col-sm-4"><strong>Receiver Employee Details</strong></label>
						</div> 	
						<div class="row ">
							<label class="text-right col-sm-4 mt-2">Employee Token No.</label>
							<div class="col-sm-4">
								<div class="">
									<input type="text" class="form-control" placeholder="" name="receiver_tokenno" id="receiver_tokenno" value="<?php echo isset($reg->req_receiever_emp_token) ? $reg->req_receiever_emp_token : '' ?>">
								</div>
							</div>
							<div class="col-sm-4">
								<a href="" class="btn btn-sm btn-danger m-0" onclick="return gorecieverdetails()">Go</a>
								<a href="" class="btn btn-sm btn-mdb-color m-0">Clear</a>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label>Name</label>
								<input type="text" id="receiver_empname" class="form-control mb-4"  name="receiver_empname" value="<?php echo isset($reg->req_receiever_emp_name) ? $reg->req_receiever_emp_name : '' ?>">
							</div>
							<div class="col-sm-4">
								<label>Department</label>
								<input type="text" id="receiver_department" class="form-control mb-4"  placeholder="" name="receiver_department" value="<?php echo isset($reg->req_receiever_emp_department) ? $reg->req_receiever_emp_department : '' ?>">
							</div>
							<div class="col-sm-4">
								<label>Telephone/Mobile no.</label>
								<input type="text" id="receiver_telephone" class="form-control mb-4" placeholder="" name="receiver_telephone" value="<?php echo isset($reg->req_receiever_telephone) ? $reg->req_receiever_telephone : '' ?>">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label>Remarks</label>
								<textarea class="form-control mb-4" name="receiver_remark" id="receiver_remark" ><?php echo isset($reg->req_receiever_remarks) ? $reg->req_receiever_remarks : '' ?></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-right">
								<hr/>
								<input type="submit" class="btn btn-md btn-outline-danger" name="buttondraft" id="buttondraft" value="Save Draft"/>
								<input type="button" class="btn btn-md btn-danger" onclick="return submitdetails();" name="buttonsubmit" id="buttonsubmit" value="Submit"/>
							</div>
						</div>
					</div>
					<input type="hidden" id="req_emp_id" name="req_emp_id" value="<?php if(isset($empdtais->emp_user_id)) echo $empdtais->emp_user_id; ?>">
					<input type="hidden" id="req_emp_token" name="req_emp_token" value="<?php if(isset($empdtais->emp_username)) echo $empdtais->emp_username; ?>">
					<input type="hidden" id="hiddenlocation" name="hiddenlocation" value="<?php if(isset($empdtais->emp_location_name)) echo $empdtais->emp_location_name; ?>">
					 <?php echo form_close() ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
  function godetails(){
    var tokenid=$("#tokenno").val();
         // alert(tokenid);
         $.ajax({
          url : "<?php echo base_url('Request/getEmployeeDetails') ?>", 
          data: {tokenid:tokenid},             
          success: function(response)
          {  
                 // alert(response.length);
                 if(response.length >2)
                 {
  					//console.log(response);

                    $.each(JSON.parse(response), function(i, data) {

                      $('#req_emp_token').val(data['emp_token']);
                      $('#empname').val(data['emp_name']);
                      $('#req_emp_id').val(data['test_empid']);
                      $('#designation').val(data['emp_desig']);
                      $('#department').val(data['emp_dept']);
                      $('#business_unit').val(data['emp_buss_unit']);

                      $('#emailaddress').val(data['emp_email']);
                      $('#extension').val(data['emp_buss_unit']);
                      $('#wbselement').val(data['emp_wbs']);
                      $('#hiddenempname').val(data['emp_name']);
                      $('#hiddendesignation').val(data['emp_desig']);
                      $('#hiddendepartment').val(data['emp_dept']);
                      $('#hiddenbusiness_unit').val(data['emp_buss_unit']);
                      $('#location').val(data['emp_location_name']);
                      $('#hiddenlocation').val(data['emp_location_name']);
                      $('#hiddenstdcode').val(data['emp_stdcode']);
                      $('#hiddentelephone').val(data['emp_landline']);
                      $('#hiddenfax').val(data['emp_fax']);
                      $('#hiddenmobile').val(data['emp_mobile']);
                      $('#hiddenemailaddress').val(data['emp_email']);
                      $('#hiddencostcenter').val(data['emp_costcenter']);
                      $('#hiddenwbselement').val(data['emp_wbs']);
                      $('#hiddenmgrtoken').val(data['emp_mgr_token']);
                      $('#hiddenmgrname').val(data['emp_mgr_name']);
                      $('#hiddenmgremail').val(data['emp_mgr_email']);
                      $('#hiddenhrmgrtoken').val(data['emp_hrmgr_token']);
                      $('#hiddenhrmgrname').val(data['emp_hrmgr_name']);
                      $('#hiddenhrmgremail').val(data['emp_hrmgr_email']);
                      $('#hiddensrhrmgrtoken').val(data['emp_sr_hrmgr_token']);
                      $('#hiddensrhrmgrname').val(data['emp_sr_hrmgr_name']);
                      $('#hiddensrhrmgremail').val(data['emp_sr_hrmgr_email']);
                  });

}
else{
  alert("Token number not available");
}
},
error: function (jqXHR, textStatus, errorThrown)
{   
    alert('Error adding / update data');
}
});
    return false;  
}
function autofile_emp_details(){
  var tokenid=$("#tokenno").val();
         // alert(tokenid);
         $.ajax({
          url : "<?php echo base_url('Request/getEmployeeDetails') ?>", 
          data: {tokenid:tokenid},             
          success: function(response)
          {  
                 // alert(response.length);
                 if(response.length >2)
                 {
  					//console.log(response);

                    $.each(JSON.parse(response), function(i, data) {

                      $('#req_emp_token').val(data['emp_token']);
                      $('#empname').val(data['emp_name']);
                      $('#req_emp_id').val(data['test_empid']);
                      $('#designation').val(data['emp_desig']);
                      $('#department').val(data['emp_dept']);
                      $('#business_unit').val(data['emp_buss_unit']);

                      $('#emailaddress').val(data['emp_email']);
                      $('#extension').val(data['emp_buss_unit']);
                      $('#wbselement').val(data['emp_wbs']);
                      $('#hiddenempname').val(data['emp_name']);
                      $('#hiddendesignation').val(data['emp_desig']);
                      $('#hiddendepartment').val(data['emp_dept']);
                      $('#hiddenbusiness_unit').val(data['emp_buss_unit']);
                      $('#location').val(data['emp_location_name']);
                      $('#hiddenlocation').val(data['emp_location_name']);
                      $('#hiddenstdcode').val(data['emp_stdcode']);
                      $('#hiddentelephone').val(data['emp_landline']);
                      $('#hiddenfax').val(data['emp_fax']);
                      $('#hiddenmobile').val(data['emp_mobile']);
                      $('#hiddenemailaddress').val(data['emp_email']);
                      $('#hiddencostcenter').val(data['emp_costcenter']);
                      $('#hiddenwbselement').val(data['emp_wbs']);
                      $('#hiddenmgrtoken').val(data['emp_mgr_token']);
                      $('#hiddenmgrname').val(data['emp_mgr_name']);
                      $('#hiddenmgremail').val(data['emp_mgr_email']);
                      $('#hiddenhrmgrtoken').val(data['emp_hrmgr_token']);
                      $('#hiddenhrmgrname').val(data['emp_hrmgr_name']);
                      $('#hiddenhrmgremail').val(data['emp_hrmgr_email']);
                      $('#hiddensrhrmgrtoken').val(data['emp_sr_hrmgr_token']);
                      $('#hiddensrhrmgrname').val(data['emp_sr_hrmgr_name']);
                      $('#hiddensrhrmgremail').val(data['emp_sr_hrmgr_email']);
                  });

}
else{
  alert("Token number not available");
}
},
error: function (jqXHR, textStatus, errorThrown)
{   
    alert('Error adding / update data');
}
});
    return false;  
}

function submitdetails(){$( "#courierform" ).submit();return false;
 var emp_location_name=$('#hiddenlocation').val();
 $.ajax({
  url : "<?php echo base_url('Request/checkFrankingstatus') ?>", 
  data: {emp_location_name:emp_location_name},             
  success: function(response)
  {
                              //alert(response);                     
                              if(response == 0){
                                alert("Franking Cost has been reached, Please Contact Desk Person");
                            }else{
                                $( "#courierform" ).submit();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                         alert('Error adding / update data');
                     }
                 }); 


}				

function gorecieverdetails(){
    var tokenid=$("#receiver_tokenno").val();
         // alert(tokenid);
         $.ajax({
          url : "<?php echo base_url('Request/getEmployeeDetails') ?>", 
          data: {tokenid:tokenid},             
          success: function(response)
          {  
                 // alert(response.length);
                 if(response.length >2)
                 {
  					//console.log(response);

                    $.each(JSON.parse(response), function(i, data) {
                         // alert(data['emp_name']);
                         $('#hiddentokenid').val(data['emp_token']);

                         $('#receiver_empname').val(data['emp_name']);
                         $('#receiver_telephone').val(data['emp_mobile']);
                         $('#fax').val(data['emp_fax']);
                         $('#mobile').val(data['emp_mobile']);
                         $('#emailaddress').val(data['emp_email']);
                         $('#costcenter').val(data['emp_costcenter']);
                         $('#wbselement').val(data['emp_wbs']);
                         $('#hiddenempname').val(data['emp_name']);
                         $('#hiddendesignation').val(data['emp_desig']);
                         $('#hiddendepartment').val(data['emp_dept']);
                         $('#hiddenbusiness_unit').val(data['emp_buss_unit']);
                         $('#location').val(data['emp_location_name']);
                         $('#hiddenlocation').val(data['emp_location_name']);
                         $('#hiddenstdcode').val(data['emp_stdcode']);
                         $('#hiddentelephone').val(data['emp_landline']);
                         $('#hiddenfax').val(data['emp_fax']);
                         $('#hiddenmobile').val(data['emp_mobile']);
                         $('#hiddenemailaddress').val(data['emp_email']);
                         $('#hiddencostcenter').val(data['emp_costcenter']);
                         $('#hiddenwbselement').val(data['emp_wbs']);
                         $('#hiddenmgrtoken').val(data['emp_mgr_token']);
                         $('#hiddenmgrname').val(data['emp_mgr_name']);
                         $('#hiddenmgremail').val(data['emp_mgr_email']);
                         $('#hiddenhrmgrtoken').val(data['emp_hrmgr_token']);
                         $('#hiddenhrmgrname').val(data['emp_hrmgr_name']);
                         $('#hiddenhrmgremail').val(data['emp_hrmgr_email']);
                         $('#hiddensrhrmgrtoken').val(data['emp_sr_hrmgr_token']);
                         $('#hiddensrhrmgrname').val(data['emp_sr_hrmgr_name']);
                         $('#hiddensrhrmgremail').val(data['emp_sr_hrmgr_email']);
                     });
    var location=$('#location').val();
    $.ajax({
        url : "<?php echo base_url('Request/locationAddress') ?>", 
        data: {location:location},             
        success: function(response)
        {
          $.each(JSON.parse(response), function(i, data) {
            $('#officeaddress').text(data['location_address']); 
            $('#hiddenofficeaddress').text(data['location_address']);
        });                          
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
         alert('Error adding / update data');
     }
 }); 
}
else{
  alert("Token number not available");
}
},
error: function (jqXHR, textStatus, errorThrown)
{   
    alert('Error adding / update data');
}
});
    return false;  
}
    $('#buttonsubmit').click(function() {
        var from_name = $("#from_name").val();
        var to_type = $("#to_type").val();
        var to_name = $("#to_name").val();

        if (from_name == "") {
          $("#err_txt_from_name").text("Please Enter From Name!").fadeIn().delay('slow').fadeOut(5000);
          return false;
      }
      if (to_type == "") {
          $("#err_txt_to_type").text("Please Select To Type").fadeIn().delay('slow').fadeOut(5000);
          return false;
      }
      if (to_name == "") {
          $("#errr_txt_to_name").text("Please Enter To Name").fadeIn().delay('slow').fadeOut(5000);
          return false;
      }

    });
    $('#courier-block').hide();
    $('#agency-block').hide();

    $("#mod_of_delivery").change(function()
    {
        if($("#mod_of_delivery").val() == 'C' || $("#mod_of_delivery").val() == 'Internal'){
            $('#courier').show();
            $('#courier-block').show(); 
            $('#agency-block').show(); 
            $('#courier').prop('disabled', false);
            $('#req_agency').prop('disabled', false);
        }else{
            $('#courier').hide(); 
            $('#courier-block').hide();
            $('#agency-block').hide(); 
            $('#courier').prop('disabled', true);
            $('#req_agency').prop('disabled', true);; 
        }
        $('.detail').find('select').val($(this).val());
    });
</script>
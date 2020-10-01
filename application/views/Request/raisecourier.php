    <div class="container">
        <div class="row">           
            <div class="col-sm-12">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> New Outward Mail </h4><hr>
                                <div class="row ">
                                </div>
                             <?php echo form_open_multipart('Request/'.(isset($reg->req_id) ? 'editCourier' : 'addCourier'),'class="form-inner" id="courierform"','autocomplete="off"'); ?>
                             <input type="hidden" id="req_id" class="form-control mb-4" placeholder="" name="req_id" value="<?php echo isset($reg->req_id) ? $reg->req_id : ''; ?>">
                             <input type="hidden" id="from_id" class="form-control mb-4" placeholder="" name="from_id" value="<?php echo $this->session->userdata['logged_in']['emp_user_id']; ?>">
							
							 
                        <?php $sessionData = $this->session->userdata['logged_in']; ?>
						<div class="row "> <label class="text-left col-sm-4"><strong>Sender Details</strong></label></div> 
                               
							   <div class="row">
                                <div class="col-sm-6">
                                    <label>Mode of Mail Delivery</label>
                                    <select class="form-control" id="mod_of_delivery" name="mod_of_delivery">
                                        <option value="O" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='O') ? 'selected' : ''; ?>>Ordinary Mail</option>
                                        <option value="R" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='R') ? 'selected' : ''; ?>>Registered /A.D.</option>
                                        <option value="U" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='U') ? 'selected' : ''; ?>>Under Certificate of Posting</option>
                                        <option value="S" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='S') ? 'selected' : ''; ?>>Speed Post</option>
                                        <option value="AM" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='AM') ? 'selected' : ''; ?>>Air Mail</option>
                                        <option value="C" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='C') ? 'selected' : ''; ?>>Courier Company</option>
                                        <option value="Internal" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='Internal') ? 'selected' : ''; ?>>Internal</option>
                                    </select>
                                </div>
                                <div class="col-sm-6" id="courier-block">
                                   <label>Courier</label>
                                  <select class="form-control" name="courier" id="courier" <?php echo (isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery=='C') ? '' : 'disabled'; ?> >
                                  <option value="Domestic" <?php echo (isset($reg->req_courier) && $reg->req_courier=='Domestic') ? 'selected' : ''; ?> >Domestic</option>
                                  <option value="International" <?php echo (isset($reg->req_courier) && $reg->req_courier=='International') ? 'selected' : ''; ?> >International</option>
                                  </select>
                                </div>    
                                 <?php if($this->session->userdata['logged_in']['role'] == 'DeskUser'): ?>
                                <div class="col-sm-6" id="agency-block">
                                        <label>Agency</label>
                                          <select class="form-control" name="req_agency" id="req_agency">
                                          <option value="0"> Select Agency </option>
                                      <?php 
                                        if (!empty($agencies)) { ?>
                                    <?php foreach ($agencies as $agency) { ?>
                                            <option  value="<?php echo $agency->agency_id; ?>"><?php echo $agency->agency_name; ?></option>
                                    <?php } ?> 
                                <?php } ?> 
                                        
                                       </select>
                                    </div>
                                        
                                        <?php endif; ?>  
                        </div>
						<?php 
					       if($this->session->userdata['logged_in']['role'] == 'DeskUser' or $this->session->userdata['logged_in']['role'] == 'Admin'){
					    ?> 
							<div class="row ">
                                <div class="col-sm-12 ">
                                        <div>
                                          <div class="col-12 pt-3">
                                            <div class="row ">
                                              <label class="text-sm-right col-sm-4 mt-2">Token No.</label>
                                              <div class="col-sm-4">
                                                <div class="">
                                                  <input type="text" class="form-control" placeholder="" name="tokenno" id="tokenno" value="<?php echo isset($reg->req_emp_token) ? $reg->req_emp_token : ''?>">
                                                   
                                                  </div>
                                              </div>
                                              <div class="col-sm-4">
                                                <a href="" class="btn btn-sm btn-danger m-0" onclick="return godetails()">Go</a>
                                                <a href="" class="btn btn-sm btn-mdb-color m-0">Clear</a>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>  
						<?php 
					}
					?>		
                                 <div class="row">
                                    <div class="col-sm-4">
                                        <label>Name</label>
                                        <input type="text" id="empname" class="form-control mb-4"  value="<?php if(isset($reg->req_emp_name)) echo $reg->req_emp_name; elseif(isset($sessionData['emp_firstname'])) echo $sessionData['emp_firstname']; ?>" readonly name="empname">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Department/Division</label>
                                        <input type="text" id="department" class="form-control mb-4" value="<?php if(isset($reg->req_emp_dept)) echo $reg->req_emp_dept; elseif(isset($sessionData['dept'])) echo $sessionData['dept']; ?>"  readonly placeholder="" name="department">
                                    </div>
									<div class="col-sm-4">
                                        <label>Phone #</label>
                                        <input type="text" id="extension" class="form-control mb-4" readonly value="<?php if(isset($reg->req_emp_extension)) echo $reg->req_emp_extension; elseif(isset($sessionData['extension'])) echo $sessionData['extension']; ?>" name="extension">
                                    </div>
									<div class="col-sm-4">
                                        <label>Cost Center</label>
                                        <input type="text" id="costcenter" class="form-control mb-4" readonly value="<?php if(isset($reg->req_emp_costcenter)) echo $reg->req_emp_costcenter; elseif(isset($sessionData['costcenter'])) echo $sessionData['costcenter']; ?>" name="costcenter">
                                    </div>
									<div class="col-sm-4">
                                        <label>Location</label>
                                        <input type="text" id="location" class="form-control mb-4" readonly value="<?php if(isset($reg->req_emp_location)) echo $reg->req_emp_location; elseif(isset($sessionData['location'])) echo $sessionData['location']; ?>" name="location">
                                    </div>
                                </div>  
									
  <hr />									
							<div class="row "> <label class="text-left col-sm-4 mt-2"><strong>Receivers Details</strong></label></div>
							
							<div class="row">
								<div class="col-sm-4">
									<div class="btn-group btn-group-toggle" >
										  <label class="">
											<input type="radio" name="req_emp_type" value="Employee" id="option1" class="emp_type"  <?php echo isset($reg->req_emp_type) && $reg->req_emp_type=='Employee' ? 'checked' : '' ?>> M&M Employee
										  </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <label class="">
											  <input type="radio" name="req_emp_type" value="nonEmployee" id="option2" class="emp_type" <?php echo isset($reg->req_emp_type) && $reg->req_emp_type=='nonEmployee' ? 'checked' : '' ?>  > Non M&M Employee
										  </label>
										  
								   </div>
								</div>
								
                            </div>
							<br><br>
							<div class="row receiverTokenSec">
								<label class="text-sm-right col-sm-4 mt-2">Receiver Token No.</label>
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
                                        <label>Address</label>
                                        <input type="text" id="receiver_address" class="form-control mb-4"  placeholder="" name="receiver_address" value="<?php echo isset($reg->req_receiever_emp_address) ? $reg->req_receiever_emp_address : '' ?>">
                                    </div>
									<div class="col-sm-4">
                                        <label>City</label>
                                        <input type="text" id="receiver_city" class="form-control mb-4" placeholder="" name="receiver_city" value="<?php echo isset($reg->req_receiever_emp_city) ? $reg->req_receiever_emp_city : '' ?>">
                                    </div>
                                </div>  
								<div class="row">
									<div class="col-sm-4">
                                        <label>Pin code</label>
                                        <input type="text" id="receiver_pincode" class="form-control mb-4" placeholder="" name="receiver_pincode" value="<?php echo isset($reg->req_receiever_emp_pincode) ? $reg->req_receiever_emp_pincode : '' ?>">
                                    </div>
									<div class="col-sm-4">
                                        <label>Telephone/Mobile no.</label>
                                        <input type="text" id="receiver_telephone" class="form-control mb-4" placeholder="" name="receiver_telephone" value="<?php echo isset($reg->req_receiever_telephone) ? $reg->req_receiever_telephone : '' ?>">
                                    </div>
                                    
									<div class="col-sm-4" id="receiver_location">
                                        <label>Location <span class="red-text">* </span></label>
                                        <select id="req_receiever_location" class="form-control"  name="req_receiever_location" <?php echo (isset($franking_id))? "disabled" : ""?> >
											<?php 
											if (!empty($fr_locations)) {
											   $locationid = $reg->req_receiever_location;
                                            ?>
											<?php foreach ($fr_locations as $frlocation) { ?>
													<option <?php if($frlocation->location_id == $locationid) { echo "selected='selected'"; } ?>   value="<?php echo $frlocation->location_id; ?>"><?php echo $frlocation->bu_code.' - '.$frlocation->location_code.' - '.$frlocation->location_type.' - '.$frlocation->location_name; ?></option>
													<?php } ?> 
											<?php } ?>
                                        </select>
										<div class="form-group has-error">
										  <span class="help-block err" id="err_txt_f_locationids"></span>
										</div>
                                    </div>
                                </div> 
								<div class="row">
								<div class="col-sm-4">
                                       <label>Remarks</label>
									   <textarea class="form-control mb-4" name="receiver_remark" id="receiver_remark" ><?php echo isset($reg->req_receiever_remarks) ? $reg->req_receiever_remarks : '' ?></textarea>
                                    </div>
								</div>
									 <hr/>
					<?php 
					
					if($this->session->userdata['logged_in']['role'] == 'DeskUser' or $this->session->userdata['logged_in']['role'] == 'Admin'){
					?>
					<div class="row "> <label class="text-left col-sm-4"><strong>Courier Details</strong></label></div> 
						<div class="row">
								 <div class="col-sm-6">
                                        <label>Weight</label>
                                       <select class="form-control" name="unit_type">
									   <option value="gram" value="<?php echo isset($reg->req_unit) && $reg->req_unit=='gram' ? 'selected' : '' ?>">Gram</option>
									   <option value="kg" value="<?php echo isset($reg->req_unit) && $reg->req_unit=='kg' ? 'selected' : '' ?>">Kg</option>
									   </select>
                                    </div>
								<div class="col-sm-6">
                                        <label>Weight</label>
                                        <input type="text" id="req_weight" class="form-control mb-4" placeholder="" name="req_weight" value="<?php echo isset($reg->req_weight) ? $reg->req_weight : '' ?>">
                                </div>
						</div>
						<div class="row"> 		
								    <div class="col-sm-4">
									<label>Courier Agency</label>
									<select class="form-control" name="receiver_type" id="receiver_type">
										<option value="Internal" value="<?php echo isset($reg->req_receiever_type) && $reg->req_receiever_type=='Internal' ? 'selected' : '' ?>">DTDC</option>
										<option value="External" value="<?php echo isset($reg->req_receiever_type) && $reg->req_receiever_type=='External' ? 'selected' : '' ?>">VICHARE</option>
									</select>
								</div>
									
									<div class="col-sm-4">
                                       <label>Fee/Charge</label>
									   <input type="text" id="req_fee" class="form-control mb-4" placeholder="" name="req_fee" value="<?php echo isset($reg->req_fee) ? $reg->req_fee : '' ?>">
                                    </div>
									
                                
                               
                                   
                                    <div class="col-sm-4">
                                       <label>AWB</label>
                                       <input type="text" id="req_awb" class="form-control mb-4" placeholder="" name="req_awb" value="<?php echo isset($reg->req_awb) ? $reg->req_fee : '' ?>">
                                    </div>
                             
								
								</div>
						<?php 
            
					} 
					?>		
                                 <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />              
                                        <input type="submit" class="btn btn-md btn-outline-danger" name="buttondraft" id="buttondraft" value="Save Draft"/>
                                        <input type="button" class="btn btn-md btn-danger" onclick="return submitdetails();" name="buttonsubmit" id="buttonsubmit" value="Submit"/>
                                    </div>
                                </div>
                            </div>
							 <input type="hidden" id="req_emp_id" name="req_emp_id" value="<?php if(isset($empdtais->emp_user_id)) echo $empdtais->emp_user_id; ?>">
							 <input type="hidden" id="req_emp_token" name="req_emp_token" value="<?php if(isset($empdtais->emp_username)) echo $empdtais->emp_username; ?>">
							 <input type="hidden" id="hiddenlocation" name="hiddenlocation" value="<?php if(isset($empdtais->emp_location_name)) echo $empdtais->emp_location_name; ?>">
							 <?php 
					if($this->session->userdata['logged_in']['role'] == 'Employee'){
					?> 
					<input type="hidden" id="hiddenemployee_token" name="hiddenemployee_token" value="">
					<?php } ?>
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
                        $('#extension').val(data['emp_number']);
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
                        $('#extension').val(data['emp_number']);
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

function submitdetails(){
	if($("#mod_of_delivery").val() != 'C' && $("#mod_of_delivery").val() != 'Internal'){ 
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
	}else{
		
		$( "#courierform" ).submit();
	}		
					
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
$(document).ready(function() {
  
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
	<?php  if(!isset($reg->req_mod_of_delivery) && $reg->req_mod_of_delivery!='C') { ?>
$('#courier-block').hide(); 	
	<?php } ?>
$('#agency-block').hide(); 

$("#receiver_type").on("change", function(){
    var receiver = $(this).find("option:selected").val();
    if(receiver=='External') {
        $(".receiverTokenSec").hide();
    }
    else {
        $(".receiverTokenSec").show();
    }
})
<?php if($reg->req_emp_type=='Employee'){?>
 $("#receiver_location").show();
 $("#req_receiever_location").show();
<?php }else{ ?>
 $("#receiver_location").hide();
 $("#req_receiever_location").hide();
<?php } ?>
$('input[name="req_emp_type"]').on("change", function(){
   
    if($(this).val() == 'Employee'){
        $("#receiver_location").show();
		$("#req_receiever_location").show();
		$("#option1").attr('checked'); 
		$("#option2").removeAttr('checked');
		$('.receiverTokenSec').show();
    }
    else {
        $("#receiver_location").hide();
		$("#req_receiever_location").hide();
		$("#option2").attr('checked'); 
		$("#option1").removeAttr('checked');
		$('.receiverTokenSec').hide();
    }
	
})

	$("#mod_of_delivery").change(function()
{  
	
	if($("#mod_of_delivery").val() == 'C'){
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

});

</script>
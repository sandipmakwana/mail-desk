
    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Agency Master</h4><hr/>
                            <?php echo form_open_multipart('Agencymaster/addAgencyMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="agency_id" id="agency_id" value="<?php if(isset($agency_id)) { echo $agency_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Agency Code<span class="red-text">* </span></label>
                                        <input type="text" id="agency_code" class="form-control" placeholder="" onChange="return checkAgencyCode();" name="agency_code"  value="<?php if(isset($agency_master->agency_code)) echo $agency_master->agency_code; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agencycode"></span></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label>Agency Name<span class="red-text">* </span></label>
                                        <input type="text" id="agencyname" class="form-control" placeholder="" name="agency_name"  value="<?php if(isset($agency_master->agency_name)) echo $agency_master->agency_name; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agencyname"></span></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label>Agency SAP Code </label>
                                        <input type="text" id="agency_sap_code" class="form-control" placeholder="" name="agency_sap_code" value="<?php if(isset($agency_master->agency_sap_code)) echo $agency_master->agency_sap_code; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_sap_code"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Agency Address <span class="red-text">* </span></label>
                                        <textarea class="form-control mb-4" name="agency_address" id="agency_address"><?php if(isset($agency_master->agency_address)) echo $agency_master->agency_address; ?></textarea>
										<div class="form-group has-error">
										  <span class="help-block err" id="err_txt_agencyaddress"></span>
										</div>
                                    </div>
                                    <div class="col-sm-4">
										<label>Agency Person Name <span class="red-text">* </span></label>
                                        <input type="text" id="agency_person_name" class="form-control" placeholder="" name="agency_person_name" value="<?php if(isset($agency_master->agency_person_name)) echo $agency_master->agency_person_name; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_person_name"></span></div>
                                    </div>
                                    <div class="col-sm-4">
										<label>Mobile Number <span class="red-text">* </span></label>
                                        <input type="text" id="agency_mobile_number" class="form-control" placeholder="" name="agency_mobile_number" value="<?php if(isset($agency_master->agency_mobile_number)) echo $agency_master->agency_mobile_number; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_mobile_number"></span></div>
                                    </div>
                                    <div class="col-sm-4">
										<label>Email Address<span class="red-text">* </span></label>
                                        <input type="text" id="agency_email_address" class="form-control" placeholder="" name="agency_email_address"   value="<?php if(isset($agency_master->agency_email_address)) echo $agency_master->agency_email_address; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_email_address"></span></div>
                                    </div>
									<div class="col-sm-12">
										<label>Tracking URL <span class="red-text">* </span></label>
                                        <input type="text" id="agency_tracking_url" class="form-control" placeholder="" name="agency_tracking_url" value="<?php if(isset($agency_master->agency_tracking_url)) echo $agency_master->agency_tracking_url; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_tracking_url"></span></div>
                                    </div>
                                    <!-- <div class="col-sm-6">
                                        <label>Delivery Locations<span class="red-text">* </span></label>
                                       
									<select id="agency_delivery_locations" class="form-control"   name="agency_delivery_location">
										<?php 
                                         
                                        if (!empty($fr_locations)) { 
                                              
                                           $locationids = explode(",",$agency_master->agency_delivery_locations);
                                            ?>
                                    <?php foreach ($fr_locations as $frlocation) { 
                                        
                                        ?>
                                                <option <?php if(in_array($frlocation->location_id,$locationids)) { echo "selected='selected'"; } ?>   value="<?php echo $frlocation->location_id; ?>"><?php echo $frlocation->location_code.' - '.$frlocation->location_name; ?></option>
                                                <?php } ?> 
                                <?php } ?> 
                                        
                                        </select>
										
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_delivery_locations"></span></div>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <hr />
                                        <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" onclick="return frmvalidation()" value="<?= $buttonname ?>"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                        <a class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Agencymaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
                                    </div>
                                </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
function frmvalidation(){
    var agency_code = $("#agency_code").val();
	 var chargename = $("#agencyname").val();
	  var agency_tracking_url = $("#agency_tracking_url").val();
	   var agency_address = $("#agency_address").val();
	    var agency_person_name = $("#agency_person_name").val();
		 var agency_mobile_number = $("#agency_mobile_number").val();
		   var agency_email_address = $("#agency_email_address").val();
		    var agency_delivery_locations = $("#agency_delivery_locations").val();
			
    if (agency_code == "") {
        $("#err_txt_agencycode").text("Please Enter Agency Code!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_code").focus();
        return false;
    }
	if (chargename == "") {
        $("#err_txt_agencyname").text("Please Enter Agency Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#agencyname").focus();
        return false;
    }
	
	if (agency_address == "") {
        $("#err_txt_agencyaddress").text("Please Enter Agency Address!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_address").focus();
        return false;
    }
	if (agency_person_name == "") {
        $("#err_txt_agency_person_name").text("Please Enter Agency Person Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_person_name").focus();
        return false;
    }
	if (agency_mobile_number == "") {
        $("#err_txt_agency_mobile_number").text("Please Enter Mobile Number!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_mobile_number").focus();
        return false;
    }
	if (agency_email_address == "") {
        $("#err_txt_agency_email_address").text("Please Enter Email Address!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_email_address").focus();
        return false;
    }
	if (agency_tracking_url == "") {
        $("#err_txt_agency_tracking_url").text("Please Enter Tracking URL!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_tracking_url").focus();
        return false;
    }
	if (agency_delivery_locations == "") {
        $("#err_txt_agency_delivery_locations").text("Please Enter Delivery Location!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_delivery_locations").focus();
        return false;
    }
}
function checkAgencyName(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var chargename = $("#agencyname").val();
        $.ajax({
            url : "<?php echo base_url('Agencymaster/checkAgencyName') ?>", 
            data: {agency_name:agencyname},
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_agencyname").text("Agency name already exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#agencyname").val('');
                    return false;
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }
}

function checkAgencyCode(){
    
        var agency_code = $("#agency_code").val();
		
        $.ajax({
            url : "<?php echo base_url('Agencymaster/checkAgencyCode') ?>", 
            data: {agency_code:agency_code},
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_agencycode").text("Agency code already exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#agency_code").val('');
                    return false;
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    
}
</script>

    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Location Master</h4><hr/>
                            <?php echo form_open_multipart('Locationmaster/addLocationMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="location_id" id="location_id" value="<?php if(isset($location_id)) { echo $location_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Business <span class="red-text">* </span></label>
                                        <?php                                           
                                            echo form_dropdown('business', $businesslist, $loc_master->business_id, 'class="form-control" id="business" '); 
                                        ?>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_business"></span></div>        
                                    </div> 
                                   <div class="col-sm-4">
                                        <label>Location Type <span class="red-text">* </span></label>
                                        <?php
                                            $locationlist = array( ''=> 'Select Type',
                                                'Area Office' => 'Area Office',
                                                'Corporate' => 'Corporate',
                                                'Plant' => 'Plant',
                                            );
                                            echo form_dropdown('locationtype', $locationlist, $loc_master->location_type, 'class="form-control" id="locationtype" '); 
                                        ?>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_locationtype"></span></div>        
                                    </div> 
                                    <div class="col-sm-8">
                                        <label>Location Name <span class="red-text">* </span></label>
                                        <input type="text" id="locationname" class="form-control" placeholder="" name="locationname" onBlur="checkLocatinName()"  value="<?php if(isset($loc_master->location_name)) echo $loc_master->location_name; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_locationname"></span></div>
                                    </div>
                                    <div class="col-sm-4">
                                    <label>Location Code <span class="red-text">* </span></label>
                                        <input type="text" id="location_code" class="form-control" placeholder="" name="location_code"   value="<?php if(isset($loc_master->location_code)) echo $loc_master->location_code; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_locationcode"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Address <span class="red-text">* </span></label>
                                        <textarea class="form-control mb-4" name="address" id="address"><?php if(isset($loc_master->location_address)) echo $loc_master->location_address; ?></textarea>
										<div class="form-group has-error">
										  <span class="help-block err" id="err_txt_locationaddress"></span>
										</div>
                                    </div>
                                     <div class="col-sm-6">
                                    <label>City <span class="red-text">* </span></label>
                                        <input type="text" id="location_city" class="form-control" placeholder="" name="location_city"   value="<?php if(isset($loc_master->location_city)) echo $loc_master->location_city; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_locationcity"></span></div>
                                    </div>
                                     <div class="col-sm-6">
                                    <label>Pin Code <span class="red-text">* </span></label>
                                        <input type="text" id="location_pincode" class="form-control" placeholder="" name="location_pincode"   value="<?php if(isset($loc_master->location_pincode)) echo $loc_master->location_pincode; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_locationpincode"></span></div>
                                    </div>
                                </div>  
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" onclick="return frmvalidation()" value="<?= $buttonname ?>"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Locationmaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
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
    var business = $("#business").val();
    var locationname = $("#locationname").val();
    var locationtype = $("#locationtype").val();
	var location_code = $("#location_code").val();
    var address = $("#address").val();
    var city = $("#location_city").val();
    var pincode = $("#location_pincode").val();
     if(business==""){
         $("#err_txt_business").text("Please Select Business").fadeIn().delay('slow').fadeOut(5000);
        $("#business").focus();
        return false;
    }
     if (locationtype == "") {
        $("#err_txt_locationtype").text("Please Select Location Type").fadeIn().delay('slow').fadeOut(5000);
        $("#locationtype").focus();
        return false;
    }
    if (locationname == "") {
        $("#err_txt_locationname").text("Please Enter Location Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#locationname").focus();
        return false;
    }
   
    if (location_code == "") {
        $("#err_txt_locationcode").text("Please Enter Location Code").fadeIn().delay('slow').fadeOut(5000);
        $("#location_code").focus();
        return false;
    }
	if (address == "") {
        $("#err_txt_locationaddress").text("Please Enter Location Address").fadeIn().delay('slow').fadeOut(5000);
        $("#address").focus();
        return false;
    }
    if (city == "") {
        $("#err_txt_locationcity").text("Please Enter City").fadeIn().delay('slow').fadeOut(5000);
        $("#address").focus();
        return false;
    }
    if (pincode == "") {
        $("#err_txt_locationpincode").text("Please Enter Pincode").fadeIn().delay('slow').fadeOut(5000);
        $("#address").focus();
        return false;
    }
   
}
function checkLocatinName(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var locationname = $("#locationname").val();
        $.ajax({
            url : "<?php echo base_url('Locationmaster/checkLocationName') ?>", 
            data: {locationname:locationname},             
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_locationname").text("Location Name Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#locationname").val('');
                    //alert("false");
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
</script>
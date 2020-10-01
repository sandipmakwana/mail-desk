    <div class="container">
        <div class="row">
            <div class="offset-sm-1 col-sm-10">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Vendor Master</h4><hr>
                            <?php echo form_open_multipart('Vendormaster/addVendorMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="vendor_id" id="vendor_id" value="<?php if(isset($vendor_id)) { echo $vendor_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label>Company Name <span class="red-text">* </span></label>
                                        <input type="text" id="companyname" class="form-control mb-4" placeholder="" name="companyname" value="<?php if(isset($ved_master->vendor_cmpname)) echo $ved_master->vendor_cmpname; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_vencomanyname"></span></div>
                                    </div>

                                    <div class="col-sm-3">
                                        <label>Contact Name <span class="red-text">* </span></label>
                                        <input type="text" id="vendorname" class="form-control mb-4" placeholder="" name="vendorname" value="<?php if(isset($ved_master->vendor_name)) echo $ved_master->vendor_name; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_venname"></span></div>
                                    </div>          
                                    <div class="col-sm-3">
                                        <label>Contact No <span class="red-text">* </span></label>
                                        <input type="text" id="contact" class="form-control mb-4" placeholder="" name="contact" value="<?php if(isset($ved_master->vendor_mobile)) echo $ved_master->vendor_mobile; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_venmobile"></span></div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Email <span class="red-text">* </span></label>
                                        <input type="text" id="email" onBlur="checkVendorEmail()" class="form-control mb-4" placeholder="" name="email" value="<?php if(isset($ved_master->vendor_email)) echo $ved_master->vendor_email; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_venemail"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>GST No.</label>
                                        <input type="text" id="gst" class="form-control mb-4" placeholder="" name="gst" value="<?php if(isset($ved_master->vendor_gst)) echo $ved_master->vendor_gst; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Pan No.</label>
                                        <input type="text" id="pan" class="form-control mb-4" placeholder="" name="pan" value="<?php if(isset($ved_master->vendor_pan)) echo $ved_master->vendor_pan; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Location <span class="red-text">* </span></label>
                                        <input type="text" id="location" class="form-control mb-4" placeholder="" name="location" value="<?php if(isset($ved_master->location_id)) echo $ved_master->location_id; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_venloc"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Address <span class="red-text">* </span></label>
                                        <textarea class="form-control mb-4" rows="2" id="address" name="address"><?php if(isset($ved_master->vendor_address)) echo $ved_master->vendor_address; ?></textarea>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_venaddress"></span></div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" value="<?=$buttonname ?>" onclick="return frmvalidation()"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Vendormaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
                                </div>
                            </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script type="text/javascript">
    function frmvalidation(){
    var companyname = $("#companyname").val();
    var vendorname = $("#vendorname").val();
    var contact = $("#contact").val();
    var email = $("#email").val();
    var location = $("#location").val();
    var address = $("#address").val();
    if (companyname == "") {
        $("#err_txt_vencomanyname").text("Please Enter Company Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#companyname").focus();
        return false;
    }
    if (vendorname == "") {
        $("#err_txt_venname").text("Please Enter Name").fadeIn().delay('slow').fadeOut(5000);
        $("#vendorname").focus();
        return false;
    }
    if (contact == "") {
        $("#err_txt_venmobile").text("Please Enter Contact Number").fadeIn().delay('slow').fadeOut(5000);
        $("#contact").focus();
        return false;
    }
    if (email == "") {
        $("#err_txt_venemail").text("Please Enter Email Address").fadeIn().delay('slow').fadeOut(5000);
        $("#email").focus();
        return false;
    }
    if (location == "") {
        $("#err_txt_venloc").text("Please Enter Location").fadeIn().delay('slow').fadeOut(5000);
        $("#location").focus();
        return false;
    }
    if (address == "") {
        $("#err_txt_venaddress").text("Please Enter Address").fadeIn().delay('slow').fadeOut(5000);
        $("#address").focus();
        return false;
    }
}
function checkVendorEmail(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var email = $("#email").val();
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
        {
            $.ajax({
                url : "<?php echo base_url('Vendormaster/checkVenEmail') ?>", 
                data: {email:email},             
                success: function(response)
                {
                    if(response.length>2){
                        $("#err_txt_venaddress").text("Email ID Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                        $("#address").val('');
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
        else{
            $("#err_txt_email").text("Please Enter the valid Email ID!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
    }
}
</script>

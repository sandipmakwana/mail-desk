    <div class="container">
        <div class="row">           
            <div class="col-sm-12">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Edit Request Form </h4><hr>
                             <?php echo form_open_multipart('Request/editRequest','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="req_id" id="req_id" value="<?php if(isset($reg->req_id)) echo $reg->req_id; ?>">

                                
                                <input type="hidden" name="hiddendesignation" id="hiddendesignation" value="<?php if(isset($reg->req_emp_org_desig)) echo $reg->req_emp_org_desig; ?>">
                                <input type="hidden" name="hiddendepartment" id="hiddendepartment" value="<?php if(isset($reg->req_emp_org_dept)) echo $reg->req_emp_org_dept; ?>">
                                <input type="hidden" name="hiddenbusiness_unit" id="hiddenbusiness_unit" value="<?php if(isset($reg->req_emp_org_buss_unit)) echo $reg->req_emp_org_buss_unit; ?>">   
                                <input type="hidden" name="hiddentelephone" id="hiddentelephone" value="<?php if(isset($reg->req_emp_landline)) echo $reg->req_emp_landline; ?>">
                                <input type="hidden" name="hiddenofficeaddress" id="hiddenofficeaddress" value="<?php if(isset($reg->req_emp_address)) echo $reg->req_emp_address; ?>">      
                                <input type="hidden" name="hiddenmobile" id="hiddenmobile" value="<?php if(isset($reg->req_emp_mobile)) echo $reg->req_emp_mobile; ?>"> 
                                <input type="hidden" name="hiddencostcenter" id="hiddencostcenter" value="<?php if(isset($reg->req_emp_costcenter)) echo $reg->req_emp_costcenter; ?>">
                                <input type="hidden" name="hiddenlocation" id="hiddenlocation" value="<?php if(isset($reg->req_emp_location_name)) echo $reg->req_emp_location_name; ?>">
                                
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Display Name on V-Card</label>
                                        <input type="text" id="empname" class="form-control mb-4" readonly name="empname" value="<?php if(isset($reg->req_emp_name)) echo $reg->req_emp_name; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Designation<span class="red-text">* </span></label>
                                        <input type="text" id="designation" class="form-control mb-4" placeholder="" name="designation" value="<?php if(isset($reg->req_emp_new_desig)) echo $reg->req_emp_new_desig; ?>">
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_design"></span></div>
                                    </div>                          
                                    <div class="col-sm-4">
                                        <label>Department/Division</label>
                                        <input type="text" id="department" class="form-control mb-4" placeholder="" name="department" value="<?php if(isset($reg->req_emp_new_dept)) echo $reg->req_emp_new_dept; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Business Unit <span class="red-text">* </span></label>
                                                <input type="text" id="business_unit" class="form-control mb-4" placeholder="" name="business_unit" value="<?php if(isset($reg->req_emp_new_buss_unit)) echo $reg->req_emp_new_buss_unit; ?>">
                                                 <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_bus_unt"></span></div>
                                            </div>                     
                                            <div class="col-sm-6">
                                                <label>Location</label>
                                                 <?php  echo form_dropdown('location', $locationlist, $reg->req_emp_new_location_name,'class="form-control" id="location" '); 
                                                ?> 
                                            </div>
                                            <div class="col-sm-6">
                                                <label>ISD/STD Code <span class="red-text">* </span></label>
                                                <input type="text" id="stdcode" class="form-control mb-4" placeholder="" name="stdcode" value="<?php if(isset($reg->req_emp_stdcode)) echo $reg->req_emp_stdcode; ?>">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_stdcode"></span></div>
                                            </div>
                                             <div class="col-sm-6">
                                                <label>Telephone <span class="red-text">* </span></label>
                                                <input type="text" id="telephone" class="form-control mb-4" placeholder="" name="telephone" value="<?php if(isset($reg->req_emp_new_landline)) echo $reg->req_emp_new_landline; ?>">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_telphoe"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Office Address <span class="red-text">* </span></label>
                                        <textarea class="form-control mb-4" name="officeaddress" id="officeaddress" ><?php if(isset($reg->req_emp_new_address)) echo $reg->req_emp_new_address; ?></textarea>
                                         <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_off_address"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Fax</label>
                                        <input type="text" id="fax" class="form-control mb-4" placeholder="" name="fax" value="<?php if(isset($reg->req_emp_fax)) echo $reg->req_emp_fax; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Mobile</label>
                                        <input type="text" id="mobile" class="form-control mb-4" placeholder="" name="mobile" value="<?php if(isset($reg->req_emp_new_mobile)) echo $reg->req_emp_new_mobile; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Official Email Address</label>
                                        <input type="text" id="emailaddress" class="form-control mb-4" placeholder="" name="emailaddress" value="<?php if(isset($reg->req_emp_email)) echo $reg->req_emp_email; ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Cost Center <span class="red-text">* </span></label>
                                        <input type="text" id="costcenter" class="form-control mb-4" placeholder="" name="costcenter" value="<?php if(isset($reg->req_emp_new_costcenter)) echo $reg->req_emp_new_costcenter; ?>">
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_cost_center"></span></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>WBS Element</label>
                                        <input type="text" id="wbselement" class="form-control mb-4" placeholder="" name="wbselement" value="<?php if(isset($reg->req_emp_wbs)) echo $reg->req_emp_wbs; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Vendor</label>
                                       <?php  echo form_dropdown('vendor', $vendorlist, $reg->req_vendor_id, 'class="form-control" id="vendor" '); 
                                        ?>  
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />               
                                        <input type="submit" class="btn btn-md btn-outline-danger" name="buttondraft" value="Save Draft"/>
                                        <input type="submit" class="btn btn-md btn-danger" name="buttonsubmit" onclick="return frmvalidation()"value="Submit"/>
                                    </div>
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
$(document).ready(function() {
function frmvalidation(){
    var designation = $("#designation").val();
    var business_unit = $("#business_unit").val();
    var telephone = $("#telephone").val();
    var stdcode = $("#stdcode").val();
    var officeaddress = $("#officeaddress").val();
    var costcenter = $("#costcenter").val();
    if (designation == "") {
        $("#err_txt_design").text("Please Enter Designation!").fadeIn().delay('slow').fadeOut(5000);
        return false;
    }
    if (business_unit == "") {
        $("#err_txt_bus_unt").text("Please Enter Business Unit").fadeIn().delay('slow').fadeOut(5000);;
        return false;
    }
    if (telephone == "") {
        $("#err_txt_telphoe").text("Please Enter Telephone Number!")..fadeIn().delay('slow').fadeOut(5000);;
        return false;
    }
    if (stdcode == "") {
        $("#err_txt_stdcode").text("Please Enter STD Code!");
        return false;
    }
    if (officeaddress == "") {
        $("#err_txt_off_address").text("Please Enter Office Address!")..fadeIn().delay('slow').fadeOut(5000);;
        return false;
    }
    if (costcenter == "") {
        $("#err_txt_cost_center").text("Please Enter Cost Center!")..fadeIn().delay('slow').fadeOut(5000);;
        return false;
    }
}
  $('select[name="location"]').on('change', function(){   
        var location=$(this).val();
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
    });
});
</script>
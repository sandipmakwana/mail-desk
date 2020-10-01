  
    <div class="container">
        <div class="row">   
           <div class="col-sm-12">
                <?php if ($this->session->flashdata('error') != null) {  ?>
                        <div class="alert alert-danger alert-dismissable">    
                            <?= $this->session->flashdata('error'); ?>
                        </div>
                <?php } ?> 
            </div>        
            <div class="col-sm-12">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> New Request Form </h4><hr>
                             <?php echo form_open_multipart('Emprequest/addRequest','class="form-inner"','autocomplete="off"'); ?> 
                                <input type="hidden" name="hiddentokenid" id="hiddentokenid" value="<?php if(isset($empdtais->emp_token)) echo $empdtais->emp_token; ?>">
                                <input type="hidden" name="hiddendesignation" id="hiddendesignation" value="<?php if(isset($empdtais->emp_desig)) echo $empdtais->emp_desig; ?>">
                                <input type="hidden" name="hiddendepartment" id="hiddendepartment" value="<?php if(isset($empdtais->emp_dept)) echo $empdtais->emp_dept; ?>">
                                <input type="hidden" name="hiddenbusiness_unit" id="hiddenbusiness_unit" value="<?php if(isset($empdtais->emp_buss_unit)) echo $empdtais->emp_buss_unit; ?>">
                                <input type="hidden" name="hiddenstdcode" id="hiddenstdcode" value="<?php if(isset($empdtais->emp_stdcode)) echo $empdtais->emp_stdcode; ?>">
                                <input type="hidden" name="hiddentelephone" id="hiddentelephone" value="<?php if(isset($empdtais->emp_landline)) echo $empdtais->emp_landline; ?>">
                                <input type="hidden" name="hiddenofficeaddress" id="hiddenofficeaddress" >
                                <input type="hidden" name="hiddenfax" id="hiddenfax" value="<?php if(isset($empdtais->emp_fax)) echo $empdtais->emp_fax; ?>">
                                <input type="hidden" name="hiddenmobile" id="hiddenmobile" value="<?php if(isset($empdtais->emp_mobile)) echo $empdtais->emp_mobile; ?>">
                                <input type="hidden" name="hiddenemailaddress" id="hiddenemailaddress" value="<?php if(isset($empdtais->emp_email)) echo $empdtais->emp_email; ?>">
                                <input type="hidden" name="hiddencostcenter" id="hiddencostcenter" value="<?php if(isset($empdtais->emp_costcenter)) echo $empdtais->emp_costcenter; ?>">     
                                <input type="hidden" name="hiddenlocation" id="hiddenlocation" value="<?php if(isset($empdtais->emp_location_name)) echo $empdtais->emp_location_name; ?>">
                                <input type="hidden" name="hiddenmgrtoken" id="hiddenmgrtoken" value="<?php if(isset($empdtais->emp_mgr_token)) echo $empdtais->emp_mgr_token; ?>">
                                <input type="hidden" name="hiddenmgrname" id="hiddenmgrname" value="<?php if(isset($empdtais->emp_mgr_name)) echo $empdtais->emp_mgr_name; ?>">
                                <input type="hidden" name="hiddenmgremail" id="hiddenmgremail" value="<?php if(isset($empdtais->emp_mgr_email)) echo $empdtais->emp_mgr_email; ?>">
                                <input type="hidden" name="hiddenhrmgrtoken" id="hiddenhrmgrtoken" value="<?php if(isset($empdtais->emp_hrmgr_token)) echo $empdtais->emp_hrmgr_token; ?>">
                                <input type="hidden" name="hiddenhrmgrname" id="hiddenhrmgrname" value="<?php if(isset($empdtais->emp_hrmgr_name)) echo $empdtais->emp_hrmgr_name; ?>">
                                <input type="hidden" name="hiddenhrmgremail" id="hiddenhrmgremail" value="<?php if(isset($empdtais->emp_hrmgr_email)) echo $empdtais->emp_hrmgr_email; ?>">
                                <input type="hidden" name="hiddensrhrmgrtoken" id="hiddensrhrmgrtoken" value="<?php if(isset($empdtais->emp_sr_hrmgr_token)) echo $empdtais->emp_sr_hrmgr_token; ?>">
                                <input type="hidden" name="hiddensrhrmgrname" id="hiddensrhrmgrname" value="<?php if(isset($empdtais->emp_sr_hrmgr_name)) echo $empdtais->emp_sr_hrmgr_name; ?>">
                                <input type="hidden" name="hiddensrhrmgremail" id="hiddensrhrmgremail" value="<?php if(isset($empdtais->emp_sr_hrmgr_email)) echo $empdtais->emp_sr_hrmgr_email; ?>">     
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Display Name on V-Card</label>
                                        <input type="text" id="empname" class="form-control mb-4" readonly name="empname" value="<?php if(isset($empdtais->emp_name)) echo $empdtais->emp_name; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Designation <span class="red-text">* </span></label>
                                        <input type="text" id="designation" class="form-control mb-4" placeholder="" name="designation" value="<?php if(isset($empdtais->emp_desig)) echo $empdtais->emp_desig; ?>">
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_design"></span></div>
                                    </div>                          
                                    <div class="col-sm-4">
                                        <label>Department/Division </label>
                                        <input type="text" id="department" class="form-control mb-4" placeholder="" name="department" value="<?php if(isset($empdtais->emp_dept)) echo $empdtais->emp_dept; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Business Unit <span class="red-text">* </span></label>
                                                <input type="text" id="business_unit" class="form-control mb-4" placeholder="" name="business_unit" value="<?php if(isset($empdtais->emp_buss_unit)) echo $empdtais->emp_buss_unit; ?>">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_bus_unt"></span></div>
                                            </div>                     
                                            <div class="col-sm-6">
                                                <label>Location <span class="red-text">* </span></label>
                                                 <?php  echo form_dropdown('location', $locationlist, $empdtais->emp_location_name,'class="form-control" id="location" '); 
                                                ?> 
                                            </div>
                                            <div class="col-sm-6">
                                                <label>ISD/STD Code <span class="red-text">* </span></label>
                                                <input type="text" id="stdcode" class="form-control mb-4" placeholder="" name="stdcode" value="<?php if(isset($empdtais->emp_stdcode)) echo $empdtais->emp_stdcode; ?>">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_stdcode"></span></div>
                                            </div>
                                             <div class="col-sm-6">
                                                <label>Telephone <span class="red-text">* </span></label>
                                                <input type="text" id="telephone" class="form-control mb-4" placeholder="" name="telephone" value="<?php if(isset($empdtais->emp_landline)) echo $empdtais->emp_landline; ?>">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_telphoe"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Office Address <span class="red-text">* </span></label>
                                        <textarea class="form-control mb-4" name="officeaddress" id="officeaddress" readonly></textarea>
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_off_address"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Fax</label>
                                        <input type="text" id="fax" class="form-control mb-4" placeholder="" name="fax" value="<?php if(isset($empdtais->emp_fax)) echo $empdtais->emp_fax; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Mobile</label>
                                        <input type="text" id="mobile" class="form-control mb-4" placeholder="" name="mobile" value="<?php if(isset($empdtais->emp_mobile)) echo $empdtais->emp_mobile; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Oficial Email Address</label>
                                        <input type="text" id="emailaddress" class="form-control mb-4" placeholder="" name="emailaddress" readonly value="<?php if(isset($empdtais->emp_email)) echo $empdtais->emp_email; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Cost Center <span class="red-text">* </span></label>
                                        <input type="text" id="costcenter" class="form-control mb-4" placeholder="" name="costcenter" value="<?php if(isset($empdtais->emp_costcenter)) echo $empdtais->emp_costcenter; ?>">
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_cost_center"></span></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>WBS Element</label>
                                        <input type="text" id="wbselement" class="form-control mb-4" placeholder="" name="wbselement" value="<?php if(isset($empdtais->emp_wbs)) echo $empdtais->emp_wbs; ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Vendor <span class="red-text">* </span></label>
                                       <?php  echo form_dropdown('vendor', $vendorlist, '', 'class="form-control" id="vendor" '); 
                                        ?>  
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />              
                                        <input type="submit" class="btn btn-md btn-outline-danger" name="buttondraft" id="buttondraft" value="Save Draft"/>
                                        <input type="submit" class="btn btn-md btn-danger" name="buttonsubmit" id="buttonsubmit"   value="Submit"/>
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
    $('#buttondraft').click(function(){
        var empname=$("#empname").val()
        if(empname==""){
            alert("Your employee master data record not found. Please contact support! ");
            return false;
        }
     });
    $('#buttonsubmit').click(function() {
        var designation = $("#designation").val();
        var business_unit = $("#business_unit").val();
        var telephone = $("#telephone").val();
        var stdcode = $("#stdcode").val();
        var officeaddress = $("#officeaddress").val();
        var costcenter = $("#costcenter").val();
        var empname=$("#empname").val()
        if(empname==""){
            alert("Your employee master data record not found. Please contact support! ");
            return false;
        }
        if (designation == "") {
            $("#err_txt_design").text("Please Enter Designation!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
        if (business_unit == "") {
            $("#err_txt_bus_unt").text("Please Enter Business Unit").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
        if (telephone == "") {
            $("#err_txt_telphoe").text("Please Enter Telephone Number!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
        if (stdcode == "") {
            $("#err_txt_stdcode").text("Please Enter STD Code!");
            return false;
        }
        if (officeaddress == "") {
            $("#err_txt_off_address").text("Please Enter Office Address!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
        if (costcenter == "") {
            $("#err_txt_cost_center").text("Please Enter Cost Center!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
        else
        {
            $.blockUI({ css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff' 
            } });
        }
    }); 

    
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
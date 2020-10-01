    <div class="container">
        <div class="row">           
            <div class="col-sm-12">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> New Mail Request </h4><hr>
                                <div class="row ">
                                    <div class="col-sm-12  p-3  mb-4">
                                        <div class="border">
                                          <div class="col-12 pt-3">
                                            <div class="row ">
                                              <label class="text-sm-right col-sm-4">Token No.</label>
                                              <div class="col-sm-4">
                                                <div class="">
                                                  <input type="text" class="form-control" placeholder="" name="tokenno" id="tokenno">
                                                   
                                                  </div>
                                              </div>
                                              <div class="col-sm-4">
                                                <a href="" class="btn btn-sm btn-danger m-0" onclick="return   godetails()">Go</a>
                                                <a href="" class="btn btn-sm btn-mdb-color m-0">Clear</a>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>  
                             <?php echo form_open_multipart('Request/addRequest','class="form-inner"','autocomplete="off"'); ?> 
                                <input type="hidden" name="hiddentokenid" id="hiddentokenid">   
                                <input type="hidden" name="hiddenempname" id="hiddenempname">
                                <input type="hidden" name="hiddendesignation" id="hiddendesignation">
                                <input type="hidden" name="hiddendepartment" id="hiddendepartment">
                                <input type="hidden" name="hiddenbusiness_unit" id="hiddenbusiness_unit">
                                <input type="hidden" name="hiddenstdcode" id="hiddenstdcode">
                                <input type="hidden" name="hiddentelephone" id="hiddentelephone">
                                <input type="hidden" name="hiddenofficeaddress" id="hiddenofficeaddress">
                                <input type="hidden" name="hiddenfax" id="hiddenfax">
                                <input type="hidden" name="hiddenmobile" id="hiddenmobile">
                                <input type="hidden" name="hiddenemailaddress" id="hiddenemailaddress">
                                <input type="hidden" name="hiddencostcenter" id="hiddencostcenter">
                                <input type="hidden" name="hiddenwbselement" id="hiddenwbselement">
                                <input type="hidden" name="hiddenlocation" id="hiddenlocation">
                                <input type="hidden" name="hiddenmgrtoken" id="hiddenmgrtoken">
                                <input type="hidden" name="hiddenmgrname" id="hiddenmgrname">
                                <input type="hidden" name="hiddenmgremail" id="hiddenmgremail">
                                <input type="hidden" name="hiddenhrmgrtoken" id="hiddenhrmgrtoken">
                                <input type="hidden" name="hiddenhrmgrname" id="hiddenhrmgrname">
                                <input type="hidden" name="hiddenhrmgremail" id="hiddenhrmgremail">
                                <input type="hidden" name="hiddensrhrmgrtoken" id="hiddensrhrmgrtoken">
                                <input type="hidden" name="hiddensrhrmgrname" id="hiddensrhrmgrname">
                                <input type="hidden" name="hiddensrhrmgremail" id="hiddensrhrmgremail">                      
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Display Name on V-Card</label>
                                        <input type="text" id="empname" class="form-control mb-4" readonly name="empname">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Designation <span class="red-text">* </span></label>
                                        <input type="text" id="designation" class="form-control mb-4" placeholder="" name="designation">
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_design"></span></div>
                                    </div>                          
                                    <div class="col-sm-4">
                                        <label>Department/Division</label>
                                        <input type="text" id="department" class="form-control mb-4" placeholder="" name="department">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label>Business Unit <span class="red-text">* </span></label>
                                                <input type="text" id="business_unit" class="form-control mb-4" placeholder="" name="business_unit">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_bus_unt"></span></div>
                                            </div>                     
                                            <div class="col-sm-6">
                                                <label>Location <span class="red-text">* </span></label>
                                                 <?php  echo form_dropdown('location', $locationlist, '','class="form-control" id="location" '); 
                                                ?> 
                                            </div>
                                            <div class="col-sm-6">
                                                <label>ISD/STD Code <span class="red-text">* </span></label>
                                                <input type="text" id="stdcode" class="form-control mb-4" placeholder="" name="stdcode">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_stdcode"></span></div>
                                            </div>
                                             <div class="col-sm-6">
                                                <label>Telephone <span class="red-text">* </span></label>
                                                <input type="text" id="telephone" class="form-control mb-4"
                                                splaceholder="" name="telephone">
                                                <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_telphoe"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Office Address</label>
                                        <textarea class="form-control mb-4" name="officeaddress" id="officeaddress" readonly></textarea>
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_off_address"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Fax</label>
                                        <input type="text" id="fax" class="form-control mb-4" placeholder="" name="fax">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Mobile</label>
                                        <input type="text" id="mobile" class="form-control mb-4" placeholder="" name="mobile">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Official Email Address</label>
                                        <input type="text" id="emailaddress" class="form-control mb-4" placeholder="" name="emailaddress" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Cost Center <span class="red-text">* </span></label>
                                        <input type="text" id="costcenter" class="form-control mb-4" placeholder="" name="costcenter">
                                        <div class="form-group has-error ml-4 pl-2"><span class="help-block err" id="err_txt_cost_center"></span></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>WBS Element</label>
                                        <input type="text" id="wbselement" class="form-control mb-4" placeholder="" name="wbselement">
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
                                        <input type="submit" class="btn btn-md btn-danger" name="buttonsubmit" id="buttonsubmit" value="Submit"/>
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
}
function godetails(){
        var tokenid=$("#tokenno").val();
        //alert(tokenid);
        $.ajax({
            url : "<?php echo base_url('Request/getEmployeeDetails') ?>", 
            data: {tokenid:tokenid},             
            success: function(response)
            {  
                //alert(response.length);
                if(response.length >2)
                {
                //alert(response.emp_name);
                    $.each(JSON.parse(response), function(i, data) {
                       // alert(data['emp_name']);hiddentokenid
                        $('#hiddentokenid').val(data['emp_token']);
                        $('#empname').val(data['emp_name']);
                        $('#designation').val(data['emp_desig']);
                        $('#department').val(data['emp_dept']);
                        $('#business_unit').val(data['emp_buss_unit']);
                        $('#location').val(data['emp_location_name']);
                        $('#stdcode').val(data['emp_stdcode']);
                        $('#telephone').val(data['emp_landline']);
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
    $('#buttondraft').click(function(){
        var empname=$("#empname").val()
        if(empname==""){
            alert("Please Enter Employee Details");
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
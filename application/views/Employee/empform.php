    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> User Master</h4><hr>
                            <?php echo form_open_multipart('Empmaster/addEmpMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="emp_id" id="emp_id" value="<?php if(isset($emp_master->emp_id)) { echo $emp_master->emp_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>First Name <span class="red-text">* </span> </label>
                                        <input id="firstname" type="text" class="form-control mb-4" placeholder="" name="firstname" value="<?php if(isset($emp_master->emp_firstname)) echo $emp_master->emp_firstname; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_fname"></span></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Last Name <span class="red-text">* </span></label>
                                        <input type="text" id="lastname" class="form-control mb-4" placeholder="" name="lastname" value="<?php if(isset($emp_master->emp_lastname)) echo $emp_master->emp_lastname; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_lname"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>User Name / Token ID <span class="red-text">* </span></label>
                                        <input type="text" id="uname" class="form-control mb-4" placeholder="" name="uname" value="<?php if(isset($emp_master->emp_username)) echo $emp_master->emp_username; ?>" onBlur="checkemploytoken()">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_uname"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Location <span class="red-text">* </span></label>
                                        <select id="emp_locationid" class="form-control"  name="emp_locationid" >
										<option value="">Choose Location</option>
                                        <?php 
                                         
                                        if (!empty($fr_locations)) { 
                                              
                                           $locationid = $emp_master->emp_locationid;
                                            ?>
                                    <?php foreach ($fr_locations as $frlocation) { 
                                        
                                        ?>
                                                <option <?php if($frlocation->location_id == $locationid) { echo "selected='selected'"; } ?>   value="<?php echo $frlocation->location_id; ?>"><?php echo $frlocation->location_code.' - '.$frlocation->location_name; ?></option>
                                                <?php } ?> 
                                <?php } ?> 
                                        
                                        </select>
										<div class="form-group has-error">
										  <span class="help-block err" id="err_txt_emp_locationid"></span>
										</div>
                                    </div>
									<div class="col-sm-12">
                                        <label>Department <span class="red-text">* </span></label>
                                        <select id="emp_departmentid" class="form-control"  name="emp_departmentid" >
										<option value="">Choose Department</option>
                                        <?php
                                         
                                        if (!empty($fr_departments)) { 
                                              
                                           $departmentid = $emp_master->emp_departmentid;
                                            ?>
                                    <?php foreach ($fr_departments as $frdepartment) { 
                                        
                                        ?>
                                                <option <?php if($frdepartment->business_id == $departmentid) { echo "selected='selected'"; } ?>   value="<?php echo $frdepartment->business_id; ?>"><?php echo $frdepartment->department_code.' - '.$frdepartment->department_name; ?></option>
                                                <?php } ?> 
                                <?php } ?> 
                                        
                                        </select>
										<div class="form-group has-error">
										  <span class="help-block err" id="err_txt_emp_departmentid"></span>
										</div>
                                    </div>
									<div class="col-sm-12">
                                        <label>Email <span class="red-text">* </span></label>
                                        <input type="text" class="form-control mb-4" id="email" placeholder="" name="email" value="<?php if(isset($emp_master->emp_email)) echo $emp_master->emp_email; ?>" onBlur="checkEmpEmail()">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_email"></span></div>
                                    </div>
                                     <div class="col-sm-12">
                                        <label>Cost Center <span class="red-text">* </span></label>
                                        <input type="text" id="costcenter" class="form-control mb-4" placeholder="" name="costcenter" value="<?php if(isset($emp_master->costcenter)) echo $emp_master->costcenter; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_costcenter"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Role <span class="red-text">* </span></label>
                                        <?php
                                            $rolelist = array(       ''=>'Select role',
                                                'Employee' => 'Employee',
                                                'DeskUser' => 'Mail Desk User',
                                                'Admin' => 'Admin',
                                            );
                                            echo form_dropdown('role', $rolelist, $emp_master->emp_role, 'class="form-control" id="role" '); 
                                        ?> 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_role"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label class="mr-3" id="lblldap">LDAP</label>
                                      <div class="form-check form-check-inline">
                                        <input type="radio"  class="form-check-input" id="true" name="ldap" value="1" <?= (($emp_master->is_ldap==1)? "checked":"");?>>
                                          <label class="form-check-label" for="true"> True</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input type="radio"  class="form-check-input"  id="false" name="ldap" value="0" <?=(($emp_master->is_ldap==0)? "checked":""); ?> >
                                        <label class="form-check-label" for="false" >
                                          False
                                        </label>
                                      </div>
                                    </div>
                                  <!--   <div class="col-sm-12" id="vendorclass">
                                       <label>Vendor</label>
                                       <?php  echo form_dropdown('vendor', $vendorlist, $emp_master->emp_role, 'class="form-control" id="vendor" '); 
                                        ?>  
                                    </div> -->
                                    
									<?php if(!isset($emp_master->emp_email)){ ?>
                                    <div class="col-sm-12" id="passwordclass">
                                        <label> Password</label>
                                        <input type="password" id="password" class="form-control mb-4" placeholder="" name="password" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_pass"></span></div>
                                    </div>
									<?php  } ?> 
                                </div>               
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" value="<?=$buttonname ?>" onclick="return frmvalidation()"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Empmaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
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
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    var uname = $("#uname").val();
	var emp_locationid = $("#emp_locationid").val();
	var emp_departmentid = $("#emp_departmentid").val();
    var email = $("#email").val();
    var role = $("#role").val();
    var password = $("#password").val();
    var costcenter=$("#costcenter").val();
    if (firstname == "") {
        $("#err_txt_fname").text("Please Enter First Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#firstname").focus();
        return false;
    }
    if (lastname == "") {
        $("#err_txt_lname").text("Please Enter Last Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#lastname").focus();
        return false;
    }
      if (uname == "") {
        $("#err_txt_uname").text("Please Enter User Name/Token Number!").fadeIn().delay('slow').fadeOut(5000);
        $("#uname").focus();
        return false;
    }
	if (emp_locationid == "") {
        $("#err_txt_emp_locationid").text("Please Choose Location!").fadeIn().delay('slow').fadeOut(5000);
        $("#emp_locationid").focus();
        return false;
    }
	if (emp_departmentid == "") {
        $("#err_txt_emp_departmentid").text("Please Choose Department!").fadeIn().delay('slow').fadeOut(5000);
        $("#emp_departmentid").focus();
        return false;
    }
    if (email == "") {
        $("#err_txt_email").text("Please Enter Email ID!").fadeIn().delay('slow').fadeOut(5000);
        $("#email").focus();
        return false;
    }
	if(costcenter=="")
    {
        $("#err_txt_costcenter").text("Cost center value is required!").fadeIn().delay('slow').fadeOut(5000);
        $("#costcenter").focus();
        return false;
    }
    if (role == "") {
        $("#err_txt_role").text("Please Select Role!").fadeIn().delay('slow').fadeOut(5000);
        $("#role").focus();
        return false;
    }
    if (password.length < 6) {
        $("#err_txt_pass").text("Password Must Be 6 Characters!").fadeIn().delay('slow').fadeOut(5000);
        $("#password").focus();
        return false;
    }
}
function checkemploytoken(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var uname = $("#uname").val();
        if(uname.length < 3){
            $("#err_txt_tokenid").text("User Name/Token Number Must Be Correct!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }else{
            $.ajax({
                url : "<?php echo base_url('Empmaster/checkEmptoken') ?>", 
                data: {tokenid:uname},             
                success: function(response)
                {
                    if(response.length>2){
                        $("#err_txt_uname").text("User Name/Token Number Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                        $("#tokenid").val(''); 
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
}
function checkEmpEmail(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var email = $("#email").val();
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
        {
             $.ajax({
                url : "<?php echo base_url('Empmaster/checkEmpEmail') ?>", 
                data: {email:email},             
                success: function(response)
                {
                    if(response.length>2){
                        $("#err_txt_email").text("Email ID Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                        $("#email").val('');
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
    $(document).ready(function(){       
        var empid= $('#emp_id').val();
        $('#passwordclass').hide();
        if(emp_id.length > 0){
            $('#passwordclass').hide();
        }
        
        $('select[name="role"]').on('change', function() {           
            if(document.getElementById("false").checked==true){
                 $('#passwordclass').show();
            }
            else{
                $('#passwordclass').hide();
                $('#vendorclass').hide();
                $("#true").prop("checked", true);
            }
        });
        $('input[type=radio][name=ldap]').change(function() {
            if (this.value == 0) {
                $('#passwordclass').show();
            }
            else{
                $('#passwordclass').hide();
            }

        });

    });
</script>
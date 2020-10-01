
    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Department Master</h4><hr/>
                            <?php echo form_open_multipart('Businessmaster/addBusinessMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="business_id" id="business_id" value="<?php if(isset($business_id)) { echo $business_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-4">
                                    <label>Department Code <span class="red-text">* </span></label>
                                        <input type="text" onChange="return checkDepartmentCode();" id="department_code" class="form-control" placeholder="" name="department_code"   value="<?php if(isset($biz_master->department_code)) echo $biz_master->department_code; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_department_code"></span></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label>Department Name <span class="red-text">* </span></label>
                                        <input type="text" id="department_name" class="form-control" placeholder="" name="department_name"  value="<?php if(isset($biz_master->department_name)) echo $biz_master->department_name; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_department_name"></span></div>
                                    </div>
                                    
                                   
                                </div> 
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Cost Center <span class="red-text">* </span></label>
                                        <input type="text" id="cost_center" class="form-control" placeholder="" name="cost_center"   value="<?php if(isset($biz_master->cost_center)) echo $biz_master->cost_center; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_costcenter"></span></div>
                                    </div>
                                    
                                   
                                </div>  
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" onclick="return frmvalidation()" value="<?= $buttonname ?>"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Businessmaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
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
    var department_code = $("#department_code").val();
    var department_name = $("#department_name").val();
    var cost_center = $("#cost_center").val();
   
    if (department_code == "") {
        $("#err_txt_department_code").text("Please Enter Department Code").fadeIn().delay('slow').fadeOut(5000);
        $("#department_code").focus();
        return false;
    }
	 if (department_name == "") {
        $("#err_txt_department_name").text("Please Enter Department Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#department_name").focus();
        return false;
    }
    if (cost_center == "") {
        $("#err_txt_costcenter").text("Please Enter Cost Center").fadeIn().delay('slow').fadeOut(5000);
        $("#cost_center").focus();
        return false;
    }
}
function checkLocatinName(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var businessname = $("#businessname").val();
        $.ajax({
            url : "<?php echo base_url('Businessmaster/checkBusinessName') ?>", 
            data: {businessname:businessname},             
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_businessname").text("Business Name Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#businessname").val('');
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


function checkDepartmentCode(){
    
        var department_code = $("#department_code").val();
		
        $.ajax({
            url : "<?php echo base_url('Businessmaster/checkDepartmentCode') ?>", 
            data: {department_code:department_code},
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_department_code").text("Department code already exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#department_code").val('');
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
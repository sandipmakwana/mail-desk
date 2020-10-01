
    <div class="container">
        <div class="row">           
            <div class="offset-sm-2 col-sm-8">             
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Business Master</h4><hr>
                            <?php echo form_open_multipart('Companymaster/addCompanyMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="bu_id" id="bu_id" value="<?php if(isset($bu_id)) { echo $bu_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label >Code <span class="red-text">* </span></label>
                                        <input type="text" id="companycode" class="form-control " placeholder="" name="companycode" value="<?php if(isset($com_master->bu_code)) echo $com_master->bu_code; ?>" onBlur="checkCompanyCode()"> 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_code"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Name <span class="red-text">* </span></label>  
                                        <input type="text" id="companyname" class="form-control" placeholder="" name="companyname" onBlur="checkCompanyName()" value="<?php if(isset($com_master->bu_name)) echo $com_master->bu_name; ?>" >
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_compname"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" value="<?=$buttonname ?>" onclick="return frmvalidation()"><i class="fa fa-save fa-fw"></i><?=$buttonname ?> </button>
                                    <a class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Companymaster/index'"><i class="fa fa-ban fa-fw"></i>Cancel</a>
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
    var companycode = $("#companycode").val();
    var companyname = $("#companyname").val();
    if (companycode == "") {
        $("#err_txt_code").text("Please Enter Business Code!").fadeIn().delay('slow').fadeOut(5000);
        $("#companycode").focus();
        return false;
    }
    if (companyname == "") {
        $("#err_txt_compname").text("Please Enter Business Name").fadeIn().delay('slow').fadeOut(5000);
        $("#companyname").focus();
        return false;
    }
}
function checkCompanyCode(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var companycode = $("#companycode").val();
        $.ajax({
            url : "<?php echo base_url('Companymaster/checkCompanyCode') ?>", 
            data: {companycode:companycode},             
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_code").text("Business Code Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#companycode").val('');                  
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
    else if(value=="Update"){
        var companycode = $("#companycode").val();
        var companyid = $("#bu_id").val();      
        $.ajax({
            url : "<?php echo base_url('Companymaster/checkCompanyCode') ?>", 
            data: {companycode:companycode, companyid:companyid},             
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_code").text("Business Code Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#companycode").val('');                  
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
function checkCompanyName(){
    var value = $('#buttonsubmit').val(); 
    
    if(value=="Save"){
        var companyname = $("#companyname").val();
        $.ajax({
            url : "<?php echo base_url('Companymaster/checkCompanyName') ?>", 
            data: {companyname:companyname},             
            success: function(response)
            {

                if(response.length>2){
                    $("#err_txt_compname").text("Business Name Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#companyname").val('');
                    return false;
                } 
                else{
                    return true;
                }                  
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
               alert('Error adding / update data');
            }
        });
    }
    else if(value=="Update"){
        var companyname = $("#companyname").val();
        var companyid = $("#bu_id").val();    
        $.ajax({
            url : "<?php echo base_url('Companymaster/checkCompanyName') ?>", 
            data: {companyname:companyname, companyid:companyid},             
            success: function(response)
            {

                if(response.length>2){
                    $("#err_txt_compname").text("Business Name Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#companyname").val('');
                    return false;
                } 
                else{
                    return true;
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


    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Charge Master</h4><hr/>
                            <?php echo form_open_multipart('Chargemaster/addChargeMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="charge_id" id="charge_id" value="<?php if(isset($charge_id)) { echo $charge_id ; }?>">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Charge Name <span class="red-text">* </span></label>
                                        <input type="text" id="chargename" class="form-control" placeholder="" name="charge_name" onBlur="checkChargeName()"  value="<?php if(isset($charge_master->charge_name)) echo $charge_master->charge_name; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_chargename"></span></div>
                                    </div>
                                    <div class="col-sm-4">
										<label>Charge Code <span class="red-text">* </span></label>
                                        <select id="charge_code" class="form-control"  name="charge_code" >
										<option value="">Choose Charge Code</option>
                                        <?php 
                                         
                                        if (!empty($chargecodes)) { 
                                              
                                            ?>
                                    <?php foreach ($chargecodes as $chargecode) { 
                                        
                                        ?>
                                                <option <?php if($chargecode->agency_code == $charge_master->charge_code) { echo "selected='selected'"; } ?>   value="<?php echo $chargecode->agency_code; ?>"><?php echo $chargecode->agency_code; ?></option>
                                                <?php } ?> 
                                <?php } ?> 
                                        
                                        </select>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_chargecode"></span></div>
                                    </div>
                                    <div class="col-sm-4">
										<label>Agency Code <span class="red-text">* </span></label>
                                        <input type="text" id="agency_code" class="form-control" placeholder="" name="agency_code"   value="<?php if(isset($charge_master->agency_code)) echo $charge_master->agency_code; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_agency_code"></span></div>
                                    </div>
                                    <div class="col-sm-4">
										<label>Charge Value <span class="red-text">* </span></label>
                                        <input type="text" id="charge_value" class="form-control" placeholder="" name="charge_value"   value="<?php if(isset($charge_master->charge_value)) echo $charge_master->charge_value; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_chargevalue"></span></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" onclick="return frmvalidation()" value="<?= $buttonname ?>"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Chargemaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
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
    var chargename = $("#chargename").val();
	var charge_code = $("#charge_code").val();
	var agency_code = $("#agency_code").val();
	var charge_value = $("#charge_value").val();
    if (chargename == "") {
        $("#err_txt_chargename").text("Please Enter Charge Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#chargename").focus();
        return false;
    }
	if (charge_code == "") {
        $("#err_txt_chargecode").text("Please Choose Charge Code!").fadeIn().delay('slow').fadeOut(5000);
        $("#charge_code").focus();
        return false;
    }
	if (agency_code == "") {
        $("#err_txt_agency_code").text("Please Enter Agency Name!").fadeIn().delay('slow').fadeOut(5000);
        $("#agency_code").focus();
        return false;
    }
	if (charge_value == "") {
        $("#err_txt_chargevalue").text("Please Enter Charge Value!").fadeIn().delay('slow').fadeOut(5000);
        $("#charge_value").focus();
        return false;
    }
}
function checkChargeName(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var chargename = $("#chargename").val();
        $.ajax({
            url : "<?php echo base_url('Chargemaster/checkChargeName') ?>", 
            data: {charge_name:chargename},
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_chargename").text("Charge Name Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#chargename").val('');
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
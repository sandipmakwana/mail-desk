
    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Frank Master <?php echo (isset($franking_id))? "(View only mode)" : ""?></h4><hr/>
                            <?php echo form_open_multipart('Frankingmaster/addFrankingMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="franking_id" id="franking_id" value="<?php if(isset($franking_id)) { echo $franking_id ; }?>">
                                <div class="row">
									<div class="col-sm-12">
                                        <label>Location <span class="red-text">* </span></label>
                                        <select id="f_locationids" class="form-control"  name="f_locationid" <?php echo (isset($franking_id))? "disabled" : ""?> >
											<?php 
											if (!empty($fr_locations)) {
											   $locationids = $fr_master->f_locationids;
                                            ?>
											<?php foreach ($fr_locations as $frlocation) { ?>
													<option <?php if($frlocation->location_id == $locationids) { echo "selected='selected'"; } ?>   value="<?php echo $frlocation->location_id; ?>"><?php echo $frlocation->bu_code.' - '.$frlocation->location_code.' - '.$frlocation->location_type.' - '.$frlocation->location_name; ?></option>
													<?php } ?> 
											<?php } ?>
                                        </select>
										<div class="form-group has-error">
										  <span class="help-block err" id="err_txt_f_locationids"></span>
										</div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Transaction/Deposited date<span class="red-text">* </span></label>
                                        <input type="text" id="transaction_dt" class="form-control" placeholder="" name="transaction_dt"   value="<?php if(isset($fr_master->transaction_dt)) echo $fr_master->transaction_dt; ?>" readonly="" <?php echo (isset($franking_id))? "disabled" : ""?>>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_transaction_dt"></span></div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Frank Amount <span class="red-text">* </span></label>
                                        <input type="text" id="f_value" class="form-control" placeholder="" name="f_value"   value="<?php if(isset($fr_master->f_value)) echo $fr_master->f_value; ?>" <?php echo (isset($franking_id))? "disabled" : ""?>>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_f_value"></span></div>
                                    </div>
                                     <div class="col-sm-4">
                                        <label>Reference Number<span class="red-text">* </span></label>
                                        <input type="text" id="f_referenceno" class="form-control" placeholder="" name="referenceno"   value="<?php if(isset($fr_master->reference_no)) echo $fr_master->reference_no; ?>" <?php echo (isset($franking_id))? "disabled" : ""?>>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_f_referenceno"></span></div>
                                    </div>
                                     <div class="col-sm-12">
                                        <label>Remark<span class="red-text">* </span></label>
										<textarea class="form-control mb-4" name="remark" id="remark" <?php echo (isset($franking_id))? "disabled" : ""?>><?php if(isset($fr_master->remark)) echo $fr_master->remark; ?></textarea>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_remark"></span></div>
                                    </div>
                                </div>  <br />
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
									<?php if(!isset($franking_id)){?>
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" onclick="return frmvalidation()" value="<?= $buttonname ?>"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
									<?php } ?>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Frankingmaster/index'><i class="fa fa-ban fa-fw" ></i><?php echo (isset($franking_id))? "Back" : "Cancel"?></a>
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
    var f_month = $("#f_month").val();
	var f_year = $("#f_year").val();
    var f_value = $("#f_value").val();
    var f_locationids = $("#f_locationids").val();
    var remark = $("#remark").val();
    var f_referenceno = $("#f_referenceno").val();
    if (f_month == "") {
        $("#err_txt_month").text("Choose Month!").fadeIn().delay('slow').fadeOut(5000);
        $("#f_month").focus();
        return false;
    }
	if (f_year == "") {
        $("#err_txt_year").text("Choose Year!").fadeIn().delay('slow').fadeOut(5000);
        $("#f_year").focus();
        return false;
    }
    if (f_locationids == "") {
        $("#err_txt_f_locationids").text("Choose Locations").fadeIn().delay('slow').fadeOut(5000);
        $("#f_locationids").focus();
        return false;
    }
	if (f_value == "") {
        $("#err_txt_f_value").text("Please Enter Frank Amount.").fadeIn().delay('slow').fadeOut(5000);
        $("#f_value").focus();
        return false;
    }
    if (f_referenceno == "") {
        $("#err_txt_f_referenceno").text("Add reference number").fadeIn().delay('slow').fadeOut(5000);
        $("#f_referenceno").focus();
        return false;
    }
    if (remark == "") {
        $("#err_txt_remark").text("Write some relevant comments").fadeIn().delay('slow').fadeOut(5000);
        $("#remark").focus();
        return false;
    }
}
function checkLocatinName(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var frankname = $("#frankname").val();
        $.ajax({
            url : "<?php echo base_url('Frankmaster/checkFrankName') ?>", 
            data: {frankname:frankname},             
            success: function(response)
            {
                if(response.length>2){
                    $("#err_txt_frankname").text("Frank Name Already Exist!").fadeIn().delay('slow').fadeOut(5000);
                    $("#frankname").val('');
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
 $(document).ready(function() {
 
  $('#transaction_dt').datepicker({
  autoclose: true,
  format: 'dd-mm-yyyy',
  }).datepicker("setDate", new Date());
});
</script>
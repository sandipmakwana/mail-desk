
    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> New Pickup Point</h4><hr/>
                            <?php echo form_open_multipart('Pickuppointmaster/addPickuppointMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="pickup_id" id="pickup_id" value="<?php if(isset($pickup_id)) { echo $pickup_id ; }?>">
                                <div class="row">

                                    <div class="col-sm-10">
                                        <label>Locations <span class="red-text">* </span></label>
                                        <select name="p_locationid" class="form-control" id="p_locationid">
                                        <?php 
                                         
                                        if (!empty($fr_locations)) { 
                                              
                                           $locationids = $fr_master->p_locationid;
                                            ?>
                                    <?php foreach ($fr_locations as $frlocation) { 
                                        
                                        ?>
                                                <option <?php if($frlocation->location_id == $locationids) { echo "selected='selected'"; } ?>   value="<?php echo $frlocation->location_id; ?>"><?php echo $frlocation->location_code.' - '.$frlocation->location_name; ?></option>
                                                <?php } ?> 
                                <?php } ?> 
                                        
                                        </select>
										<div class="form-group has-error">
										  <span class="help-block err" id="p_locationid"></span>
										</div>
                                    </div>
									
							<div class="col-sm-10">
                                    <label>Pickup Point <span class="red-text">* </span></label>
                                        <input type="text" id="p_pickuppoint" class="form-control" placeholder="" name="p_pickuppoint"   value="<?php if(isset($fr_master->p_pickuppoint)) echo $fr_master->p_pickuppoint; ?>" > 
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_p_pickuppoint"></span></div>
                                    </div>
							<div class="col-sm-10">
                                    <label>Description <span class="red-text">* </span></label>
									<textarea id="p_desc" class="form-control" placeholder="" name="p_desc"  ><?php if(isset($fr_master->p_desc)) echo $fr_master->p_desc; ?></textarea>
                                        
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_p_desc"></span></div>
                                    </div>		
                                </div>  <br />
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" onclick="return frmvalidation()" value="<?= $buttonname ?>"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Pickuppointmaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
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
    var p_pickuppoint = $("#p_pickuppoint").val();
    var p_desc = $("#p_desc").val();
    
    if (p_pickuppoint == "") {
        $("#err_txt_p_pickuppoint").text("Please Enter Pickup Point!").fadeIn().delay('slow').fadeOut(5000);
        $("#p_pickuppoint").focus();
        return false;
    }
    if (p_desc == "") {
        $("#err_txt_p_desc").text("Please Enter Description").fadeIn().delay('slow').fadeOut(5000);
        $("#p_desc").focus();
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
</script>
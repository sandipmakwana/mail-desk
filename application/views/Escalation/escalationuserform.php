    <div class="container">
        <div class="row">
            <div class="offset-sm-2 col-sm-8">
                <!-- Default form login -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="pink-text"> Escalation User Master</h4><hr>
                            <?php echo form_open_multipart('Escmaster/addEscMaster ','class="form-inner"','autocomplete="off"'); ?>
                                <input type="hidden" name="vs_escmstid" id="vs_escmstid" value="<?php if(isset($vs_escmstid)) { echo $vs_escmstid ; }?>">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Token ID <span class="red-text">* </span></label>
                                        <input type="text" id="tokenid" class="form-control mb-4" placeholder="" name="tokenid" value="<?php if(isset($esc_master->vs_esc_emptoken)) echo $esc_master->vs_esc_emptoken; ?>" onBlur="checkemploytoken()">
                                         <div class="form-group has-error"><span class="help-block err" id="err_txt_tokenid"></span></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Name <span class="red-text">* </span></label>
                                        <input type="text" id="name" class="form-control mb-4" placeholder="" name="name" value="<?php if(isset($esc_master->vs_esc_empname)) echo $esc_master->vs_esc_empname; ?>">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_empname"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Email <span class="red-text">* </span></label>
                                        <input type="text" id="email" class="form-control mb-4" placeholder="" name="email" value="<?php if(isset($esc_master->vs_esc_empemail)) echo $esc_master->vs_esc_empemail; ?>" onBlur="checkEmpEmail()">
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_empmail"></span></div>
                                    </div>
                                    <div class="col-sm-12">
                                        <label>Role <span class="red-text">* </span></label>
                                        <?php
                                            $rolelist = array( 
                                            ''=>'Select role',       
                                                'Central HR' => 'Central HR',
                                                'SSU' => 'SSU',
                                                'Admin' => 'Admin',
                                            );
                                            echo form_dropdown('role', $rolelist, $esc_master->vs_esc_role, 'class="form-control" id="role" '); 
                                        ?>
                                        <div class="form-group has-error"><span class="help-block err" id="err_txt_emprole"></span></div>    
                                    </div>
                                </div>              
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                    <hr />
                                    <button type="submit" class="btn btn-md btn-danger" id="buttonsubmit" value="<?=$buttonname ?>" onclick="return frmvalidation()"><i class="fa fa-save fa-fw"></i><?= $buttonname ?> </button>
                                    <a  class="btn btn-md btn-outline-danger" href='<?php echo base_url();?>Escmaster/index'><i class="fa fa-ban fa-fw" ></i>Cancel</a>
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
    var tokenid = $("#tokenid").val();
    var name = $("#name").val();
    var contact = $("#contact").val();
    var email = $("#email").val();
    var role = $("#role").val();
    
    if (tokenid == "") {
        $("#err_txt_tokenid").text("Please Enter Token Number!").fadeIn().delay('slow').fadeOut(5000);
        $("#tokenid").focus();
        return false;
    }
    if (name == "") {
        $("#err_txt_empname").text("Please Enter Employee Name").fadeIn().delay('slow').fadeOut(5000);
        $("#name").focus();
        return false;
    }
    if (email == "") {
        $("#err_txt_empmail").text("Please Enter Employee Email").fadeIn().delay('slow').fadeOut(5000);
        $("#email").focus();
        return false;
    }
    if (role == "") {
        $("#err_txt_emprole").text("Please Select Employee Role").fadeIn().delay('slow').fadeOut(5000);
        $("#email").focus();
        return false;
    }
}
function checkemploytoken(){
    var value = $('#buttonsubmit').val(); 
    if(value=="Save"){
        var tokenid = $("#tokenid").val();
        if(tokenid.length < 3){
            $("#err_txt_tokenid").text("Token Number Must Be Correct!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }else{
            $.ajax({
                url : "<?php echo base_url('Escmaster/checkEmptoken') ?>", 
                data: {tokenid:tokenid},             
                success: function(response)
                {
                    if(response.length>2){
                        $("#err_txt_tokenid").text("Employee Token Number Already Exist!").fadeIn().delay('slow').fadeOut(5000);
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
                url : "<?php echo base_url('Escmaster/checkEmpEmail') ?>", 
                data: {email:email},             
                success: function(response)
                {
                    if(response.length>2){
                        $("#err_txt_empmail").text("Email ID Already Exist!").fadeIn().delay('slow').fadeOut(5000);
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
            $("#err_txt_empmail").text("Please Enter the valid Email ID!").fadeIn().delay('slow').fadeOut(5000);
            return false;
        }
    }
}
</script>
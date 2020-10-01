    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card">
                    <div class="card-body">
                      <h4 class="pink-text"> Pending Approval/Rejected Request List </h4>
                      <hr/>
                        <table id="requestlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Token No</th>
                                    <th>Name </th> 
                                    <th>Vendor </th>  
                                    <th>Submited Date</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reg_master)) { ?>
                                    <?php foreach ($reg_master as $req) { ?>
                                        <tr>
                                            <td><?php echo $req->req_emp_token; ?></td>
                                            <td><?php echo $req->req_emp_name; ?></td>
                                            <td><?php echo $req->vendor_cmpname; ?></td>
                                            <td><?= date('d-m-Y', strtotime($req->req_submitteddate));?></td>
                                            <td><?php echo $req->req_status?></td>
                                           <td class="center">
                                                <a href="<?php echo base_url("Emprequest/preview/$req->req_id") ?>"class="btn btn-md btn-outline-danger">Preview </a> 
                                                <a onclick="hrapproval('<?= $req->req_id; ?>')" class="btn btn-md btn-danger">
                                                    Approve
                                                </a>
                                                <?php echo '<a onclick="hrreject('. $req->req_id .',\''.$req->req_emp_name.'\',\''.$req->req_emp_email.'\',\''.$req->req_emp_token.'\',\''.$req->req_emp_hrmgr_name.'\')" class="btn btn-md btn-outline-danger">Reject </a>';?> 
                                            </td>
                                        </tr>                                     
                                    <?php } ?> 
                                <?php } ?> 
                            </tbody>
                        </table>  <!-- /.table-responsive -->
                      <!--   <?php echo $links; ?> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="modal_reason" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title red-text"><center>
                    Reject Request
                </center></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="reqid" id="reqid">
                  <!--   <input type="hidden" name="empname" id="empname"> -->
                    <input type="hidden" name="empmail" id="empmail">
                <!--     <input type="hidden" name="emptoken" id="emptoken"> -->
                    <input type="hidden" name="hrname" id="hrname">
                    <div class="form_row">
                         <div class="sign-pass">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="Name">Name </label>
                                    <input type="text" name="empname" id ="empname" class="form-control" readonly />
                                </div>
                                <div class="col-sm-6">
                                    <label for="dispatch_date">Token No.</label>
                                    <input type="text" name="emptoken" class="form-control" id="emptoken" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="sign-pass-again">
                            <label for="l_name">Reason</label>
                            <textarea name="reason" class="form-control" id="reason"></textarea> 
                            <div class="form-group has-error"><span class="help-block err" id="err_txt_reason"></span></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="return save()" class="btn btn-danger">Submit</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#requestlist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 5, 'desc' ]],
        });
    });
    function hrapproval(id){
        $.ajax({
            url : "<?php echo base_url('Emprequest/hrlinkApproval') ?>", 
            data: {id:id},             
            success: function(response)
            {
                var obj= JSON.parse(response);
                swal({
                    title: "Approved!",
                    text: "Requst for " + obj.req_emp_name + " and " + obj.req_emp_token + " has been successfully approved.",
                });
                              
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
               alert('Error adding / update data');
            }
        });         
    }
    function hrreject(id, name, email, emptoken, hrname){
        $('#form')[0].reset();
        $('#modal_reason').modal('show');
        $('#reqid').val(id);
        $('#empname').val(name);
        $('#empmail').val(email);
        $('#emptoken').val(emptoken);
        $('#hrname').val(hrname);
    }
    function save()
    {
        if (reason == "") {
            $("#err_txt_reason").text("Please Enter Reason!").fadeIn().delay('slow').fadeOut(5000);
            $("#reason").focus();
            return false;
        }
        else{
            var url="<?php echo base_url('Emprequest/hrlinkRejected'); ?>";
            $.ajax({
                url : url,
                type: "POST",
                data: $('#form').serialize(),
                success: function(data)
                {
                    alert(data);
                   var obj= JSON.parse(data);
                   $('#modal_form').modal('hide');
                    swal({
                    title: "Reject!",
                    text: "Requst for " + obj.emp_name + " and " + obj.token + " has been successfully Rejected.",
                    },
                    function(){
                        location.reload();
                    });
                   
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                }
            });
        }
    }
</script>
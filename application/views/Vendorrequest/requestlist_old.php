    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card">
                    <div class="card-body">
                      <h4 class="pink-text"> Pending  Request List </h4>
                      <hr/>
                        <table id="requestlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Token No</th>
                                    <th>Requester Name </th>
                                    <th>Approved Date</th>
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
                                            <td><?= date('d-m-Y', strtotime($req->req_submitteddate));?></td>
                                            <td>
                                                <?= (($req->req_status=="Request Send to Vendor")?'pending':'')?>
                                            </td>
                                           <td class="center">
                                                <a href="<?php echo base_url("Vendorreq/preview/$req->req_id") ?>" title="view"><i class="fa fa-eye fa-fw text-info"></i></a> 
                                                <?= (($req->req_status=="Request Send to Vendor")? '<a  onclick="dispatch('. $req->req_id .',\''.$req->req_emp_name.'\',\''.$req->req_emp_email.'\',\''.$req->req_emp_token.'\')"><img src="../img/dispatchIconGreen.png" /></a>':'');?> 
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
<div class="modal fade" id="modal_formdispatch" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title red-text"><center>
                    Dispatch Order
                </center></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="reqid" id="reqid">
                   <!--  <input type="hidden" name="empname" id="empname"> -->
                    <input type="hidden" name="empmail" id="empmail">
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
                        <div class="sign-pass">
                            <label for="dispatch_date">Dispatch Date </label>
                            <input type="text" name="dispatch_date" class="form-control" id="dispatch_date" readonly />
                            <div class="form-group has-error"><span class="help-block err" id="err_txt_date"></span></div>
                        </div>
                        <div class="sign-pass-again">
                            <label for="l_name">Dispatch Details</label>
                            <textarea name="dispatch_details" class="form-control" id="dispatch_details"></textarea> 
                            <div class="form-group has-error"><span class="help-block err" id="err_txt_reason"></span></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="return save()" class="btn btn-danger">Dispatch</button>
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
            "order": [[ 4, 'desc' ]],
        });
         $('#dispatch_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
        }).datepicker("setDate", new Date());
       var  val = $('#dispatch_date').datepicker('setDate', '0');
      // alert(val);
    } );
    function dispatch(id, name, email,emptoken)
    {
        $('#form')[0].reset();
        $('#modal_formdispatch').modal('show');
        $('#reqid').val(id);
        $('#empname').val(name);
        $('#empmail').val(email);
        $('#emptoken').val(emptoken);
    }
    function save()
    {
        var dispatch_date = $("#dispatch_date").val();
        var dispatch_details =$("#dispatch_details").val();      
        if (dispatch_date == "") {
            $("#err_txt_date").text("Please Select Date!").fadeIn().delay('slow').fadeOut(5000);
            $("#dispatch_date").focus();
            return false;
        }
        if (dispatch_details == "") {
            $("#err_txt_reason").text("Please Enter Reason!").fadeIn().delay('slow').fadeOut(5000);
            $("#dispatch_details").focus();
            return false;
        }
        else{
            var url="<?php echo base_url('Vendorreq/dispatchRequest'); ?>";
            //alert(url);
           // ajax adding data to database
            $.ajax({
                url : url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data)
                {
                   $('#modal_form').modal('hide');
                   location.reload();// for reload a page
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                }
            });
        }
    }
   
</script>
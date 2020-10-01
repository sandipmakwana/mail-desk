    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card">
                    <div class="card-body">
                      <h4 class="pink-text"> Dispatch  Request List </h4>
                      <hr/>
                        <table id="requestlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Token No</th>
                                    <th>Requester Name </th>
                                    <th>Approved Date</th>
                                    <th>Dispatch Date</th>
                                    <th>Dispatch Details</th>
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
                                            <td><?= date('d-m-Y', strtotime($req->req_vendor_dispatchdate));?></td>
                                            <td><?php echo $req->req_vendor_remark; ?></td>
                                           <!--  <td>
                                                <?= (($req->req_status=="Request Send to Vendor")?'pending':'')?>
                                            </td> -->
                                           <td class="center">
                                                <a href="<?php echo base_url("Vendorreq/preview/$req->req_id") ?>" title="view"><i class="fa fa-eye fa-fw text-info"></i></a> 
                                               
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#requestlist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
        });
    })
</script>
<div class="modal fade" id="modal_formdispatch" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><center>
                    Dispatch Order
                </center></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                    <input type="hidden" name="reqid" id="reqid">
                    <input type="hidden" name="empname" id="empname">
                    <input type="hidden" name="empmail" id="empmail">
                    <div class="form_row">
                        <div class="sign-pass">
                            <label for="dispatch_date">Dispatch Date </label>
                            <input type="text" name="dispatch_date" id="dispatch_date" readonly required/>
                        </div>
                        <div class="sign-pass-again">
                            <label for="l_name">Dispatch Details</label>
                            <textarea name="dispatch_details" id="dispatch_details" required>
                            </textarea> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Dispatch</button>
            </div>
        </div>
    </div>
</div>

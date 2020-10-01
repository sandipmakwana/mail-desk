    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card">
                    <div class="card-body">
                      <h4 class="pink-text"> Inward List </h4>
                      <hr/>
                        <table id="requestlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Token No</th>
                                    <th>Name </th> 
                                    <th>Courier Received Date/Time</th>
									<th>Courier Received By</th>
									<th>Courier Delivery Date/Time</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>   
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($reg_master)) { ?>
                                    <?php foreach ($reg_master as $req) { ?>
                                        <tr>
                                            <td><?php echo $req->req_receiever_emp_token; ?></td>
                                            <td><?php echo $req->req_receiever_emp_name; ?></td>
                                            <td><?= date('d-m-Y', strtotime($req->req_datetime));?></td>
											<td><?php echo $req->req_receiever_emp_name; ?></td>
											<td><?= date('d-m-Y', strtotime($req->req_datetime));?></td>
                                            <td><?php echo $req->req_status?></td>
                                           <td class="center">
                                                <a href="<?php echo base_url("Request/preview/$req->req_id") ?>" title="view"><i class="fa fa-eye fa-fw text-info"></i></a>
                                                <?php //if($req->req_status=="Draft"){?><a href="<?php echo base_url("Request/receivecourier/$req->req_id")?>" title="Edit"><i class="fa fa-edit  fa-fw text-primary"></i></a><?php //};?> 
                                            </td>
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
            "order": [[ 5, 'desc' ]],
        } );
    } );
</script>
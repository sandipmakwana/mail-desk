    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                  <div class="card-body">
                      <h4 class="pink-text clearfix"> Franking List 
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Frankingmaster/frankingForm") ?>"> <i class="fa fa-plus fa-fw"></i> Franking Master</a>  
                      
                      </h4>
                      <hr/>
                      
                        <table id="frankinglist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Location</th>
                                    <th>Transaction Date </th>
                                    <th>Amount</th>
                                    <th>Reference NO</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>               
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($fr_master)) { ?>
                                    <?php foreach ($fr_master as $frmaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $frmaster->location_name; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($frmaster->transaction_dt)); ?></td>
                                            <td><?php echo $frmaster->f_value; ?></td>
                                            <td><?php echo $frmaster->reference_no; ?></td>
                                            <td><?php echo $frmaster->remark; ?></td>
                                            <td><?php echo (($frmaster->isactive==1)?'active':'inactive'); ?></td>
											<td class="center">              
                                                <a href="<?php echo base_url("Frankingmaster/frankingForm/$frmaster->franking_id") ?>" title="Edit"><i class="fa fa-eye fa-fw text-primary"></i></a>
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
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#frankinglist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 0, 'asc' ]],
        } );
    } );
</script>
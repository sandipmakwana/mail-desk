    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                  <div class="card-body">
                      <h4 class="pink-text clearfix"> Charges List
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Chargemaster/chargeForm") ?>"> <i class="fa fa-plus fa-fw"></i> Add New Charge</a>
                      </h4>
                      <hr/>
                      
                        <table id="chargelist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Charge Name</th>
                                    <th>Agency Code</th>
                                    <th>Charges Value(Base Rate)</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($charge_master)) { ?>
                                    <?php foreach ($charge_master as $chargemaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $chargemaster->charge_name; ?></td>
                                            <td><?php echo $chargemaster->agency_code; ?></td>
                                            <td><?php echo $chargemaster->charge_value; ?></td>
                                            <td><?php echo (($chargemaster->charge_status==1)? 'Active' : 'Inactive'); ?></td>
                                           <td class="center">              
                                                <a href="<?php echo base_url("Chargemaster/chargeForm/$chargemaster->charge_id") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Chargemaster/delete/$chargemaster->charge_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete? ' ?>')"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                <?= (($chargemaster->charge_status==1)? '<a href="'. base_url("Chargemaster/inactive/$chargemaster->charge_id").'" title="Inactive"><i class="fa fa-times-circle fa-fw text-warning"></i></a>' : '<a href="'. base_url("Chargemaster/active/$chargemaster->charge_id").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?> 
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
        $('#chargelist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 3, 'desc' ]],
        } );
    } );
</script>
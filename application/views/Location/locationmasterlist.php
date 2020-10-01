    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                  <div class="card-body">
                      <h4 class="pink-text clearfix"> Location List 
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Locationmaster/locationForm") ?>"> <i class="fa fa-plus fa-fw"></i> Location Master</a>  
                      
                      </h4>
                      <hr/>
                      
                        <table id="locationlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Business </th>
                                    <th>Type</th>
                                    <th>Name </th>
                                    <th>Code</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Pincode</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>               
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($loc_master)) { ?>
                                    <?php foreach ($loc_master as $locmaster) { ?>
                                        <tr>        
                                            <td><?php echo $locmaster->bu_code ." - ".$locmaster->bu_name; ?></td>
                                             <td><?php echo $locmaster->location_type; ?></td>
                                            <td><?php echo $locmaster->location_name; ?></td>
                                            <td><?php echo $locmaster->location_code; ?></td>
                                            <td><?php echo $locmaster->location_address; ?></td>
                                            <td><?php echo $locmaster->location_city; ?></td>
                                            <td><?php echo $locmaster->location_pincode; ?></td>
                                            <td><?php echo (($locmaster->isactive==1)?'active':'inactive'); ?></td>
                                           <td class="center">              
                                                <a href="<?php echo base_url("Locationmaster/locationForm/$locmaster->location_id") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Locationmaster/delete/$locmaster->location_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete? ' ?>')"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                <?= (($locmaster->isactive==1)? '<a href="'. base_url("Locationmaster/inactive/$locmaster->location_id").'" title="Inactive"><i class="fa fa-times-circle fa-fw text-warning"></i></a>' : '<a href="'. base_url("Locationmaster/active/$locmaster->location_id").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?> 
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
        $('#locationlist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 7, 'desc' ]],
        } );
    } );
</script>
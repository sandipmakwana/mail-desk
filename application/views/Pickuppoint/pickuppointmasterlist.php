    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                  <div class="card-body">
                      <h4 class="pink-text clearfix"> Pickup Point List 
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Pickuppointmaster/pickuppointForm") ?>"> <i class="fa fa-plus fa-fw"></i> Pickup Point</a>  
                      
                      </h4>
                      <hr/>
                      
                        <table id="pickuppointlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Pickup Point </th>
                                    <th>Location </th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>               
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($fr_master)) { ?>
                                    <?php foreach ($fr_master as $frmaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $frmaster->p_pickuppoint; ?></td>
                                            <td><?php echo $frmaster->location_name; ?></td>
                                            <td><?php echo $frmaster->p_desc; ?></td>
                                            <td><?php echo (($frmaster->isactive==1)?'active':'inactive'); ?></td>
                                           <td class="center">              
                                                <a href="<?php echo base_url("Pickuppointmaster/pickuppointForm/$frmaster->pickup_id") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Pickuppointmaster/delete/$frmaster->pickup_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete? ' ?>')"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                <?= (($frmaster->isactive==1)? '<a href="'. base_url("Pickuppointmaster/inactive/$frmaster->pickup_id").'" title="Inactive"><i class="fa fa-times-circle fa-fw text-warning"></i></a>' : '<a href="'. base_url("Pickuppointmaster/active/$frmaster->pickup_id").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?> 
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
        $('#pickuppointlist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 3, 'desc' ]],
        } );
    } );
</script>
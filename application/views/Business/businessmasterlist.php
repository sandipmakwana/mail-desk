    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                  <div class="card-body">
                      <h4 class="pink-text clearfix"> Department List 
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Businessmaster/businessForm") ?>"> <i class="fa fa-plus fa-fw"></i> Department Master</a>  
                      
                      </h4>
                      <hr/>
                      
                        <table id="businesslist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Code </th>
                                    <th>Name</th>
                                    <th>Cost Center</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>               
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($biz_master)) { ?>
                                    <?php foreach ($biz_master as $bizmaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $bizmaster->department_code; ?></td>
                                            <td><?php echo $bizmaster->department_name; ?></td>
                                            <td><?php echo $bizmaster->cost_center; ?></td>
                                            <td><?php echo (($bizmaster->isactive==1)?'active':'inactive'); ?></td>
                                           <td class="center">              
                                                <a href="<?php echo base_url("Businessmaster/businessForm/$bizmaster->business_id") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Businessmaster/delete/$bizmaster->business_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete? ' ?>')"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                <?= (($bizmaster->isactive==1)? '<a href="'. base_url("Businessmaster/inactive/$bizmaster->business_id").'" title="Inactive"><i class="fa fa-times-circle fa-fw text-warning"></i></a>' : '<a href="'. base_url("Businessmaster/active/$bizmaster->business_id").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?> 
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
        $('#businesslist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 3, 'desc' ]],
        } );
    } );
</script>
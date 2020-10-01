    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card">                     
                    <div class="card-body">
                      <h4 class="pink-text clearfix"> Business List 
                       <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Companymaster/companyForm") ?>"> <i class="fa fa-plus fa-fw"></i>  Business</a>
                      </h4>
                      <hr/>
                        <table id="companylist" width="100%" class="datatable table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Code </th>
                                    <th>Name</th>
                                    <th>Status</th>  
                                    <th>Choose Action</th>                
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($comp_master)) { ?>
                                    <?php foreach ($comp_master as $compmaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $compmaster->bu_code; ?></td>
                                            <td><?php echo $compmaster->bu_name; ?></td>
                                            <td><?php echo (($compmaster->isactive==1)?'active':'inactive'); ?></td>
                                            <td>
                                           <a href="<?php echo base_url("Companymaster/companyForm/$compmaster->bu_id") ?>" title="Edit" class=""><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Companymaster/delete/$compmaster->bu_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete?' ?>')"><i class="fa fa-trash  fa-fw text-danger"></i></a>
                                                <?= (($compmaster->isactive==1)? '<a href="'. base_url("Companymaster/inactive/$compmaster->bu_id").'" title="Inactive"><i class="fa fa-times-circle  fa-fw text-warning"></i></a>' : '<a href="'. base_url("Companymaster/active/$compmaster->bu_id").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?>
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
        $('#companylist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 3, 'desc' ]],
        } );
    } );
</script>
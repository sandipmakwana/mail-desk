    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card">
                    <div class="card-body">
                      <h4 class="pink-text clearfix"> User List 
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Empmaster/empForm") ?>"> <i class="fa fa-plus"></i> User Master</a>  
                      </h4>
                      <hr/>
                        <table id="employeelist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr>    
                                    <th>Name</th> 
                                    <th>Username/Token</th>    
                                    <th>Email</th>  
                                    <th>Location</th> 
                                    <th>Department</th> 
                                    <th>Cost Center</th>                                  
                                     <th>Role</th>      
                                    <th>Status</th>
                                    <th>Choose Action</th>            
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($emp_master)) { ?>
                                    <?php foreach ($emp_master as $empmaster) { ?>
                                        <tr>
                                            <td><?php echo $empmaster->emp_name ; ?></td>
                                            <td><?php echo $empmaster->emp_username ; ?></td>
                                            <td><?php echo $empmaster->emp_email; ?></td>
                                            <td><?php echo $empmaster->location; ?></td>
                                            <td><?php echo $empmaster->department; ?></td>
                                            <td><?php echo $empmaster->costcenter; ?></td>
                                            <td><?php echo $empmaster->emp_role; ?></td>
                                            <td><?php echo $empmaster->emp_status; ?></td>
                                           <td class="center">
                                                <a href="<?php echo base_url("Empmaster/empForm/$empmaster->emp_id") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                               <!--  <a href="<?php echo base_url("Empmaster/delete/$empmaster->emp_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete?' ?>')"><i class="fa fa-trash"></i></a> -->
                                                <?= (($empmaster->emp_status=='Active')? '<a href="'. base_url("Empmaster/inactive/$empmaster->emp_id").'" title="Inactive"><i class="fa fa-times-circle  fa-fw text-warning"></i></a>' : '<a href="'. base_url("Empmaster/active/$empmaster->emp_id").'" title="Active"><i class="fa fa-check-circle  fa-fw text-success"></i></a>');?> 
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
        $('#employeelist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 0, 'asc' ]],
        } );
    } );
</script>
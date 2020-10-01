    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div  class="card"> 
                    <div class="card-body">
                      <h4 class="pink-text clearfix">
                        Escalation User List
                        <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Escmaster/escForm") ?>"> <i class="fa fa-plus"></i> Escalation User Master</a>  
                      </h4>
                      <hr/>
                        <table id="escalationuserlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Token ID</th>
                                    <th>Name </th>       
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>               
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($esc_master)) { ?>
                                    <?php foreach ($esc_master as $escmaster) { ?>
                                        <tr>
                                            <td><?php echo $escmaster->vs_esc_emptoken; ?></td>
                                            <td><?php echo $escmaster->vs_esc_empname; ?></td>
                                            <td><?php echo $escmaster->vs_esc_empemail; ?></td>
                                            <td><?php echo $escmaster->vs_esc_role; ?></td>
                                            <td><?php echo (($escmaster->isactive==1)?'active':'inactive'); ?></td>
                                           <td class="center">
                                                <a href="<?php echo base_url("Escmaster/escForm/$escmaster->vs_escmstid") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Escmaster/delete/$escmaster->vs_escmstid") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete?' ?>')"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                <?= (($escmaster->isactive==1)? '<a href="'. base_url("Escmaster/inactive/$escmaster->vs_escmstid").'" title="Inactive"><i class="fa fa-times-circle fa-fw text-warning"></i></a>' : '<a href="'. base_url("Escmaster/active/$escmaster->vs_escmstid").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?> 
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
        $('#escalationuserlist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 5, 'desc' ]],
        } );
    } );
</script>
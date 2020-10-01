
    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                   
                    <div class="card-body">
                      <h4 class="pink-text clearfix"> Vendor List 
                         <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Vendormaster/vendorForm") ?>"> <i class="fa fa-plus fa-fw"></i> Vendor Master</a>  

                      </h4>
                      <hr/>
                        <table id="vendorlist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead> 
                                <tr> 
                                    <th>Company Name </th>
                                    <th>Name </th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>               
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($ven_master)) { ?>
                                    <?php foreach ($ven_master as $venmaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $venmaster->vendor_cmpname; ?></td>
                                            <td><?php echo $venmaster->vendor_name; ?></td>
                                            <td><?php echo $venmaster->vendor_mobile; ?></td>
                                             <td><?php echo $venmaster->vendor_email; ?></td>
                                              <td><?php echo $venmaster->location_id; ?></td>
                                            <td><?php echo (($venmaster->isactive==1)?'active':'inactive'); ?></td>
                                           <td class="center">              
                                                <a href="<?php echo base_url("Vendormaster/vendorForm/$venmaster->vendor_id") ?>" title="Edit"><i class="fa fa-edit  fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Vendormaster/delete/$venmaster->vendor_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete?' ?>')"><i class="fa fa-trash  fa-fw text-danger"></i></a>
                                                <?= (($venmaster->isactive==1)? '<a href="'. base_url("Vendormaster/inactive/$venmaster->vendor_id").'" title="Inactive"><i class="fa fa-times-circle  fa-fw text-warning"></i></a>' : '<a href="'. base_url("Vendormaster/active/$venmaster->vendor_id").'" title="Active"><i class="fa fa-check-circle  fa-fw text-success"></i></a>');?> 
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
        $('#vendorlist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
             "order": [[ 6, 'desc' ]],
        } );
    } );
</script>
    <div class="container">
        <div class="row">        
            <!--  table area -->
            <div class="col-sm-12">
                <div  class="card"> 
                  <div class="card-body">
                      <h4 class="pink-text clearfix"> Courier Agencies List
                            <a class="btn btn-sm btn-danger pull-right m-0" href="<?php echo base_url("Agencymaster/agencyForm") ?>"> <i class="fa fa-plus fa-fw"></i> Add new Agency</a>
                      </h4>
                      <hr/>
                      
                        <table id="agencylist" width="100%" class="datatable2 table table-striped table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>Agency Code</th>
                                    <th>Agency Name</th>
                                    <th>Agency SAP Code</th>
                                    <th>Agency Address</th>
                                    <th>Agency Email Address</th>
                                    <th>Agency Contact Person</th>
                                    <th>Agency Contact Number</th>
                                    <th>Status</th>
                                    <th>Choose Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($agency_master)) { ?>
                                    <?php foreach ($agency_master as $agencymaster) { ?>
                                        <tr>                                  
                                            <td><?php echo $agencymaster->agency_code; ?></td>
                                            <td><?php echo $agencymaster->agency_name; ?></td>
                                            <td><?php echo $agencymaster->agency_sap_code; ?></td>
                                            <td><?php echo $agencymaster->agency_address; ?></td>
                                            <td><?php echo $agencymaster->agency_email_address; ?></td>
                                            <td><?php echo $agencymaster->agency_person_name; ?></td>
                                            <td><?php echo $agencymaster->agency_mobile_number; ?></td>
                                            <td><?php echo (($agencymaster->agency_status==1)? 'Active' : 'Inactive'); ?></td>
                                           <td class="center">              
                                                <a href="<?php echo base_url("Agencymaster/agencyForm/$agencymaster->agency_id") ?>" title="Edit"><i class="fa fa-edit fa-fw text-primary"></i></a> 
                                                <a href="<?php echo base_url("Agencymaster/delete/$agencymaster->agency_id") ?>" title="Delete" onclick="return confirm('<?php echo 'Are you sure you want to delete? ' ?>')"><i class="fa fa-trash fa-fw text-danger"></i></a>
                                                <?= (($agencymaster->agency_status==1)? '<a href="'. base_url("Agencymaster/inactive/$agencymaster->agency_id").'" title="Inactive"><i class="fa fa-times-circle fa-fw text-warning"></i></a>' : '<a href="'. base_url("Agencymaster/active/$agencymaster->agency_id").'" title="Active"><i class="fa fa-check-circle fa-fw text-success"></i></a>');?>
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
        $('#agencylist').DataTable( {
            "paging":   true,
            "ordering": true,
            "info":     true,
            "order": [[ 7, 'desc' ]],
        } );
    } );
</script>
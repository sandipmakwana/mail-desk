<div class="container">
  <div class="row">
    <!--  table area -->
    <div class="col-sm-12">
      <div  class="card">
        <div class="card-body">
          <h4 class="pink-text"> Outward Report </h4>
          <hr/>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-3">
                <label>Location</label>
                <?php  echo form_dropdown('location', $locationlist, '','class="form-control" id="location" '); 
                                    ?>
              </div>
              <div class="col-sm-3">
                <label>from</label>
                <input type="text" name="from_date" id="from_date"  class="form-control" id="to_date" readonly="" />
                <div class="form-group has-error ml-4 pl-2">
                  <span class="help-block err" id="err_txt_off_from_date"></span>
                </div>
              </div>
              <div class="col-sm-3">
                <label>To</label>
                <input type="text" name="to_date" id="to_date" class="form-control" id="to_date" readonly="" />
                <div class="form-group has-error ml-4 pl-2">
                  <span class="help-block err" id="err_txt_off_to_date"></span>
                </div>
              </div>

              <div class="col-sm-1">
                <br/>
                <button onclick="search()" class="btn btn-md btn-outline-danger" name="buttondraft">Search </button>
              </div>
            </div>
          </div>
          <table id="requestlist" width="100%" class="display table table-striped table-bordered table-hover table-responsive">
            <thead>
              <tr>
                <th>Token</th>
                <th>Name </th>
                <th>Business Unit </th>
                <th>Department </th>
                <th>Cost Center</th>
                <th>Date</th>
                <th>Courier Mode</th>
                <th>Courier Agency</th>
                <th>Weight</th>
                <th>Fee/Charge</th>
                <th>AWB No</th>
                <th>Tracking No</th>
                <th>WBS</th>
                <th>VendorName</th>
                <th>Created Date</th>
                <th>HR Approval Date</th>
                <th>Current Status</th>
                <th>HR First Escalation</th>
                <th>HR First Escalation Date</th>
                <th>HR Second Escalation</th>
                <th>HR Second Escalation Date</th>
                <th>Request received by Vendor</th>
                <th>Dispatched Date</th>
                <th>Close Date</th>
                <th>Courier Details</th>
                <th>VENDOR First Escalation</th>
                <th>VENDOR First Escalation Date</th>
                <th>VENDOR Second Escalation</th>
                <th>VENDOR Second Escalation Date</th>
                <th>VENDOR Third Escalation</th>
                <th>VENDOR Third Escalation Date</th>
                <th>VENDOR Fourth Escalation</th>
                <th>VENDOR Fourth Escalation Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($reg_master)) { ?>
              <?php foreach ($reg_master as $req) { ?>
              <tr>
                <td>
                  <?php echo $req->RequesterToken; ?>
                </td>
                <td>
                  <?php echo $req->DisplayName; ?>
                </td>
                <td>
                  <?php echo $req->Designation; ?>
                </td>
                <td>
                  <?= $req->Department_Division;?>
                </td>
                <td>
                  <?= $req->Business_Unit;?>
                </td>
                <td>
                  <?php echo $req->Address?>
                </td>
                <td>
                  <?php echo $req->Admin?>
                </td>
                <td>
                  <?php echo $req->ISDCode?>
                </td>
                <td>
                  <?php echo $req->Telephone?>
                </td>
                <td>
                  <?php echo $req->Mobile?>
                </td>
                <td>
                  <?php echo $req->Official_Email?>
                </td>
                <td>
                  <?php echo $req->Cost_Center?>
                </td>
                <td>
                  <?php echo $req->WBS?>
                </td>
                <td>
                  <?php echo $req->Vendor_Name?>
                </td>
                <td>
                  <?php echo $req->Created_Date?>
                </td>
                <td>
                  <?php echo $req->HR_Approve_Date?>
                </td>
                <td>
                  <?php echo $req->Current_Status?>
                </td>
                <td>
                  <?php echo $req->HR_First_Escalation?>
                </td>
                <td>
                  <?php echo $req->HR_First_Escalation_Date?>
                </td>
                <td>
                  <?php echo $req->HR_Second_Escalation?>
                </td>
                <td>
                  <?php echo $req->HR_Second_Escalation_Date?>
                </td>
                <td>
                  <?php echo $req->Request_received_by_Vendor?>
                </td>
                <td>
                  <?php echo $req->Dispatched_Date?>
                </td>
                <td>
                  <?php echo $req->Close_Date?>
                </td>
                <td>
                  <?php echo $req->Courie_details?>
                </td>
                <td>
                  <?php echo $req->VENDOR_First_Escalation?>
                </td>
                <td>
                  <?php echo $req->VENDOR_First_Escalation_Date?>
                </td>
                <td>
                  <?php echo $req->VENDOR_Second_Escalation?>
                </td>
                <td>
                  <?php echo $req->VENDOR_Second_Escalation_Date?>
                </td>
                <td>
                  <?php echo $req->VENDOR_Third_Escalation?>
                </td>
                <td>
                  <?php echo $req->VENDOR_Third_Escalation_Date?>
                </td>
                <td>
                  <?php echo $req->VENDOR_Fourth_Escalation?>
                </td>
                <td>
                  <?php echo $req->VENDOR_Third_Fourth_Date?>
                </td>
              </tr>
              <?php } ?>
              <?php } ?>
            </tbody>
          </table>
          <!-- /.table-responsive -->
          <!--   <?php echo $links; ?> -->
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
  $('#requestlist').DataTable( {
  "paging":   true,
  "ordering": true,
  "info":     true,
  "scrollX": true,
  "dom": 'lBfrtip',
  buttons: [
  { extend: 'excel', text: 'Export to Excel' }
  //'csv',
  ]

  });
  $('#to_date').datepicker({
  autoclose: true,
  format: 'dd-mm-yyyy',
  }).datepicker("setDate", new Date());
  $('#from_date').datepicker({
  autoclose: true,
  format: 'dd-mm-yyyy',
  }).datepicker("setDate", '01-mm-yyyy');
  });
  function search(){
  var to_date = $("#to_date").val();
  var from_date = $("#from_date").val();
  if (to_date == "") {
  $("#err_txt_off_to_date").text("Please Select Date!").fadeIn().delay('slow').fadeOut(5000);
  return false;
  }
  if (from_date == "") {
  $("#err_txt_off_from_date").text("Please Select Date!").fadeIn().delay('slow').fadeOut(5000);
  return false;
  }
  else{
  var location = $("#location").val();
  $.ajax({
  url : "<?php echo base_url('Request/searchReport') ?>",
  data: {location:location, from_date:from_date, to_date:to_date},
  type: 'post',
  dataType: 'json',
  success: function(response)
  {
  var table = $('#requestlist').DataTable({
  data: response.data,
  "bPaginate": true,
  "bLengthChange": true,
  "destroy": true,
  "bFilter": false,
  "bInfo": true,
  "bAutoWidth": true,
  "scrollX": true,
  "dom": 'lBfrtip',
  buttons: [
  { extend: 'excel', text: 'Export to Excel' },
  // 'csv',
  ],
  columns: [{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},{},
  ],
  });
  },
  error: function (jqXHR, textStatus, errorThrown)
  {
  alert('Error adding / update data');
  }
  });

  }
  }
</script>
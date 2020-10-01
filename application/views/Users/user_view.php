<?php
if (!isset($this->session->userdata['adm_login']['adm_username'])) {
$url = base_url()."login/adm_login";
redirect($url);
}
?>
<div class="datalisting  animated fadeIn mt-4">


  <?php

  if($this->session->flashdata('message')){?>
          <div align="center">
            <?php echo $this->session->flashdata('message')?>
          </div>
        <?php } ?>
<!--************************* END SESSION SETFLASH MESSAGES   ************************-->

  <div class="container">
<div class="card">
<div class="card-body">


        <div class="clearfix">
          <p class="h5 text-left mb-4 red-text float-left">Users Management</p>
          <input type="button" name="resetsrch" id="resetsrch" class="btn btn-sm btn-outline-danger float-right " value="Reset"/>
          <a href="<?php echo site_url('routes/add_user/1'); ?>" class="float-right btn-sm btn btn-danger">Add new User
          </a>
        </div>



        <table id="table" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                     <th>Email</th>
                     <th>Role</th>
                     <th>Status</th>
                    <th class="no-sort">Action</th>
                </tr>
            </thead>
             <tbody>

            </tbody>

            <!--<tfoot>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
            </tfoot>-->
        </table>
    </div>
</div>
</div>
</div>

<script type="text/javascript">

var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({
   		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
   		"pagingType": "full_numbers",
		"stateSave": true,
		"processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
		"stateLoadParams": function (settings, data) {
		    data.search.search = "";
  		},
		// Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('routes/user_listing')?>",
            "type": "POST",
            "data": {
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
         "headers": {
		            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        }

        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
         {
			"orderable": false,
			"targets": ["no-sort"]
         }
        ],
    });

    var info = table.page.info();

	$('#resetsrch').on('click', function(){
			//alert("hi");
			var cl = $('.dataTables_filter input').val('');
			table.search('').draw();
	});

	$(window).on('load', function(){
	var lastpg =  document.referrer;
	var sec = lastpg.split( '/' );
	 var flg = sec[5];
	var referrer = window.location.href;
	 var segments = referrer.split( '/' );
	 var fromflg = segments[5];
	 var flgid = segments[6];
	if(!flgid && flg=="user_list")
	   {

				table.draw( true );

		}

	});






//alert('Currently showing page '+(info.page+1)+' of '+info.pages+' pages.');


});

</script>


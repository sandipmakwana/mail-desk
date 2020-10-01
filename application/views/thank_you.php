<!--Main Layout-->
    <main class="text-center py-4">
        <div class="page-title">
            <!--<h3><strong class="text-white">Logging</strong></h3>-->
        </div>
        <div class="container card-container">
            <div class="card card-cascade wider reverse">

                <!--Card content-->
                <div class="card-body mx-4 mt-4 text-center wow fadeIn" data-wow-delay="0.2s" style="animation-name: none; visibility: visible;">
                    <!--Title-->
                    <h4 class="card-title"><strong>Thank You</strong></h4>
                    <h5 class=" mb-5"><strong>Thank you for submitting opportunity.</strong></h5>

                    <!--<p class="card-text mb-5">
                        Submit your Innovative Ideas
                    </p>-->

                    <div class="row">
                        <div class="col-12 col-sm-12 text-center">
                            <!-- Form register -->
                              <div class="text-center">
                                    <!--<button class="btn btn-red">Add More Information</button>-->
                                    <button class="btn btn-red" id="addmoreinfo">Add More Information</button>
                                    <button class="btn btn-red" onclick="window.location.href='<?php echo base_url().'opinion/ideas/' ;?>';">Opportunity Listing</button>
                                </div>

                           
                            <!-- Form register -->

                        </div>

                    </div>

                </div>
                <!--/.Card content-->

            </div>
        </div>
        
        <!-- Additional Information dialogue box -->
							<div id="overlay" class="web_dialog_overlay"></div>
							<div id="dialog" class="web_dialog">
							<table style="width: 100%; border: 0px;" cellpadding="3" cellspacing="0">
								  <tr>
									 <td class="web_dialog_title" id="di_title_com">Additional Information</td>
									 <td class="web_dialog_title" id="di_title_data">Submit Information</td>
									 <td class="web_dialog_title align_right">
										<a href="#" id="btnClose">Close</a>
									 </td>
								  </tr>

								  <tr>
									 <td colspan="2" style="padding-left: 15px;">
										   <textarea id="idea_info_details" name="idea_info_details"></textarea>
										   <div class="form-group has-error">
											<span class="help-block err" id="err_comments"></span></div>
											
							 </td>
								  </tr>

										<tr>
										   <td colspan="2" style="padding-left: 15px;">
										 
											<input id="btnSubmit" type="button" value="Submit" class="btn btn-danger btn-rounded btn-md"/>
									 </td>
								  </tr>


							   </table>
							</div>

							<!-- Add info ends here -->
     </main>

   <style type="text/css">

.web_dialog_overlay
{
   position: fixed;
   top: 0;
   right: 0;
   bottom: 0;
   left: 0;
   height: 100%;
   width: 100%;
   margin: 0;
   padding: 0;
   background: #000000;
   opacity: .15;
   filter: alpha(opacity=15);
   -moz-opacity: .15;
   z-index: 101;
   display: none;
}
.web_dialog
{
   display: none;
   position: fixed;
   width: 380px;
   height: 230px;
   top: 50%;
   left: 50%;
   margin-left: -190px;
   margin-top: -100px;
   background-color: #ffffff;

   padding: 0px;
   z-index: 102;
   font-family: Verdana;
   font-size: 10pt;
}
.web_dialog_title
{
   border-bottom: solid 2px #E51636;
   background-color: #E51636;
   padding: 4px;
   color: White;
   font-weight:bold;
}
.web_dialog_title a
{

   color: White;
   text-decoration: none;
}
.align_right
{
   text-align: right;
}

</style>
	<script type="text/javascript">

   $(document).ready(function ()
   {
	
	  $("#addmoreinfo").click(function (e)
      {		
			
			$('#di_title_com').show();
			$('#di_title_data').hide();
			$('#btnSubmit').show();		
      		ShowDialog(true);
	        e.preventDefault();

      });

	   $("#btnClose").click(function (e)
      {
         HideDialog();
         e.preventDefault();
      });

      $("#btnSubmit").click(function (e)
      {
		 
      		var idea_info_details = ($("#idea_info_details").val());			

			if(idea_info_details == ""){
				
				$('#err_comments').text("Please enter information.");
				return false;
			}
			else{
				
				$.ajax({
					url:baseurl+'opinion/additional_information/',
					type:'POST',
					data:{idea_info_details:idea_info_details},
					success:function(data){
						//alert(data);
						alert("Information added successfully.");						
					}
				});
	         	HideDialog();
	        	e.preventDefault(); 
				
				

			}

      });

   });
   function ShowDialog(modal)
   {


      $("#overlay").show();
      $("#dialog").fadeIn(300);

      if (modal)
      {
         $("#overlay").unbind("click");

      }
      else
      {
         $("#overlay").click(function (e)
         {
            HideDialog();
         });
      }
   }

   function HideDialog()
   {
      $("#overlay").hide();
      $("#dialog").fadeOut(300);
   }
</script>
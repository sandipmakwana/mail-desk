<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        @import url(http://fonts.googleapis.com/css?family=Lato:300,400);
        /* All your usual CSS here */
    </style>
</head>
<body style="margin: 0; padding: 0;font-family: Lato, sans-serif;">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
    <tr>
        <td>
            <img src="<?php echo base_url('img/logo.png') ?>" alt="Email Header with logo"/>
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" style="padding:25px 55px">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        Dear <?php echo $empname ?>,
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 1.5">
					<br/>
    <p><?php echo $opportunity_owner;?> has shared opinion on Opportunity - <?php echo $op_id;?></p>
	
						<p>Opportunity Title: <?php echo $optitle;?></p>
                        
                    </td>
					
                </tr>
				
				<!--<tr>
					<td>
					<?php 
					
					//print_r($data);
	/*echo "<table border='1'><tr><th>Assessment</th>";
	foreach($assetment_heading as $table)
	{
		echo "<th>".$table->stage_name."</th>";
	}
	echo "</tr><tr><td>Opinion requested</td>";
	
	
	foreach($assetment_heading as $table)
	{
		//echo "<th>".$table->stage_name.'-'.$table->stage_id."</th>";
		foreach($opinion_data as $opinion)
			{
				//$op=explode(',',$opinion->stages_assigned);
				//$eval_stages = json_decode($opinion->stages_assigned);
			//print_r($table->stage_id);
				if ($table->stage_id == $opinion->stage_id)
				  {
				  echo "<td><center>Yes</center></td>";
				  }
				else
				  {
				  echo "<td><center>No</center></td>";
				  }
				
				
			}
	}
	
					/*echo "<pre>";
					print_r($opinion_data);
					echo "</pre>";
	echo "</tr>";				
	?>

	<?php
	echo "</table>";*/
					?>
					</td>
				</tr>-->
				<tr>
				<td style="padding-top: 45px; line-height: 1.5;">
				<p><a href="<?php echo base_url().'opinion/view_idea/'.$op_id;?>">Click here</a> to view the details.</p>
				</td>
				</tr>
                <tr>
                    <td style="padding-top: 45px; line-height: 1.5;">
                        This is an auto-generated mail. For any queries send a mail to <a href='mailto:miscout@mahindra.com'>miscout@mahindra.com</a>   
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    
</table>
</body>
</html>
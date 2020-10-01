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
                        Dear <?php echo $selected_user?>,
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 1.5">
					<br/>
					<p><?php echo $requester;?> has requested for your opinion on Opportunity - <?php echo $op_id;?></p>
						<p>Opportunity Title: <?php echo $optitle;?></p>
                        
                    </td>
					
                </tr>
				<tr>
					<td>
					<?php 
					
					//print_r($data);
	echo "<table border='1'><tr><th>Assessment</th>";
	foreach($assetment_heading as $table)
	{
		echo "<td><center>".$table->stage_name."</center></td>";
	}
	echo "</tr><tr><th>Opinion requested</th>";
	
	
	foreach($assetment_heading as $table)
	{
		foreach($opinion_data as $opinion)
			{
				//echo "<td>".$opinion->stages_assigned."</td>";
				//$op=explode(',',$opinion->stages_assigned);
				//print_r($op);
				$eval_stages = json_decode($opinion->stages_assigned);
				//echo "<pre>";
			
				//print_r($eval_stages);
				//$marks = array(100, 65, 70, 87);
 
					if (in_array($table->stage_id, $eval_stages))
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
					echo "</pre>";*/
	echo "</tr>";				
	?>

	<?php
	echo "</table>";
					?>
					</td>
				</tr>
                <tr>
					<td style="line-height: 1.5">
					<br/>
					<p><a href="<?php echo base_url().'opinion/view_idea/'.$op_id;?>">Click here</a> to evaluate the opportunity.</p>
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
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
                        Dear <?php echo $empname?>,
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 1.5"><br/>
						<p><?php echo $requested;?> has declined to share opinion on Opportunity  -  <?php echo $op_id;?></p>
						<p><b>Opportunity Title: <?php echo $optitle;?></b></p>
                        
                    </td>
                </tr>
				<tr>
					<td>
					<?php 
	echo "<table border='1'><tr><th>Assessment</th>";
	foreach($assetment_heading as $table)
	{
		echo "<td><center>".$table->stage_name."</center></td>";
	}
	
	echo "<tr><th>Opinion requested</th>";
	
	foreach($assetment_heading as $table)
	{
		foreach($opinion_data as $opinion)
		{
			$eval_stages = json_decode($opinion->stages_assigned);
			if(in_array($table->stage_id, $eval_stages))
			  {
			  echo "<td><center>Yes</center></td>";
			  }
			else
			  {
			  echo "<td><center>No</center></td>";
			  }
		}
	}
	echo "</tr>";
	
	echo "</tr><tr><th>Opinion received </th>";
	echo "<td><center>No</center></td>";
	echo "<td><center>No</center></td>";
	echo "<td><center>No</center></td>";
	echo "<td><center>No</center></td></tr>";
	echo "</table>";		
	?></td></tr>
                <tr>
					<td style="line-height: 1.5"><br/>
						<p><a href="<?php echo base_url().'opinion/view_idea/'.$op_id;?>">Click here </a>to view the challenge details.</p>
						
                    </td>
					</tr>
					<tr>
                    <td style="padding-top: 45px; line-height: 1.5;">
                        This is an auto-generated mail. For any queries send a mail to <a href='mailto:miscout@mahindra.com'>miscout@mahindra.com</a>   
                    </td>
                </tr>
            </table>
        </td>
    
   
    
</table>
</body>
</html>
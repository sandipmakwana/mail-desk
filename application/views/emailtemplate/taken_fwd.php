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
                        Dear <?php echo $empusernm?>,
                    </td>
                </tr>
                <tr>
                    <td>
					<br/>
                        Congratulations! ,
                    </td>
                </tr>
				<tr>
                    <td style="line-height: 1.5">
     <p>The opportunity submitted by you -<?php echo $op_id;?> is selected to be 'Taken Forward'.</p>
						
						<p>Opportunity Title: <?php echo $optitle;?></p>
                        
                    </td>
				</tr>
				<tr>
					<td>
					<br/>
					Remark from <?php echo $requester;?><br/> 
					<div style='border:1px solid; padding:5px'>
					<?php
						//echo strip_tags();
						echo str_replace("%20"," ",$comments);
					?>
					</div>
					</td>
				</tr>
				
               
				<tr>
                    <td style="line-height: 1.5">
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
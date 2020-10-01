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
                    <td style="line-height: 1.5">
					<br/>
                      <p>This is to notify you that new opportunity is submited  opportunity no. is <?php echo $op_id;?></p>
						
						<p>Opportunity Title: <?php echo $optitle;?><br/>
                        Opportunity Owner: <?php echo $opportunity_owner;?></p>
                    </td>
                </tr>
				 <tr>
                    <td style="line-height: 1.5">
					<br/>
                        <p>Remark from  <?php echo $opportunity_owner;?></p>
						<p style="border: 1px solid;padding: 7px;"> <?php echo $idea_details;?></p>
					</td>
                </tr>
				
                <tr>
				 <tr>
                    <td style="line-height: 1.5">
					<br/>
                      <p><a href="<?php echo base_url().'opinion/view_idea/'.$op_id;?>">Click here</a> to view the information added.</p>
						
                    </td>
                </tr>
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
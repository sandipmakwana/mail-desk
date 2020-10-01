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
					<p><?php echo $otp;?> is your one time password (OTP). Please enter the OTP to proceed.</p>
						
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
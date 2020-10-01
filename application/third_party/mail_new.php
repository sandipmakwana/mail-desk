<?php
require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
$mail             = new PHPMailer();
$msg="TEST MESSAGE FOR MAIL WORKING";
$mail->IsSMTP(); // telling the class to use SMTP
//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
//$mail->SMTPAuth   = true;                  // enable SMTP authentication
//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "172.32.0.173";      // sets GMAIL as the SMTP server
$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
$mail->Username   = '';  // GMAIL username
$mail->Password   = '';            // GMAIL password
$mail->SetFrom("MOODLESUPPORT@ecommunication-mahindra.com","TFK");
//$mail->AddReplyTo($from,"Reply : ");
$mail->Subject    = "Mail Testing";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";// optional, comment out and test
$mail->MsgHTML($msg);
$mail->AddAddress("CHAUDHARI.YOGESH@mahindra.com","godase.manohar@mahindra.com");
if(!$mail->Send()) {
	
	 ?>
	 <script type="text/javascript"> alert(<?php echo $mail->ErrorInfo; ?>);</script>
	<?php } else { ?>
	  <script type="text/javascript"> alert("Message sent!");
	//location="success.php?txt_name=<?php echo $txt_name ?>" 
	</script>
<?php }
?>




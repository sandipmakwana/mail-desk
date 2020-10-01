<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_email extends CI_Controller {


	 public function __construct()
	 {
                parent::__construct();
     }
     public function sendEmail()
     {
		$this->load->library('email');

		$subject = 'This is a test mail ';
		$message = '<p>This message has been sent for testing purpose.</p>';



		$result = $this->email
		    ->from('miscout@mahindra.com')
		    ->to('apoorvaa@gmail.com')
		    ->subject($subject)
		    ->message($message)
		    ->send();

		var_dump($result);
		echo '<br />';
		echo $this->email->print_debugger();
		exit;
	}
}
?>
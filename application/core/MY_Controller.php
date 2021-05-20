<?php
class MY_Controller extends CI_Controller
{
	function __construct()
    {
        parent::__construct();
		$this->load->library(array('session','pagination','email','encryption','form_validation'));  //load required libraries.
		$this->load->helper(array('url','form','security'));                    			//load helper classes.
    }
	
	public function sendMailUsingPhpMailer($email,$subject,$msg)
	{
		require_once 'PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		                            
		$mail->isSMTP();                                     
		$mail->Host = 'smtp.gmail.com;smtp2.gmail.com';  
		$mail->SMTPAuth = true;
		
		$mail->Username = 'jeetendra.gawas@quagnitia.com';                
		$mail->Password = 'k3gPersist2012';       
				
		$mail->SMTPSecure = 'tls';                          
		$mail->Port = 587;
		
		$mail->setFrom($this->config->item("fromEmail"),$this->config->item("fromName"));
		if(is_array($email))
		{
			foreach($email as $singleEmail)
			{
				$mail->addAddress($singleEmail, $singleEmail);
			}
		}
		else
		{
			$mail->addAddress($email, $email);
		}
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $msg;
		$mail->AltBody = $msg;

		$mailRes = $mail->send();
		return $mailRes;
	}
}
?>
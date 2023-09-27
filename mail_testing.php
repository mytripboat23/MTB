<?php include("includes/connection.php");

$ammessage   = "<b> Hi Sujib </b><br><br>";			
			$ammessage  .= "Welcome to My Trip Boat! <br><br>";
			$ammessage  .= "To get started, please use this below one time password and activate your account. <br><br>";			
			//$ammessage  .= "<a href='".FURL."activation.php?uactid=".$obj->encryptPass($newUserId)."'>CLICK HERE TO ACTIVATE YOUR ACCOUNT </a> <br> <br>";		
			$ammessage  .= "<strong>One time apssword:</strong> 123456<br><br>";
			//$ammessage  .= "Please Note:<br><br>"; 
			//$ammessage  .= "You have received this message to confirm your subscription to Hashing Ad Space."; 
			//$ammessage  .= "If you have received this email in error, please disregard it. No action needs to be taken and we will not contact you again."; 
			//$ammessage  .= "If you did intend to register with Hashing Ad Space, click the link above to activate your account."; 
			$ammessage  .= "We hope to see you in ".SITE_TITLE."!"; 
			$ammessage  .= MAIL_THANK_YOU; 
			//$ammessage  .= "You can unsubscribe at any time by clicking Unsubscribe below, or by contacting our support department.";			
			//$ammessage  .="<br><br>See you there!<br><br>".MAIL_THANK_YOU; 
			//$ammessage  .="<br><br>"; 
			//$ammessage  .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
			//$ammessage .= '<p style="border-top:1px solid #808080"></p>';  
			////$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			//$ammessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($newUserId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';			
			$body2 = $obj->mailBody($ammessage); 	 
			$from2 = FROM_EMAIL_2;
			$to2   = "sujib.paul@gmail.com";			 
			$subject2 = "Activate your Hashing Ad Space account";			
			
			echo $mamam = $obj->sendMail_server($to2,$subject2,$body2,$from2,SITE_TITLE,$type); 	
?>
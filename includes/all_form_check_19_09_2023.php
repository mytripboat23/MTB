<?php 

if(strpos($_SERVER['HTTP_REFERER'],HOST_DOMAIN)!==false)
{

if(isset($_POST['SignUp']))
{	 
	$_SESSION['messageClass'] = "errorClass";
	
	//if($_SESSION['regToken']!=$_POST['reg_token']){$obj->add_message("message","Invalid Token!");}
	
	if($obj->get_message("message")=="")
	{
		if(trim($_POST['user_full_name'])=="") {$obj->add_message("message","Please enter your full name");}
		if(!$obj->alphaSpace($_POST['user_full_name'])) {$obj->add_message("message","Please provide alphabet only for name");}	
		if(trim($_POST['user_email_id'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
		if(!$obj->isEmail(trim($_POST['user_email_id']))) {$obj->add_message("message","Email Should Be Valid!");}
		if($_POST['user_passw']=="") {$obj->add_message("message","Password Should Not Be Blank!");}
		if($obj->password_validation($_POST['user_passw'])){ $obj->add_message("message",$obj->password_validation($_POST['user_passw']));}
		if($_POST['user_cpassw']=="") {$obj->add_message("message","Confirm password Should Not Be Blank!");}
		if($_POST['user_passw']!=$_POST['user_cpassw']) {$obj->add_message("message","Password and Confirm password Should Not Be Blank!");}

	}
	/*if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];
        if(!$captcha){
          $obj->add_message("message","Please check the captcha form!");
        }
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfXgQwTAAAAAIS1g5rRHZd-rVQyUZu054lICJwS&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if($obj->get_message("message")=="")
		{
			if($response['success'] == false)
			{			 
			  $obj->add_message("message","Wrong Captcha information!");
			}
		}*/	  
	if($obj->get_message("message")=="")
	{ 
		$user_email = $obj->filter_mysql($_POST['user_email_id']);
		$ulogD 		= $obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$user_email."' and u_login_status <> 'Deleted'",1);
		if($ulogD['u_login_id']=='')	
		{			
				$email = isset($_POST['user_email']) ? strtolower(trim($_POST['user_email_id']))  : '';
				list ($emailuser, $emaildomain) = array_pad(explode("@", $email, 2), 2, null);
				
				if($emaildomain=='gmail.com')
				{
					$gmail_cleartext = $obj->get_gmail_user_cleartext($_POST['user_email_id']);
					$sqlUGS = $obj->selectData(TABLE_USER_LOGIN,"","u_login_gmail_cleartext='".$gmail_cleartext."' and u_login_status<>'Deleted'",1);
					if($sqlUGS['u_login_id'])
					{
						$obj->add_message("message","Sorry! An account with that email already exists!");
					}
					else
					{
						$userlogArr['u_login_gmail_cleartext'] = $gmail_cleartext;
					}
					
				  }	
	
			}			
			else
			{
				
				$obj->add_message("message","Sorry! email id already exists. Please try another.");
			}
	 }	

		if($obj->get_message("message")=="")
		{	
			$userlogArr = array();
			$userlogArr['u_login_password']   		= password_hash($_POST['user_passw'],PASSWORD_DEFAULT);
			$userlogArr['u_login_user_email'] 		= $obj->filter_mysql($_POST['user_email_id']);
			$userlogArr['u_login_gmail_cleartext'] 	= $gmail_cleartext;
			$userlogArr['u_login_created']	  		= CURRENT_DATE_TIME;
			$userlogArr['u_login_modified']   		= CURRENT_DATE_TIME;	
			$userlogArr['u_login_attempt']    		= 0;
			$userlogArr['u_pass_update']   	  		= CURRENT_DATE_TIME;
			$userlogArr['u_login_status']   		= "Registered";
			$userlogArr['u_login_recent_password']  = $userlogArr['u_login_password'];
		 	
			$newUserId = $obj->insertData(TABLE_USER_LOGIN,$userlogArr);
			
			$_SESSION['new_user_id']    		= $newUserId;	
			$_SESSION['new_user_email'] 		= $obj->filter_mysql($_POST['user_email_id']);			
			$userlArr['u_login_id']      		= $newUserId;
			//$userlArr['user_referrer']   		= $obj->filter_mysql($_SESSION['aff_ref_id']);
			$userlArr['user_email']      		= $obj->filter_mysql($_POST['user_email_id']);
			$userlArr['user_full_name'] 		= $obj->filter_mysql($_POST['user_full_name']);
			$userlArr['user_display_name'] 		= $obj->filter_mysql($_POST['user_full_name']);
			$userlArr['user_dob']      			= date(DB_DATE_FORMAT,strtotime($_POST['user_dob']));
			$userlArr['user_reg_ip']    		= $ip;
			$userlArr['user_reg_browser']    	= $_SERVER['HTTP_USER_AGENT'];
			$userlArr['user_created']    		= CURRENT_DATE_TIME;
			$userlArr['user_modified']   		= CURRENT_DATE_TIME;
			$userlArr['user_status']   			= "Registered";		
			
			$opt_val = "";
			if(isset($_POST['tour_operator']) && $_POST['tour_operator']=='y')
			{
					$opt_val = "ind,";
					if(isset($_POST['opt_type'])){
					  if (is_array($_POST['opt_type'])) {						
						  $opt_val .= implode(",",$_POST['opt_type']);
					  } 
					}
			}
			 $userlArr['user_type']  = $opt_val;		
	
			$obj->insertData(TABLE_USER,$userlArr);
			
			$reg_activation_number = rand(111111,999999);
			
			$userRAN['uo_otp'] = $reg_activation_number;
			$userRAN['user_id'] = $newUserId;
			$obj->insertData(TABLE_USER_OTP,$userRAN);
			 
			 
			$ammessage   = "<b> Hi ".ucfirst($_POST['user_full_name'])."</b><br><br>";			
			$ammessage  .= "Welcome to My Trip Boat! <br><br>";
			$ammessage  .= "To get started, please use this below one time password and activate your account. <br><br>";			
			//$ammessage  .= "<a href='".FURL."activation?uactid=".$obj->encryptPass($newUserId)."'>CLICK HERE TO ACTIVATE YOUR ACCOUNT </a> <br> <br>";		
			$ammessage  .= "<strong>One time password (OTP):</strong> ".$userRAN['uo_otp']."<br><br>";
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
			//$ammessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation?uactid=".$obj->encryptPass($newUserId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';			
			$body2 = $obj->mailBody($ammessage); 	 
			$from2 = FROM_EMAIL_2;
			$to2   = $_POST['user_email_id'];			 
			$subject2 = "Activate your ".SITE_TITLE." account";			
			$mamam = $obj->sendMail_server($to2,$subject2,$body2,$from2,SITE_TITLE,$type); 	
			 	
			
			
			$_SESSION['messageClass'] = "successClass";				
			//$malink  = "<a href='".FURL."missing_activation'>CLICK HERE</a>"; 		 
			$obj->add_message("message","Congratulations. You have successfully registered with ".SITE_TITLE.". An one time password has been sent to your registered email-id. Please use that OTP to activate your account.");	
			
			$obj->reDirect("verification");	 
			 
		}
	
}

if(isset($_POST['otpVerify']) && $_POST['otpVerify']=='Verify')
{
	$_SESSION['messageClass'] = "errorClass";
	
	if(!isset($_SESSION['new_user_id']) || $_SESSION['new_user_id'] == '') {$obj->add_message("message","Invalid Request!");}
	if($obj->get_message("message")=="")
	{
		if(trim($_POST['otp_val'])=="") {$obj->add_message("message","OTP Should Not Be Blank!");}
		if($obj->otp_num_validation($_POST['otp_val'])) {$obj->add_message("message","Please provide us a valid OTP!");}
	}
	
	
	if($obj->get_message("message")=="")
	{
		$otp_input =  $obj->filter_numeric($_POST['otp_val']);
		$new_user_id = $_SESSION['new_user_id'];
		
		$userV = $obj->selectData(TABLE_USER_OTP,"","user_id='".$new_user_id."' and uo_otp='".$otp_input."' and uo_status='Active'",1);
		if(isset($userV['uo_id']) && $userV['uo_id']>0)
		{
			$userLSU['u_login_status'] = 'Active';
			$obj->updateDataAll(TABLE_USER_LOGIN,$userLSU,"u_login_id='".$new_user_id."'"); 
			
			$userSU['user_status'] = 'Active';
			$obj->updateDataAll(TABLE_USER,$userSU,"u_login_id='".$new_user_id."'"); 
		
			$_SESSION['messageClass'] = "successClass";					 
			$obj->add_message("message","Congratulations. You have successfully verified your account. You can now login into our site to access the various features of our site.");	
			
			$obj->reDirect("login");	 
		}
	}
}

if(isset($_POST['SignIn']))
{	
	$_SESSION['messageClass'] = "errorClass";
	//if($_SESSION['loginToken']!=$_POST['login_token']){$obj->add_message("message","Invalid Token!".$_SESSION['loginToken']."||".$_POST['login_token']);}
	
	if($obj->get_message("message")=="")
	{
		if(trim($_POST['login_email'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
		if(!$obj->isEmail(trim($_POST['login_email']))) {$obj->add_message("message","Email Should Be Valid!");}
		if(trim($_POST['login_pw'])=="") {$obj->add_message("message","Password Should Not Be Blank!");}
	
	}
	
	 
	/*if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];
        if(!$captcha){
          $obj->add_message("message","Please check the captcha form!");
        }
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfXgQwTAAAAAIS1g5rRHZd-rVQyUZu054lICJwS&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if($obj->get_message("message")=="")
		{
			if($response['success'] == false)
			{			 
			  $obj->add_message("message","Wrong Captcha information!");
			}
		}
	 */
	
	$log_email = $obj->filter_mysql($_POST['login_email']);
	if($obj->get_message("message")=="")
	{			
		$sqlUS1=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$log_email."' and u_suspend_status='y'",1);
		if($sqlUS1)
		{
			$obj->add_message("message","Your account has been suspended. Please contact support for details.");
		}
	}
	if($obj->get_message("message")=="")
	{	
		$sqlUS2=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$log_email."' and u_login_status='Inactive'",1);
		//pre($sqlUS2); exit;
		//echo password_verify($_POST['login_pw'],$sqlUS2['u_login_password']);
		//exit;
		if($sqlUS2)
		{
			if(password_verify($_POST['login_pw'],$sqlUS2['u_login_password']))
			{ 
				$malink  = "<a href='".FURL."login?uactid=".$obj->encryptPass($sqlUS2['u_login_id'])."'>CLICK HERE</a>";
				$obj->add_message("message",'Your account has been deactivated. Please '.$malink.' to receive your activation email and reactivate your account.'); 
			}
			else  $obj->add_message("message","Your account has been suspended. Please contact support.");
		}
	}	
				
  
	if($obj->get_message("message")=="")
	{
		//$u_login_password  = md5($_POST['u_login_password']);
		$user_login = $obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$log_email."' and u_login_status='Active' and u_suspend_status='n'",1);				
		if($user_login)
		{ 
			//pre($user_login); exit;
			if($user_login['u_login_failed']>=3)
			{
				$_SESSION['messageClass'] = 'errorClass';
				$obj->add_message("message","You have exceeded max number of login attempt. Please reset your password and try again.");
				$obj->reDirect("login");   
				exit;
			}
		 // password_verify($_POST['login_pw'],$user_login['u_login_password']);
		
			if(password_verify($_POST['login_pw'],$user_login['u_login_password']))
			{	
				  
				$loginAtmp['u_login_attempt']  = $user_login['u_login_attempt']+1;
				$loginAtmp['u_login_failed']   = 0;
				$loginAtmp['u_last_login']     = CURRENT_DATE_TIME;	

				$obj->updateData(TABLE_USER_LOGIN,$loginAtmp,"u_login_id='".$user_login['u_login_id']."'");	
				
				$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$user_login['u_login_id']."'",1);
				
					$_SESSION['session_login_time']   = time();			
					$_SESSION['user'] = $user_login;
					$_SESSION['user']['user_type'] = $userD['user_type'];
					$_SESSION['user']['ip'] = $ip;
					$_SESSION['user']['notification'] = 'n';
				 	if(isset($_SESSION['user_request_path']) && $_SESSION['user_request_path']!='') $obj->reDirect($_SESSION['user_request_path']);  
					else $obj->reDirect("dashboard");  
					exit;
			}
			else
			{
				$loginF['u_login_failed'] = $user_login['u_login_failed'] + 1;
				$obj->updateData(TABLE_USER_LOGIN,$loginF,"u_login_id='".$user_login['u_login_id']."'");	
				$_SESSION['messageClass'] = 'errorClass';
				$obj->add_message("message","Incorrect login details. Please try again.");
			}
		}
		else
		{ 
					 
			$_SESSION['messageClass'] = 'errorClass';
			$obj->add_message("message","Incorrect login details. Please try again.");
			$obj->reDirect("login");
		}
	}
	
}





if(isset($_POST['resetPass']))
{  
	$_SESSION['messageClass'] = "errorClass";
	
	if($_SESSION['resetPToken']!=$_POST['reset_pass_token']){$obj->add_message("message","Invalid Token!");}

	if($obj->get_message("message")=="")
	{
		if(trim($_POST['reg_email_id'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
		if(!$obj->isEmail(trim($_POST['reg_email_id']))) {$obj->add_message("message","Email Should Be Valid!");}
	}
	/*if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];
        if(!$captcha){
          $obj->add_message("message","Please check the captcha form!");
        }
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfXgQwTAAAAAIS1g5rRHZd-rVQyUZu054lICJwS&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if($obj->get_message("message")=="")
		{
			if($response['success'] == false)
			{			 
			  $obj->add_message("message","Wrong Captcha information!");
			}
		}*/

	if($obj->get_message("message")=="")
	{
		$user_remail = $obj->filter_mysql($_POST['reg_email_id']);
		$sqlS=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$user_remail."' and u_login_status='Active'",1);
		if(!$sqlS['u_login_id'])
		{
			$obj->add_message("message","Sorry! email id does't exists.");
			$obj->reDirect('reset_password');
			exit;
		}
	}	
		
	if($obj->get_message("message")=="")
	{		

			$userD		= $obj->selectData(TABLE_USER,"","u_login_id='".$sqlS['u_login_id']."'",1);
			$userLD		= $obj->selectData(TABLE_USER_LOGIN,"u_login_user_email","u_login_id='".$sqlS['u_login_id']."'",1);		
			
			
			$otpU['uo_status'] = 'Deleted';
			$otpD = $obj->updateDataAll(TABLE_USER_OTP,$otpU,"user_id='".$sqlS['u_login_id']."' and uo_status<>'Deleted'"); 
			$reg_activation_number = rand(111111,999999);
			
			$userRAN['uo_otp'] = $reg_activation_number;
			$userRAN['user_id'] = $sqlS['u_login_id'];
			$obj->insertData(TABLE_USER_OTP,$userRAN);
			
			$_SESSION['reset_pass_user_id'] = $sqlS['u_login_id'];
				
		  
			$rpin_mail_message  = "<b> Dear ".$userD['user_full_name']."</b>, your one time password for resetting password is provided below.<br><br>";			
			$rpin_mail_message .= "OTP: ".$reg_activation_number."<br>";
			$rpin_mail_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;
			

		    $body 		= $obj->mailBody($rpin_mail_message);	 
			$from 		= FROM_EMAIL_2;
			$to   		= $userLD['u_login_user_email'];	  
			$subject 	= "Reset Your Password - ".$userD['user_full_name'];		     
			$evalue = $obj->sendMail_server($to,$subject,$body,$from,SITE_TITLE,$type);	
				
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Please check your email account and use the OTP to reset your password.");					
			$obj->reDirect('reset_password_final');
	}
}
 
if(isset($_POST['resetPassFinal']))
{
	$_SESSION['messageClass'] = "errorClass";
	if($_POST['set_pass']=="") {$obj->add_message("message","Password Should Not Be Blank!");}
	if($obj->password_validation($_POST['set_pass'])){ $obj->add_message("message",$obj>password_validation($_POST['set_pass']));}
	if($_POST['set_re_pass']=="") {$obj->add_message("message","Confirm password Should Not Be Blank!");}
	if($_POST['set_pass']!=$_POST['set_re_pass']) {$obj->add_message("message","Password and Confirm password Should Not Be Blank!");}
	if(trim($_POST['otp_val'])=="") {$obj->add_message("message","OTP Should Not Be Blank!");}
	if($obj->otp_num_validation($_POST['otp_val'])) {$obj->add_message("message","Please provide us a valid OTP!");}
	
	$u_login_id = $obj->filter_mysql($_SESSION['reset_pass_user_id']); 			
	$userLoginD = $obj->selectData(TABLE_USER_LOGIN,"","u_login_id=".$u_login_id."",1);
	
		$otp_input =  $obj->filter_numeric($_POST['otp_val']);

		
		$userV = $obj->selectData(TABLE_USER_OTP,"","user_id='".$u_login_id."' and uo_otp='".$otp_input."' and uo_status='Active'",1);
		if(!isset($userV['uo_id']) || $userV['uo_id']=="")
		{
				$obj->add_message("message","OTP mismatched!");
		}
	 
	
	if($obj->get_message("message")=="")
	{	 
		if($userLoginD)
		{
			$new_pass = password_hash($_POST['set_pass'],PASSWORD_DEFAULT);
			
			$ulogDU['u_login_password'] = $new_pass;
			$ulogDU['u_login_failed'] = 0;
			$ulogDU['u_login_modified'] = CURRENT_DATE_TIME;
			
			$obj->updateData(TABLE_USER_LOGIN,$ulogDU,"u_login_id='".$u_login_id."'");
			
			$otpU['uo_status'] = 'Deleted';
			$otpD = $obj->updateDataAll(TABLE_USER_OTP,$otpU,"user_id='".$u_login_id."' and uo_status<>'Deleted'"); 
			
			
			$obj->add_message("message","Your password has been reset successfully");	
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect('login');			
		}
		else
		{
			$obj->add_message("message","Invalid Url");
			$_SESSION['messageClass'] = 'errorClass';
		}	
	}
}

if(isset($_POST['ForgotPass']))
{
	$_SESSION['messageClass'] = "errorClass";

	if(trim($_POST['user_email'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
	if(!$obj->isEmail(trim($_POST['user_email']))) {$obj->add_message("message","Email Should Be Valid!");}

	if($obj->get_message("message")=="")
	{
		$user_email = $obj->filter_mysql($_POST['user_email']);
		$sqlS=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$user_email."' and u_login_status='Active'",1);
		if(!$sqlS['u_login_id'])
		{
			$obj->add_message("message","Sorry! email id does't exists. Please try another.");
		}	
	}	
	 
	if($obj->get_message("message")=="")
	{		
	
			$rp_id = 0;
			$user_rp_search = $obj->selectData(TABLE_RECOVER_PASS,"","rp_user='".$sqlS['u_login_id']."'",1);
			if($user_rp_search['rp_id']!='')
			{
				if(strtotime(date("Y-m-d",strtotime($user_rp_search['rp_time'])))==strtotime(date("Y-m-d"))){
					if(isset($user_rp_search['rp_counter']) && $user_rp_search['rp_counter']>=THRESOLD_RESETPWD_LINK_COUNTER){
						$obj->add_message("message",MSG_THRESOLD_RESETPWD_LINK_COUNTER);	
						$_SESSION['messageClass'] = 'errorClass';
						$obj->reDirect('login');
						exit;
					}
					$rp_counter = $user_rp_search['rp_counter']+1;
				}else{
					$rp_counter = 1;
				}
				$encryptPassToStore = $obj->encryptPass($user_rp_search['rp_id']);
				$arrRPU['rp_time'] = date("Y-m-d H:i:s");
				$arrRPU['rp_ip']   = $ip;
				$arrRPU['rp_counter'] = $rp_counter;
				$arrRPU['rp_token']   = $encryptPassToStore;
				$obj->updateData(TABLE_RECOVER_PASS,$arrRPU,"rp_id='".$user_rp_search['rp_id']."'");
				$rp_id = $user_rp_search['rp_id'];
					
			}
			else
			{	
				$arrRPI['rp_user'] = $sqlS['u_login_id'];
				$arrRPI['rp_ip']   = $ip;
				$arrRPI['rp_time'] = date("Y-m-d H:i:s");
				$arrRPI['rp_counter'] = 1;
				$rp_id = $obj->insertData(TABLE_RECOVER_PASS,$arrRPI);
				$encryptPassToStore = $obj->encryptPass($rp_id);
				$arrRPIT["rp_token"] = $encryptPassToStore;
				$obj->updateData(TABLE_RECOVER_PASS,$arrRPIT,"rp_id='".$rp_id."'");
			}
			$userD=$obj->selectData(TABLE_USER,"","u_login_id='".$sqlS['u_login_id']."'",1);
			$userLD=$obj->selectData(TABLE_USER_LOGIN,"u_login_user_email","u_login_id='".$sqlS['u_login_id']."'",1);
		  
			$forgot_mail_message  = "<b> Dear ".$userD['user_first_name']."</b>, your reset password reset link provided below.<br><br>";			
			$forgot_mail_message .= "Please <a href='".FURL."reset_password?uactid=".$encryptPassToStore."'>Click Here </a> to change your password. <br>";
			$forgot_mail_message .= "Note: The above link is valid for one hour only";
			$forgot_mail_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;
			
			$forgot_mail_message .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$forgot_mail_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation?uactid=".$obj->encryptPass($sqlS['u_login_id']).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$body = $obj->mailBody($forgot_mail_message); 
			$from = FROM_EMAIL_2;
			$to   = $userLD['u_login_user_email'];	  
			$subject = "Forgot Password - ".$userD['user_first_name'];		     
			$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);	
				
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have sent your password reset instructions to your email.");					
			$obj->reDirect('forgot_password');
	}
}

if(isset($_POST['ResetPass']))
{
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['u_login_password'])=="") {$obj->add_message("message","Password Should Not Be Blank!");}
	if(trim($_POST['u_login_cpassword'])=="") {$obj->add_message("message","Retype Password Should Not Be Blank!");}
	if(trim($_POST['u_login_cpassword'])!=trim($_POST['u_login_password'])) {$obj->add_message("message","Password & Retype Password
	Should Be Same!");}
	
	if($obj->get_message("message")=="")
	{	
		$rp_id = $obj->filter_mysql($obj->retrievePass($_REQUEST['uactid'])); 
		$user_rpD = $obj->selectData(TABLE_RECOVER_PASS,"","rp_id='".$rp_id."'",1);
		if($user_rpD['rp_user']!='')
		{
				if(strtolower($_REQUEST['uactid']) != strtolower($user_rpD['rp_token'])){
					$obj->add_message("message","The token has expired.");
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('forgot_password');
					exit;
				}
				if(date("Y-m-d H:i:s")-strtotime($user_rpD['rp_time'])>3600)
				{
					$obj->add_message("message","Recovery link expired! Please submit the form below and request a new link.");
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('forgot_password');
					exit;
				}
				else if($user_rpD['rp_ip']!=$ip)
				{
					$obj->add_message("message",'Unauthorized request. It seems your IP address or device may have changed during your password reset. Please try and "recover password" again ensuring you use the same device and are connected to a stable internet connection.');
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('forgot_password');
					exit;
				}
				else
				{
					$u_login_id = $user_rpD['rp_user'];
					
				}	
		}
		else
		{
			$obj->add_message("message","Invalid Request!");
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect('forgot_password');
			exit;
		}	
	
		//$new_password=md5($_POST['u_login_password']);password_hash($_POST['u_login_password'],PASSWORD_DEFAULT);
		$new_password = password_hash($_POST['u_login_password'],PASSWORD_DEFAULT);
		$pass_matched=$obj->selectData(TABLE_USER_LOGIN,"","u_login_id=".$u_login_id."",1);
		//pre($pass_matched); exit;
		if($pass_matched)
		{			 
			if(isset($pass_matched["u_login_password"]) && $pass_matched["u_login_password"] != ""){
				$resentPwd = $pass_matched["u_login_recent_password"];
				if($resentPwd != ""){
					$allRecentPwd = explode(",", $resentPwd);
					for($i=0;$i<count($allRecentPwd);$i++){
						if(password_verify($_POST['u_login_password'],$allRecentPwd[$i])){
						$obj->add_message("message","Please choose a password that is different from your last ".THRESOLD_NUM_REST_PASSWORD." passwords");
							$_SESSION['messageClass'] = 'errorClass';
							$obj->reDirect('forgot_password');
							exit;
							//exit;	
						}
					}
				}else{
					if($resentPwd==""){
						$saveRecentPasswordArry=array('u_login_recent_password'=>$new_password);		
						$obj->updateData(TABLE_USER_LOGIN,$saveRecentPasswordArry,"u_login_id='".$u_login_id."'");
					}
				}
				if(isset($allRecentPwd) && count($allRecentPwd)==THRESOLD_NUM_REST_PASSWORD){
					$allRecentPwd = explode(",", $resentPwd);
					$new_password = array($new_password);
					$recentPwdArry = array_merge($new_password,$allRecentPwd);
					$finalRecentPwdArray = array_pop($recentPwdArry);
					$recentPwd = implode(",",$recentPwdArry);
					$newupdatedRecentPwdArry=array('u_login_recent_password'=>$recentPwd);			
					$obj->updateData(TABLE_USER_LOGIN,$newupdatedRecentPwdArry,"u_login_id='".$u_login_id."'");
				}else{
					if($resentPwd!=""){
						$allRecentPwd = explode(",", $resentPwd);
						$recentPwdArry = array_merge(array($new_password),$allRecentPwd);
						$recentPwd = implode(",",$recentPwdArry);
						$newupdatedRecentPwdArry=array('u_login_recent_password'=>$recentPwd);			
						$obj->updateData(TABLE_USER_LOGIN,$newupdatedRecentPwdArry,"u_login_id='".$u_login_id."'");
					}
				}
			}
			$msges ='Password reset successfully.';	 
			$type = 't';
			$obj->monitor_making_changes($u_login_id,$ip,$msges,$type);
					
			$ch_pass_arr=array('u_login_password'=>$new_password,'u_pass_update'=>date('Y-m-d H:i:s'),'u_pass_reset'=>'y','u_pass_reset_2'=>'y');			
			$obj->updateData(TABLE_USER_LOGIN,$ch_pass_arr,"u_login_id='".$u_login_id."'");
			
			$dataEr = $obj->selectData(TABLE_EMERGENCY_RESET,"","user_id='".$u_login_id."' and er_setup='n'",1);
			$ch_pass_eamer=array('er_setup'=>'y');			
			$obj->updateData(TABLE_EMERGENCY_RESET,$ch_pass_eamer,"er_id='".$dataEr['er_id']."'");
			
			$obj->deleteData(TABLE_RECOVER_PASS,"rp_id='".$rp_id."'");
			
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect('login');
		}
		else
		{
			 
			$msges ='Password mismatch.';			 
			$type = 'f';
			$obj->monitor_making_changes($u_login_id,$ip,$msges,$type);
			 
			$obj->add_message("message",PASSWORD_MISMATCH);
			$_SESSION['messageClass'] = 'errorClass';
		}	
	}
}
 
if (isset($_POST['changePass'])) 
{
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_opass'])=="") {$obj->add_message("message","Old password should not be blank!");}
	if(trim($_POST['user_npass'])=="") {$obj->add_message("message","New password should not be blank!");}
	if(trim($_POST['user_rpass'])=="") {$obj->add_message("message","Retype password should not be blank!");}
	if(trim($_POST['user_npass'])!=trim($_POST['user_rpass'])) {$obj->add_message("message","New Password & Retype password should be same!");}
	if(trim($_POST['user_opass'])==trim($_POST['user_npass'])) {$obj->add_message("message","New and old password cannot be same!");}
	if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];
        if(!$captcha){
          $obj->add_message("message","Please check the captcha form!");
        }
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LceAhYTAAAAAHBVPJVgBkM8Pdi_fUcUIoeBiPEB&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if($obj->get_message("message")=="")
		{
			if($response['success'] == false)
			{			 
			   $obj->add_message("message","Wrong Captcha information!");
			}
		}			
	
	if($obj->get_message("message")=="") {
		$userId = $_SESSION['user']['u_login_id'];
		$userP = $obj->selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."'",1);		
		if($userP['u_pass_reset_2']=='y')
		{
			if(!password_verify($_POST['user_opass'],$userP['u_login_password']))
			{	
				$obj->add_message("message","Incorrect old password, Please try again.");	
				$msges ='Incorrect old password.';			 
				$type = 'f';
				$obj->monitor_making_changes($userId,$ip,$msges,$type);
			}
		}	
	}
	
	if($obj->get_message("message")=="")
	{
		$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."'",1);
		$userP = $obj->selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."'",1);		
		$newPass['u_login_password'] =  password_hash($_POST['user_npass'],PASSWORD_DEFAULT);
		$newPass['u_pass_reset_2'] = 'y';
		$obj->updateData(TABLE_USER_LOGIN,$newPass,"u_login_id='".$userId."'");	
		
		 
		$msges ='Password changed successfully.';			 
		$type = 't';
		$obj->monitor_making_changes($userId,$ip,$msges,$type);
		 
		$change_mail_message  = "<b> Hello ".$userD['user_first_name']."</b>,<br><br>";	
		$change_mail_message .= "This is a courtesy email to inform you the Primary Password (log in password) to your account has been successfully changed. <br><br>";
		$change_mail_message .= "We take the security of your account seriously, and thank you for changing your Primary Password regularly to safeguard your account.<br><br>";
		$change_mail_message .= "If you did not change your Primary Password, please contact support immediately.<br><br>";	
		$change_mail_message .= "Always feel free to reach out with any questions you may have.<br><br>";	
		$change_mail_message .= "Maximize Your Online Business Success!<br><br>";		
		$change_mail_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;

		$forgot_mail_message .= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$forgot_mail_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
		$body = $obj->mailBody($change_mail_message);				 
		$from = FROM_EMAIL_2;
		$to   = $userP['u_login_user_email'];		 
		$subject = "You have successfully changed your password";	
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);		
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","Password changed successfully. Thanks");		
	}	
	$obj->reDirect("change_password");
}

	if(isset($_POST['create_new_package']) && $_POST['create_new_package']=='Yes')
	{
		
		$_SESSION['messageClass'] = "errorClass";
		if(trim($_POST['pck_title'])=="") {$obj->add_message("message","Please enter package name");}
		//if(!$obj->alphaSpace($_POST['pck_name'])) {$obj->add_message("message","Please provide alphabet only for package name");}
		
		if(trim($_POST['pck_dest'])=="") {$obj->add_message("message","Please enter package destination");}
		//if(!$obj->alphaSpace($_POST['pck_destination'])) {$obj->add_message("message","Please provide alphabet only for package destination");}	
		
		if(trim($_POST['pck_month'])=="") {$obj->add_message("message","Please select package month");}
		
		if(trim($_POST['pck_start_pt'])=="") {$obj->add_message("message","Please enter start point");}
		
		if(trim($_POST['pck_end_pt'])=="") {$obj->add_message("message","Please enter end point");}
		
		//if(trim($_POST['pck_start_date'])=="") {$obj->add_message("message","Please enter package start date");}
		//if(!$obj->isValidDate($_POST['pck_start_date'],"Y-m-d")){$obj->add_message("message","Please enter valid start date");}
		
		//if(trim($_POST['pck_start_time'])=="") {$obj->add_message("message","Please enter package start time");}
		
		//if(trim($_POST['pck_end_date'])=="") {$obj->add_message("message","Please enter package end date");}
		//if(!$obj->isValidDate($_POST['pck_end_date'],"Y-m-d")){$obj->add_message("message","Please enter valid end date");}
		
				
		//if(trim($_POST['pck_capacity'])=="") {$obj->add_message("message","Please enter seat availablity");}
		
		//if(!is_integer(intval($_POST['pck_capacity']))){$obj->add_message("message","Please enter valid seat availability");}
		
		if(trim($_POST['pck_price'])=="") {$obj->add_message("message","Please enter package price");}
		if(!is_integer(intval($_POST['pck_price']))) {$obj->add_message("message","Please enter valid price");}
		
		/*if(trim($_POST['pck_discount_price'])!="") {
			if(!is_integer(intval($_POST['pck_discount_price']))) {$obj->add_message("message","Please enter valid discount price");}
		}*/
		
		
		/*if($_FILES['up_img']['tmp_name']=="") {$obj->add_message("message","Please uplod an image");}
		
		$pck_photo = "";
		
		if($_FILES['up_img']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('up_img',PACKAGE, 'jpg,png,jpeg,gif');
			if($error)
			{
				$obj->add_message("message",$error);
				$err=1;
			} else {
				$pck_photo = $fileName;				
			}
		}		*/
		
		if(!isset($_SESSION['pck_photos'][0]) || $_SESSION['pck_photos'][0]=="")
		{
			//$obj->add_message("message","Please upload an image");
		}
		
		if($obj->get_message("message")=="")
		{
			$pckD = array();
			
			$pckD['user_id'] = $_SESSION['user']['u_login_id'];
			
			$pckD['pck_title'] 		= $obj->filter_mysql($_POST['pck_title']);
			$pckD['pck_dest']  		= $obj->filter_mysql($_POST['pck_dest']);
			
			$pckD['pck_month']  	= $obj->filter_mysql($_POST['pck_month']);
			
			$pckD['pck_start_dt']   = $_POST['pck_start_dt'];
			
			if($_POST['pck_start_dt']=="") 
			{
				if($pckD['pck_month']<date("n"))
				{
					$pckD['pck_year'] = date("Y")+1;
				}
				else
				{
					$pckD['pck_year'] = date("Y");
				}
			}
						
			if($_POST['pck_end_dt']!="") $pckD['pck_end_dt']     = $obj->filter_mysql($_POST['pck_end_dt']);
			else 
			{
				$e_date = date("Y")."-".$pckD['pck_month']."-"."1";
				$pckD['pck_end_dt']     = date("Y-m-t", strtotime($e_date));
			}

			
			$pckD['pck_start_tm']   = $obj->filter_mysql($_POST['pck_start_tm']);
			
			$pckD['pck_start_pt']   = $obj->filter_mysql($_POST['pck_start_pt']);
			$pckD['pck_end_pt']     = $obj->filter_mysql($_POST['pck_end_pt']);
			
			
			
			$pckD['pck_capacity']   = $obj->filter_mysql($_POST['pck_capacity']);
			$pckD['pck_price']   	= $obj->filter_mysql($_POST['pck_price']);
			//$pckD['pck_dis_price']  = $obj->filter_mysql($_POST['pck_discount_price']);
			$pckD['pck_desc']   	= $obj->filter_mysql($_POST['pck_desc']);
			$pckD['pck_hotel_veh']  = $obj->filter_mysql($_POST['pck_hotel_veh']);
			
			if(isset($_POST['faci']) && !empty($_POST['faci'])) $pckD['pck_inc']   = ",".implode(",",$_POST['faci']).",";
			else $pckD['pck_inc']= "";

			
		 if(isset($_REQUEST['pck_tags_list'][0]) && $_REQUEST['pck_tags_list'][0]!='')
		 {
		   for($tagCounter=0;$tagCounter<count($_REQUEST['pck_tags_list']);$tagCounter++)
		   {
				$searchTags = $obj->selectData(TABLE_TAGS,"","tag_title = '" .$_REQUEST[ 'pck_tags_list' ][$tagCounter]."'",1);
				if(!isset($searchTags['tag_id']))
				{
					$tagA = array();
					$tagA['tag_title']  = $_REQUEST['pck_tags_list'][$tagCounter];
					$tagA['tag_status'] = 'Inactive';
					$tagAdd = $obj->insertData(TABLE_TAGS,$tagA);
				}
			}
			
			$pckD['pck_tags']   	= implode(",",$_POST['pck_tags_list']);
		}	
			$pckD['pck_photo']   	= $_SESSION['pck_photos'][0];
			
			if($_POST['pck_cust']=='y') $pckD['pck_cust'] = 'y';
			else  $pckD['pck_cust'] = 'n';
			
			
			if($_POST['share']==1) $pckD['pck_share'] = 'pub';
			else if($_POST['share']==2) $pckD['pck_share'] = 'fnd';
			else $pckD['pck_share'] = 'pvt';
			
			$new_tour_id = $obj->insertData(TABLE_PACKAGE,$pckD);	
			
			unset($_SESSION['pck_photos']);
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message","Tour Package created sucecssfully. <a href='tour-details?tId=".$new_tour_id."'> View</a> your package.");
			
			$obj->reDirect("create-package");
		}
	
	}	 
	
	if(isset($_POST['edit_package']) && $_POST['edit_package']=='Yes')
	{
		
		$_SESSION['messageClass'] = "errorClass";
		$_SESSION['messageClass'] = "errorClass";
		if(trim($_POST['pck_title'])=="") {$obj->add_message("message","Please enter package name");}
					
		if(trim($_POST['pck_dest'])=="") {$obj->add_message("message","Please enter package destination");}
			
		
		if(trim($_POST['pck_month'])=="") {$obj->add_message("message","Please select package month");}
		
		if(trim($_POST['pck_start_pt'])=="") {$obj->add_message("message","Please enter start point");}
		
		if(trim($_POST['pck_end_pt'])=="") {$obj->add_message("message","Please enter end point");}
		
		if(trim($_POST['pck_price'])=="") {$obj->add_message("message","Please enter package price");}
		if(!is_integer(intval($_POST['pck_price']))) {$obj->add_message("message","Please enter valid price");}
		
		
		
		if($obj->get_message("message")=="")
		{
			$pckD = array();
			
			$pckD['user_id'] = $_SESSION['user']['u_login_id'];
			
			$pckD['pck_title'] 		= $obj->filter_mysql($_POST['pck_title']);
			$pckD['pck_dest']  		= $obj->filter_mysql($_POST['pck_dest']);
			$pckD['pck_month']  	= $obj->filter_mysql($_POST['pck_month']);
			
			$pckD['pck_start_dt']   = $_POST['pck_start_dt'];
			
			if($_POST['pck_start_dt']=="") 
			{
				if($pckD['pck_month']<date("n"))
				{
					$pckD['pck_year'] = date("Y")+1;
				}
				else
				{
					$pckD['pck_year'] = date("Y");
				}
			}
						
			if($_POST['pck_end_dt']!="") $pckD['pck_end_dt']     = $obj->filter_mysql($_POST['pck_end_dt']);
			else 
			{
				$e_date = date("Y")."-".$pckD['pck_month']."-"."1";
				$pckD['pck_end_dt']     = date("Y-m-t", strtotime($e_date));
			}

			
			$pckD['pck_start_tm']   = $obj->filter_mysql($_POST['pck_start_tm']);
			
			$pckD['pck_start_pt']   = $obj->filter_mysql($_POST['pck_start_pt']);
			$pckD['pck_end_pt']     = $obj->filter_mysql($_POST['pck_end_pt']);
			
			
			
			$pckD['pck_capacity']   = $obj->filter_mysql($_POST['pck_capacity']);
			$pckD['pck_price']   	= $obj->filter_mysql($_POST['pck_price']);
			//$pckD['pck_dis_price']  = $obj->filter_mysql($_POST['pck_discount_price']);
			$pckD['pck_desc']   	= $obj->filter_mysql($_POST['pck_desc']);
			$pckD['pck_hotel_veh']  = $obj->filter_mysql($_POST['pck_hotel_veh']);
			
			//$pckD['pck_tags']   	= implode(",",$_POST['pck_tags_list']);
			
			if(isset($_POST['faci']) && !empty($_POST['faci'])) $pckD['pck_inc']   = ",".implode(",",$_POST['faci']).",";
			else $pckD['pck_inc']= "";
			
			$pckD['pck_terms']   	= $obj->filter_mysql($_POST['pck_terms']);
			
			if(isset($_SESSION['pck_photos'][0]) && $_SESSION['pck_photos'][0]!="") $pckD['pck_photo']   	= $_SESSION['pck_photos'][0];
			else $pckD['pck_photo']   	= "";
			
			if($_POST['pck_cust']=='y') $pckD['pck_cust'] = 'y';
			else  $pckD['pck_cust'] = 'n';
			
			
			$obj->updateData(TABLE_PACKAGE,$pckD,"pck_id='".$_REQUEST['package_id']."'");	
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message","Tour Package updated sucecssfully!");
			
			$obj->reDirect("tours?uId=".$_SESSION['user']['u_login_id']);
		}
	
	}	 
	
	if(isset($_POST['update_id_details']) && $_POST['update_id_details']=='Submit')
	{
		$_SESSION['messageClass'] = "errorClass";
		if(trim($_POST['id_num'])=="") {$obj->add_message("message","Please enter ID number");}
		if($_FILES['id_photo']['tmp_name']==""){$obj->add_message("message","Please upload ID photo");}
		if($_FILES['id_self_photo']['tmp_name']==""){$obj->add_message("message","Please upload self photo");}
		
		if($obj->get_message("message")=="")
		{
			$id_photo_1 = "";
			if($_FILES['id_photo']['tmp_name'])
			{
				list($fileName,$error)=$obj->uploadFile('id_photo',ID, 'jpg,png,jpeg,gif');
				if($error)
				{
					$obj->add_message("message",$error);
					$err=1;
				} else {
					$id_photo_1 = $fileName;				
				}
			}		
		}
		
		if($obj->get_message("message")=="")
		{
			$id_self_photo_1 = "";
			if($_FILES['id_self_photo']['tmp_name'])
			{
				list($fileName,$error)=$obj->uploadFile('id_self_photo',ID, 'jpg,png,jpeg,gif');
				if($error)
				{
					$obj->add_message("message",$error);
					$err=1;
				} else {
					$id_self_photo_1 = $fileName;				
				}
			}		
		}
		if($obj->get_message("message")=="")
		{
			$log_user_id = $_SESSION['user']['u_login_id'];
			$dataP = array();
			$dataP["user_id_no"]               = $_POST['id_num'];
			$dataP["user_id_photo"]   		   = $id_photo_1;
			$dataP["user_self_photo"]          = $id_self_photo_1;


			$obj->updateData(TABLE_USER,$dataP,"u_login_id='".$log_user_id."'");
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message","Your Verification details uploaded sucecssfully. Please wait for admin approval");
		}
	}
	
	
	if(isset($_POST['create_new_ad']) && $_POST['create_new_ad']=='Yes')
	{
		
		$_SESSION['messageClass'] = "errorClass";
		if(trim($_POST['pck_name'])=="") {$obj->add_message("message","Please enter package name");}
		
		if(trim($_POST['comp_name'])=="") {$obj->add_message("message","Please enter company name");}
		
		if(trim($_POST['contact_name'])=="") {$obj->add_message("message","Please enter contact name");}
		if(!$obj->alphaSpace($_POST['contact_name'])) {$obj->add_message("message","Please provide alphabet only for contact name");}	
		
		if(trim($_POST['contact_num'])=="") {$obj->add_message("message","Please enter contact phone");}
		if(trim($_POST['contact_email'])=="") {$obj->add_message("message","Please enter contact email");}
		if(!$obj->isEmail(trim($_POST['contact_email']))) {$obj->add_message("message","Email Should Be Valid!");}
		
		if(trim($_POST['ad_subject'])=="") {$obj->add_message("message","Please enter subject");}		
		
		if($_FILES['ad_file']['tmp_name']=="") {$obj->add_message("message","Please uplod an image");}
		if(trim($_POST['ad_desc'])=="") {$obj->add_message("message","Please enter Description");}
		
		$ad_file = "";
		
		if($_FILES['ad_file']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('ad_file',AD, 'jpg,png,jpeg,gif');
			if($error)
			{
				$obj->add_message("message",$error);
				$err=1;
			} else {
				$ad_file = $fileName;				
			}
		}		
		
		if($obj->get_message("message")=="")
		{
			$adD = array();
			
			$adD['user_id'] = $_SESSION['user']['u_login_id'];
			
			$adD['ad_pck_name'] 	        = $obj->filter_mysql($_POST['pck_name']);
			$adD['ad_comp_name']  	        = $obj->filter_mysql($_POST['comp_name']);
			$adD['ad_contact_name']         = $obj->filter_mysql($_POST['contact_name']);
			$adD['ad_contact_phone']  		= $obj->filter_mysql($_POST['contact_num']);
			$adD['ad_contact_address']      = $obj->filter_mysql($_POST['contact_address']);
			$adD['ad_contact_email']      	= $obj->filter_mysql($_POST['contact_email']);			
					
			$adD['ad_subject']   			= $obj->filter_mysql($_POST['ad_subject']);
			$adD['ad_desc']   				= $obj->filter_mysql($_POST['ad_desc']);
			$adD['ad_photo']      			= $ad_file;

			$tId = $obj->insertData(TABLE_ADS,$adD);	
			
			
			$ammessage  .= "Dear Admin,".$_POST['comp_name']." has sent you an Ad request.<br><br>";
			$ammessage  .= "Package - ".$_POST['pck_name']."<br>";				
			$ammessage  .= "Company - ".$_POST['comp_name']."<br>";
			$ammessage  .= "Contact Name - ".$_POST['contact_name']."<br>";
			$ammessage  .= "Contact No - ".$_POST['contact_num']."<br>";
			$ammessage  .= "Address - ".$_POST['contact_address']."<br>";
			$ammessage  .= "Email - ".$_POST['contact_email']."<br>";
			$ammessage  .= "Subject - ".$_POST['ad_subject']."<br>";
			$ammessage  .= "Details - ".$_POST['ad_desc']."<br>";
			$ammessage  .= "Ad Image - <a href=".FURL.AD.$ad_file.">Downlaod</a><br><br>";
			

			$ammessage  .= MAIL_THANK_YOU; 
			
			$body  = $obj->mailBody($ammessage); 	 
			$from  = FROM_EMAIL_2;
			$to    = ADMIN_EMAIL_1;			 
			$subject = "Ad Request Submission";			
			$mailTH = $obj->sendMail_server($to,$subject,$body,$from,SITE_TITLE,$type,$_POST['contact_email'],"",""); 
			
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message","We have received your create ad request sucecssfully!");
			
			$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$_SESSION['user']['u_login_id']."'",1);
			
			$comp_ids = trim($obj->get_user_friend_ids($_SESSION['user']['u_login_id']));
			$obj->set_notification($comp_ids,"New Trip added by ".$userD['user_display_name'],"story-details?tId=".$tId);
			
			$obj->reDirect("create-ad");
		}
	
	}	 
	
	if(isset($_POST['career_submission']) && $_POST['career_submission']=='Yes')
	{
		
		$_SESSION['messageClass'] = "errorClass";
		if(trim($_POST['car_name'])=="") {$obj->add_message("message","Please enter name");}
		if(!$obj->alphaSpace($_POST['car_name'])) {$obj->add_message("message","Please provide alphabet only for name");}	
		
		if(trim($_POST['car_con_num'])=="") {$obj->add_message("message","Please enter contact number");}
		if(trim($_POST['car_email'])=="") {$obj->add_message("message","Please enter email");}
		if(!$obj->isEmail(trim($_POST['car_email']))) {$obj->add_message("message","Email Should Be Valid!");}
		
		if(trim($_POST['car_qual'])=="") {$obj->add_message("message","Please enter qualification");}
		if(trim($_POST['car_pos'])=="") {$obj->add_message("message","Please enter position applying for");}		
		
		if($_FILES['car_res']['tmp_name']=="") {$obj->add_message("message","Please uplod a resume");}

		
		$resume = "";
		
		if($_FILES['car_res']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('car_res',RESUME, 'doc,docx,pdf');
			if($error)
			{
				$obj->add_message("message",$error);
				$err=1;
			} else {
				$resume = $fileName;				
			}
		}		
		
		if($obj->get_message("message")=="")
		{
			$careerD = array();
			
			$careerD['user_id'] = $_SESSION['user']['u_login_id'];
			
			$careerD['car_name'] 	       		= $obj->filter_mysql($_POST['car_name']);
			$careerD['car_phone']  	        	= $obj->filter_mysql($_POST['car_con_num']);
			$careerD['car_email']         		= $obj->filter_mysql($_POST['car_email']);
			$careerD['car_qualification']  		= $obj->filter_mysql($_POST['car_qual']);
			$careerD['car_position']      		= $obj->filter_mysql($_POST['car_pos']);
		

			$adD['car_resume']      			= $resume;

			$obj->insertData(TABLE_CAREERS,$careerD);	
			
			
			$ammessage  .= "Dear Admin,".$_POST['car_name']." has sent you a career application form.<br><br>";
			$ammessage  .= "Name - ".$_POST['car_name']."<br>";				
			$ammessage  .= "Phone - ".$_POST['car_con_num']."<br>";
			$ammessage  .= "Email - ".$_POST['car_email']."<br>";
			$ammessage  .= "Qualification - ".$_POST['car_qual']."<br>";
			$ammessage  .= "Position - ".$_POST['car_pos']."<br>";
			$ammessage  .= "Resume - <a href=".FURL.RESUME.$resume.">Downlaod</a><br><br>";

			$ammessage  .= MAIL_THANK_YOU; 
			
			$body  = $obj->mailBody($ammessage); 	 
			$from  = FROM_EMAIL_2;
			$to    = ADMIN_EMAIL_1;			 
			$subject = "Career Application Submission";			
			$mailTH = $obj->sendMail_server($to,$subject,$body,$from,SITE_TITLE,$type,$_POST['car_email'],"",""); 
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message","We have received your career application details sucecssfully!");
			
			$obj->reDirect("careers");
		}
	
	}	 
	
	


}
  
?>
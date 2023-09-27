<?php 
if(strpos($_SERVER['HTTP_REFERER'],HOST)!==false)
{

if(isset($_POST['AdvSignUp']))
{	 
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_first_name'])=="") {$obj->add_message("message","Please Enter your Name");}
	if(!alphaSpace($_POST['user_first_name'])) {$obj->add_message("message","Please provide alphabet only for name");}	
	if(trim($_POST['user_checked'])=="") {$obj->add_message("message","Required I agree to the Terms of Use");}	
	if(trim($_POST['user_age_checked'])=="") {$obj->add_message("message","Required. I declare that I am 18 years old or over.");}	
	if(trim($_POST['user_email'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
	if(!$obj->isEmail(trim($_POST['user_email']))) {$obj->add_message("message","Email Should Be Valid!");}
	if(!$obj->isEmail(trim($_POST['user_email_cnf']))) {$obj->add_message("message","Confirm Email Should Be Valid!");}
	if(trim($_POST['user_email'])!=trim($_POST['user_email_cnf'])) {$obj->add_message("message","Your entered email addresses must match!");}	 
	if(trim($_POST['u_login_password'])=="") {$obj->add_message("message","Password Should Not Be Blank!");}
	 
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
	 
		if($obj->get_message("message")=="")
		{	 
			$email = isset($_POST['user_email']) ? strtolower(trim($_POST['user_email']))  : '';
			list ($emailuser, $emaildomain) = array_pad(explode("@", $email, 2), 2, null);			
			if($emaildomain=='gmail.com')
			{
				$gmail_cleartext = $obj->get_gmail_user_cleartext($_POST['user_email']);
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
		
		if($obj->get_message("message")=="")
		{
			$user_email = $obj->filter_mysql($_POST['user_email']);
			$sqlUS=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$user_email."' and u_login_status<>'Deleted'",1);
			if($sqlUS['u_login_user_email'])
			{
				$obj->add_message("message","Sorry! email id already exists. Please try another.");
			}
			$reg_email = $obj->filter_mysql($_POST['user_email']);	
			$email_domain_name = substr(strrchr($reg_email, "@"), 1);		 
			$domainEBL = $obj->selectData(TABLE_EMAIL_DOMAIN_BLACKLIST,"","edb_url like '%".$email_domain_name."%' and edb_status='Active'",1);
			if($domainEBL['edb_id'])
			{
				$obj->add_message("message","Your email provider is not supported by our system. Please try another email address.");
			}		
			
		}	
		

		if($obj->get_message("message")=="")
		{			
			$userlogArr['u_login_password']  	= password_hash($_POST['u_login_password'],PASSWORD_DEFAULT);
			$userlogArr['u_login_user_email'] 	= $obj->filter_mysql($_POST['user_email']);
			$userlogArr['u_login_created']	  	= CURRENT_DATE_TIME;
			$userlogArr['u_login_modified']   	= CURRENT_DATE_TIME;
			$userlogArr['u_login_attempt']   	= 0;
			$userlogArr['u_pass_update']   		= CURRENT_DATE_TIME;
			// $userlogArr['u_login_status']   	= "Active";
		 	$newUserId = $obj->insertData(TABLE_USER_LOGIN,$userlogArr);			
			$_SESSION['new_user_id']    		= $newUserId;	
			$_SESSION['new_user_email'] 		= $obj->filter_mysql($_POST['user_email']);		
			$userlArr['u_login_id']      		= $newUserId;
			$userlArr['user_referrer']   		= $obj->filter_mysql($_SESSION['aff_ref_id']);
			$userlArr['user_type']   			= 'advertiser';			
			$userlArr['user_email']      		= $obj->filter_mysql($_POST['user_email']);
			$userlArr['user_first_name'] 		= $obj->filter_mysql($_POST['user_first_name']);
			$userlArr['user_reg_ip']    		= $ip;
			$userlArr['user_reg_browser']    	= $_SERVER['HTTP_USER_AGENT'];
			$userlArr['user_created']    		= CURRENT_DATE_TIME;
			$userlArr['user_modified']   		= CURRENT_DATE_TIME;
			// $userlArr['user_status']   			= "Active";
		 	$obj->insertData(TABLE_USER,$userlArr);
			
			$AuthArr['user_id'] 			= $newUserId;		 
			$AuthArr['auth_created']  		= CURRENT_DATE_TIME;
			$AuthArr['auth_modified'] 		= CURRENT_DATE_TIME;
		 	$obj->insertData(TABLE_USER_AUTH,$AuthArr);	
			$obj->monitor_login_ads($newUserId,$ip);
			////////////joining Reward//////////////
			 
			$setStat =$obj->selectData(TABLE_SETTINGS,"set_join_asimi_status,set_join_asimi,set_join_ban_imp,set_join_mint_imp","set_id=1",1);
			if($setStat['set_join_asimi_status']=='Active')
			{ 			
				if($setStat['set_join_asimi'] > '0') 
				{ 
					$JRArr['user_id'] = $newUserId;		 
					$JRArr['wall_type']='j';	 
					$JRArr['wall_asimi']= $setStat['set_join_asimi'];
					$JRArr['wall_created']=CURRENT_DATE_TIME;
					$JRArr['wall_modified']=CURRENT_DATE_TIME;
					$obj->insertData(TABLE_USER_WALLET,$JRArr);		
				}
				if(!empty($setStat['set_join_ban_imp'])) 
				{ 
					$hrzArr['user_id'] 				= $newUserId;
					$hrzArr['ic_impression'] 		= $setStat['set_join_ban_imp']/2;
					$hrzArr['ic_imp_type'] 			= 'banhrz';
					$hrzArr['ic_allocate_type'] 	= 'r';
					$hrzArr['ic_created']=CURRENT_DATE_TIME;			 
					$obj->insertData(TABLE_IMPRESSION_CREDIT,$hrzArr);
					
					$sqrArr['user_id'] 				= $newUserId;
					$sqrArr['ic_impression'] 		= $setStat['set_join_ban_imp']/2;
					$sqrArr['ic_imp_type'] 			= 'bansqr';
					$sqrArr['ic_allocate_type'] 	= 'r';
					$sqrArr['ic_created']=CURRENT_DATE_TIME;			 
					$obj->insertData(TABLE_IMPRESSION_CREDIT,$sqrArr);
				}
				if(!empty($setStat['set_join_mint_imp'])) 
				{ 
					$mintArr['user_id'] 			= $newUserId;
					$mintArr['ic_impression'] 		= $setStat['set_join_mint_imp'];
					$mintArr['ic_imp_type'] 		= 'minter';
					$mintArr['ic_allocate_type'] 	= 'r';
					$mintArr['ic_created']=CURRENT_DATE_TIME;			 
					$obj->insertData(TABLE_IMPRESSION_CREDIT,$mintArr);
				}
			
			} 	
			 	
			 
			$userAffData = $obj->selectData(TABLE_USER,"","u_login_id='".$_SESSION['aff_ref_id']."' and user_status<>'Deleted'",1);
			if($userAffData['u_login_id'])
			{
				$reg_message  = "Hi <b>".$userAffData['user_first_name'].' '.$userAffData['user_last_name']."</b><br>";
				$reg_message .= "Congratulations! You have a new Hashing Ad Space referral.<br><br>";
				$reg_message .= "Here are their details:<br><br>";		    
				$reg_message .= "Name: ".$_POST['user_first_name']."<br><br>";		 
				$reg_message .= "Email : ".$_POST['user_email']."<br /><br />";
				$reg_message .= "You'll earn commissions on any products they purchase on our site for life! <br /><br />";
				$reg_message .= "That's exciting!<br><br>";
				$reg_message .= "Tip: Following up with referrals within 24 hours is proven to drastically improve sales conversions.<br /><br />";
				$reg_message .= "Make sure your new referral has the information they need to get earning/advertising in Hashing Ad Space. It will result more commissions for you.<br /><br />";
				$reg_message .= "Keep up the good work!<br /><br /><br /><br />";
				
				$reg_message .="<br>Thank You<br>".MAIL_THANK_YOU;
				
				$reg_message .= '<p style="border-top:1px solid #808080"></p>';  
				$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
				$reg_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($_SESSION['aff_ref_id']).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
				
				$body = $obj->mailBody($reg_message);  			
				$from = FROM_EMAIL_2;
				$to   = $userAffData['user_email'];			
				$subject = "You have a new referral.";			
				$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);
				
			}
		    
		     
			
			$ammessage  = "<b> Hi ".ucfirst($_POST['user_first_name'])."</b><br><br>";			
			$ammessage  .= "Welcome to Hashing Ad Space! <br><br>";
			$ammessage  .= "To get started, please click below to confirm your email and activate your account. <br><br>";			
			$ammessage  .= "<a href='".FURL."advertiser/activation.php?uactid=".$obj->encryptPass($newUserId)."'>CLICK HERE TO ACTIVATE YOUR ACCOUNT </a> <br>";
			$ammessage  .= "Please Note:"; 
			$ammessage  .= "You have received this message to confirm your subscription to Hashing Ad Space."; 
			$ammessage  .= "If you have received this email in error, please disregard it. No action needs to be taken and we will not contact you again."; 
			$ammessage  .= "If you did intend to register with Hashing Ad Space, click the link above to activate your account."; 
			$ammessage  .= "We hope to see you in Hashing Ad Space!"; 
			$ammessage  .= "The Hashing Ad Space Team."; 
			$ammessage  .= "You can unsubscribe at any time by clicking Unsubscribe below, or by contacting our support department."; 			
			$ammessage .="<br><br>See you there!<br><br>".MAIL_THANK_YOU;
			$ammessage .="<br><br>"; 
			$ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';	 	
			
			$ammessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$ammessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($newUserId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';		
			
		 	$body2 = $obj->mailBody($ammessage); 
			$from2 = FROM_EMAIL_2;
			$to2   = $_POST['user_email'];			 
			$subject2 = "Activate your Hashing Ad Space account";			
			$obj->sendMailSES($to2,$subject2,$body2,$from2,SITE_TITLE,$type);	
			 
			$_SESSION['messageClass'] = "successClass";			 
			$malink  = "<a href='".FURL."advertiser/missing_activation.php'>CLICK HERE</a>";				
			$obj->add_message("message","Congratulations. You have successfully registered with Hashing Ad Space. An account activation email has been sent to your registered email-id. Please click the link provided in the email to activate your account.<br><br>".$malink." IF YOU DID NOT RECEIVE YOUR EMAIL. ");			
			if(!empty($_SESSION['baff_username']))  $obj->reDirect("advertising_register.php?ref=".$_SESSION['baff_username']);	
			else $obj->reDirect("advertising_register.php");	
			 
		}
		
}

if(isset($_POST['AdvSignIn']))
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['u_login_password'])=="") {$obj->add_message("message","Password Should Not Be Blank!");}
	if(trim($_POST['u_login_user_email'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
	if(!$obj->isEmail(trim($_POST['u_login_user_email']))) {$obj->add_message("message","Email Should Be Valid!");}	 
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
	
	$log_email = $obj->filter_mysql($_POST['u_login_user_email']);
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
		if($sqlUS2)
		{
			if(password_verify($_POST['u_login_password'],$sqlUS2['u_login_password']))
			{ 
				$malink  = "<a href='".FURL."login.php?uactid=".$obj->encryptPass($sqlUS2['u_login_id'])."'>CLICK HERE</a>";
				$obj->add_message("message",'Your account has been deactivated. Please '.$malink.' to receive your activation email and reactivate your account.'); 
			}
			else $obj->add_message("message","Your account has been suspended. Please contact support.");
		}
	}				
  
	if($obj->get_message("message")=="")
	{	 
		//$user_login = $obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$log_email."' and u_login_status='Active' and u_suspend_status='n' and user_suspend_comm_status='n'",1);
		$user_login = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","","ul.u_login_id=u.u_login_id and ul.u_login_user_email='".$log_email."' and ul.u_login_status='Active' and ul.u_suspend_status='n' and ul.user_suspend_comm_status='n' ",1); 
		// pre($user_login); exit; 
		if($user_login)
		{ 
			if(password_verify($_POST['u_login_password'],$user_login['u_login_password']))
			{				 
				$dataEr = $obj->selectData(TABLE_EMERGENCY_RESET,"","user_id='".$user_login['u_login_id']."' and er_setup='n' order by er_id desc",1);
				if($dataEr['er_setup']=='n')
				{ 
					$_SESSION['er_setup'] 	= $dataEr['er_id'];
					$_SESSION['user_login_id'] = $user_login['u_login_id'];
					$obj->reDirect("login.php"); exit;
				}				 
				  
				$loginAtmp['u_login_attempt'] = $user_login['u_login_attempt']+1;
				$loginAtmp['u_login_modified'] = CURRENT_DATE_TIME;	
				//$ga = new GoogleAuthenticator();
				//$secret = $ga->createSecret();
				
				if($user_login['u_login_secret']=="")
				{
					$loginAtmp['u_login_secret'] = $secret;
					$obj->updateData(TABLE_USER_LOGIN,$loginAtmp,"u_login_id='".$user_login['u_login_id']."'");	
				}
				
				if($user_login['u_login_secret']=="")
				{
					$loginAtmp['u_login_secret'] = $secret;
					$obj->updateData(TABLE_USER_LOGIN,$loginAtmp,"u_login_id='".$user_login['u_login_id']."'");	
				}
				
				$today    	= date("Y-m-d");		 
				$lapD   = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_date='".$today."' and (lap_url1_status='y' or lap_url2_status='y') and lap_pstatus ='p'",1);				
				if($lapD['lap_id'])
				{	
					if($lapD['lap_url1_status'] =='y' && $lapD['lap_url2_status'] =='y')
					{
						 $atot_view = 	$lapD['lap_url1_tot_view'];
						 $btot_view = 	$lapD['lap_url2_tot_view'];
						 if($atot_view <= $btot_view) $login_ad_url = 1; 
						 else if($atot_view > $btot_view) $login_ad_url = 2;					 					 
					} else if($lapD['lap_url1_status'] =='y' && $lapD['lap_url2_status'] =='n')
					{
						$login_ad_url = 1; 
					} else if($lapD['lap_url1_status'] =='n' && $lapD['lap_url2_status'] =='y')
					{
						$login_ad_url = 2; 
					}
					 
					$_SESSION['login_ad_lap_id']   	= $lapD['lap_id'];
					$_SESSION['login_ad_url']   	= $login_ad_url;
					
					if($login_ad_url == '1')
					{
						$lapArr['lap_url1_tot_view'] = $lapD['lap_url1_tot_view']+1; 
					}	
					else if($login_ad_url == '2')
					{
						$lapArr['lap_url2_tot_view'] = $lapD['lap_url2_tot_view']+1; 	
					} 
					$obj->updateData(TABLE_LOGIN_AD_PACKAGE,$lapArr,"lap_id='".$lapD['lap_id']."'");
					
				}
				 
					$current_login_stake = $obj->get_member_login_stake($user_login['u_login_id']);
					if(!empty($current_login_stake))
					{		
						$laspD = $obj->selectData(TABLE_LOGIN_AD_STAKER_PACKAGE,"","lasp_date='".$today."' and (lasp_url1_status='y' or lasp_url2_status='y') and lasp_pstatus ='p'",1);				
						if($laspD['lasp_id'])
						{	
							if($laspD['lasp_url1_status'] =='y' && $laspD['lasp_url2_status'] =='y')
							{
								$atot_view = 	$laspD['lasp_url1_tot_view'];
								$btot_view = 	$laspD['lasp_url2_tot_view'];
								if($atot_view <= $btot_view) $login_ad_str_url = 1; 
								else if($atot_view > $btot_view) $login_ad_str_url = 2;					 					 
							} else if($laspD['lasp_url1_status'] =='y' && $laspD['lasp_url2_status'] =='n')
							{
								$login_ad_str_url = 1; 
							} else if($laspD['lasp_url1_status'] =='n' && $laspD['lasp_url2_status'] =='y')
							{
								$login_ad_str_url = 2; 
							}
							 
							$_SESSION['login_ad_lasp_id']   	= $laspD['lasp_id'];
							$_SESSION['login_ad_str_url']   	= $login_ad_str_url;
							
							if($login_ad_str_url == '1')
							{
								$lapArr['lasp_url1_tot_view'] = $laspD['lasp_url1_tot_view']+1; 
							}	
							else if($login_ad_str_url == '2')
							{
								$lapArr['lasp_url2_tot_view'] = $laspD['lasp_url2_tot_view']+1; 	
							} 
							$obj->updateData(TABLE_LOGIN_AD_STAKER_PACKAGE,$lapArr,"lasp_id='".$laspD['lasp_id']."'");
							
						} 

						$_SESSION['session_login_str_time']   = time();			
						$_SESSION['login_ad_str_view_status'] = 'n';
					
					} 
				 	
				
				$_SESSION['session_login_time']   = time();	
				$_SESSION['login_ad_view_status'] = 'n';
				$_SESSION['user'] = $user_login;
				$_SESSION['user']['ip'] = $ip;		
				$_SESSION['user']['notification'] = 'n'; 
				
				$monlID = $obj->monitor_login_ads($user_login['u_login_id'],$ip);
				$_SESSION['monitor_login_id']= $monlID;				
				
				$site_set=$obj->selectData(TABLE_SETTINGS,"","set_id=1",1);
				 
				if($site_set['set_google_auth']=='y')
				{
					$_SESSION['user']['2fa_chk_status'] = 'n';
				}
				else
				{
					$_SESSION['user']['2fa_chk_status'] = 'y';
				}		  
						 
				
				if($user_login['u_login_auth']=='y')
				{
					if($_SESSION['user']['2fa_chk_status']=='n')
					{
						$obj->reDirect("two_step_auth.php");
						exit;					
					}
					else
					{						 
						$obj->reDirect("dashboard.php");
						exit;						 
					}
				}
				else
				{					 
					$obj->reDirect("dashboard.php");
					exit;
				}	 
				
				// $obj->reDirect("dashboard.php");				
			}
			else
			{			
				$msges ='Incorrect login password';
				$type = 'f';
				$obj->monitor_making_changes($user_login['u_login_id'],$ip,$msges,$type);			
				$loginBtmp['u_login_failed'] = $user_login['u_login_failed'] + 1;
				$obj->updateData(TABLE_USER_LOGIN,$loginBtmp,"u_login_id='".$user_login['u_login_id']."'");	
				$_SESSION['messageClass'] = 'errorClass';
				$obj->add_message("message","Incorrect login details. Please try again.");
			}
		}
		else
		{ 			  
			$msges ='Incorrect login email';			 
			$type = 'f';
			$obj->monitor_making_changes('0',$ip,$msges,$type);						 
			$_SESSION['messageClass'] = 'errorClass';
			$obj->add_message("message","Incorrect login details. Please try again.");
			$obj->reDirect("login.php");
		}
	}
	
}

if(isset($_POST['authVerify']) && $_POST['authVerify']='Verify')
{
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['auth_code'])=="") {$obj->add_message("message","Google Code Should Not Be Blank!");}
	if(!ctype_digit($_POST['auth_code'])) {$obj->add_message("message","Google Code Should be numeric!");}
	if($obj->get_message("message")=="")
	{
		$userId = $_SESSION['user']['u_login_id'];
		$userLD = $obj->selectData(TABLE_USER_LOGIN,"u_login_user_email,u_login_secret","u_login_id='".$userId."' and u_login_status='Active' and u_suspend_status='n'",1);

		$secret = $userLD['u_login_secret'];
		$code   = $_POST['auth_code'];
		$ga = new GoogleAuthenticator();
		$checkResult = $ga->verifyCode($secret,$code,2);    // 2 = 2*30sec clock tolerance
	
		if ($checkResult) 
		{
			$_SESSION['user']['2fa_chk_status'] = 'y';
			$obj->reDirect("dashboard.php");
			exit;
		} 
		else 
		{
			 
			$msges ='Google Auth verification failed.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);
			 
			
			$_SESSION['messageClass'] = 'errorClass';
			$obj->add_message("message","Google Auth verification failed. Please try again.");
			$obj->reDirect("two_step_auth.php");
			exit;
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
				
				$arrRPU['rp_time'] = date("Y-m-d H:i:s");
				$arrRPU['rp_ip']   = $ip;
				$obj->updateData(TABLE_RECOVER_PASS,$arrRPU,"rp_id='".$user_rp_search['rp_id']."'");
				$rp_id = $user_rp_search['rp_id'];
					
			}
			else
			{
				$arrRPI['rp_user'] = $sqlS['u_login_id'];
				$arrRPI['rp_ip']   = $ip;
				$arrRPI['rp_time'] = date("Y-m-d H:i:s");
				$rp_id = $obj->insertData(TABLE_RECOVER_PASS,$arrRPI);
				
			}
			$userD=$obj->selectData(TABLE_USER,"","u_login_id='".$sqlS['u_login_id']."'",1);
			$userLD=$obj->selectData(TABLE_USER_LOGIN,"u_login_user_email","u_login_id='".$sqlS['u_login_id']."'",1);
		  
			$forgot_mail_message  = "<b> Dear ".$userD['user_first_name']."</b>, your reset password reset link provided below.<br><br>";			
			$forgot_mail_message .= "Please <a href='".FURL."advertiser/reset_password.php?uactid=".$obj->encryptPass($rp_id)."'>Click Here </a> to change your password. <br>";
			$forgot_mail_message .= "Note: The above link is valid for one hour only";
			$forgot_mail_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;
			
			$forgot_mail_message .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$forgot_mail_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($sqlS['u_login_id']).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
		    $body = $obj->mailBody($forgot_mail_message); 
			$from = FROM_EMAIL_2;
			$to   = $userLD['u_login_user_email'];	  
			$subject = "Forgot Password - ".$sqlS['user_first_name'];		     
			$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);	
				
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have sent your password reset instructions to your email.");					
			$obj->reDirect('forgot_password.php');
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
		$rp_id = $obj->filter_numeric($obj->retrievePass($_REQUEST['uactid'])); 
		$user_rpD = $obj->selectData(TABLE_RECOVER_PASS,"","rp_id='".$rp_id."'",1);
		
		if($user_rpD['rp_user']!='')
		{
				if(date("Y-m-d H:i:s")-strtotime($user_rpD['rp_time'])>3600)
				{
					$obj->add_message("message","Recovery link expired! Please submit the form below and request a new link.");
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('forgot_password.php');
					exit;
				}
				else if($user_rpD['rp_ip']!=$ip)
				{
					$obj->add_message("message",'Unauthorized request. It seems your IP address or device may have changed during your password reset. Please try and "recover password" again ensuring you use the same device and are connected to a stable internet connection.');
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('forgot_password.php');
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
			$obj->reDirect('forgot_password.php');
			exit;
		}	 
		$new_password = password_hash($_POST['u_login_password'],PASSWORD_DEFAULT);
		$pass_matched=$obj->selectData(TABLE_USER_LOGIN,"","u_login_id=".$u_login_id."",1);
		// pre($pass_matched); exit;
		if($pass_matched)
		{			 
			
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
			$obj->reDirect('login.php');
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
 
 
if(isset($_POST['editPro']))
{ 	 
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_first_name'])=="") {$obj->add_message("message","Please Enter your First Name");}
	if(trim($_POST['user_last_name'])=="") {$obj->add_message("message","Please Enter your Last Name");}
	if(!alphaSpace($_POST['user_first_name'])) {$obj->add_message("message","Please provide alphabet only for First Name");}
	if(!alphaSpace($_POST['user_last_name'])) {$obj->add_message("message","Please provide alphabet only for Last Name");}		 
	//if(trim($_POST['user_wallet_address'])=="") {$obj->add_message("message","Please Enter your Wallet Address");}
	if(trim($_POST['user_dob'])=="") {$obj->add_message("message","Please select your Date Of Birth");}
	if(trim($_POST['user_country'])=="") {$obj->add_message("message","Country Should Not Be Blank!");}
	if(trim($_POST['auth_user_pin'])=="") {$obj->add_message("message","2 step auth (Secondary pin) Should Not Be Blank!");}	
	if(ctype_digit($_POST['auth_user_pin'])==false || $_POST['auth_user_pin']<=0) {$obj->add_message("message","2 step auth (Secondary pin) Should Be Numeric!");}		 
	 
	$dateString=$_POST['user_dob'];
	$tot_years = round((time()-strtotime($dateString))/(3600*24*365.25));
	  
	if($tot_years < 18)
	{ 
		$obj->add_message("message","Sorry! you are not eligible to operate an account on Hashing Ad Space.");
		//$obj->reDirect("dashboard.php");
	}	 
			 
	if($obj->get_message("message")=="")
	{		
		$userId = $_SESSION['user']['u_login_id'];			 
		$userD = $obj->selectData(TABLE_USER,"user_id,u_login_id","u_login_id='".$userId."' and user_status='Active'",1);				
		
		$edipArr['user_first_name']	= $obj->filter_mysql($obj->filter_alphabet($_POST['user_first_name']));
		$edipArr['user_last_name']	= $obj->filter_mysql($_POST['user_last_name']);
		$edipArr['user_dob']		= $obj->filter_mysql($_POST['user_dob']);
		$edipArr['user_country']	= $obj->filter_mysql($_POST['user_country']);			 
		$edipArr['user_modified']	= CURRENT_DATE_TIME;			
		$sql=$obj->updateData(TABLE_USER,$edipArr,"u_login_id='".$userId."'");
		 
		
		$msges ='Profile update.';			 
		$type = 't';
		$obj->monitor_making_changes($userId,$ip,$msges,$type);
		
		$userAD=$obj->selectData(TABLE_USER_AUTH,"","user_id='".$userId."' and auth_status='Active'",1);
		
		if(empty($userAD['user_id']))
		{ 	
			$athdata['user_id']			= $_SESSION['user']['u_login_id'];		
			$athdata['auth_user_pin'] 	= password_hash($_POST['auth_user_pin'],PASSWORD_DEFAULT);	
			$athdata['auth_created']	= CURRENT_DATE_TIME;
			$athdata['auth_modified']	= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_USER_AUTH,$athdata);
		} 
		else
		{
			$updateAuth['auth_user_pin'] = password_hash($_POST['auth_user_pin'],PASSWORD_DEFAULT);					 
			$updateAuth['auth_modified']	= CURRENT_DATE_TIME;					 
			$obj->updateData(TABLE_USER_AUTH,$updateAuth,"auth_id='".$userAD['auth_id']."'");	
		}	
		
		$full_name = $_POST['user_first_name'].' '.$_POST['user_last_name'];
		// $obj->getResponse_subscription($full_name,$_SESSION['user']['u_login_user_email']); 
			 
		$reg_message  = " <b>".$_POST['user_first_name'].' '.$_POST['user_last_name']."</b>, your Hashing Ad Space profile has just been updated.<br><br>";
		$reg_message .= "First Name: ".$_POST['user_first_name']."<br>";
		$reg_message .= "Last Name: ".$_POST['user_last_name']."<br>";
		$reg_message .= "Date Of Birth: ".$_POST['user_dob']."<br>";
		$reg_message .= "Country or residence : ".$obj->getCountry($_POST['user_country'])."<br>";
		//$reg_message .= "Wallet Address : ".$_POST['user_wallet_address']."<br><br>";			
		$reg_message .= "If these changes were made by you, no further action is required. <br><br>";
		$reg_message .= "If these changes were NOT made by you, login, change your password, and contact support immediately. <br>";			
		$reg_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;

		$reg_message .= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$reg_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
		$body = $obj->mailBody($reg_message);	 
		$from = FROM_EMAIL_2;
		$to   = $_SESSION['user']['u_login_user_email'];			
		$subject = "Profile Updated By ".$_POST['user_first_name'];			
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","You have successfully updated your profile.");
		$obj->reDirect("dashboard.php");
	}
}
 
 
if(isset($_POST['editProfile']))
{ 	 
	$_SESSION['messageClass'] = "errorClass";
	$userId = $_SESSION['user']['u_login_id'];	
	$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
	if($userD['user_first_name'] =='')
	{
		if(trim($_POST['user_first_name'])=="") {$obj->add_message("message","Please Enter Your First Name");}
		if(!alphaSpace($_POST['user_first_name'])) {$obj->add_message("message","Please provide alphabet only for First Name");}	
	}
	if($userD['user_last_name'] =='')
	{
		if(trim($_POST['user_last_name'])=="") {$obj->add_message("message","Please Enter Your Last Name");}
		if(!alphaSpace($_POST['user_last_name'])) {$obj->add_message("message","Please provide alphabet only for Last Name");}
	}
	 
	if(trim($_POST['user_dob'])=="" && $userD['user_dob'] =="") {$obj->add_message("message","Please select Your Date Of Birth");}
	if(trim($_POST['user_country'])=="" && $userD['user_country'] =="") {$obj->add_message("message","Country Should Not Be Blank!");}
 
	if(trim($_POST['user_state'])=="") {$obj->add_message("message","State Should Not Be Blank!");}
	if(trim($_POST['user_city'])=="") {$obj->add_message("message","City Should Not Be Blank!");}
	if(trim($_POST['user_zip'])=="") {$obj->add_message("message","Zip Should Not Be Blank!");}
	if(trim($_POST['user_address_1'])=="") {$obj->add_message("message","Address Should Not Be Blank!");}
	if(trim($_POST['auth_user_pin'])=="") {$obj->add_message("message","2nd step auth pin Should Not Be Blank!");}		
	
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
		   $obj->reDirect("edit_proifle.php");
		}
	}		
	$userId = $_SESSION['user']['u_login_id'];	
	if($obj->get_message("message")=="")
	{
		$userAD = $obj->selectData(TABLE_USER_AUTH,"","user_id='".$userId."' and auth_status='Active'",1);		 
		if(!password_verify($_POST['auth_user_pin'],$userAD['auth_user_pin']))
		{
			$obj->add_message("message","Sorry You have entered an incorrect Secondary Pin, please enter the correct Pin.");
			$msges ='Update profile failed. Your 2nd step auth pin doesn\'t match.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);
		}
		
		$dateString=$_POST['user_dob'];
		$tot_years = round((time()-strtotime($dateString))/(3600*24*365.25));		  
		if($tot_years < 18)
		{ 
			$obj->add_message("message","Sorry! you are not eligible to create an account on Hashing Ad Space.");
			$obj->reDirect("dashboard.php");
		}		
	}			
	 
	if($obj->get_message("message")=="")
	{ 					 
		   
		if($userD['user_first_name'] =='') 	$userArru['user_first_name']	= $obj->filter_mysql($obj->filter_alphabet($_POST['user_first_name']));
		if($userD['user_last_name'] =='') 	$userArru['user_last_name']		= $obj->filter_mysql($_POST['user_last_name']);
		if($userD['user_dob'] =='') 		$userArru['user_dob']			= $obj->filter_mysql($_POST['user_dob']);
		if($userD['user_country'] =='') 	$userArru['user_country']		= $obj->filter_mysql($_POST['user_country']);
		$userArru['user_state']			= $obj->filter_mysql($_POST['user_state']);
		$userArru['user_city']			= $obj->filter_mysql($_POST['user_city']);
		$userArru['user_zip']			= $obj->filter_mysql($_POST['user_zip']);
		$userArru['user_address_1']		= $obj->filter_mysql($_POST['user_address_1']);
		$userArru['user_modified']		= CURRENT_DATE_TIME;			
		$sql=$obj->updateData(TABLE_USER,$userArru,"user_id='".$userD['user_id']."'");		
		 
		$msges ='Profile update.';			 
		$type = 't';
		$obj->monitor_making_changes($userId,$ip,$msges,$type); 
		//$uLog['u_login_auth'] = $_POST['user_auth'];
		//$obj->updateData(TABLE_USER_LOGIN,$uLog,"u_login_id='".$userId."'");
		$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1); 
		$reg_message  = "<b>".$userD['user_first_name'].' '.$userD['user_last_name']."</b>, your Hashing Ad Space profile has just been updated.<br><br>";
		$reg_message .= "First Name: ".$userD['user_first_name']."<br>";
		$reg_message .= "Last Name: ".$userD['user_last_name']."<br>";
		$reg_message .= "Date Of Birth: ".$userD['user_dob']."<br>";
		$reg_message .= "Country : ".$obj->getCountry($userD['user_country'])."<br>";	
		$reg_message .= "State : ".$userD['user_state']."<br>";	
		$reg_message .= "City : ".$userD['user_city']."<br>";	
		$reg_message .= "Zip Code : ".$userD['user_zip']."<br>";	
		$reg_message .= "Address : ".$userD['user_address_1']."<br><br>";
		$reg_message .= "If these changes were made by you, no further action is required. <br><br>";
		$reg_message .= "If these changes were NOT made by you, login, change your password, and contact support immediately. <br>";			
		$reg_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;	
		
		$reg_message .= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$reg_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
		
		$body = $obj->mailBody($reg_message);  	
		$from = FROM_EMAIL_2;
		$to   = $_SESSION['user']['u_login_user_email'];			
		$subject = "Profile Updated By ".$userD['user_first_name'];			
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);

		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","You have successfully updated your profile.");
		$obj->reDirect("dashboard.php");
	}
}

if(isset($_POST['recover_pin']))
{  
	$_SESSION['messageClass'] = "errorClass";

	if(trim($_POST['user_remail'])=="") {$obj->add_message("message","Email Should Not Be Blank!");}
	if(!$obj->isEmail(trim($_POST['user_remail']))) {$obj->add_message("message","Email Should Be Valid!");}

	if($obj->get_message("message")=="")
	{
		$user_remail = $obj->filter_mysql($_POST['user_remail']);
		$sqlS=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$user_remail."' and u_login_status='Active'",1);
		if(!$sqlS['u_login_id'])
		{
			$obj->add_message("message","Sorry! email id does't exists.");
			$obj->reDirect('recover_pin.php');
			exit;
		}
		if($_SESSION['user']['u_login_user_email'] != $user_remail)
		{
			$obj->add_message("message","Sorry! Please provide your registerd Email-ID, you are currently logged in.");
			$obj->reDirect('recover_pin.php');
			exit;
		}	
	}	
		
	if($obj->get_message("message")=="")
	{		
			$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']); 
			$userD=$obj->selectData(TABLE_USER,"","u_login_id='".$sqlS['u_login_id']."'",1);
			$userLD=$obj->selectData(TABLE_USER_LOGIN,"u_login_user_email","u_login_id='".$userId."'",1);
		  
			$rpin_mail_message  = "<b> Dear ".$userD['user_first_name']."</b>, your secondary pin reset link provided below.<br><br>";			
			$rpin_mail_message .= "Please <a href='".FURL."reset_pin.php?uactid=".$obj->encryptPass($sqlS['u_login_id'])."'>Click Here </a> to change your secondary pin.<br>";
			$rpin_mail_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;
			
			$rpin_mail_message .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$rpin_mail_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
		
		    $body = $obj->mailBody($rpin_mail_message);	 
			$from = FROM_EMAIL_2;
			$to   = $userLD['u_login_user_email'];	  
			$subject = "Recover Your Pin - ".$sqlS['user_first_name'];		     
			$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);	
				
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Please check your Email-ID to reset your secondary pin.");					
			$obj->reDirect('recover_pin.php');
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

		$change_mail_message .= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$change_mail_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';

		$body = $obj->mailBody($change_mail_message);				 
		$from = FROM_EMAIL_2;
		$to   = $userP['u_login_user_email'];		 
		$subject = "You have successfully changed your password";	
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);		
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","Password changed successfully. Thanks");		
	}	
	$obj->reDirect("change_password.php");
}

if (isset($_POST['changeSecPass'])) 
{
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['auth_user_pin_opass'])=="") {$obj->add_message("message","Old password should not be blank!");}
	if(trim($_POST['auth_user_pin_npass'])=="") {$obj->add_message("message","New password should not be blank!");}
	if(trim($_POST['auth_user_pin_rpass'])=="") {$obj->add_message("message","Retype password should not be blank!");}
	if(trim($_POST['auth_user_pin_npass'])!=trim($_POST['auth_user_pin_rpass'])) {$obj->add_message("message","New Password & Retype password should be same!");}
	if(trim($_POST['auth_user_pin_opass'])==trim($_POST['auth_user_pin_npass'])) {$obj->add_message("message","New and old password cannot be same!");}
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
	
	
	if($obj->get_message("message")=="")
	{
		$userId = $_SESSION['user']['u_login_id'];
		$userP = $obj->selectData(TABLE_USER_AUTH,"","user_id='".$userId."'",1);
		//if($userP['auth_user_pin']!=$_POST['auth_user_pin_opass'])
		if(!password_verify($_POST['auth_user_pin_opass'],$userP['auth_user_pin']))
		{
			$obj->add_message("message","Incorrect old secondary password, Please try again.");				 
			$msges ='Incorrect old secondary password.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);
		}
	}
	
	if($obj->get_message("message")=="") {
		$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."'",1);
		$userP = $obj->selectData(TABLE_USER_AUTH,"","user_id='".$userId."'",1);		 
		$newPass['auth_user_pin'] = password_hash($_POST['auth_user_pin_npass'],PASSWORD_DEFAULT);
		$obj->updateData(TABLE_USER_AUTH,$newPass,"auth_id='".$userP['auth_id']."'");	
		 
		$msges ='Secondary password changed successfully.';			 
		$type = 't';
		$obj->monitor_making_changes($userId,$ip,$msges,$type);

		$change_mail_message  = "<b> Hello ".$userD['user_first_name']."</b>,<br><br>";	
		$change_mail_message .= "Your Secondary Pin has been successfully changed. <br><br>";
		$change_mail_message .= "We take the security of your account seriously, and thank you for changing your Secondary Pin regularly to safeguard your account. <br><br>";
		$change_mail_message .= "If you did not change your Secondary Pin, please contact support Immediately.<br><br>";
		$change_mail_message .= "Always feel free to reach out with any questions you may have.<br><br>";
		$change_mail_message .= "Maximize Your Online Business Success!<br><br>";	
		 							
		$change_mail_message .="<br><br>Thank You<br>".MAIL_THANK_YOU;		
		
				$change_mail_message .= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$change_mail_message .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';


		$body = $obj->mailBody($change_mail_message);				 
		$from = FROM_EMAIL_2;
		$to   = $_SESSION['user']['u_login_user_email'];		 
		$subject = "You have successfully changed your Pin";			
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);		
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","Secondary Password changed successfully. Thanks");		
	}	
	$obj->reDirect("change_password.php");
}
   
  
if(isset($_POST['AddWallAdd']))
{ 	 //pre($_POST); exit;
	$_SESSION['message'] = '';
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_wallet_address'])=="") {$obj->add_message("message","Please Enter your Wallet Address.");}
		if(trim($_POST['user_wallet_address'])=="3P3iU6DhBkFYuRsWS1Wd4DZyuC7MuELoJDh") {$obj->add_message("message",'This wallet address blocked in system.');}
	
	if(trim($_POST['user_wallet_address'])==$_POST['admin_wallet_address']) {$obj->add_message("message",'This is not your correct Asimi address. Please refer back to your Waves Wallet and click on the "Asimi - Receive" address to find your unique and correct Asimi wallet address');}
	//if(!is_int(trim($_POST['user_wallet_address']))) {$obj->add_message("message","Please check your your Wallet Address!");}	
	if(trim($_POST['auth_user_pin'])=="") {$obj->add_message("message","2nd step auth pin Should Not Be Blank!");}		
	
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
		   $obj->reDirect("update_wallet_address.php");
		   exit;
		}
	}		
	
	if($obj->get_message("message")=="")
	{	
		$myStr = trim($_POST['user_wallet_address']);
		$res_char = substr($myStr, 0, 2);		 
		if($res_char !="3P")
			{
				$obj->add_message("message","Please provide correct Asimi address.");
				$obj->reDirect("update_wallet_address.php");
				exit;
			} 		
	}
	
	if($obj->get_message("message")=="")
	{
		$userAD = $obj->selectData(TABLE_USER_AUTH,"","user_id='".$_SESSION['user']['u_login_id']."' and auth_status='Active'",1);
		//if($userAD['auth_user_pin'] != $_POST['auth_user_pin'])
		if(!password_verify($_POST['auth_user_pin'],$userAD['auth_user_pin']))
		{
			
			$obj->add_message("message","Sorry You have entered an incorrect Secondary Pin, please enter the correct Pin.");
			$obj->reDirect('update_wallet_address.php');
			exit;
		}	 
	} 
	$userId = $_SESSION['user']['u_login_id'];			 
	$userD = $obj->selectData(TABLE_USER,"user_id,u_login_id,user_wallet_address","u_login_id='".$userId."' and user_status='Active'",1); 
	if($obj->get_message("message")=="")
	{
		$chkUserWall = $obj->selectData(TABLE_USER,"user_id,user_wallet_address","user_wallet_address='".trim($_POST['user_wallet_address'])."' and u_login_id !='".$userId."' and user_status='Active'",1); 
		if($chkUserWall['user_id'])
		{
			$obj->add_message("message","Wallet address provided by you is already exists. Please try with an unique address.");
			$obj->reDirect('update_wallet_address.php');
			exit;		 
		}
	}
	if($obj->get_message("message")=="")
	{
		if($userD['user_wallet_address'] == trim($_POST['user_wallet_address']))
		{
			$obj->add_message("message","Wallet address provided by you is same as your old wallet address. Please try with an unique address.");
			$obj->reDirect('update_wallet_address.php');
			exit;	
		}
	}
	
	if($obj->get_message("message")=="")
	{ 			 
		$wallArr['user_wallet_address']		= trim($obj->filter_mysql($_POST['user_wallet_address']));
		$wallArr['user_modified']			= CURRENT_DATE_TIME;				
		$_SESSION['wall_address_change_details'] =$wallArr;			
		//$sql=$obj->updateData(TABLE_USER,$wallArr,"user_id='".$userD['user_id']."'");	 
		$randnum = '';
		$otpD = $obj->selectData(TABLE_USER_OTP,"","uo_id='".$_SESSION['user_wall_change_otp_id']."'",1); 
		if(empty($otpD['uo_id'])) 
		{	
			$randnum =  mt_rand(100000, 999999);
			$optu['uo_otp'] 			= password_hash($randnum,PASSWORD_DEFAULT);
			$optu['user_id'] 			= $_SESSION['user']['u_login_id'];		 
			$optu['uo_created']			= date('Y-m-d');  
			$optu['uo_modified']		= CURRENT_DATE_TIME;				
			$user_otp_id = $obj->insertData(TABLE_USER_OTP,$optu);
			$_SESSION['user_wall_change_otp_id'] = $user_otp_id;
		}
		else 
		{
			$randnum =  mt_rand(100000, 999999);
			$optu['uo_otp'] 				= password_hash($randnum,PASSWORD_DEFAULT);
			$optu['user_id'] 				= $_SESSION['user']['u_login_id'];			 
			$optu['uo_modified']			= CURRENT_DATE_TIME;		
			$obj->updateData(TABLE_USER_OTP,$optu,"uo_id='".$otpD['uo_id']."'"); 
			$_SESSION['user_wall_change_otp_id'] = $otpD['uo_id'];
		}
				
		$wmsg   = "<b> Hi ".$obj->getUserName($userId)."</b><br><br>";			
		$wmsg  .= "Your One Time Password (OTP) is ".$randnum."<br><br>";		 
		$wmsg  .= "Thank You for using our Services.<br><br>";		 
		$wmsg .="<br><br>Thank you!<br><br>".MAIL_THANK_YOU;

		$wmsg .= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$wmsg .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';

		
		$body = $obj->mailBody($wmsg); 	 
		$from = FROM_EMAIL_2;
		$to   = $obj->getUserEmail($userId);			 
		$subject = "OTP for wallet address change.";			
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);		 
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","We have successfully sent an email to your registered email address with a one time password (OTP).");
	}
		
		$obj->reDirect('update_wallet_address.php');
}


if(isset($_POST['AddOtpWallAdd']))
{ 	 //pre($_POST); exit;
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_otp'])=="") {$obj->add_message("message","Please provide your OTP");}
	  
	 
	if($obj->get_message("message")=="")
	{ 	
		$userId = $_SESSION['user']['u_login_id'];			 
		$userD = $obj->selectData(TABLE_USER,"user_id,u_login_id,user_wallet_address","u_login_id='".$userId."' and user_status='Active'",1);
		
		$otpD = $obj->selectData(TABLE_USER_OTP,"","uo_id='".$_SESSION['user_wall_change_otp_id']."' and user_id='".$_SESSION['user']['u_login_id']."'",1);
				
		if(password_verify($_POST['user_otp'],$otpD['uo_otp']))	
		{ 
			$wallArr['user_wallet_address']		= trim($_SESSION['wall_address_change_details']['user_wallet_address']);
			$wallArr['user_modified']			= CURRENT_DATE_TIME;			
			$sql = $obj->updateData(TABLE_USER,$wallArr,"user_id='".$userD['user_id']."'");	
  
			$msges ='Wallet update.';			 
			$type = 't';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);		
			
		 
			$wallmsg   = "<b> Hi ".$obj->getUserName($userId)."</b><br><br>";			
			$wallmsg  .= "Your Asimi wallet address has been successfully changed within your Hashing Ad Space account.<br><br>";
			$wallmsg  .= "<b>Note:</b> If you DID NOT CHANGE your address, your account may be compromised and you should do the following immediately:<br><br>";
			$wallmsg  .= "<b>1.</b>Try to log in to your account and change your password and two-factor authentications and then update your Asimi wallet address to your correct address.<br><br>";
			$wallmsg  .= "<b>2.</b>Submit a support request to let us know about this issue.<br><br>";	

			$wallmsg .="<br><br>Thank you!<br><br>".MAIL_THANK_YOU;

			$wallmsg .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$wallmsg .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';

			
			$body = $obj->mailBody($wallmsg); 	 
			$from = FROM_EMAIL_2;
			$to   = $obj->getUserEmail($userId);			 
			$subject = "Your Asimi wallet address has been changed.";			
			$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);
			$obj->deleteData(TABLE_USER_OTP,"uo_id='".$_SESSION['user_wall_change_otp_id']."'");
			unset($_SESSION['user_wall_change_otp_id']); 
			unset($_SESSION['wall_address_change_details']); 			
			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","You have successfully setup your wallet address.");
			$obj->reDirect("wallet_address.php");
		}
		else 
		{ 
			$msges ='Wallet update failed.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);				
			
			$obj->deleteData(TABLE_USER_OTP,"uo_id='".$_SESSION['user_wall_change_otp_id']."'");
			unset($_SESSION['user_wall_change_otp_id']); 
			unset($_SESSION['wall_address_change_details']);
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","The One Time Password you entered does not match.");
			$obj->reDirect('wallet_address.php');		
			
		}
		 
		
	}
}

/////////////////////////Advertisement Setup////////////////////////////////

if(isset($_POST['AdDirListing'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['adl_title'])=="") {$obj->add_message("message","Please enter Ad Minter name.");}
	if(trim($_POST['adl_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['adl_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}
	if($obj->filter_numeric(trim($_POST['admi_impression']))=="") {$obj->add_message("message","Impression Should Not Be Blank!");}	
	if(trim($_POST['admi_impression']) <= 0) {$obj->add_message("message","Impression Should Be Valid!");}
	if(ctype_digit(trim($_POST['admi_impression']))==false || trim($_POST['admi_impression']) <=0) {$obj->add_message("message","Impression must be a number!");}	
	
	if(strpos($_POST['adl_desti_url'],'<') !== false){$obj->add_message("message","Error! The destination URL contains a not allowed character (<)");}
	if(strpos($_POST['adl_desti_url'],'>') !== false){$obj->add_message("message","Error! The destination URL contains a not allowed character (>)");}
	if(strpos($_POST['adl_desti_url'],"'") !== false){$obj->add_message("message","Error! The destination URL contains a not allowed character (')");}
	if(strpos($_POST['adl_desti_url'],'"') !== false){$obj->add_message("message",'Error! The destination URL contains a not allowed character (")');}	
	
	$desti_url 		= $obj->filter_mysql($_POST['adl_desti_url']); 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));	
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('ad_minter_setup.php');
	}	
	$title_name = $obj->filter_alphanum($_POST['adl_title']);
	if($title_name =='') 
	{
		$obj->add_message("message",'Please enter a valid alphanumeric title.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('ad_minter_setup.php');	
	}
	$userId 	= $obj->filter_numeric($_SESSION['user']['u_login_id']);	 
	$totval 	= $obj->get_tot_ad_directory_listing_user($userId);
	$totbanval 	= $obj->get_max_adminter_ad_setup_permission($userId); 
	if($totval >= $totbanval)
	{
		$obj->add_message("message",'You Reached The Maximum Number Of Ad Directory Listing.');
		$_SESSION['messageClass'] = 'errorClass';
	} 	
	
	$unssindImp 		= $obj->ad_minter_views_unassigned($userId);
	$admi_impression 	= $obj->filter_numeric($_POST['admi_impression']); 
	if($admi_impression > $unssindImp)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	$dbWhite = $obj->selectData(TABLE_DOMAIN_WHITELIST,"","(dw_url like '%".$get_full_url."%' or dw_url like '%".$get_url."%' or dw_url like '%".$domain_url."%') and dw_status='Active'",1); 
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{
			$AdList['adl_title'] 		= $obj->filter_alphanum($_POST['adl_title']);
			$AdList['adl_impression']   = $admi_impression;
			$AdList['adl_desti_url']    = $obj->filter_mysql(addslashes(stripslashes($_POST['adl_desti_url'])));
			$AdList['user_id'] 		    = $userId;
			if($dbWhite['dw_id']) $AdList['adl_status']	    = 'Active';	
			else  $AdList['adl_status']	    = 'Inactive';	
			 				 
			$AdList['adl_created']	= CURRENT_DATE_TIME;
			$AdList['adl_modified']	= CURRENT_DATE_TIME;				
			$newAdMinterId = $obj->insertData(TABLE_AD_DIRECTORY_LISTING,$AdList);	

			$banAdSet['adl_id'] 			= $newAdMinterId;
			$banAdSet['admi_add_type'] 		= 'A';
			$banAdSet['admi_impression'] 	= $admi_impression;		 
			$banAdSet['user_id'] 			= $userId;	
			$banAdSet['pack_id'] 			= 1;		 
			$banAdSet['admi_created']		= CURRENT_DATE_TIME;
			$banAdSet['admi_modified']		= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_ADMINTER_IMPRESSION,$banAdSet);			
			
			$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);  
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
			//////New mail (Ad Minter Campaign Setup) ////////////
			$aMintMessage = '<p>Hello  '.$userD['user_first_name']." ".$userD['user_last_name'].',</p>
			<p>Thank you for choosing to advertise with Hashing Ad Space.<br>We have received your submitted Ad Minter Ad campaign titled "'.$AdList['adl_title'].'".</p>
			<p>We are now reviewing this submission and will notify you once your ad is approved.<br>Always feel free to reach out with any questions you may have.</p>
			<p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
			 
			$aMintMessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$aMintMessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			
			$aMintBody = $obj->mailBody($aMintMessage); 
			$aMintSubject = 'You Ad Minter Ad Campaign has been submitted for approval';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $aMintSubject,$aMintBody,$from,"Hashing Ad Space",$type);
			$obj->add_message("message","Your ad has been submitted. It is now pending admin approval.");
			//////////////////////////////////////////////////////////////////			
			$_SESSION['messageClass'] = "successClass";				
			$obj->reDirect('ad_minter_setup.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something wrong. please try again.");
			$obj->reDirect('ad_minter_setup.php');			
		}			
			
	}
		
} 

if(isset($_POST['AdDirListingGT_CNF'])) 
{	
	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['adl_title'])=="") {$obj->add_message("message","Please Enter Name");}
	if(trim($_POST['adl_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['adl_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}
	if($obj->filter_numeric(trim($_POST['admi_impression']))=="") {$obj->add_message("message","Impression Should Not Be Blank!");}	
	if(trim($_POST['admi_impression']) <= 0) {$obj->add_message("message","Impression Should Be Valid!");}
	if(ctype_digit(trim($_POST['admi_impression']))==false || $_POST['admi_impression']<=0) {$obj->add_message("message","Impression must be a number!");}	
	
	if(strpos($_POST['adl_desti_url'],'<') !== false){$obj->add_message("message","Error! The destination URL contains a not allowed character (<)");}
	if(strpos($_POST['adl_desti_url'],'>') !== false){$obj->add_message("message","Error! The destination URL contains a not allowed character (>)");}
	if(strpos($_POST['adl_desti_url'],"'") !== false){$obj->add_message("message","Error! The destination URL contains a not allowed character (')");}
	if(strpos($_POST['adl_desti_url'],'"') !== false){$obj->add_message("message",'Error! The destination URL contains a not allowed character (")');}
	
	
	$desti_url 		= $_POST['adl_desti_url']; 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));	
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('ad_minter_setup.php');
	}	
	$title_name = $obj->filter_alphabet($_POST['adl_title']);
	if($title_name =='') 
	{
		$obj->add_message("message",'Please enter a valid alphanumeric title.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('ad_minter_setup.php');	
	}
	$userId 	= $obj->filter_numeric($_SESSION['user']['u_login_id']);	 
	$totval 	= $obj->get_tot_ad_directory_listing_user($userId);
	$totbanval 	= $obj->get_max_adminter_ad_setup_permission($userId);	 
	if($totval >= $totbanval)
	{
		$obj->add_message("message",'You Reached The Maximum Number Of Ad Directory Listing.');
		$_SESSION['messageClass'] = 'errorClass';
	} 	
	 
	$admi_impression 	= $obj->filter_numeric($_POST['admi_impression']);	
	$gconID 			= $obj->filter_mysql($_POST['geo_country_id']);
	$gcon_groupID 		= $obj->filter_mysql($_POST['geo_select_con_group_id']);	
	$selected_lang      = $obj->filter_mysql($_POST['sal']);
	$cheklang      		= $obj->filter_mysql($_POST['sal_lan']);
	
	if(($gconID !='' || $gcon_groupID !='') && ($selected_lang !='')) $set_geo_impression = round($admi_impression*.7);
	else if($gconID !='' || $gcon_groupID !='') $set_geo_impression = round($admi_impression*.7); 
	else if($selected_lang !='') $set_geo_impression = round($admi_impression*.7);  
	else $set_geo_impression = $admi_impression; 
	  
	$unssindImp 		= $obj->ad_minter_views_unassigned($userId);
	if($admi_impression > $unssindImp)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	$dbWhite = $obj->selectData(TABLE_DOMAIN_WHITELIST,"","(dw_url like '%".$get_full_url."%' or dw_url like '%".$get_url."%' or dw_url like '%".$domain_url."%') and dw_status='Active'",1);
	 
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{
			$AdList['adl_title'] 			= $obj->filter_alphabet($_POST['adl_title']); 
			$AdList['adl_title2'] 			= $obj->filter_mysql($_POST['adl_title2']);
			$AdList['adl_sub_title'] 		= $obj->filter_mysql($_POST['adl_sub_title']);
			$AdList['adl_color'] 			= $obj->filter_mysql($_POST['adl_color']); 
			$AdList['adl_desti_url']   	 	= $obj->filter_mysql(addslashes(stripslashes($_POST['adl_desti_url'])));
			
			if($gconID !='' || $gcon_groupID !='' || $selected_lang !='')
			{
				$AdList['adl_geo_impression'] 	= $admi_impression;
				$AdList['adl_impression'] 		= $set_geo_impression;
				if($gcon_groupID)$AdList['adl_geo_con_group_id'] = ",".$gcon_groupID.",";
				if($gconID) 	 $AdList['adl_geo_con_id'] 		 = ",".$gconID.",";		 
				if($selected_lang)	 	$AdList['adl_geo_lang_id'] 	= ",".$selected_lang.",";
				//else if($cheklang)	 	$AdList['adl_geo_lang_id'] 	= 'all';			
			} 
			else
			{
				$AdList['adl_impression']     = $admi_impression; 
				// $AdList['adl_geo_impression'] = $set_geo_impression;	
			}
			 
			$AdList['user_id'] 		    	= $userId;			
			if($dbWhite['dw_id']) $AdList['adl_status']	 = 'Active';	
			else  $AdList['adl_status']	    = 'Inactive';			 			 
			$AdList['adl_created']	= CURRENT_DATE_TIME;
			$AdList['adl_modified']	= CURRENT_DATE_TIME;				
			$newAdMinterId = $obj->insertData(TABLE_AD_DIRECTORY_LISTING,$AdList);	

			$aiArr['adl_id'] 				= $newAdMinterId;
			$aiArr['admi_add_type'] 		= 'A'; 
			$aiArr['admi_impression'] 		= $AdList['adl_impression'];	
			$aiArr['admi_geo_impression'] 	= $AdList['adl_geo_impression']; 
			$aiArr['user_id'] 				= $userId;	
			$aiArr['pack_id'] 				= 1;		 
			$aiArr['admi_created']			= CURRENT_DATE_TIME;
			$aiArr['admi_modified']			= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_ADMINTER_IMPRESSION,$aiArr);			
			
			$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);  
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
			//////New mail (Ad Minter Campaign Setup) ////////////
			$aMintMessage = '<p>Hello  '.$userD['user_first_name']." ".$userD['user_last_name'].',</p>
			<p>Thank you for choosing to advertise with Hashing Ad Space.<br>We have received your submitted Ad Minter Ad campaign titled "'.$AdList['adl_title'].'".</p>
			<p>We are now reviewing this submission and will notify you once your ad is approved.<br>Always feel free to reach out with any questions you may have.</p>
			<p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
			$aMintMessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$aMintMessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$aMintBody = $obj->mailBody($aMintMessage); 
			$aMintSubject = 'You Ad Minter Ad Campaign has been submitted for approval';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $aMintSubject,$aMintBody,$from,"Hashing Ad Space",$type);
			$obj->add_message("message","Your ad has been submitted. It is now pending admin approval.");
			//////////////////////////////////////////////////////////////////			
			$_SESSION['messageClass'] = "successClass";				
			$obj->reDirect('ad_minter_setup.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something wrong. please try again.");
			$obj->reDirect('ad_minter_setup.php');			
		}			
			
	}
		
} 
  
  
if(isset($_POST['UpdateAdDir'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['adl_title'])=="") {$obj->add_message("message","Please Enter Name");}
	if(trim($_POST['adl_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['adl_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}
	 
	 
	$desti_url 		= $obj->filter_mysql($_POST['adl_desti_url']); 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	}	 
	if($obj->get_message("message")=="")
	{	
		if($_SESSION['user']['u_login_id'] > 0) 
		{
			$adl_id = $obj->filter_numeric($obj->retrievePass($_REQUEST['adlId']));			
			$dataADL['adl_title']		= $obj->filter_mysql($_POST['adl_title']);	
			$dataADL['adl_desti_url']	= $obj->filter_mysql(addslashes(stripslashes($_POST['adl_desti_url'])));	
			$dataADL['adl_modified']	= CURRENT_DATE_TIME;					
			$sql=$obj->updateData(TABLE_AD_DIRECTORY_LISTING,$dataADL,"adl_id='".$adl_id."'");	 
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have successfully updated your Ad Directory Listing.");		 
			$obj->reDirect('active_campaign.php');
		} else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something wrong. please try again.");
			$obj->reDirect('active_campaign.php');			
		}				
	}
		
} 

if (isset($_POST['AddAdMinterImp'])) 
{
	$_SESSION['messageClass'] = "errorClass";
	if ($obj->filter_numeric(trim($_POST['admi_impression'])) == "") { $obj->add_message("message", "Impression Should Not Be Blank!"); }
	if (trim($_POST['admi_impression']) <= 0) { $obj->add_message("message", "Impression Should Be Valid!"); }
	if (ctype_digit(trim($_POST['admi_impression'])) == false || $_POST['admi_impression'] <= 0) { $obj->add_message("message", "Impression must be a number!"); }	

	$userId  = $obj->filter_numeric($_SESSION['user']['u_login_id']);
	$dataADL = $obj->selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_id ='".$obj->filter_numeric($_POST['adl_id'])."' AND user_id = '".$userId."' AND adl_status = 'Active'", 1);		
	if(empty($dataADL)) 
	{
		$obj->add_message("message", 'You are not authorized to make any changes.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	}
	 
	$add_maximum_impression = $obj->ad_minter_views_unassigned($userId);
	$admi_impression 		= $obj->filter_numeric($_POST['admi_impression']); 
	if ($admi_impression >= $add_maximum_impression) 
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for this Ad Minter Setup.');
		$_SESSION['messageClass'] = 'errorClass';
	}
	 
	$countryId 			= $dataADL['adl_geo_con_id'];
	$countryGroupId 	= $dataADL['adl_geo_con_group_id'];	
	$langID      		= $dataADL['adl_geo_lang_id'];
	
	if(($countryId !='' || $countryGroupId !='') && ($langID !='')) 	$set_geo_impression = round($admi_impression/5);
	else if($countryId !='' || $countryGroupId !='') 					$set_geo_impression = round($admi_impression/3); 
	else if($langID !='') 												$set_geo_impression = round($admi_impression/3);  
	else 																$set_geo_impression = $admi_impression;
	//pre($set_geo_impression);
	if($obj->get_message("message") == "")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{		
			$banAdSet['adl_id'] 			= $obj->filter_numeric($_POST['adl_id']);
			$banAdSet['admi_add_type'] 		= 'A'; 
			if($countryId !='' || $countryGroupId !='' || $langID !='')
			{ 	 
				$banAdSet['admi_geo_impression'] 		= $admi_impression;
				$banAdSet['admi_impression'] 			= $set_geo_impression; 
				$Arr['adl_impression'] 					= $dataADL['adl_impression'] + $set_geo_impression;
				$Arr['adl_geo_impression'] 				= $dataADL['adl_geo_impression'] + $admi_impression;
			} 
			else
			{
				$banAdSet['admi_impression']     		= $admi_impression;
				$Arr['adl_impression'] 					= $dataADL['adl_impression'] + $admi_impression;
			} 
			//  pre($Arr); exit;
			$banAdSet['user_id'] 			= $userId;
			$banAdSet['pack_id'] 			= 1;
			$banAdSet['admi_created']		= CURRENT_DATE_TIME;
			$banAdSet['admi_modified']		= CURRENT_DATE_TIME;
			$obj->insertData(TABLE_ADMINTER_IMPRESSION, $banAdSet);
			
			 
			$Arr['adl_modified']		= CURRENT_DATE_TIME;
			$Arr['adl_status']			= 'Active';			
			$sql=$obj->updateData(TABLE_AD_DIRECTORY_LISTING,$Arr,"adl_id='".$dataADL['adl_id']."'");
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message", "We have successfully saved your Ad Minter Impression.");
			$obj->reDirect('active_campaign.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something wrong. please try again.");
			$obj->reDirect('active_campaign.php');			
		}		
	}
}

if(isset($_POST['DelAdMinterImp']))
{	
	$_SESSION['messageClass'] = "errorClass"; 
	if($obj->filter_numeric(trim($_POST['admi_impression']))=="") {$obj->add_message("message","Impression Should Not Be Blank!");}
	if(ctype_digit(trim($_POST['admi_impression']))==false || trim($_POST['admi_impression'])<=0) {$obj->add_message("message","Impression must be a number!");}	
	if(trim($_POST['admi_impression']) <= 0) {$obj->add_message("message","Impression Should Be Valid!");}
	
    $admi_impression 			= 	$obj->filter_numeric($_POST['admi_impression']); 
	//$delete_maximum_impression 	= 	$obj->filter_numeric(['delete_maximum_impression']);
	
	
	$adl_id 	= 	$obj->filter_numeric($_POST['adl_id']);
	$userId 	= 	$obj->filter_numeric($_SESSION['user']['u_login_id']);
	
	$dataA 		= $obj->selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_id='".$adl_id."' and user_id='".$userId."' and adl_status='Active'",1);
	$countryId 			= $dataA['adl_geo_con_id'];
	$countryGroupId 	= $dataA['adl_geo_con_group_id'];	
	$langID      		= $dataA['adl_geo_lang_id'];
	
	if(($countryId !='' || $countryGroupId !='') && ($langID !='')) 	$set_geo_impression = round($admi_impression/5);
	else if($countryId !='' || $countryGroupId !='') 					$set_geo_impression = round($admi_impression/3); 
	else if($langID !='') 												$set_geo_impression = round($admi_impression/3);  
	else 																$set_geo_impression = $admi_impression;
		// pre($set_geo_impression);
	
	$delete_maximum_impression  = $dataA['adl_impression']- $dataA['adl_impression_view'];	
	 
	if($set_geo_impression > $delete_maximum_impression)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression for this Ad Minter Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	
	$dataADL = $obj->selectData(TABLE_AD_DIRECTORY_LISTING,"","adl_id='".$adl_id."' and user_id='".$userId."' and adl_status='Active'",1);	 
	if(empty($dataADL))
	{		 
		$obj->add_message("message",'You are not authorized to make any changes.');		
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	} 
	
	if($obj->get_message("message")=="")
	{ 
		$banAdSet['adl_id'] 			= $dataADL['adl_id'];
		$banAdSet['admi_add_type'] 		= 'D';
		$banAdSet['admi_impression'] 	= $admi_impression;  

		if($countryId !='' || $countryGroupId !='' || $langID !='')
		{ 	 
			$banAdSet['admi_geo_impression'] 		= $admi_impression;
			$banAdSet['admi_impression'] 			= $set_geo_impression; 
			$Arr['adl_impression'] 					= $dataADL['adl_impression'] - $set_geo_impression;
			$Arr['adl_geo_impression'] 				= $dataADL['adl_geo_impression'] - $admi_impression;
		} 
		else
		{
			$banAdSet['admi_impression']     		= $admi_impression;
			$Arr['adl_impression'] 					= $dataADL['adl_impression'] - $admi_impression;
		} 
		
		$banAdSet['user_id'] 			= $userId; 
		$banAdSet['admi_created']		= CURRENT_DATE_TIME;
		$banAdSet['admi_modified']		= CURRENT_DATE_TIME;				
		$obj->insertData(TABLE_ADMINTER_IMPRESSION,$banAdSet);

		//$Arr['adl_impression']= $dataADL['adl_impression']-$admi_impression;
		
		$Arr['adl_modified']		= CURRENT_DATE_TIME;
		//$Arr['adl_status']			= 'Active';			
		$sql=$obj->updateData(TABLE_AD_DIRECTORY_LISTING,$Arr,"adl_id='".$dataADL['adl_id']."'"); 
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","We have successfully deleted your Ad Minter Impression.");
		$obj->reDirect('active_campaign.php');
	}	
}

if(isset($_POST['AdBannerSetup'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['banad_title'])=="") {$obj->add_message("message","Please Enter Banner Name");}
	if(trim($_POST['banad_type_size'])=="") {$obj->add_message("message","Please Select Banner Type Size");}
	if($obj->filter_numeric(trim($_POST['banad_impression']))=="") {$obj->add_message("message","Impression Should Not Be Blank!");}
	if(trim($_POST['banad_impression']) <= 0) {$obj->add_message("message","Impression Should Be Valid!");}
	if(ctype_digit(trim($_POST['banad_impression']))==false || trim($_POST['banad_impression'])<=0) {$obj->add_message("message","Impression must be a number!");}	
	
	if(trim($_POST['banad_url'])=="") {$obj->add_message("message","Banner URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_url']))) {$obj->add_message("message","Banner URL Should Be Valid!");}
	if(trim($_POST['banad_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}	
	
	if(strpos($_POST['banad_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	
	if(strpos($_POST['banad_desti_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_desti_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_desti_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_desti_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);	
	if($userId =='60432') $bannerAdsView ='50';
	else $bannerAdsView = $adset['baset_banner_ad_view'];	
	
	
	$totpackpuchase = $obj->get_tot_package_purchased_by_pack_id(3);
	$totbanval = ($bannerAdsView*$totpackpuchase);	
	
	$totval = $obj->get_tot_banner_ads_user($userId);
	
	$desti_url 		= $obj->filter_mysql($_POST['banad_desti_url']); 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host'])); 
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('banner_setup.php');
	} 
		 
	if($_POST['banad_type_size'] =='square' && $totval[0] > $bannerAdsView)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Square Type Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	if($_POST['banad_type_size'] =='horizontal' && $totval[1] > $bannerAdsView)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Horizontal Type Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	
	 
	$balPermSquare 	= $obj->sq_ban_ads_imp_unassigned($userId);  
	$balPermHori 	= $obj->hr_ban_ads_imp_unassigned($userId); 
	
	$banad_impression = $obj->filter_numeric($_POST['banad_impression']); 
	if($_POST['banad_type_size'] == 'square' && $banad_impression > $balPermSquare)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression for Square Type Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	if($_POST['banad_type_size'] == 'horizontal' && $banad_impression > $balPermHori)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression for Horizontal Type Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}

	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			$banAdSet['banad_title'] 		= $obj->filter_mysql($_POST['banad_title']);
			$banAdSet['banad_type_size'] 	= $obj->filter_mysql($_POST['banad_type_size']);
			$banAdSet['banad_impression'] 	= $obj->filter_numeric($_POST['banad_impression']);
			$banAdSet['banad_url'] 			= $obj->filter_mysql($_POST['banad_url']);
			$banAdSet['banad_desti_url'] 	= $obj->filter_mysql($_POST['banad_desti_url']);
			$banAdSet['user_id'] 			= $userId;	
			$banAdSet['banad_status']		= 'Inactive';			
			$banAdSet['banad_created']		= CURRENT_DATE_TIME;
			$banAdSet['banad_modified']		= CURRENT_DATE_TIME;
			
			$arrED  = $_POST;
			$arrED1 = array_merge($arrED,$_SESSION);			
			$banAdSet['banad_en_details']   = serialize($arrED1);
							
			$newBanAdId = $obj->insertData(TABLE_BANNER_ADS,$banAdSet);
			
			$banAdSet['banad_id'] 			= $newBanAdId;
			$banAdSet['banimp_type_size'] 	= $obj->filter_mysql($_POST['banad_type_size']);
			$banAdSet['banimp_impression'] 	= $banad_impression;		 
			$banAdSet['user_id'] 			= $userId;	
			$banAdSet['banimp_add_type'] 	= 'A';	
			//$banAdSet['pack_id'] 			= 3;		 
			$banAdSet['banimp_created']		= CURRENT_DATE_TIME;
			$banAdSet['banimp_modified']	= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_BANNER_IMPRESSION,$banAdSet);
			
			 
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
			
			//////New mail (Ad Banner Campaign Setup) ////////////
			$aBanMessage = '<p>Hello '.$userD['user_first_name']." ".$userD['user_last_name'].',</p>
			<p>Thank you for choosing to advertise with Hashing Ad Space.<br>We have received your submitted Banner Ad campaign titled "'.$banAdSet['banad_title'].'".</p>
			<p>We are now reviewing this submission and we will notify you once your ad is approved.<br>Always feel free to reach out with any questions you may have.</p>
			<p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
			$aBanMessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$aBanMessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$aBanBody = $obj->mailBody($aBanMessage); 
			$aBanSubject = 'Your Banner Ad campaign has been submitted for approval';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $aBanSubject,$aBanBody,$from,"Hashing Ad Space",$type);
			//////////////////////////////////////////////////////////////////
			
			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your banner ad has been submitted. It is now pending admin approval.");			 
			$obj->reDirect('banner_setup.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('banner_setup.php');
			
		}		
			
			
	}	
}
//
if(isset($_POST['LeaderboardAdBannerSetupp'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['banad_title'])=="") {$obj->add_message("message","Please Enter Banner Name");}
	if(trim($_POST['banad_type_size'])=="") {$obj->add_message("message","Please Select Banner Type Size");}
	if(trim($_POST['banad_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}	
	
	
	if(strpos($_POST['banad_desti_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_desti_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_desti_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_desti_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);	
	$totpackpuchase = $obj->get_tot_package_purchased_by_pack_id_special($_REQUEST["pkID"]);
	$pkdetail = $obj->selectData(TABLE_PACKAGE,"","pack_id='".$_REQUEST["pkID"]."'",1);
	if($_POST['banad_type_size'] =='horizontal'){
		$bannerAdsView=$pkdetail["pack_banner_imp_horiz"];
		$lb_type="L";
	}else{
		$bannerAdsView=$pkdetail["pack_banner_imp_square"];
		$lb_type="A";
	}
	$dataStaId = $obj->selectData(TABLE_LEADERBOARD_BANNER,"count(lb_id) as tot_leaderboard","lb_type='".$lb_type."'",1);  
	$lb_virtual_id = $dataStaId['tot_leaderboard']+1;
	$desti_url 		= $obj->filter_mysql($_POST['banad_desti_url']); 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host'])); 
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('leaderboard_banner_set_up.php');
	} 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			if($_FILES['banad_image']['tmp_name'])
			{
				list($fileName,$error)=$obj->uploadFile('banad_image', "../images/banner/", 'gif,jpg,png,jpeg');
				if($error)
				{
					$msg=$error;
					$errImage=1;
					$obj->add_message("message",$msg);
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('leaderboard_banner_setup.php');
				}
				else
				{
					$testAr['test_image']=$fileName;
				}
			}
			//
			$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));  
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1); 
			$ordDataId = $obj->selectData(TABLE_ORDER,"","order_id='".$_REQUEST["ordID"]."'",1); 
			$order_end_date = $ordDataId['order_end_date'];
			$endDay = $order_end_date;
			$arrA['uid'] 				= $userId; 
			$arrA['lb_email'] 			= $obj->filter_mysql($userD["user_email"]); 
			$arrA['lb_amount'] 			= $obj->filter_mysql($ordDataId['order_total']); 
			$arrA['lb_amount_btc'] 		= $obj->filter_mysql($ordDataId['order_tot_btc']); 
			$arrA['banner_image'] 		= $obj->filter_mysql($fileName);
			$arrA['lb_desti_url'] 		= $obj->filter_mysql($_REQUEST["banad_desti_url"]);
			$arrA['lb_title'] 			= $obj->filter_alphanum($_POST['banad_title']);
			$arrA['lb_ad_type'] 		= $obj->filter_mysql('c');
			$arrA['lb_start_date'] 		= CURRENT_DATE_TIME;
			$arrA['lb_end_date'] 		= $endDay;
			$arrA['lb_status'] 			= $obj->filter_mysql("Active");	
			$arrA['lb_type'] 			= $lb_type;
			$arrA['lb_virtual_id'] 		= $lb_virtual_id;	
			$arrA['lb_created']			= CURRENT_DATE_TIME;
			$arrA['lb_modified']		= CURRENT_DATE_TIME;
			$arrA['order_id']		=$_REQUEST["ordID"];
			$arrA['allocated_impression']		= $bannerAdsView;
			$arrA['pack_id']		= $_REQUEST["pkID"];
			$obj->insertData(TABLE_LEADERBOARD_BANNER,$arrA);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your banner ad has been created.");			 
			$obj->reDirect('active_campaign.php');
		}else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('leaderboard_banner_set_up.php');
		}			
	}	
}
//
if(isset($_POST['SquareAdBannerSetupp'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['banad_title'])=="") {$obj->add_message("message","Please Enter Banner Name");}
	if(trim($_POST['banad_type_size'])=="") {$obj->add_message("message","Please Select Banner Type Size");}
	if(trim($_POST['banad_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}	
	
	
	if(strpos($_POST['banad_desti_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_desti_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_desti_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_desti_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);	
	$totpackpuchase = $obj->get_tot_package_purchased_by_pack_id_special($_REQUEST["pkID"]);
	$pkdetail = $obj->selectData(TABLE_PACKAGE,"","pack_id='".$_REQUEST["pkID"]."'",1);
	if($_POST['banad_type_size'] =='horizontal'){
		$bannerAdsView=$pkdetail["pack_banner_imp_horiz"];
		$lb_type="L";
	}else{
		$bannerAdsView=$pkdetail["pack_banner_imp_square"];
		$lb_type="A";
	}
	$dataStaId = $obj->selectData(TABLE_LEADERBOARD_BANNER,"count(lb_id) as tot_leaderboard","lb_type='".$lb_type."'",1);  
	$lb_virtual_id = $dataStaId['tot_leaderboard']+1;
	$desti_url 		= $obj->filter_mysql($_POST['banad_desti_url']); 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host'])); 
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('leaderboard_banner_set_up.php');
	} 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			if($_FILES['banad_image']['tmp_name'])
			{
				list($fileName,$error)=$obj->uploadFile('banad_image', "../images/banner/", 'gif,jpg,png,jpeg');
				if($error)
				{
					$msg=$error;
					$errImage=1;
					$obj->add_message("message",$msg);
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('leaderboard_banner_setup.php');
				}
				else
				{
					$testAr['test_image']=$fileName;
				}
			}
			//
			$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));  
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1); 
			$ordDataId = $obj->selectData(TABLE_ORDER,"","order_id='".$_REQUEST["ordID"]."'",1); 
			$order_end_date = $ordDataId['order_end_date'];
			$endDay = $order_end_date;
			$arrA['uid'] 				= $userId; 
			$arrA['lb_email'] 			= $obj->filter_mysql($userD["user_email"]); 
			$arrA['lb_amount'] 			= $obj->filter_mysql($ordDataId['order_total']); 
			$arrA['lb_amount_btc'] 		= $obj->filter_mysql($ordDataId['order_tot_btc']); 
			$arrA['banner_image'] 		= $obj->filter_mysql($fileName);
			$arrA['lb_desti_url'] 		= $obj->filter_mysql($_REQUEST["banad_desti_url"]);
			$arrA['lb_title'] 			= $obj->filter_alphanum($_POST['banad_title']);
			$arrA['lb_ad_type'] 		= $obj->filter_mysql('c');
			$arrA['lb_start_date'] 		= CURRENT_DATE_TIME;
			$arrA['lb_end_date'] 		= $endDay;
			$arrA['lb_status'] 			= $obj->filter_mysql("Active");	
			$arrA['lb_type'] 			= $lb_type;
			$arrA['lb_virtual_id'] 		= $lb_virtual_id;	
			$arrA['lb_created']			= CURRENT_DATE_TIME;
			$arrA['lb_modified']		= CURRENT_DATE_TIME;
			$arrA['order_id']		=$_REQUEST["ordID"];
			$arrA['allocated_impression']		= $bannerAdsView;
			$arrA['pack_id']		= $_REQUEST["pkID"];
			$obj->insertData(TABLE_LEADERBOARD_BANNER,$arrA);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your banner ad has been created.");			 
			$obj->reDirect('active_campaign.php');
		}else{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('square_banner_set_up.php');
		}			
	}	
}
//

if(isset($_POST['AdBannerSetupGT_CNF'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['banad_title'])=="") {$obj->add_message("message","Please Enter Banner Name");}
	if(trim($_POST['banad_type_size'])=="") {$obj->add_message("message","Please Select Banner Type Size");}
	if($obj->filter_numeric(trim($_POST['banad_impression']))=="") {$obj->add_message("message","Impression Should Not Be Blank!");}
	if(trim($_POST['banad_impression']) <= 0) {$obj->add_message("message","Impression Should Be Valid!");}
	if(ctype_digit(trim($_POST['banad_impression']))==false || trim($_POST['banad_impression'])<=0) {$obj->add_message("message","Impression must be a number!");}	
	
	if(trim($_POST['banad_url'])=="") {$obj->add_message("message","Banner URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_url']))) {$obj->add_message("message","Banner URL Should Be Valid!");}
	if(trim($_POST['banad_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}	
	
	if(strpos($_POST['banad_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	
	if(strpos($_POST['banad_desti_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_desti_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_desti_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_desti_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);	
	if($userId =='60432') $bannerAdsView ='50';
	else $bannerAdsView = $adset['baset_banner_ad_view'];	
	
	$totpackpuchase = $obj->get_tot_package_purchased_by_pack_id(3);
	$totbanval = ($bannerAdsView*$totpackpuchase);	
	
	$totval = $obj->get_tot_banner_ads_user($userId);
	
	$desti_url 		= $_POST['banad_desti_url']; 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host'])); 
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('banner_setup.php');
	} 
		 
	if($_POST['banad_type_size'] =='square' && $totval[0] > $bannerAdsView)
	{		 
		$obj->add_message("message",'You have reached the maximum number of square banner ad campaigns.﻿Please delete at least one campaign to create a new one.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	if($_POST['banad_type_size'] =='horizontal' && $totval[1] > $bannerAdsView)
	{		 
		$obj->add_message("message",'You have reached the maximum number of horizontal banner ad campaigns.﻿Please delete at least one campaign to create a new one.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	 
	$balPermSquare 	= $obj->sq_ban_ads_imp_unassigned($userId);  
	$balPermHori 	= $obj->hr_ban_ads_imp_unassigned($userId); 
	
	$banad_impression = $obj->filter_numeric($_POST['banad_impression']); 
	if($_POST['banad_type_size'] == 'square' && $banad_impression > $balPermSquare)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression for Square Type Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	if($_POST['banad_type_size'] == 'horizontal' && $banad_impression > $balPermHori)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression for Horizontal Type Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}

	$banad_impression 	= $obj->filter_numeric($_POST['banad_impression']);	
	$gconID 			= $obj->filter_mysql($_POST['geo_country_id']);
	$gcon_groupID 		= $obj->filter_mysql($_POST['geo_select_con_group_id']);	
	$selected_lang      = $obj->filter_mysql($_POST['sal']);
	$cheklang      		= $obj->filter_mysql($_POST['sal_lan']);
	
	 
	 
	if(($gconID !='' || $gcon_groupID !='') && ($selected_lang !='')) $set_geo_impression = round($banad_impression*.7);
	else if($gconID !='' || $gcon_groupID !='') $set_geo_impression = round($banad_impression*.7); 
	else if($selected_lang !='') $set_geo_impression = round($banad_impression*.7); 
	else $set_geo_impression = $banad_impression;  
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			$banAdSet['banad_title'] 		= $obj->filter_mysql($_POST['banad_title']);
			$banAdSet['banad_type_size'] 	= $obj->filter_mysql($_POST['banad_type_size']);
			 
			
			if($gconID !='' || $gcon_groupID !='' || $selected_lang !='')
			{ 
				$banAdSet['banad_geo_impression'] 		= $obj->filter_numeric($_POST['banad_impression']);
				$banAdSet['banad_impression'] 			= $set_geo_impression; 
				if($gcon_groupID) $banAdSet['banad_geo_con_group_id'] 	= ",".$gcon_groupID.",";
				if($gconID) 	  $banAdSet['banad_geo_con_id'] 		= ",".$gconID.",";		 
				if($selected_lang)	 	$banAdSet['banad_geo_lang_id'] 	= ",".$selected_lang.",";
				//else if($cheklang)	 	$banAdSet['banad_geo_lang_id'] 	= 'all';	
			} 
			else
			{
				$banAdSet['banad_impression']     		= $obj->filter_numeric($_POST['banad_impression']);
			}
			
			$banAdSet['banad_url'] 			= $obj->filter_mysql($_POST['banad_url']);
			$banAdSet['banad_desti_url'] 	= $obj->filter_mysql($_POST['banad_desti_url']);
			$banAdSet['user_id'] 			= $_SESSION['user']['u_login_id'];	
			$banAdSet['banad_status']		= 'Inactive';			
			$banAdSet['banad_created']		= CURRENT_DATE_TIME;
			$banAdSet['banad_modified']		= CURRENT_DATE_TIME;	
			
			$arrED  = $_POST;
			$arrED1 = array_merge($arrED,$_SESSION);			
			$banAdSet['banad_en_details']   = serialize($arrED1); 
			$newBanAdId = $obj->insertData(TABLE_BANNER_ADS,$banAdSet);
			
			$banImpArr['banad_id'] 					= $newBanAdId;
			$banImpArr['banimp_type_size'] 			= $obj->filter_mysql($_POST['banad_type_size']); 
			$banImpArr['banimp_impression'] 		= $banAdSet['banad_impression'];	
			$banImpArr['banimp_geo_impression'] 	= $banAdSet['banad_geo_impression']; 
			$banImpArr['user_id'] 					= $userId;	
			$banImpArr['banimp_add_type'] 			= 'A';				 
			$banImpArr['banimp_created']			= CURRENT_DATE_TIME;
			$banImpArr['banimp_modified']			= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_BANNER_IMPRESSION,$banImpArr);      
			
			$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));  
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
			
			//////New mail (Ad Banner Campaign Setup) ////////////
			$aBanMessage = '<p>Hello '.$userD['user_first_name']." ".$userD['user_last_name'].',</p>
			<p>Thank you for choosing to advertise with Hashing Ad Space.<br>We have received your submitted Banner Ad campaign titled "'.$banAdSet['banad_title'].'".</p>
			<p>We are now reviewing this submission and we will notify you once your ad is approved.<br>Always feel free to reach out with any questions you may have.</p>
			<p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
			$aBanMessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$aBanMessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$aBanBody = $obj->mailBody($aBanMessage); 
			$aBanSubject = 'Your Banner Ad campaign has been submitted for approval';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $aBanSubject,$aBanBody,$from,"Hashing Ad Space",$type);
			//////////////////////////////////////////////////////////////////
			
			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your banner ad has been submitted. It is now pending admin approval.");			 
			$obj->reDirect('banner_setup.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('banner_setup.php');
			
		}		
			
			
	}	
}
//
if(isset($_POST['UpdateBanAdssLeSq'])) 
{	
			//
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['banad_title'])=="") {$obj->add_message("message","Please Enter Banner Name");}
	if(trim($_POST['banad_type_size'])=="") {$obj->add_message("message","Please Select Banner Type Size");}
	if(trim($_POST['banad_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}	
	
	
	if(strpos($_POST['banad_desti_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_desti_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_desti_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_desti_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	$desti_url 		= $obj->filter_mysql($_POST['banad_desti_url']); 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host'])); 
	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	} 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			if($_FILES['banad_image']['tmp_name'])
			{
				list($fileName,$error)=$obj->uploadFile('banad_image', "../images/banner/", 'gif,jpg,png,jpeg');
				if($error)
				{
					$msg=$error;
					$errImage=1;
					$obj->add_message("message",$msg);
					$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('active_campaign.php');
				}
				else
				{
					$testAr['test_image']=$fileName;
				}
				$arrA['banner_image'] 		= $obj->filter_mysql($fileName);
			}
			//
			$arrA['lb_desti_url'] 		= $obj->filter_mysql($_REQUEST["banad_desti_url"]);
			$arrA['lb_title'] 			= $obj->filter_alphanum($_POST['banad_title']);
			$arrA['lb_modified']		= CURRENT_DATE_TIME;

			$obj->updateData(TABLE_LEADERBOARD_BANNER,$arrA,"lb_id='".$_REQUEST["savebanad_id"]."'");
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have successfully updated your Banner Ad Setup.");		
			$obj->reDirect('active_campaign.php');
		}
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('active_campaign.php');			
		}		
	}	
}
//

if(isset($_POST['UpdateBanAds'])) 
{	 
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['banad_title'])=="") {$obj->add_message("message","Please Enter Name");}
	if(trim($_POST['banad_type_size'])=="") {$obj->add_message("message","Please Select Banner Type Size");}
	//if(trim($_POST['banad_impression'])=="") {$obj->add_message("message","Impression Should Not Be Blank!");}
	if(trim($_POST['banad_url'])=="") {$obj->add_message("message","Banner URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_url']))) {$obj->add_message("message","Banner URL Should Be Valid!");}
	if(trim($_POST['banad_desti_url'])=="") {$obj->add_message("message","Destination URL Should Not Be Blank!");}	
	if(!$obj->isURL(trim($_POST['banad_desti_url']))) {$obj->add_message("message","Destination URL Should Be Valid!");}
	
	if(strpos($_POST['banad_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}
	
	if(strpos($_POST['banad_desti_url'],'<') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (<)");}
	if(strpos($_POST['banad_desti_url'],'>') !== false){$obj->add_message("message","Error! The banner contains a not allowed character (>)");}
	if(strpos($_POST['banad_desti_url'],"'") !== false){$obj->add_message("message","Error! The banner contains a not allowed character (')");}
	if(strpos($_POST['banad_desti_url'],'"') !== false){$obj->add_message("message",'Error! The banner contains a not allowed character (")');}	
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);	
	if($userId =='60432') $bannerAdsView ='50';
	else $bannerAdsView = $adset['baset_banner_ad_view'];	
	
	$totpackpuchase = $obj->get_tot_package_purchased_by_pack_id(3);
	$totbanval = ($bannerAdsView*$totpackpuchase);	
	$totval = $obj->get_tot_banner_ads_user($_SESSION['user']['u_login_id']);

	$desti_url 		= $_POST['banad_desti_url']; 
	$parse 			= parse_url(filter_var($desti_url, FILTER_SANITIZE_URL));	 
	$get_full_url 	= $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
	$get_url 		= $obj->filter_mysql($parse['host']); 	
	$domain_url 	= $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));

	$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);	 
	if($domainBL['db_id'])
	{
		$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.<br /> Please try another URL.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	} 
 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			$banad_id = $obj->filter_numeric($obj->retrievePass($_REQUEST['banadId']));			
			$dataUAL['banad_title']     = $obj->filter_mysql($_POST['banad_title']);
			$dataUAL['banad_type_size'] = $obj->filter_mysql($_POST['banad_type_size']);
			$dataUAL['banad_url'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['banad_url'])));
			$dataUAL['banad_desti_url'] = $obj->filter_mysql($_POST['banad_desti_url']);
			$dataUAL['banad_modified']  = CURRENT_DATE_TIME;					
			$sql=$obj->updateData(TABLE_BANNER_ADS,$dataUAL,"banad_id='".$banad_id."'");
			 
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have successfully updated your Banner Ad Setup.");		
			$obj->reDirect('active_campaign.php');
		}
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('active_campaign.php');			
		}		
	}	
}

if(isset($_POST['AddBanAdImp'])) 
{
	$_SESSION['messageClass'] = "errorClass";
	if ($obj->filter_numeric(trim($_POST['banimp_impression'])) == "") { $obj->add_message("message","Impression Should Not Be Blank!"); }
	if (trim($_POST['banimp_impression']) <= 0) { $obj->add_message("message","Impression Should Be Valid!"); }
	if (ctype_digit($_POST['banimp_impression']) == false || trim($_POST['banimp_impression'])<=0) { $obj->add_message("message","Impression must be a number!"); }
	
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);
	$dataBan = $obj->selectData(TABLE_BANNER_ADS,"","banad_id = '".$obj->filter_numeric($_POST['banad_id'])."' AND user_id = '".$userId."' AND banad_status ='Active'",1);
	if(empty($dataBan)) 
	{
		$obj->add_message("message", 'You are not authorized to make any changes.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	}
	 
	if($dataBan['banad_type_size'] == 'square') 
	{
		$add_maximum_impression = $obj->sq_ban_ads_imp_unassigned($userId);
	} else 
	{
		$add_maximum_impression = $obj->hr_ban_ads_imp_unassigned($userId);
	}
	$banimp_impression 	= $obj->filter_numeric($_POST['banimp_impression']);	
	
	if($banimp_impression >= $add_maximum_impression) 
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for this Ad Banner.');
		$_SESSION['messageClass'] = 'errorClass';
	}	 
	$allotimpSquare = $obj->sq_ban_ads_imp_unassigned($userId);
	$allotimpHori 	= $obj->hr_ban_ads_imp_unassigned($userId);	
	if($_POST['banimp_type_size'] == 'square' && $allotimpSquare < $banimp_impression)
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for Square Type Ad Banner.');
		$_SESSION['messageClass'] = 'errorClass';
	}
	if($_POST['banimp_type_size'] == 'horizontal' && $allotimpHori < $banimp_impression) 
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for Horizontal Type Ad Banner.');
		$_SESSION['messageClass'] = 'errorClass';
	}
	
	$countryId 			= $dataBan['banad_geo_con_id'];
	$countryGroupId 	= $dataBan['banad_geo_con_group_id'];	
	$langID      		= $dataBan['banad_geo_lang_id'];
	if(($countryId !='' || $countryGroupId !='') && ($langID !='')) 	$set_geo_impression = round($banimp_impression/5);
	else if($countryId !='' || $countryGroupId !='') 					$set_geo_impression = round($banimp_impression/3); 
	else if($langID !='') 												$set_geo_impression = round($banimp_impression/3);  
	else 																$set_geo_impression = $banimp_impression;
	
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{
			$banAdSet['banad_id'] 			= $dataBan['banad_id'];
			$banAdSet['banimp_type_size'] 	= $obj->filter_mysql($_POST['banimp_type_size']);
			$banAdSet['banimp_add_type'] 	= 'A'; 
			if($countryId !='' || $countryGroupId !='' || $langID !='')
			{ 	
				$banAdSet['banimp_geo_impression'] 		= $banimp_impression;
				$banAdSet['banimp_impression'] 			= $set_geo_impression; 
				$Arr['banad_impression'] 				= $dataBan['banad_impression'] + $set_geo_impression;
				$Arr['banad_geo_impression'] 			= $dataBan['banad_geo_impression'] + $banimp_impression;
			} 
			else
			{
				$banAdSet['banimp_impression']     		= $banimp_impression;
				$Arr['banad_impression'] 				= $dataBan['banad_impression'] + $banimp_impression;
			}
			 
			$banAdSet['user_id'] 			= $userId;
			$banAdSet['banimp_created']		= CURRENT_DATE_TIME;
			$banAdSet['banimp_modified']	= CURRENT_DATE_TIME;
			//pre($banAdSet); exit;
			$obj->insertData(TABLE_BANNER_IMPRESSION, $banAdSet);
			
		   
			$Arr['banad_modified']		= CURRENT_DATE_TIME;
			$Arr['banad_status']		= 'Active';
			$obj->updateData(TABLE_BANNER_ADS,$Arr,"banad_id='".$dataBan['banad_id']."'");
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message", "We have successfully saved your Banner Ad Impression.");
			$obj->reDirect('active_campaign.php');
		}
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('active_campaign.php');
		}		
	}	
}
//NP
if(isset($_POST['AddBanAdImp2'])) 
{
	$_SESSION['messageClass'] = "errorClass";
	if ($obj->filter_numeric(trim($_POST['banimp_impression'])) == "") { $obj->add_message("message","Impression Should Not Be Blank!"); }
	if (trim($_POST['banimp_impression']) <= 0) { $obj->add_message("message","Impression Should Be Valid!"); }
	if (ctype_digit($_POST['banimp_impression']) == false || trim($_POST['banimp_impression'])<=0) { $obj->add_message("message","Impression must be a number!"); }
	
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);
	$dataBan = $obj->selectData(TABLE_BANNER_ADS,"","banad_id = '".$obj->filter_numeric($_POST['banad_id'])."' AND user_id = '".$userId."' AND banad_status ='Active'",1);
	if(empty($dataBan)) 
	{
		$obj->add_message("message", 'You are not authorized to make any changes.');
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	}
	 
	if($dataBan['banad_type_size'] == 'square') 
	{
		//$add_maximum_impression = $obj->sq_ban_ads_imp_unassigned($userId);
		$add_maximum_impression=1500000;
	} else 
	{
		//$add_maximum_impression = $obj->hr_ban_ads_imp_unassigned($userId);
		$add_maximum_impression=1500000;
	}
	$banimp_impression 	= $obj->filter_numeric($_POST['banimp_impression']);	
	
	if($banimp_impression >= $add_maximum_impression) 
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for this Ad Banner.');
		$_SESSION['messageClass'] = 'errorClass';
	}	 
	// $allotimpSquare = $obj->sq_ban_ads_imp_unassigned($userId);
	// $allotimpHori 	= $obj->hr_ban_ads_imp_unassigned($userId);	
	$allotimpSquare = 1500000;
	$allotimpHori 	= 1500000;	
	if($_POST['banimp_type_size'] == 'square' && $allotimpSquare < $banimp_impression)
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for Square Type Ad Banner.');
		$_SESSION['messageClass'] = 'errorClass';
	}
	if($_POST['banimp_type_size'] == 'horizontal' && $allotimpHori < $banimp_impression) 
	{
		$obj->add_message("message", 'You Reached The Maximum Number Of Impression for Horizontal Type Ad Banner.');
		$_SESSION['messageClass'] = 'errorClass';
	}
	
	$countryId 			= $dataBan['banad_geo_con_id'];
	$countryGroupId 	= $dataBan['banad_geo_con_group_id'];	
	$langID      		= $dataBan['banad_geo_lang_id'];
	if(($countryId !='' || $countryGroupId !='') && ($langID !='')) 	$set_geo_impression = round($banimp_impression/5);
	else if($countryId !='' || $countryGroupId !='') 					$set_geo_impression = round($banimp_impression/3); 
	else if($langID !='') 												$set_geo_impression = round($banimp_impression/3);  
	else 																$set_geo_impression = $banimp_impression;
	
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{
			$banAdSet['banad_id'] 			= $dataBan['banad_id'];
			$banAdSet['banimp_type_size'] 	= $obj->filter_mysql($_POST['banimp_type_size']);
			$banAdSet['banimp_add_type'] 	= 'A'; 
			if($countryId !='' || $countryGroupId !='' || $langID !='')
			{ 	
				$banAdSet['banimp_geo_impression'] 		= $banimp_impression;
				$banAdSet['banimp_impression'] 			= $set_geo_impression; 
				$Arr['banad_impression'] 				= $dataBan['banad_impression'] + $set_geo_impression;
				$Arr['banad_geo_impression'] 			= $dataBan['banad_geo_impression'] + $banimp_impression;
			} 
			else
			{
				$banAdSet['banimp_impression']     		= $banimp_impression;
				$Arr['banad_impression'] 				= $dataBan['banad_impression'] + $banimp_impression;
			}
			 
			$banAdSet['user_id'] 			= $userId;
			$banAdSet['banimp_created']		= CURRENT_DATE_TIME;
			$banAdSet['banimp_modified']	= CURRENT_DATE_TIME;
			//pre($banAdSet); exit;
			$obj->insertData(TABLE_BANNER_IMPRESSION, $banAdSet);
			
		   
			$Arr['banad_modified']		= CURRENT_DATE_TIME;
			$Arr['banad_status']		= 'Active';
			$obj->updateData(TABLE_BANNER_ADS,$Arr,"banad_id='".$dataBan['banad_id']."'");
			
			$_SESSION['messageClass'] = "successClass";
			$obj->add_message("message", "We have successfully saved your Banner Ad Impression.");
			$obj->reDirect('active_campaign.php');
		}
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('active_campaign.php');
		}		
	}	
}

//
if(isset($_POST['DelBanAdImp'])) 
{ 
	$_SESSION['messageClass'] = "errorClass"; 
	if($obj->filter_numeric(trim($_POST['banimp_impression']))=="") {$obj->add_message("message","Impression Should Not Be Blank!");}
	if(trim($_POST['banimp_impression']) <= 0) {$obj->add_message("message","Impression Should Be Valid!");}
	if(ctype_digit(trim($_POST['banimp_impression']))==false || trim($_POST['banimp_impression'])<=0) {$obj->add_message("message","Impression must be a number!");}
	
	
	$userId  = $obj->filter_numeric($_SESSION['user']['u_login_id']);	
	$dataBan = $obj->selectData(TABLE_BANNER_ADS,"","banad_id='".$obj->filter_numeric($_POST['banad_id'])."' and user_id='".$userId."'",1);
	if(empty($dataBan))
	{		 
		$obj->add_message("message",'You are not authorized to make any changes.');		
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect('active_campaign.php');
	} 
	 
	$banimp_impression 			= $obj->filter_numeric($_POST['banimp_impression']);
	//$delete_maximum_impression 	= $obj->filter_numeric($_POST['delete_maximum_impression']);
	
	$dataB = $obj->selectData(TABLE_BANNER_ADS,"","banad_id='".$obj->filter_numeric($_POST['banad_id'])."' and user_id='".$userId."' and banad_status='Active'",1);
	$impre   = $obj->get_tot_banner_ads_impression_used($userId,$obj->filter_numeric($_POST['banad_id']));
	if($dataB['banad_type_size'] =='square') 
	{
		$delete_maximum_impression = $impre[0] - $data['banad_view_imp_ach'];
		 
	}
	 else if($dataB['banad_type_size'] =='horizontal') 
	{			 
		$delete_maximum_impression  =  $impre[0] - $data['banad_view_imp_ach'];

	}  
	if($banimp_impression > $delete_maximum_impression)
	{		 
		$obj->add_message("message",'You Reached The Maximum Number Of Impression for this Ad Banner Setup.');		
		$_SESSION['messageClass'] = 'errorClass';
	}
	
	$countryId 			= $dataBan['banad_geo_con_id'];
	$countryGroupId 	= $dataBan['banad_geo_con_group_id'];	
	$langID      		= $dataBan['banad_geo_lang_id'];
	if(($countryId !='' || $countryGroupId !='') && ($langID !='')) 	$set_geo_impression = round($banimp_impression/5);
	else if($countryId !='' || $countryGroupId !='') 					$set_geo_impression = round($banimp_impression/3); 
	else if($langID !='') 												$set_geo_impression = round($banimp_impression/3);  
	else 																$set_geo_impression = $banimp_impression;
	
	if($obj->get_message("message")=="")
	{	
		if($_SESSION['user']['u_login_id'] > 0) 
		{
			$banAdSet['banad_id'] 			= $dataBan['banad_id'];
			$banAdSet['banimp_type_size'] 	= $obj->filter_mysql($_POST['banimp_type_size']);
			$banAdSet['banimp_add_type'] 	= 'D';				 
			 
			if($countryId !='' || $countryGroupId !='' || $langID !='')
			{ 
				$banAdSet['banimp_geo_impression'] 		= $banimp_impression;
				$banAdSet['banimp_impression'] 			= $set_geo_impression; 
				$Arr['banad_impression'] 				= $dataBan['banad_impression'] - $set_geo_impression;
				$Arr['banad_geo_impression'] 			= $dataBan['banad_geo_impression'] - $banimp_impression;
			} 
			else
			{
				$banAdSet['banimp_impression']     		= $banimp_impression;
				$Arr['banad_impression'] 				= $dataBan['banad_impression'] - $banimp_impression;
			}
			
			$banAdSet['user_id'] 			= $userId;		 
			$banAdSet['banimp_created']		= CURRENT_DATE_TIME;
			$banAdSet['banimp_modified']	= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_BANNER_IMPRESSION,$banAdSet);
			
		 
			$Arr['banad_modified']		= CURRENT_DATE_TIME;
			//$Arr['banad_status']		= 'Active';
			$sql=$obj->updateData(TABLE_BANNER_ADS,$Arr,"banad_id='".$dataBan['banad_id']."'"); 		
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have successfully deleted your Banner Ad Impression.");		 
			$obj->reDirect('active_campaign.php');
		}
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('active_campaign.php');
		}		
			
	}	
}


/////////////////////////V2E Ads////////////////////////////////

if(isset($_POST['APPLYPRM'])) 
{				 
	$_SESSION['messageClass'] = "errorClass";
	 
	if(trim($_POST['cs_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!"); $obj->reDirect('view_to_earn_campaign_setup.php');}	
	if(trim($_POST['cs_set_view']) <= 0) {$obj->add_message("message","Apply Views should be valid!"); $obj->reDirect('view_to_earn_campaign_setup.php');} 	
	if(trim($_POST['cs_click_view']) <= 0) {$obj->add_message("message","Click Value should be valid!"); $obj->reDirect('view_to_earn_campaign_setup.php');}
	if(trim($_POST['cs_ad_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!"); $obj->reDirect('view_to_earn_campaign_setup.php');}		
		 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	 
			 
			$tot_click_view_x 	= $obj->filter_mysql($_POST['cs_asimi'])/$obj->filter_mysql($_POST['cs_set_view']);	 
			$tot_click_val_cost = $obj->floorDec($tot_click_view_x);
			 
			$_SESSION['cs_set_asimi'] 	= $obj->filter_mysql($_POST['cs_asimi']);
			$_SESSION['cs_view'] 		= $obj->filter_mysql($_POST['cs_set_view']);
			$_SESSION['cs_view_cost'] 	= $obj->filter_mysql($tot_click_val_cost);
			$_SESSION['cs_duration'] 	= $obj->filter_mysql($_POST['cs_ad_duration']);	
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","You have successfully applied your advertising parameters.");			 
			//$obj->reDirect('campaign_setup.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			//$obj->reDirect('campaign_setup.php');		
		}		
	}	
}

if(isset($_POST['Advtoe_campaign_setup'])) 
{				 
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['cs_title'])=="") {$obj->add_message("message","Please enter Ad campiagn title");}
	if(trim($_POST['cs_title_2'])=="") {$obj->add_message("message","Please enter advertising title description");}
	 
	if(trim($_POST['cs_desc_1'])=="") {$obj->add_message("message","Please enter advertising description line 2");}
	if(trim($_SESSION['cs_set_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_POST['cs_desti_url'])=="") {$obj->add_message("message","Destination URL should not be blank!");}		
	if(!$obj->isURL(trim($_POST['cs_desti_url']))) {$obj->add_message("message","Destination URL should be valid!");}		
	if(trim($_SESSION['cs_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!");}			
	if(strpos($_POST['cs_desti_url'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['cs_desti_url'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['cs_desti_url'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['cs_desti_url'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	
	if(trim($_SESSION['cs_view']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_SESSION['cs_view_cost']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);  
	if($obj->get_message("message")=="")
	{ 
		$desti_url = $_POST['cs_desti_url'];  
		$parse = parse_url($desti_url);
		$get_full_url = $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
		$get_url = $obj->filter_mysql($parse['host']); 	
		$domain_url = $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));
		
		$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);
		
		if(!empty($domainBL['db_id'])){
			$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.Please try another URL.');				
			$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
		}
	}
		
			
	if($obj->get_message("message")=="")
	{		 
		$cs_title_2 = strlen($obj->filter_mysql($_POST['cs_title_2']));
		$cs_desc_1 = strlen($obj->filter_mysql($_POST['cs_desc_1']));			 
		if($cs_title_2 > 30)
		{				 
			$obj->add_message("message","Maximum character limit of Advertising Title Description is 30.");
		}		 
		if($cs_desc_1 > 50)
		{				 
			$obj->add_message("message","Maximum character limit of Advertising Description Line 2 is 50");
		}
	}
	
	if($obj->get_message("message")=="")
	{ 
		$vtoeBalance = $obj->get_view_to_earn_asimi_balance($userId);
		$cs_asimi = trim($obj->filter_mysql($_SESSION['cs_set_asimi']));			 
		if($cs_asimi > $vtoeBalance)
		{				 
			$obj->add_message("message","You have insufficient funds to setup this campaign.");
		}
	}
	
	if($obj->get_message("message")=="")
	{ 
		$cs_asimi = trim($_SESSION['cs_set_asimi']);
		$cs_set_view = trim($_SESSION['cs_view']); 
		$cs_click_view = trim($_SESSION['cs_view_cost']); 
		// $min_set_view = ($cs_asimi*10);
		//$max_set_view = ($cs_asimi*2000);
		$min_set_view = $cs_asimi;
		 
		$max_set_view = round($cs_asimi*$_SESSION['cur_asimi_rate']*100*100);		
		$cs_click_view_new = $obj->floorDec($cs_asimi/$cs_set_view,8);
		
		if($min_set_view > $cs_set_view || $max_set_view < $cs_set_view)
		{				 
			$obj->add_message("message","Please set your Apply Views between ".$min_set_view." to ".$max_set_view."");
		}
	}
	
	if($obj->get_message("message")=="")
	{ 
		if(trim($_SESSION['cs_duration']) !="")
		{	
			$val_duration = $obj->filter_mysql(trim($_SESSION['cs_duration']));
			if($val_duration !='7' && $val_duration !='15')
			{  
				$obj->add_message("message","Please select valid ad duration time!");					 
			}
		}
	}	
 
	if($obj->get_message("message")=="")
	{ 	
		$totvtoepermis = $adset['baset_vtoe_ad_view'];
		$tot_setup_camp = $obj->total_active_vtoe_campaign($userId);
		if($tot_setup_camp >= $totvtoepermis)
		{			
			$obj->add_message("message",'You Reached The Maximum Number Of V2E ads.');				 
		}
	}
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{			  
			$vtoeArr['cs_title'] 			= $obj->filter_mysql($_POST['cs_title']);
			$vtoeArr['cs_title_2'] 			= $obj->filter_mysql($_POST['cs_title_2']);
			$vtoeArr['cs_desc_1'] 			= $obj->filter_mysql($_POST['cs_desc_1']);			 
			$vtoeArr['cs_desti_url'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['cs_desti_url'])));   
			$vtoeArr['cs_ad_duration'] 		= $obj->filter_mysql($_SESSION['cs_duration']);	
			$vtoeArr['cs_asimi'] 			= $obj->filter_mysql($_SESSION['cs_set_asimi']);	
			$vtoeArr['cs_view'] 			= $obj->filter_mysql($_SESSION['cs_view']);	
			$vtoeArr['cs_view_cost'] 		= $cs_click_view_new;				
			$vtoeArr['user_id'] 			= $userId;	
			$vtoeArr['cs_status']			= 'Inactive';			
			$vtoeArr['cs_created']			= CURRENT_DATE_TIME;
			$vtoeArr['cs_modified']			= CURRENT_DATE_TIME;				
			$newcsID = $obj->insertData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr);
			
			$vaaR['vaa_asimi'] 			= $obj->filter_mysql($_SESSION['cs_set_asimi']);				
			$vaaR['user_id'] 			= $userId;					 
			$vaaR['vaa_type']			= 'A';
			$vaaR['cs_id']				= $newcsID;			
			$vaaR['vaa_created']		= CURRENT_DATE_TIME;
			$vaaR['vaa_modified']		= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_VTOE_ADD_ASIMI,$vaaR);	
			
			$vcountR['vcount_view'] 		= $obj->filter_mysql($cs_set_view);				
			$vcountR['user_id'] 			= $userId;					 
			$vcountR['vcount_type']			= 'A';
			$vcountR['cs_id']				= $newcsID;			
			$vcountR['vcount_created']		= CURRENT_DATE_TIME;
			$vcountR['vcount_modified']	= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_VTOE_ADD_VIEW,$vcountR);
			 
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
			//////New mail (V2E Campaign Setup) //////////// 
			
			$v2eMessage = '<p>Hello '.$userD['user_first_name'].' '.$userD['user_last_name'].',</p><p>Thank you for choosing to advertise with Hashing Ad Space.  We have received your submitted V2E campaign titled "'.$vtoeArr['cs_title'].'".</p><p>We are now reviewing this submission and we will notify you once your ad is approved.</p><p>Always feel free to reach out with any questions you may have.</p><p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
			$v2eMessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$v2eMessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$v2eBody = $obj->mailBody($v2eMessage); 
			$v2eSubject = 'Your V2E Advertising Campaign has been submitted for approval';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $v2eSubject,$v2eBody,$from,"Hashing Ad Space",$type);
		//////////////////////////////////////////////////////////////////

			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your ad campaign has been submitted. It is now pending for admin approval.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php');
			
		}		
	}	
}

if(isset($_POST['Advtoe_campaign_setupGT_CNF'])) 
{	
	// pre($_POST); exit;			
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['cs_title'])=="") {$obj->add_message("message","Please enter Ad campiagn title");}
	if(trim($_POST['cs_title_2'])=="") {$obj->add_message("message","Please enter advertising title description");}	 
	if(trim($_POST['cs_desc_1'])=="") {$obj->add_message("message","Please enter advertising description line 2");}
	if(trim($_SESSION['cs_set_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_POST['cs_desti_url'])=="") {$obj->add_message("message","Destination URL should not be blank!");}		
	if(!$obj->isURL(trim($_POST['cs_desti_url']))) {$obj->add_message("message","Destination URL should be valid!");}		
	if(trim($_SESSION['cs_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!");}			
	if(strpos($_POST['cs_desti_url'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['cs_desti_url'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['cs_desti_url'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['cs_desti_url'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}	
	if(trim($_SESSION['cs_view']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_SESSION['cs_view_cost']) <= 0) {$obj->add_message("message","Asimi should be valid!");}
	
	if($obj->get_message("message")=="")
	{ 
		$desti_url    = $_POST['cs_desti_url'];  
		$parse        = parse_url($desti_url);
		$get_full_url = $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
		$get_url      = $obj->filter_mysql($parse['host']); 	
		$domain_url   = $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));		
		$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);		
		if(!empty($domainBL['db_id']))
		{
			$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.Please try another URL.');		
			$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
		}
	}
	 	
	if($obj->get_message("message")=="")
	{		 
		$cs_title_2 	= strlen($obj->filter_mysql($_POST['cs_title_2']));
		$cs_desc_1 		= strlen($obj->filter_mysql($_POST['cs_desc_1']));			 
		if($cs_title_2 > 30) $obj->add_message("message","Maximum character limit of Advertising Title Description is 30."); 
		if($cs_desc_1 > 50) $obj->add_message("message","Maximum character limit of Advertising Description Line 2 is 50"); 
	}
	
	if($obj->get_message("message")=="")
	{ 
		$vtoeBalance 	= $obj->get_view_to_earn_asimi_balance($_SESSION['user']['u_login_id']);
		$cs_asimi 		= trim($obj->filter_mysql($_SESSION['cs_set_asimi']));			 
		if($cs_asimi > $vtoeBalance) $obj->add_message("message","You have insufficient funds to setup this campaign.");		 
	}
	
	if($obj->get_message("message")=="")
	{ 
		if(trim($_SESSION['cs_duration']) !="")
		{	
			$val_duration = $obj->filter_mysql(trim($_SESSION['cs_duration']));
			if($val_duration !='7' && $val_duration !='15') $obj->add_message("message","Please select valid ad duration time!");	 
		}
	}	
	
	if($obj->get_message("message")=="")
	{ 	
		$totvtoepermis 	= $adset['baset_vtoe_ad_view'];
		$tot_setup_camp = $obj->total_active_vtoe_campaign($_SESSION['user']['u_login_id']);
		if($tot_setup_camp >= $totvtoepermis) $obj->add_message("message",'You Reached The Maximum Number Of V2E ads.'); 
	}
	
	if($obj->get_message("message")=="")
	{ 
		$cs_asimi 		= trim($_SESSION['cs_set_asimi']);
		$cs_set_view 	= trim($_SESSION['cs_view']); 
		$cs_click_view 	= trim($_SESSION['cs_view_cost']); 
		// $min_set_view 	= ($cs_asimi*10); 
		$min_set_view 	= $cs_asimi;
		$max_set_view   = round($cs_asimi*$_SESSION['cur_asimi_rate']*100*100);	 
		if($min_set_view > $cs_set_view || $max_set_view < $cs_set_view)
		{
			$obj->add_message("message","Please set your Apply Views between ".$min_set_view." to ".$max_set_view."");
			$obj->reDirect('view_to_earn_campaign_setup.php');
		}			
	}
	
	$cs_geo_view 		= $obj->filter_numeric($_SESSION['cs_view']);	
	$gconID 			= $obj->filter_mysql($_POST['geo_country_id']);
	$gcon_groupID 		= $obj->filter_mysql($_POST['geo_select_con_group_id']); 
	$selected_lang      = $obj->filter_mysql($_POST['sal']);
	$cheklang      		= $obj->filter_mysql($_POST['sal_lan']);
	 
	if(($gconID !='' || $gcon_groupID !='') && ($selected_lang !='')) $set_cs_geo_view = round($cs_geo_view*.7);  
	else if($gconID !='' || $gcon_groupID !='')  $set_cs_geo_view = round($cs_geo_view*.7); 
	else if($selected_lang !='')  $set_cs_geo_view = round($cs_geo_view*.7); 
	else $set_cs_geo_view = $cs_geo_view;
	
	$cs_click_view_cost = $obj->floorDec($cs_asimi/$set_cs_geo_view,8); 
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{ 
			$vtoeArr['cs_title'] 			= $obj->filter_mysql($_POST['cs_title']);
			$vtoeArr['cs_title_2'] 			= $obj->filter_mysql($_POST['cs_title_2']);
			$vtoeArr['cs_desc_1'] 			= $obj->filter_mysql($_POST['cs_desc_1']);			 
			$vtoeArr['cs_desti_url'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['cs_desti_url'])));   
			$vtoeArr['cs_ad_duration'] 		= $obj->filter_mysql($_SESSION['cs_duration']);	
			$vtoeArr['cs_asimi'] 			= $obj->filter_mysql($_SESSION['cs_set_asimi']);			
			$vtoeArr['cs_view_cost'] 		= $cs_click_view_cost;
			if($gconID !='' || $gcon_groupID !='' || $selected_lang !='')
			{
				$vtoeArr['cs_view']  				= $set_cs_geo_view;
				$vtoeArr['cs_geo_view'] 			= $obj->filter_mysql($_SESSION['cs_view']);	
				if($gcon_groupID) $vtoeArr['cs_geo_con_group_id']  	= ",".$gcon_groupID.",";
				if($gconID) 	  $vtoeArr['cs_geo_con_id'] 		= ",".$gconID.",";		 
				if($selected_lang)	 	$vtoeArr['cs_geo_lang_id'] 	= ",".$selected_lang.",";
				//else if($cheklang)	 	$vtoeArr['cs_geo_lang_id'] 	= 'all';			
			}
			else
			{
				$vtoeArr['cs_view']  			= $obj->filter_mysql($_SESSION['cs_view']);
				// $vtoeArr['cs_geo_view'] 		= $set_cs_geo_view;				
			}
			
			$vtoeArr['user_id'] 			= $_SESSION['user']['u_login_id'];	
			$vtoeArr['cs_status']			= 'Inactive';			
			$vtoeArr['cs_created']			= CURRENT_DATE_TIME;
			$vtoeArr['cs_modified']			= CURRENT_DATE_TIME;				
			$newcsID = $obj->insertData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr);
			
			$vaaR['vaa_asimi'] 				= $obj->filter_mysql($_SESSION['cs_set_asimi']);				
			$vaaR['user_id'] 				= $_SESSION['user']['u_login_id'];					 
			$vaaR['vaa_type']				= 'A';
			$vaaR['cs_id']					= $newcsID;			
			$vaaR['vaa_created']			= CURRENT_DATE_TIME;
			$vaaR['vaa_modified']			= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_VTOE_ADD_ASIMI,$vaaR);	
			 
			if($gconID) {
				$vcountR['vcount_view']  		= $set_cs_geo_view;
				$vcountR['vcount_geo_view'] 	= $obj->filter_mysql($cs_set_view);	
			}				
			else {
				$vcountR['vcount_view']  		= $obj->filter_mysql($cs_set_view);	
				//$vcountR['vcount_geo_view'] 	= $set_cs_geo_view;		
			}				
			$vcountR['user_id'] 			= $_SESSION['user']['u_login_id'];					 
			$vcountR['vcount_type']			= 'A';
			$vcountR['cs_id']				= $newcsID;			
			$vcountR['vcount_created']		= CURRENT_DATE_TIME;
			$vcountR['vcount_modified']		= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_VTOE_ADD_VIEW,$vcountR);	
			
			$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));  
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
			//////New mail (V2E Campaign Setup) //////////// 
			
			$v2eMessage = '<p>Hello '.$userD['user_first_name'].' '.$userD['user_last_name'].',</p><p>Thank you for choosing to advertise with Hashing Ad Space.  We have received your submitted V2E campaign titled "'.$vtoeArr['cs_title'].'".</p><p>We are now reviewing this submission and we will notify you once your ad is approved.</p><p>Always feel free to reach out with any questions you may have.</p><p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
		  
			$v2eMessage .= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$v2eMessage .='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			
			$v2eBody = $obj->mailBody($v2eMessage); 
			$v2eSubject = 'Your V2E Advertising Campaign has been submitted for approval';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $v2eSubject,$v2eBody,$from,"Hashing Ad Space",$type);
			//////////////////////////////////////////////////////////////////

			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your ad campaign has been submitted. It is now pending for admin approval.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php');
			
		}		
	}	
} 
 


if(isset($_POST['UPCAMPSETUP'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['cs_title'])=="") {$obj->add_message("message","Please enter Ad campiagn title");}
	if(trim($_POST['cs_title_2'])=="") {$obj->add_message("message","Please enter advertising title description");}
	
	if(trim($_POST['cs_desc_1'])=="") {$obj->add_message("message","Please enter advertising description line 2");}
	//if(trim($_POST['cs_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_POST['cs_desti_url'])=="") {$obj->add_message("message","Destination URL should not be blank!");}		
	if(!$obj->isURL(trim($_POST['cs_desti_url']))) {$obj->add_message("message","Destination URL should be valid!");}		
	if(trim($_POST['cs_ad_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!");}			
	if(strpos($_POST['cs_desti_url'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['cs_desti_url'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['cs_desti_url'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['cs_desti_url'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	
	if($obj->get_message("message")=="")
	{		 
		$cs_title_2 = strlen($obj->filter_mysql($_POST['cs_title_2']));
		$cs_desc_1 = strlen($obj->filter_mysql($_POST['cs_desc_1']));			 
		if($cs_title_2 > 30)
		{				 
			$obj->add_message("message","Maximum character limit of Advertising Title Description is 30.");
		}		 
		if($cs_desc_1 > 50)
		{				 
			$obj->add_message("message","Maximum character limit of Advertising Description Line 2 is 50");
		}
	}
	
	
	if($obj->get_message("message")=="")
	{ 
		$desti_url = $_POST['cs_desti_url'];  
		$parse = parse_url($desti_url);
		$get_full_url = $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
		$get_url = $obj->filter_mysql($parse['host']); 	
		$domain_url = $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));
		
		$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);
		
		if(!empty($domainBL['db_id'])){
			$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.Please try another URL.');				
			$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
		}
	}
	
	$csId = $obj->filter_mysql($obj->filter_numeric($_REQUEST['csId']));
	if($obj->get_message("message")=="")
	{ 
		if(trim($_POST['cs_ad_duration']) !="")
		{	
			$val_duration = trim($_POST['cs_ad_duration']);				 
			if($val_duration !='7' && $val_duration !='15')
			{   
				$obj->add_message("message","Please select valid ad duration times!");
				$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
			}
		}
	}	
 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			$dataVE = $obj->selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","user_id='".$userId."' and cs_id='".$csId."' and cs_status='Pause'",1); 
			
			if(strlen($dataVE['cs_title_2']) != $obj->filter_mysql(strlen($_POST['cs_title_2'])) || strlen($dataVE['cs_desc_1']) != $obj->filter_mysql(strlen($_POST['cs_desc_1']))|| strlen($dataVE['cs_desti_url']) != $obj->filter_mysql(strlen($_POST['cs_desti_url']))) $cs_status = 1;	
					
			$vtoeArr['cs_title'] 			= $obj->filter_mysql($_POST['cs_title']);
			$vtoeArr['cs_title_2'] 			= $obj->filter_mysql($_POST['cs_title_2']);
			$vtoeArr['cs_desc_1'] 			= $obj->filter_mysql($_POST['cs_desc_1']);			 
			$vtoeArr['cs_desti_url'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['cs_desti_url']))); 	 
			$vtoeArr['cs_ad_duration'] 		= $obj->filter_mysql($_POST['cs_ad_duration']);	
			if($cs_status) {
				$vtoeArr['cs_status']		= 'Inactive';
			} else {
				$vtoeArr['cs_status']		= 'Active';
			}			
			$vtoeArr['cs_created']			= CURRENT_DATE_TIME;
			$vtoeArr['cs_modified']			= CURRENT_DATE_TIME;		 
			$obj->updateData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr,"cs_id='".$csId."'");	
			
			if($cs_status) {				
				$obj->add_message("message","Your ad campaign has been updated. It is now pending for admin approval.");	
			} else {
				$obj->add_message("message","Your ad campaign has been updated.");		
			}
			$_SESSION['messageClass'] = "successClass";	
			$obj->reDirect('active_campaign.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php?csId='.$_REQUEST['csId']);
			
		}		
	}	
}

if(isset($_POST['UPDATE_CAMP_PARAM'])) 
{	
	$_SESSION['messageClass'] = "errorClass";		 
	if(trim($_POST['cs_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");}
	if(trim($_POST['cs_view']) <= 0) {$obj->add_message("message","Asimi should be valid!");}
	if(trim($_POST['cs_view_cost']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	 
	$csId = $obj->filter_numeric($_REQUEST['csId']);
	$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));
	$dataVE = $obj->selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","user_id='".$userId."' and cs_id='".$csId."' and cs_status='Pause'",1);  
	
	$cs_asimi 		= $obj->filter_mysql($_POST['cs_asimi']);
	$cs_view 		= $obj->filter_mysql($_POST['cs_view']);
	// $cs_view_cost 	= $obj->filter_mysql($_POST['cs_view_cost']);
	// $min_set_view 	= ($cs_asimi*10);
	// $max_set_view 	= ($cs_asimi*2000);
	$min_set_view 	= $cs_asimi;
	$max_set_view 	= round($cs_asimi*$_SESSION['cur_asimi_rate']*100*100);
	
	$cs_view_cost_new_x = $cs_asimi/$cs_view;
	$cs_view_cost_new = $obj->floorDec($cs_view_cost_new_x);
	
 
	
	if($obj->get_message("message")=="")
	{ 
		if($min_set_view > $cs_view || $max_set_view < $cs_view)
		{				 
			$obj->add_message("message","Please set your Apply Views between ".$min_set_view." to ".$max_set_view."");
		}
	}
	
	if($obj->get_message("message")=="")
	{	$applyAsimi = ($dataVE['cs_asimi_used']+$cs_asimi);
		if($applyAsimi < $dataVE['cs_asimi_used'])
		{				
			$obj->add_message("message","Your current Apply Asimi is not less than used asimi.");	
		}			
	}
		
	 
		
	if($obj->get_message("message")=="")
	{ 
		$vtoeBalance = $obj->get_view_to_earn_asimi_balance($_SESSION['user']['u_login_id']);
		 
		$tot_valable_balance =  $vtoeBalance+($dataVE['cs_asimi']-$dataVE['cs_asimi_used']);		
		if($cs_asimi > $tot_valable_balance)
		{				 
			$obj->add_message("message","You have insufficient funds to setup this campaign.");
		}
	}
	
		
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			/*
			if($cs_asimi > $dataVE['cs_asimi'] || $cs_view > $dataVE['cs_view'])
			{				 
				$vaa_asimi 		=  $cs_asimi-$dataVE['cs_asimi']+$dataVE['cs_asimi_used'];
				$vcount_view 	=  $cs_view-$dataVE['cs_view']+$dataVE['cs_view_used'];
				$vaaArr['vaa_type'] 	= 'A';
				$vaaR['vcount_type'] 	= 'A';				
			} else if($cs_asimi < $dataVE['cs_asimi'] || $cs_view < $dataVE['cs_view'])
			{				
				$vaa_asimi 		=  $dataVE['cs_asimi']-$cs_asimi-$dataVE['cs_asimi_used'];
				$vcount_view 	=  $dataVE['cs_view']-$cs_view-$dataVE['cs_view_used'];
				$vaaArr['vaa_type'] 	= 'D';
				$vaaR['vcount_type'] 	= 'D';	
			}
			*/
			if($cs_asimi >= ($dataVE['cs_asimi']-$dataVE['cs_asimi_used']))
			{				 
				$vaa_asimi 		        =  $cs_asimi-$dataVE['cs_asimi']+$dataVE['cs_asimi_used'];				 
				$vaaArr['vaa_type'] 	= 'A';					
			}
			else
			{
				$vaa_asimi 		        =  $dataVE['cs_asimi']-$cs_asimi-$dataVE['cs_asimi_used'];				 
				$vaaArr['vaa_type'] 	= 'D';
			} 
			
			if($cs_view >= ($dataVE['cs_view'] - $dataVE['cs_view_used']))
			{				
				$vcount_view 	        =  $cs_view-$dataVE['cs_view']+$dataVE['cs_view_used'];			 
				$vaaR['vcount_type'] 	= 'A';		
			}
			else
			{
				$vcount_view 	        =  $dataVE['cs_view']-$cs_view-$dataVE['cs_view_used'];				 
				$vaaR['vcount_type'] 	= 'D';	
			}
			
			$vtoeArr['cs_asimi'] 			= $cs_asimi+$dataVE['cs_asimi_used'];
			$vtoeArr['cs_view'] 			= $cs_view+$dataVE['cs_view_used'];
			$vtoeArr['cs_view_cost'] 		= $cs_view_cost_new;			 
			$vtoeArr['cs_status']			= 'Active';			 
			$vtoeArr['cs_modified']			= CURRENT_DATE_TIME;			
			$obj->updateData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr,"cs_id='".$csId."'");
			
			if($cs_asimi != $dataVE['cs_asimi'])
			{	
				if($vaa_asimi > 0)
				{	
					$vaaArr['user_id'] 				= $userId;
					$vaaArr['cs_id'] 				= $csId;
					$vaaArr['vaa_asimi'] 			= $vaa_asimi;			 
					$vaaArr['vaa_created'] 			= date('Y-m-d');	
					$vaaArr['vaa_modified'] 		= CURRENT_DATE_TIME;			 
					$obj->insertData(TABLE_VTOE_ADD_ASIMI,$vaaArr);
				}
			}
			if($cs_view != $dataVE['cs_view'])
			{	
				if($vcount_view > 0)
				{						
					$vaaR['vcount_view'] 		= $vcount_view;				
					$vaaR['user_id'] 			= $userId;				 
					$vaaR['cs_id']				= $csId;			
					$vaaR['vcount_created']		= CURRENT_DATE_TIME;
					$vaaR['vcount_modified']	= CURRENT_DATE_TIME;				
					$obj->insertData(TABLE_VTOE_ADD_VIEW,$vaaR);				
				}
			}
			
			$obj->add_message("message","Your ad campaign has been updated.");			 
			$_SESSION['messageClass'] = "successClass";	
			$obj->reDirect('active_campaign.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php?csId='.$_REQUEST['csId']);
		}		
	}	
}


 
/*********************************premium ***********************/

if(isset($_POST['APPLYPRMPRE'])) 
{				 
	$_SESSION['messageClass'] = "errorClass";
	 
	if(trim($_POST['cs_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");}	
	if(trim($_POST['cs_set_view']) <= 0) {$obj->add_message("message","Apply Views should be valid!");} 	
	if(trim($_POST['cs_click_view']) <= 0) {$obj->add_message("message","Click Value should be valid!");}	
	if(trim($_POST['cs_ad_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!");}		
		 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	

			$tot_click_view_x 	= $obj->filter_mysql($_POST['cs_asimi'])/$obj->filter_mysql($_POST['cs_set_view']);	 
			$tot_click_val_cost = $obj->floorDec($tot_click_view_x,8);
	
			$_SESSION['cs_set_asimi'] 	= $obj->filter_mysql($_POST['cs_asimi']);
			$_SESSION['cs_view'] 		= $obj->filter_mysql($_POST['cs_set_view']);
			$_SESSION['cs_view_cost'] 	= $obj->filter_mysql($tot_click_val_cost);
			$_SESSION['cs_duration'] 	= 30;	
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","You have successfully applied your advertising parameters.");			 
			//$obj->reDirect('view_to_earn_premium_campaign_setup.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			//$obj->reDirect('view_to_earn_premium_campaign_setup.php');		
		}		
	}	
}

if(isset($_POST['Advtoe_pre_campaign_setup'])) 
{				 
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['cs_title'])=="") {$obj->add_message("message","Please enter Ad campiagn title");}
	if(trim($_POST['cs_title_2'])=="") {$obj->add_message("message","Please enter advertising title description");}
	
	if(trim($_POST['cs_desc_1'])=="") {$obj->add_message("message","Please enter advertising description line 2");}
	if(trim($_SESSION['cs_set_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_POST['cs_desti_url'])=="") {$obj->add_message("message","Destination URL should not be blank!");}		
	if(!$obj->isURL(trim($_POST['cs_desti_url']))) {$obj->add_message("message","Destination URL should be valid!");}		
	if(trim($_SESSION['cs_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!");}			
	if(strpos($_POST['cs_desti_url'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['cs_desti_url'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['cs_desti_url'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['cs_desti_url'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	
	if(trim($_SESSION['cs_view']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_SESSION['cs_view_cost']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	 
	$userId = $obj->filter_numeric($_SESSION['user']['u_login_id']);   
	if($obj->get_message("message")=="")
	{ 
		$desti_url = $_POST['cs_desti_url'];  
		$parse = parse_url($desti_url);
		$get_full_url = $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
		$get_url = $obj->filter_mysql($parse['host']); 	
		$domain_url = $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));
		
		$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);
		
		if(!empty($domainBL['db_id'])){
			$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.Please try another URL.');				
			$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
		}
	}
		
			
	if($obj->get_message("message")=="")
	{		 
		$cs_title_2 = strlen($obj->filter_mysql($_POST['cs_title_2']));
		$cs_desc_1 = strlen($obj->filter_mysql($_POST['cs_desc_1']));			 
		if($cs_title_2 > 30)
		{				 
			$obj->add_message("message","Maximum character limit of Advertising Title Description is 30.");
		}		 
		if($cs_desc_1 > 50)
		{				 
			$obj->add_message("message","Maximum character limit of Advertising Description Line 2 is 50");
		}
	}
	
	if($obj->get_message("message")=="")
	{ 
		$vtoeBalance = $obj->get_view_to_earn_asimi_balance($userId);
		$cs_asimi = trim($obj->filter_mysql($_SESSION['cs_set_asimi']));			 
		if($cs_asimi > $vtoeBalance)
		{				 
			$obj->add_message("message","You have insufficient funds to setup this campaign.");
		}
	}
	
	if($obj->get_message("message")=="")
	{ 
		$cs_asimi = trim($_SESSION['cs_set_asimi']);
		$cs_set_view = trim($_SESSION['cs_view']); 
		$cs_click_view = trim($_SESSION['cs_view_cost']); 
		
		$min_set_view = round(($cs_asimi*$_SESSION['cur_asimi_rate']*100)/5);		 
		$max_set_view =  round(($cs_asimi*2*$_SESSION['cur_asimi_rate']*100)/1);			 
		$cs_click_view_new = $obj->floorDec($cs_asimi/$cs_set_view,8);		
		if($min_set_view > $cs_set_view || $max_set_view < $cs_set_view)
		{				 
			$obj->add_message("message","Please set your Apply Views between ".$min_set_view." to ".$max_set_view."");
		}
	}
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			  
			$vtoeArr['cs_title'] 			= $obj->filter_mysql($_POST['cs_title']);
			$vtoeArr['cs_title_2'] 			= $obj->filter_mysql($_POST['cs_title_2']);
			$vtoeArr['cs_desc_1'] 			= $obj->filter_mysql($_POST['cs_desc_1']);			 
			$vtoeArr['cs_desti_url'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['cs_desti_url'])));   
			$vtoeArr['cs_ad_duration'] 		= 30;	
			$vtoeArr['cs_asimi'] 			= $obj->filter_mysql($_SESSION['cs_set_asimi']);	
			$vtoeArr['cs_view'] 			= $obj->filter_mysql($_SESSION['cs_view']);	
			$vtoeArr['cs_view_cost'] 		= $cs_click_view_new;				
			$vtoeArr['user_id'] 			= $userId;
			$vtoeArr['cs_type'] 			= 'p';	
			$vtoeArr['cs_status']			= 'Inactive';			
			$vtoeArr['cs_created']			= CURRENT_DATE_TIME;
			//$vtoeArr['cs_modified']			= CURRENT_DATE_TIME;				
			$newcsID = $obj->insertData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr);
			
			$vaaR['vaa_asimi'] 			= $obj->filter_mysql($_SESSION['cs_set_asimi']);				
			$vaaR['user_id'] 			= $userId;					 
			$vaaR['vaa_type']			= 'A';
			$vaaR['cs_id']				= $newcsID;			
			$vaaR['vaa_created']		= CURRENT_DATE_TIME;
			$vaaR['vaa_modified']		= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_VTOE_ADD_ASIMI,$vaaR);	
			
			$vcountR['vcount_view'] 		= $obj->filter_mysql($cs_set_view);				
			$vcountR['user_id'] 			= $userId;					 
			$vcountR['vcount_type']			= 'A';
			$vcountR['cs_id']				= $newcsID;			
			$vcountR['vcount_created']		= CURRENT_DATE_TIME;
			//$vcountR['vcount_modified']		= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_VTOE_ADD_VIEW,$vcountR);	
			 
				//////////////////////////////////////////////////////////////////	
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);			 
			$v2eMessage = '<p>Hello '.$userD['user_first_name'].' '.$userD['user_last_name'].',</p><p>Thank you for choosing to advertise with Hashing Ad Space.  We have received your submitted V2E campaign titled "'.$vtoeArr['cs_title'].'".</p><p>We are now reviewing this submission and we will notify you once your ad is approved.</p><p>Always feel free to reach out with any questions you may have.</p><p>Maximize Your Online Business Success!<br></p><br><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>';

			
			$v2eMessage 	.= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr 		= "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$v2eMessage 	.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$v2eBody 		= $obj->mailBody($v2eMessage); 
			$v2eSubject 	= 'Your V2E Advertising premium Campaign has been submitted for approval';	
			$to       		= $_SESSION['user']['u_login_user_email'];
			$from     		= FROM_EMAIL_2;	
			$obj->sendMailSES($to, $v2eSubject,$v2eBody,$from,"Hashing Ad Space",$type);				
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your ad premium campaign has been submitted. It is now pending for admin approval.");			 
			$obj->reDirect('active_campaign.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('active_campaign.php');
			
		}		
	}	
}

if(isset($_POST['UPPRECAMPSETUP'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['cs_title'])=="") {$obj->add_message("message","Please enter Ad campiagn title");}
	if(trim($_POST['cs_title_2'])=="") {$obj->add_message("message","Please enter advertising title description");}
	if(strlen($_POST['cs_title_2']) > 30) {$obj->add_message("message","Advertising title description maximum 30 characters!");} 
	if(trim($_POST['cs_desc_1'])=="") {$obj->add_message("message","Please enter advertising description line 2");}
	//if(trim($_POST['cs_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	if(trim($_POST['cs_desti_url'])=="") {$obj->add_message("message","Destination URL should not be blank!");}		
	if(!$obj->isURL(trim($_POST['cs_desti_url']))) {$obj->add_message("message","Destination URL should be valid!");}		
	if(trim($_POST['cs_ad_duration'])=="") {$obj->add_message("message","Please select valid ad duration time!");}			
	if(strpos($_POST['cs_desti_url'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['cs_desti_url'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['cs_desti_url'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['cs_desti_url'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	
	
	if($obj->get_message("message")=="")
	{ 
		$desti_url = $_POST['cs_desti_url'];  
		$parse = parse_url($desti_url);
		$get_full_url = $obj->filter_mysql($parse['scheme'].'://'.$parse['host']); 
		$get_url = $obj->filter_mysql($parse['host']); 	
		$domain_url = $obj->filter_mysql(preg_replace('/^www\./', '', $parse['host']));
		
		$domainBL = $obj->selectData(TABLE_DOMAIN_BLACKLIST,"","(db_url like '%".$get_full_url."%' or db_url like '%".$get_url."%' or db_url like '%".$domain_url."%') and db_status='Active'",1);
		
		if(!empty($domainBL['db_id'])){
			$obj->add_message("message",'The ad you are trying to submit belongs to a domain that is banned from advertising.Please try another URL.');				
			$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
		}
	}
	
	$csId = $obj->filter_mysql($obj->filter_numeric($_REQUEST['csId']));
	if($obj->get_message("message")=="")
	{ 
		if(trim($_POST['cs_ad_duration']) !="")
		{	
			$val_duration = trim($_POST['cs_ad_duration']);				 
			if($val_duration !='7' && $val_duration !='15' && $val_duration !='30')
			{   
				$obj->add_message("message","Please select valid ad duration times!");
				$obj->reDirect('update_view_to_earn_campaign_setup.php?csId='.$csId);
			}
		}
	}	
 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			$dataVE = $obj->selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","user_id='".$userId."' and cs_id='".$csId."' and cs_status='Pause'",1); 
			
			if(strlen($dataVE['cs_title_2']) != $obj->filter_mysql(strlen($_POST['cs_title_2'])) || strlen($dataVE['cs_desc_1']) != $obj->filter_mysql(strlen($_POST['cs_desc_1']))|| strlen($dataVE['cs_desti_url']) != $obj->filter_mysql(strlen($_POST['cs_desti_url']))) $cs_status = 1;	
					
			$vtoeArr['cs_title'] 			= $obj->filter_mysql($_POST['cs_title']);
			$vtoeArr['cs_title_2'] 			= $obj->filter_mysql($_POST['cs_title_2']);
			$vtoeArr['cs_desc_1'] 			= $obj->filter_mysql($_POST['cs_desc_1']);			 
			$vtoeArr['cs_desti_url'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['cs_desti_url']))); 	 
			$vtoeArr['cs_ad_duration'] 		= $obj->filter_mysql($_POST['cs_ad_duration']);	
			if($cs_status) {
				$vtoeArr['cs_status']		= 'Inactive';
			} else {
				$vtoeArr['cs_status']		= 'Active';
			}			
			//$vtoeArr['cs_created']			= CURRENT_DATE_TIME;
			$vtoeArr['cs_modified']			= CURRENT_DATE_TIME;		 
			$obj->updateData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr,"cs_id='".$csId."'");	
			
			if($cs_status) {				
				$obj->add_message("message","Your ad campaign has been updated. It is now pending for admin approval.");	
			} else {
				$obj->add_message("message","Your ad campaign has been updated.");		
			}
			$_SESSION['messageClass'] = "successClass";	
			$obj->reDirect('active_campaign.php');
		} 
		else {
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php?csId='.$_REQUEST['csId']);
			
		}		
	}	
}

if(isset($_POST['UPDATE_PRE_CAMP_PARAM'])) 
{	
	$_SESSION['messageClass'] = "errorClass";		 
	if(trim($_POST['cs_asimi']) <= 0) {$obj->add_message("message","Asimi should be valid!");}
	if(trim($_POST['cs_view']) <= 0) {$obj->add_message("message","Asimi should be valid!");}
	if(trim($_POST['cs_view_cost']) <= 0) {$obj->add_message("message","Asimi should be valid!");} 
	 
	$csId = $obj->filter_mysql($obj->filter_numeric($_REQUEST['csId']));
	$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));
	$dataVE = $obj->selectData(TABLE_VTOE_CAMPAIGN_SETUP,"","user_id='".$userId."' and cs_id='".$csId."' and cs_status='Pause'",1);  
	
	 $cs_asimi 		= $obj->filter_mysql($_POST['cs_asimi']);
	  $cs_view 		= $obj->filter_mysql($_POST['cs_view']);
	 
	$min_set_view = round(($cs_asimi*$_SESSION['cur_asimi_rate']*100)/5);		 
	$max_set_view = round(($cs_asimi*2*$_SESSION['cur_asimi_rate']*100)/1);	 
	 
	$cs_view_cost_new_x = $cs_asimi/$cs_view;
	$cs_view_cost_new = $obj->floorDec($cs_view_cost_new_x,8);
 
 
	if($obj->get_message("message")=="")
	{ 
		if($min_set_view > $cs_view || $max_set_view < $cs_view)
		{				 
			$obj->add_message("message","Please set your Apply Views between ".$min_set_view." to ".$max_set_view."");
		}
	}
	
	
	if($obj->get_message("message")=="")
	{	$applyAsimi = ($dataVE['cs_asimi_used']+$cs_asimi);
		if($applyAsimi < $dataVE['cs_asimi_used'])
		{				
			$obj->add_message("message","Your current Apply Asimi is not less than used asimi.");	
		}			
	}


	
	if($obj->get_message("message")=="")
	{ 
		$vtoeBalance = $obj->get_view_to_earn_asimi_balance($_SESSION['user']['u_login_id']);
		$tot_valable_balance =  $vtoeBalance+($dataVE['cs_asimi']-$dataVE['cs_asimi_used']);
		if($cs_asimi > $tot_valable_balance)
		{				 
			$obj->add_message("message","You have insufficient funds to setup this campaign.");
		}
	}
	
		
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			/*
			if($cs_asimi > $dataVE['cs_asimi'] || $cs_view > $dataVE['cs_view'])
			{				 
				$vaa_asimi 		=  $cs_asimi-$dataVE['cs_asimi']+$dataVE['cs_asimi_used'];				 
				$vaaArr['vaa_type'] 	= 'A';	
				$vcount_view 	=  $cs_view-$dataVE['cs_view']+$dataVE['cs_view_used'];			 
				$vaaR['vcount_type'] 	= 'A';		
				
			} else if($cs_asimi < $dataVE['cs_asimi'] || $cs_view < $dataVE['cs_view'])
			{				
				$vaa_asimi 		=  $dataVE['cs_asimi']-$cs_asimi-$dataVE['cs_asimi_used'];				 
				$vaaArr['vaa_type'] 	= 'D';
				$vcount_view 	=  $dataVE['cs_view']-$cs_view-$dataVE['cs_view_used'];				 
				$vaaR['vcount_type'] 	= 'D';	
			}
			*/
			if($cs_asimi >= ($dataVE['cs_asimi']-$dataVE['cs_asimi_used']))
			{				 
				$vaa_asimi 		        =  $cs_asimi-$dataVE['cs_asimi']+$dataVE['cs_asimi_used'];				 
				$vaaArr['vaa_type'] 	= 'A';					
			}
			else
			{
				$vaa_asimi 		        =  $dataVE['cs_asimi']-$cs_asimi-$dataVE['cs_asimi_used'];				 
				$vaaArr['vaa_type'] 	= 'D';
			} 
			
			if($cs_view >= ($dataVE['cs_view'] - $dataVE['cs_view_used']))
			{				
				$vcount_view 	        =  $cs_view-$dataVE['cs_view']+$dataVE['cs_view_used'];			 
				$vaaR['vcount_type'] 	= 'A';		
			}
			else
			{
				$vcount_view 	        =  $dataVE['cs_view']-$cs_view-$dataVE['cs_view_used'];				 
				$vaaR['vcount_type'] 	= 'D';	
			}
						
 
			
			$vtoeArr['cs_asimi'] 			= $cs_asimi+$dataVE['cs_asimi_used'];
			$vtoeArr['cs_view'] 			= $cs_view+$dataVE['cs_view_used'];
			$vtoeArr['cs_view_cost'] 		= $cs_view_cost_new;			 
			$vtoeArr['cs_status']			= 'Active';			 
			$vtoeArr['cs_modified']			= CURRENT_DATE_TIME; ////////////new code added//////////////
			$obj->updateData(TABLE_VTOE_CAMPAIGN_SETUP,$vtoeArr,"cs_id='".$csId."'");
			
			
			if($cs_asimi != $dataVE['cs_asimi'])
			{	
				if($vaa_asimi > 0)
				{	
					$vaaArr['user_id'] 				= $userId;
					$vaaArr['cs_id'] 				= $csId;
					$vaaArr['vaa_asimi'] 			= $vaa_asimi;			 
					$vaaArr['vaa_created'] 			= date('Y-m-d');	
					$vaaArr['vaa_modified'] 		= CURRENT_DATE_TIME;			 
					$obj->insertData(TABLE_VTOE_ADD_ASIMI,$vaaArr);
				}
			}	
			if($cs_view != $dataVE['cs_view'])
			{	
				if($vcount_view > 0)
				{						
					$vaaR['vcount_view'] 		= $vcount_view;				
					$vaaR['user_id'] 			= $userId;				 
					$vaaR['cs_id']				= $csId;			
					$vaaR['vcount_created']		= CURRENT_DATE_TIME;
					$vaaR['vcount_modified']	= CURRENT_DATE_TIME;				
					$obj->insertData(TABLE_VTOE_ADD_VIEW,$vaaR);				
				}
			}
			
			/*******new code added********/			 
			$obj->vtoe_setup_raffle_entry($csId);			 	
			/***************/
			$obj->add_message("message","Your ad premium campaign has been updated.");			 
			$_SESSION['messageClass'] = "successClass";	
			$obj->reDirect('active_campaign.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('view_to_earn_campaign_setup.php?csId='.$_REQUEST['csId']);
		}		
	}	
}

if(isset($_POST['LAPSetup'])) 
{	 // pre($_POST); exit;			 
	$_SESSION['messageClass'] = "errorClass";
	 
	if(trim($_POST['lap_url_1'])=="" && trim($_POST['lap_url_2'])=="") {$obj->add_message("message","Login Ad URL A and URL B both should not be blank!");}		
	 
	if(strpos($_POST['lap_url_1'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['lap_url_1'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['lap_url_1'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['lap_url_1'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	
	if(strpos($_POST['lap_url_2'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['lap_url_2'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['lap_url_2'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['lap_url_2'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	
	
	$desti_url1 = $_POST['lap_url_1'];  
	$parse1 = parse_url($desti_url1);
	$desti_url2 = $_POST['lap_url_1'];  
	$parse2 = parse_url($desti_url1);
	 
	if($parse1['scheme'] !='https' && $parse2['scheme'] !='https')
	{	
		$obj->add_message("message","Your Both link does not start with https:// .");	
		$obj->reDirect('login_ad_setup.php');
		exit;
	} 
	else if($parse1['scheme'] !='https')
	{	
		$obj->add_message("message","Your URL A link does not start with https.");	
		$obj->reDirect('login_ad_setup.php');
		exit;
	} 
	else if ($parse2['scheme'] !='https')
	{
		$obj->add_message("message","Your URL B link does not start with https.");
		$obj->reDirect('login_ad_setup.php');
		exit;		
	}
	 
	 
	
	$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));
	$lapId  = $obj->filter_mysql($obj->filter_numeric($_POST['lap_id']));	
	$lapD   = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_id='".$lapId."' and user_id='".$userId."' and lap_pstatus !='u'",1);	
	if($lapD['lap_id'] =='')
	{
		$obj->add_message("message","Package not found in your account. Please puchase Login Ad package.");	
	}
	
	if($obj->get_message("message")=="")
	{ 
		$start = date("Y-m-d H:i:s");	
		$t2 = strtotime( $start );
		$t1 = strtotime( $lapD['lap_date'] );
		$diff = $t1 - $t2;
		$hours = $diff / ( 60 * 60 );
		
		if($hours < 0)
		{
			$obj->add_message("message","Time is over to setup your Login Ad.");	
		}	
	}
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			if($lapD['lap_url_1'] =='')
			{
				$lapArr['lap_url_1']	  = $obj->filter_mysql(addslashes(stripslashes($_POST['lap_url_1'])));	
				$lapArr['lap_url1_status'] = 'n'; 
			}
			if($lapD['lap_url_2'] =='')
			{
				$lapArr['lap_url_2'] 	  = $obj->filter_mysql(addslashes(stripslashes($_POST['lap_url_2'])));	
				$lapArr['lap_url2_status'] = 'n'; 				
			}		 
			$lapArr['lap_modified']		  = CURRENT_DATE_TIME; 
			$lapArr['lap_publish_status'] = 'n'; 
			$obj->updateData(TABLE_LOGIN_AD_PACKAGE,$lapArr,"lap_id='".$lapId."'");
			// pre($lapD);
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);		  
			$LASMessage = '<p>Hello '.$userD['user_first_name'].' '.$userD['user_last_name'].',</p>
			<p>thank you for choosing to advertise with Hashing Ad Space. We have received your URL submission for the Login Ad campaign on day < '.$lapD['lap_date'].' >.</p>

			<p>We are now reviewing this submission, and we will notify you once your ad is approved.</p><p>Always feel free to reach out with any questions you may have.</p><p>Maximize Your Online Business Success!<br><br></p><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>----------------------------------------------';


			$LASMessage 	.= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr 		= "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$LASMessage 	.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$lapBody = $obj->mailBody($LASMessage); 
			$lapSubject = 'Your submission for the Login Ad campaign ';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $lapSubject,$lapBody,$from,"Hashing Ad Space",$type);
 
			 
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your Login Ad Setup has been submitted. It is now pending for admin approval.");			 
			$obj->reDirect('login_ad_setup.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('login_ad_setup.php');
			
		}		
	}	
}


if(isset($_POST['LASPSetup'])) 
{	 
	$_SESSION['messageClass'] = "errorClass"; 
	if(trim($_POST['lasp_url_1'])=="" && trim($_POST['lasp_url_2'])=="") {$obj->add_message("message","Login Ad URL A and URL B both should not be blank!");}if(strpos($_POST['lasp_url_1'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['lasp_url_1'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['lasp_url_1'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['lasp_url_1'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}	
	if(strpos($_POST['lasp_url_2'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['lasp_url_2'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['lasp_url_2'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['lasp_url_2'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');} 
	
	$desti_url1 = $_POST['lasp_url_1'];  
	$parse1 = parse_url($desti_url1);
	$desti_url2 = $_POST['lasp_url_1'];  
	$parse2 = parse_url($desti_url1);
	 
	if($parse1['scheme'] !='https' && $parse2['scheme'] !='https')
	{	
		$obj->add_message("message","Your Both link does not start with https:// .");	
		$obj->reDirect('login_ad_stakers_setup.php');
		exit;
	} 
	else if($parse1['scheme'] !='https')
	{	
		$obj->add_message("message","Your URL A link does not start with https.");	
		$obj->reDirect('login_ad_stakers_setup.php');
		exit;
	} 
	else if ($parse2['scheme'] !='https')
	{
		$obj->add_message("message","Your URL B link does not start with https.");
		$obj->reDirect('login_ad_stakers_setup.php');
		exit;		
	}
	  
	$userId = $obj->filter_mysql($obj->filter_numeric($_SESSION['user']['u_login_id']));
	$lapId  = $obj->filter_mysql($obj->filter_numeric($_POST['lasp_id']));	
	$laspD   = $obj->selectData(TABLE_LOGIN_AD_STAKER_PACKAGE,"","lasp_id='".$lapId."' and user_id='".$userId."' and lasp_pstatus !='u'",1);	
	if($laspD['lasp_id'] =='') $obj->add_message("message","Package not found in your account. Please puchase Login Ad package.");	
	  
	if($obj->get_message("message")=="")
	{ 
		$start = date("Y-m-d H:i:s");	
		$t2 = strtotime( $start );
		$t1 = strtotime( $laspD['lasp_date'] );
		$diff = $t1 - $t2;
		$hours = $diff / ( 60 * 60 ); 
		if($hours < 0) $obj->add_message("message","Time is over to setup your Login Ad.");	 
	}
	 
	if($obj->get_message("message")=="")
	{
		if($_SESSION['user']['u_login_id'] > 0) 
		{	
			if($laspD['lasp_url_1'] =='')
			{
				$lapArr['lasp_url_1']	  = $obj->filter_mysql(addslashes(stripslashes($_POST['lasp_url_1'])));	
				$lapArr['lasp_url1_status'] = 'n'; 
			}
			if($laspD['lasp_url_2'] =='')
			{
				$lapArr['lasp_url_2'] 	  = $obj->filter_mysql(addslashes(stripslashes($_POST['lasp_url_2'])));	
				$lapArr['lasp_url2_status'] = 'n'; 				
			}		 
			$lapArr['lasp_modified']		  = CURRENT_DATE_TIME; 
			$lapArr['lasp_publish_status'] = 'n'; 
			$obj->updateData(TABLE_LOGIN_AD_STAKER_PACKAGE,$lapArr,"lasp_id='".$lapId."'");
			// pre($laspD);
			$userD  = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);		  
			$LASMessage = '<p>Hello '.$userD['user_first_name'].' '.$userD['user_last_name'].',</p>
			<p>thank you for choosing to advertise with Hashing Ad Space. We have received your URL submission for the Login Ad Stakers campaign on '.$laspD['lasp_date'].'.</p>
			<p>We are now reviewing this submission, and we will notify you once your ad is approved.</p><p>Always feel free to reach out with any questions you may have.</p><p>Maximize Your Online Business Success!<br><br></p><strong>Hashing Ad Space</strong><br>'.ADDRESS_LINE_1.ADDRESS_SEPRATER_1.'<br>'.ADDRESS_LINE_2.ADDRESS_SEPRATER_2.ADDRESS_LINE_3.'<br>----------------------------------------------<br>S: https://hashingadspace.zendesk.com/<br>W: '.FURL.'<br>----------------------------------------------';

 
			$LASMessage 	.= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr 		= "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$LASMessage 	.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
			$lapBody = $obj->mailBody($LASMessage); 
			$lapSubject = 'Your submission for the Login Ad Stakers campaign ';	
			$to       = $_SESSION['user']['u_login_user_email'];
			$from     = FROM_EMAIL_2;	
			$obj->sendMailSES($to, $lapSubject,$lapBody,$from,"Hashing Ad Space",$type); 
			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your Login Ad Setup has been submitted. It is now pending for admin approval.");			 
			$obj->reDirect('login_ad_stakers_setup.php');
		} 
		else 
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Something Wrong.Please try again.");			 
			$obj->reDirect('login_ad_stakers_setup.php');
			
		}		
	}	
}



if(isset($_POST['GetAsimi'])) 
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['wall_asimi_token'])=="") {$obj->add_message("message","Please Enter Asimi token");}		
	if($obj->get_message("message")=="")
	{	  
		$userId = $_SESSION['user']['u_login_id']; 
		$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);

		$_SESSION['user_wallet_address'] = $userD['user_wallet_address'];
		$_SESSION['wall_asimi_token'] = $obj->filter_mysql($_POST['wall_asimi_token']);	
		
	}	
} 

if(isset($_POST['WallAsimiConfirmPay']))
{ 		
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_wallet_address'])=="") {$obj->add_message("message","Please Enter your Wallet Address.");}
	if(trim($_POST['wall_asimi_token'])=="") {$obj->add_message("message","Please Enter your Total ASIMI.");}
	if(trim($_POST['wall_transaction_id'])=="") {$obj->add_message("message","Please Enter your Wallet Transaction Id.");}
	 if($obj->get_message("message")=="")
		{ 			
			$headers = array();
			$headers[] = 'Content-Type: application/json';			
			//$ch = curl_init('https://nodes.wavesplatform.com/transactions/address/'.$settings['set_wallet_address'].'/limit/600');
			$ch = curl_init('https://nodes.wavesnodes.com/transactions/info/'.trim($_POST['wall_transaction_id']).'');			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");				 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);			
			$response = json_decode(curl_exec($ch));
			// pre($response);//exit;
			$assetId 				=  substr($response->assetId, -3);
			$tansaction_id 			=  $response->id;
			$sender_wall_id 		=  $response->sender;			
			$wall_type 				=  $response->type;
 
			if($wall_type =='11') 
			{ 
				$recipient_wall_id 	=  $response->transfers['0']->recipient;	 
				$amount 			=  $response->transfers['0']->amount/100000000;

			}
			else if($wall_type =='4') 
			{
				$recipient_wall_id 	=  $response->recipient; 
				$amount 			=  $response->amount/100000000;
			}			
			//$recipient_wall_id 		=  $response->recipient;
			//$amount 				=  $response->amount/100000000;					
			$stimestamp 			=  $response->timestamp/1000;
			$trdate 				=  date('Y-m-d', $stimestamp);
			$trtime 				=  date('H:i:s', $stimestamp);
			
			
			if($sender_wall_id == trim($_POST['user_wallet_address']) && $recipient_wall_id == $settings['set_wallet_address'] && $amount == trim($_POST['wall_asimi_token']) && $assetId == 'wZ5') 
			{				 
				$get_valid_transaction_id =  $response->id;						 
			}			
			 
				// pre($trtime); exit;
			if(!empty($get_valid_transaction_id)) 
			{				
				$datawalet = $obj->selectData(TABLE_USER_WALLET,"wall_id","wall_from_wallet='".$obj->filter_mysql($_POST['user_wallet_address'])."' and wall_to_wallet='".$settings['set_wallet_address']."' and wall_transaction_id='".$obj->filter_mysql($_POST['wall_transaction_id'])."' and wall_type='d' and wall_pstatus='p' and wall_status='Active'",1);
					
				if(empty($datawalet['wall_id'])) { 
					$logAdSet['wall_pstatus'] 		= 'p';
					$logAdSet['wall_type'] 			= 'd';
					$logAdSet['wall_from_wallet'] 	= $obj->filter_mysql($_POST['user_wallet_address']);	
					$logAdSet['wall_to_wallet'] 	= $settings['set_wallet_address'];	
					$logAdSet['wall_transaction_id']= $obj->filter_mysql($_POST['wall_transaction_id']);
					$logAdSet['wall_asimi']			= $obj->filter_mysql($_POST['wall_asimi_token']);	
					$logAdSet['user_id'] 			= $_SESSION['user']['u_login_id'];	
					$logAdSet['wall_time']			= $trtime;
					$logAdSet['wall_created']		= $trdate; 								
					$logAdSet['wall_modified']		= CURRENT_DATE_TIME;				
					$wall_id =	$obj->insertData(TABLE_USER_WALLET,$logAdSet);					 
					$_SESSION['messageClass'] = "successClass";	
					$obj->add_message("Wmessage","You have successfully deposit your asimi in your wallet.Your payment has been initiated.");
					$obj->reDirect("wallet_fund_account.php?GetWallID=".$obj->encryptPass($wall_id));	
				} else {
					$wall_id = $datawalet['wall_id'];					
					$_SESSION['messageClass'] = "errorClass";	
					$obj->add_message("Wmessage","The transaction id already exists in our record.");
					$obj->reDirect("wallet_fund_account.php?GetWallID=".$obj->encryptPass($datawalet['wall_id']));								
				} 
			}
			else 
			{				 
				$_SESSION['messageClass'] = "errorClass";	
				$obj->add_message("message","Your transaction id didn't match in our server.<br />".$_POST['wall_transaction_id']);
				$obj->reDirect("wallet_fund_account.php");		
				 
			}
 
			curl_close($ch);

			
			//exit;
			 
		}
	}
  
if(isset($_POST['WithdAsimi_old']))
{	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['wall_asimi'])=="") {$obj->add_message("message","Please Enter Asimi token");}		
	if(trim($_POST['auth_user_pin'])=="") {$obj->add_message("message","2nd step auth pin Should Not Be Blank!");}	
	
	if(isset($_POST['g-recaptcha-response']))
	$captcha=$_POST['g-recaptcha-response'];
	if(!$captcha){
	  $obj->add_message("message","Please check the captcha form!");
	   $obj->reDirect("wallet_withdraw_fund_account.php");
	   exit;
	}
	$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LceAhYTAAAAAHBVPJVgBkM8Pdi_fUcUIoeBiPEB&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	if($obj->get_message("message")=="")
	{
		if($response['success'] == false)
		{			 
		   $obj->add_message("message","Wrong Captcha information!");
		   $obj->reDirect("wallet_withdraw_fund_account.php");
		   exit;
		}
	}
		$userId = $_SESSION['user']['u_login_id'];	
		$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status ='Active'",1);
		if($obj->get_message("message")=="")
		{	
			$myStr = trim($userD['user_wallet_address']);
			$res_char = substr($myStr, 0, 2);		 
			if($res_char !="3P")
				{
					$obj->add_message("message","Please correct your Asimi wallet address.");
					$obj->reDirect("update_wallet_address.php");
					exit;
				} 		
		}	
					
		$available_balance = $obj->get_withdraw_account_balance($userId);
		if($_POST['wall_asimi'] > $available_balance)
		{		 
			$obj->add_message("message",'You are exceeding your Asimi Withdrawal limit.');		
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}
		
		$curAsimiprice = $obj->get_asimi_curr_price();
		$withd_price = ($obj->filter_mysql($_POST['wall_asimi'])*$curAsimiprice);
		if($withd_price < 50)
		{		 
			$obj->add_message("message",'You are attempting to withdraw less than the minimum withdrawal value of $50 of Asimi.Please enter a higher value.');		
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}	
		
	if($obj->get_message("message")=="")
	{
		$userId = $_SESSION['user']['u_login_id'];
		$userAD = $obj->selectData(TABLE_USER_AUTH,"","user_id='".$userId."' and auth_status='Active'",1);
		//if($userAD['auth_user_pin'] != $_POST['auth_user_pin'])
		if(!password_verify($_POST['auth_user_pin'],$userAD['auth_user_pin']))
		{
			$obj->add_message("message","We are sorry you have entered an incorrect Secondary Pin, please enter the correct Pin.");			 
			$msges ='Withdraw Asimi. Your 2nd step auth pin doesn\'t match.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);		
		}	 
	} 
		
	if($obj->get_message("message")=="")
	{	  
		$curAsimiprice = $obj->get_asimi_curr_price();
		$withd_price = ($_POST['wall_asimi']*$curAsimiprice);
		$logAdSet['wall_asimi'] 		= $obj->filter_mysql(round($_POST['wall_asimi'],8));
		$logAdSet['wall_price'] 		= $withd_price;
		$logAdSet['wall_pstatus'] 		= 'p';
		$logAdSet['wall_type'] 			= 'w';
		$logAdSet['user_id'] 			= $_SESSION['user']['u_login_id'];		 
		$logAdSet['wall_created']		= CURRENT_DATE_TIME; 
		$logAdSet['wall_time']			= date("H:i:s");
		$logAdSet['wall_modified']		= CURRENT_DATE_TIME;				
		// $obj->insertData(TABLE_USER_WALLET,$logAdSet);	
		$_SESSION['user_withdrawal_details'] = 	$logAdSet;
		 
		$randnum = ''; 
		$otpD = $obj->selectData(TABLE_USER_OTP,"","user_id='".$_SESSION['user']['u_login_id']."'",1); 
		if(empty($otpD['uo_id'])) 
		{	
			$randnum =  mt_rand(100000, 999999);
			$optu['uo_otp'] 			= password_hash($randnum,PASSWORD_DEFAULT);
			$optu['user_id'] 			= $_SESSION['user']['u_login_id'];		 
			$optu['uo_created']			= date('Y-m-d');  
			$optu['uo_modified']		= CURRENT_DATE_TIME;				
			$user_otp_id = $obj->insertData(TABLE_USER_OTP,$optu);
			$_SESSION['user_otp_id'] = $user_otp_id;
		} else {
			$randnum =  mt_rand(100000, 999999);
			$optu['uo_otp'] 				= password_hash($randnum,PASSWORD_DEFAULT); 
			$optu['user_id'] 				= $_SESSION['user']['u_login_id'];			 
			$optu['uo_modified']			= CURRENT_DATE_TIME;		
			$obj->updateData(TABLE_USER_OTP,$optu,"uo_id='".$otpD['uo_id']."'");
			$_SESSION['user_otp_id'] = $otpD['uo_id'];
		}
				
		$wmsg   = "<b> Hi ".$obj->getUserName($userId)."</b><br><br>";			
		$wmsg  .= "Your One Time Password (OTP) is ".$randnum."<br><br>";		 
		$wmsg  .= "Thank You for using our Services.<br><br>";		 
		$wmsg  .="<br><br>Thank you!<br><br>".MAIL_THANK_YOU;

		$wmsg 	.= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr 		= "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$wmsg 	.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
			
		$body = $obj->mailBody($wmsg); 	 
		$from = FROM_EMAIL_2;
		$to   = $obj->getUserEmail($userId);			 
		$subject = "OTP for withdrawal request.";			
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);		 
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","We have sent a One Time Password (OTP) to your email, please enter it below.");
		$obj->reDirect('wallet_withdraw_fund_account.php');
		 
	}	
}

if(isset($_POST['WithdAsimi'])) {	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['wall_asimi'])=="") {$obj->add_message("message","Please Enter Asimi token");}		
	if(trim($_POST['auth_user_pin'])=="") {$obj->add_message("message","2nd step auth pin Should Not Be Blank!");}	
	 
	if(isset($_POST['g-recaptcha-response']))
	$captcha=$_POST['g-recaptcha-response'];
	if(!$captcha){
	  $obj->add_message("message","Please check the captcha form!");
	   $obj->reDirect("wallet_withdraw_fund_account.php");
	   exit;
	}
	$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LceAhYTAAAAAHBVPJVgBkM8Pdi_fUcUIoeBiPEB&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	if($obj->get_message("message")=="")
	{
		if($response['success'] == false)
		{			 
		   $obj->add_message("message","Wrong Captcha information!");
		   $obj->reDirect("wallet_withdraw_fund_account.php");
		   exit;
		}
	}
	
	$userId 				= $_SESSION['user']['u_login_id'];
	$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status ='Active'",1);	 
	$walletD = $obj->selectData(TABLE_USER_WALLET,"sum(wall_price) as tot_dol_price","user_id='".$userId."' and wall_type = 'w' and wall_pstatus ='p' and wall_status ='Active'",1);
	 
	$pastWDV 				= $walletD['tot_dol_price'];	
	$wall_asimi 			= $obj->filter_mysql(round($_POST['wall_asimi'],8)); //$obj->filter_mysql($_POST['wall_asimi']);
	$available_balance 		= $obj->get_withdraw_account_balance($userId);
	$withdrawal_fee 		= $obj->get_current_asimi_token(1.95);		
	$withd_price 			= $obj->get_asimi_token_doller_value($wall_asimi); 
	$total_stakes  			= $obj->memebr_max_ads_to_view_today($userId);
	$curr_login_stake  	    = $obj->get_member_login_stake($userId);
 
	$tot_withd_price 		= $pastWDV+$withd_price;
	
	if($obj->get_message("message")=="")
	{	
		$myStr = trim($userD['user_wallet_address']);
		$res_char = substr($myStr, 0, 2);		 
		if($res_char !="3P")
		{
			$obj->add_message("message","Please correct your Asimi wallet address.");
			$obj->reDirect("update_wallet_address.php");
			exit;
		} 		
	}
	if($obj->get_message("message")=="")
	{	
		if($wall_asimi > $available_balance)
		{		 
			$obj->add_message("message",'You have tried to withdraw more than your balance. Please try again with a smaller amount.');		
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}
		$minimum_withdraw 		= $obj->get_current_asimi_token(2);
		if($wall_asimi < $minimum_withdraw)
		{		 
			$obj->add_message("message","Your current balance is not enough to cover the processing fee.");		
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}
	}
	
	$userDL = $obj->selectData(TABLE_USER_LOGIN,"is_kyc_verified","u_login_id='".$userId."' and u_login_status ='Active' and u_suspend_status='n' and user_suspend_comm_status='n'",1);
	
	if($obj->get_message("message")=="")
	{
		$totlog        = $obj->tot_member_login_with_lastIp($userId);
		$totWithdraw   = $obj->total_withdrawal_doller($userId);	 
		$flaG = 0;	 
		if(empty($totlog) && $totWithdraw <= 400) $flaG = 1;
		
		if($userDL['is_kyc_verified'] =='n' && $total_stakes < 5 && $curr_login_stake < 10000 && $flaG == 0)
		{
			//$obj->add_message("message",'Please verify your account to withdraw amount.');	
			$msgL = "Your desired withdrawal requires account verification. Please verify your account by clicking <a href=".FURL."account_verification.php>here.</a>";
			$obj->add_message("message",$msgL);		
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}
		else if($userDL['is_kyc_verified'] =='n' && ($total_stakes >= 5 || $curr_login_stake >= 10000) && $tot_withd_price > 500)
		{			 
			//$obj->add_message("message",'You are attempting to withdraw more than the maximum withdrawal value of $500 of Asimi.Please verify your account to withdraw higher value.');	
			$msgL = "Your desired withdrawal requires account verification. Please verify your account by clicking <a href=".FURL."account_verification.php>here.</a>";
			$obj->add_message("message",$msgL);					
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}	
		else if($userDL['is_kyc_verified'] =='n' || $flaG == 0)
		{	 
			$msgL = "Your desired withdrawal requires account verification. Please verify your account by clicking <a href=".FURL."account_verification.php>here.</a>";
			$obj->add_message("message",$msgL);					
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}		
	}
	
	if($obj->get_message("message")=="")
	{
		if($total_stakes < 5 && $curr_login_stake < 10000 && $withd_price < 10)
		{
			$obj->add_message("message",'You are attempting to withdraw less than the minimum withdrawal value of $10 of Asimi.Please enter a higher value.');		
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect("wallet_withdraw_fund_account.php");
			exit;
		}		
	}	
	 
	/*
	$curAsimiprice = $obj->get_asimi_curr_price();
	$withd_price = ($obj->filter_mysql($_POST['wall_asimi'])*$curAsimiprice);
	if($withd_price < 50)
	{		 
		$obj->add_message("message",'You are attempting to withdraw less than the minimum withdrawal value of $50 of Asimi.Please enter a higher value.');		
		$_SESSION['messageClass'] = 'errorClass';
		$obj->reDirect("wallet_withdraw_fund_account.php");
		exit;
	}		
	*/	
	
	
	
	if($obj->get_message("message")=="")
	{
		$userId = $_SESSION['user']['u_login_id'];
		$userAD = $obj->selectData(TABLE_USER_AUTH,"","user_id='".$userId."' and auth_status='Active'",1);		
		if(!password_verify($_POST['auth_user_pin'],$userAD['auth_user_pin']))
		{
			$obj->add_message("message","We are sorry you have entered an incorrect Secondary Pin, please enter the correct Pin.");			 
			$msges ='Withdraw Asimi. Your 2nd step auth pin doesn\'t match.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);		
		}	 
	} 
		
	if($obj->get_message("message")=="")
	{	  
		 
		$logAdSet['wall_asimi'] 		= $wall_asimi;
		$logAdSet['wall_wd_fee'] 		= 1.95;
		$logAdSet['wall_wd_fee_asimi'] 	= $withdrawal_fee;
		$logAdSet['wall_price'] 		= $withd_price;
		$logAdSet['wall_pstatus'] 		= 'p';
		$logAdSet['wall_type'] 			= 'w';
		$logAdSet['user_id'] 			= $_SESSION['user']['u_login_id'];		 
		$logAdSet['wall_created']		= date("Y-m-d");  
		$logAdSet['wall_time']			= date("H:i:s");
		$logAdSet['wall_modified']		= CURRENT_DATE_TIME;				
		// $obj->insertData(TABLE_USER_WALLET,$logAdSet);	
		$_SESSION['user_withdrawal_details'] = 	$logAdSet;
		 
		$randnum = ''; 
		$otpD = $obj->selectData(TABLE_USER_OTP,"","user_id='".$_SESSION['user']['u_login_id']."'",1); 
		if(empty($otpD['uo_id'])) 
		{	
			$randnum =  mt_rand(100000, 999999);
			$optu['uo_otp'] 			= password_hash($randnum,PASSWORD_DEFAULT);
			$optu['user_id'] 			= $_SESSION['user']['u_login_id'];		 
			$optu['uo_created']			= date('Y-m-d');  
			$optu['uo_modified']		= CURRENT_DATE_TIME;				
			$user_otp_id = $obj->insertData(TABLE_USER_OTP,$optu);
			$_SESSION['user_otp_id'] = $user_otp_id;
		} else {
			$randnum =  mt_rand(100000, 999999);
			$optu['uo_otp'] 				= password_hash($randnum,PASSWORD_DEFAULT); 
			$optu['user_id'] 				= $_SESSION['user']['u_login_id'];			 
			$optu['uo_modified']			= CURRENT_DATE_TIME;		
			$obj->updateData(TABLE_USER_OTP,$optu,"uo_id='".$otpD['uo_id']."'");
			$_SESSION['user_otp_id'] = $otpD['uo_id'];
		}
				
		$wmsg   = "<b> Hi ".$obj->getUserName($userId)."</b><br><br>";			
		$wmsg  .= "Your One Time Password (OTP) is ".$randnum."<br><br>";		 
		$wmsg  .= "Thank You for using our Services.<br><br>";		 
		$wmsg  .="<br><br>Thank you!<br><br>".MAIL_THANK_YOU;

		$wmsg 	.= '<p style="border-top:1px solid #808080"></p>';  
		$StyleVAr 		= "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
		$wmsg 	.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
		
		$body = $obj->mailBody($wmsg); 	 
		$from = FROM_EMAIL_2;
		$to   = $obj->getUserEmail($userId);			 
		$subject = "OTP for withdrawal request.";			
		$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);		 
		$_SESSION['messageClass'] = "successClass";	
		$obj->add_message("message","We have sent a One Time Password (OTP) to your email, please enter it below.");
		$obj->reDirect('wallet_withdraw_fund_account.php');
		 
	}	
}
  
  
if(isset($_POST['WithdOtpAsimi']))
 {
	//pre($_POST['WithdOtpAsimi']);	
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['user_otp'])=="") {$obj->add_message("message","Please provide your OTP");}	
	
	if($obj->get_message("message")=="")
	{	
		 $userId = $_SESSION['user']['u_login_id']; 	
		 $otpD = $obj->selectData(TABLE_USER_OTP,"","uo_id='".$_SESSION['user_otp_id']."' and user_id='".$userId."'",1);
		
		 if(password_verify($_POST['user_otp'],$otpD['uo_otp']))	 
		 { 
			$logAdSet['wall_asimi'] 		= round($_SESSION['user_withdrawal_details']['wall_asimi'],8);
			$logAdSet['wall_price'] 		= $_SESSION['user_withdrawal_details']['wall_price'];
			$logAdSet['wall_pstatus'] 		= 'p';
			$logAdSet['wall_type'] 			= 'w';
			$logAdSet['user_id'] 			= $_SESSION['user']['u_login_id'];		 
			$logAdSet['wall_created']		= CURRENT_DATE_TIME; 
			$logAdSet['wall_time']			= date("H:i:s");
			$logAdSet['wall_modified']		= CURRENT_DATE_TIME;				
			$obj->insertData(TABLE_USER_WALLET,$logAdSet);	
			  	 
			$msges ='Successfully received withdraw request.';			 
			$type = 't';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);		 
					 
					 
			$wmsg   = "<b> Hi ".$obj->getUserName($userId)."</b><br><br>";			
			$wmsg  .= "A withdrawal has just been requested from your Hashing Ad Space account. Withdrawals are processed on business days only, 24-72 hours after the request.<br><br>";
			$wmsg  .= "<b>Note:</b> If you DID NOT REQUEST this withdrawal, your account may be compromised and you should do the following immediately:<br><br>";
			$wmsg  .= "<b>1.</b> Try to log in to your account and change your password and two-factor authentications.<br><br>";
			$wmsg  .= "<b>2.</b> Submit a support request to inform us that you did not request this withdarw and support will cancel the request.<br><br>";		 
			$wmsg .="<br><br>Thank you!<br><br>".MAIL_THANK_YOU;

			$wmsg 	.= '<p style="border-top:1px solid #808080"></p>';  
			$StyleVAr 		= "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
			$wmsg 	.='<p style="'.$StyleVAr.'">Stop Receiving These Emails: <a target="_blank" href="'.FURL."deactivation.php?uactid=".$obj->encryptPass($userId).'">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
		
			$body = $obj->mailBody($wmsg); 	 
			$from = FROM_EMAIL_2;
			$to   = $obj->getUserEmail($userId);			 
			$subject = "You have submitted a withdrawal request.";			
			$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);
			
			$obj->deleteData(TABLE_USER_OTP,"uo_id='".$_SESSION['user_otp_id']."'");
			unset($_SESSION['user_otp_id']);
			unset($_SESSION['user_withdrawal_details']);	
			
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","We have successfully received your withdraw request. We will process it soon.");
			$obj->reDirect('withdraw.php');
		 } else {
			  
			$msges ='Withdraw Asimi. The One Time Password doesn\'t match.';			 
			$type = 'f';
			$obj->monitor_making_changes($userId,$ip,$msges,$type);		
			 
			unset($_SESSION['user_otp_id']);
			$obj->deleteData(TABLE_USER_OTP,"uo_id='".$_SESSION['user_otp_id']."'");
			unset($_SESSION['user_withdrawal_details']);	
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","The One Time Password you entered does not match.");
			$obj->reDirect('wallet_fund_account.php'); 
			 
		 }
			
	}	
}
  
 

}
  
?>
<?php

if (strpos($_SERVER['HTTP_REFERER'], HOST) !== false)
{
	if (isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id'] > 0){
		if(isset($_REQUEST["doRemove"]) && $_REQUEST["doRemove"] ==1){
			unset($_SESSION['ord_banner_type']);
			$obj->reDirect('advertising_products.php');
			exit;
		}
    if (isset($_POST['SignUpAndBuyBanner']))
    {
	  	if(!isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==""){
			$obj->reDirect('advertising_products.php');
			exit;
		}
        if (isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id'] > 0)
        {
        	include_once("banner_payment_cls.php");
			$objBannerPayment = new banner_payment();
        	if(trim($_POST['pmt_method'])=="") {$obj->add_message("message","Please select payment method");}
        	if(trim($_POST['pmt_method'])=="cardpayment") {
        		if(trim($_POST['cnumber'])=="") {$obj->add_message("message","Please enter credit card number");}
        		if(trim($_POST['month'])=="") {$obj->add_message("message","Please enter credit card expiry month/year");}
        		if(trim($_POST['cvv'])=="") {$obj->add_message("message","Please enter credit card CVV");}
        	}
        	if($obj->get_message("message")=="")
			{
				if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
					$orderID = $objBannerPayment->createBannerOrder();
					include_once("stripe_payment.php");
				}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypalpayment"){
					$orderID = $objBannerPayment->createBannerOrder();
					$_SESSION["paypalOrderID"] =$orderID; 
					$obj->reDirect('paypalpayment.php');
					exit;
				}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cryptopayment"){
					$orderID = $objBannerPayment->createBannerOrder();
					$_SESSION["cryptoOrderID"] =$orderID; 
					$obj->reDirect('cryptopayment.php');
					exit;
				}
			}else{
				if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==LEADERBOAD_BANNER_TITLE){
					$obj->reDirect('leaderboard_ad_purchase.php');
					exit;
				}
				if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==SQUARE_BANNER_TITLE){
					$obj->reDirect('leaderboard_ad_purchase.php');
					exit;
				}
	    		exit;
			}
        }
        else
        {
        	if(trim($_POST['pmt_method'])=="") {$obj->add_message("message","Please select payment method");}
        	if(trim($_POST['pmt_method'])=="cardpayment") {
        		if(trim($_POST['cnumber'])=="") {$obj->add_message("message","Please enter credit card number");}
        		if(trim($_POST['month'])=="") {$obj->add_message("message","Please enter credit card expiry month/year");}
        		if(trim($_POST['cvv'])=="") {$obj->add_message("message","Please enter credit card CVV");}
        	}
        	if($obj->get_message("message")=="")
			{
            //
	            $user_email = $obj->filter_mysql($_POST['user_email2']);
	            $ulogD = $obj->selectData(TABLE_USER, "", "user_email='" . $user_email . "' and user_status = 'Lead'", 1);
	            if (empty($ulogD['u_login_id']))
	            {
	                if ($obj->get_message("message") == "")
	                {
	                    $user_email = $obj->filter_mysql($_POST['user_email2']);
	                    $sqlUS = $obj->selectData(TABLE_USER_LOGIN, "", "u_login_user_email='" . $user_email . "' and u_login_status<>'Deleted'", 1);
	                    if ($sqlUS['u_login_user_email'])
	                    {
	                        $obj->add_message("message", "Sorry! email id already exists. Please try another.");
	                    }

	                    $reg_email = $obj->filter_mysql($_POST['user_email2']);
	                    $email_domain_name = substr(strrchr($reg_email, "@") , 1);
	                    $domainEBL = $obj->selectData(TABLE_EMAIL_DOMAIN_BLACKLIST, "", "edb_url like '%" . $email_domain_name . "%' and edb_status='Active'", 1);
	                    if ($domainEBL['edb_id'])
	                    {
	                        $obj->add_message("message", "Your email provider is not supported by our system. Please try another email address.");
	                    }
	                }
	                //
	                if ($obj->get_message("message") == "")
	                {
	                    $userlogArr = array();
	                    $userlogArr['u_login_password'] = password_hash($_POST['u_login_password2'], PASSWORD_DEFAULT);
	                    $userlogArr['u_login_user_email'] = $obj->filter_mysql($_POST['user_email2']);
	                    $userlogArr['u_login_created'] = CURRENT_DATE_TIME;
	                    $userlogArr['u_login_modified'] = CURRENT_DATE_TIME;
	                    $userlogArr['u_login_attempt'] = 0;
	                    $userlogArr['u_pass_update'] = CURRENT_DATE_TIME;
	                    $userlogArr['u_login_status'] = "Active";
	                    $userlogArr['u_login_recent_password'] = $userlogArr['u_login_password'];


	                    if (!empty($ulogD['u_login_id']))
	                    {
	                        $obj->updateData(TABLE_USER_LOGIN, $userlogArr, "u_login_id='" . $ulogD['u_login_id'] . "'");
	                        $newUserId = $ulogD['u_login_id'];
	                    }
	                    else
	                    {
	                        $newUserId = $obj->insertData(TABLE_USER_LOGIN, $userlogArr);
	                    }

	                    $_SESSION['new_user_id'] = $newUserId;
	                    $_SESSION['user']['u_login_id'] = $newUserId;
	                    $_SESSION['new_user_email'] = $obj->filter_mysql($_POST['user_email2']);
	                    $userlArr['u_login_id'] = $newUserId;
	                    $userlArr['user_referrer'] = $obj->filter_mysql($_SESSION['aff_ref_id']);
	                    $userlArr['user_email'] = $obj->filter_mysql($_POST['user_email2']);
	                    $userlArr['user_first_name'] = $obj->filter_mysql($_POST['user_first_name2']);
	                    $userlArr['user_reg_ip'] = $ip;
	                    $userlArr['user_reg_browser'] = $_SERVER['HTTP_USER_AGENT'];
	                    $userlArr['user_created'] = CURRENT_DATE_TIME;
	                    $userlArr['user_modified'] = CURRENT_DATE_TIME;
	                    $userlArr['user_status'] = "Active";//Registered
	                    if (!empty($ulogD['u_login_id'])) $sql = $obj->updateData(TABLE_USER, $userlArr, "u_login_id='" . $ulogD['u_login_id'] . "'");
	                    else $obj->insertData(TABLE_USER, $userlArr);

	                    $AuthArr = array();
	                    $AuthArr['user_id'] = $newUserId;
	                    $AuthArr['auth_created'] = CURRENT_DATE_TIME;
	                    $AuthArr['auth_modified'] = CURRENT_DATE_TIME;
	                    $obj->insertData(TABLE_USER_AUTH, $AuthArr);

	                    $ammessage = "<b> Hi " . ucfirst($_POST['user_first_name']) . "</b><br><br>";
	                    $ammessage .= "Welcome to Hashing Ad Space! <br><br>";
	                    $ammessage .= "To get started, please click below to confirm your email and activate your account. <br><br>";
	                    $ammessage .= "<a href='" . FURL . "activation.php?uactid=" . $obj->encryptPass($newUserId) . "'>CLICK HERE TO ACTIVATE YOUR ACCOUNT </a> <br> <br>";
	                    $ammessage .= "Please Note:<br><br>";
	                    $ammessage .= "You have received this message to confirm your subscription to Hashing Ad Space.";
	                    $ammessage .= "If you have received this email in error, please disregard it. No action needs to be taken and we will not contact you again.";
	                    $ammessage .= "If you did intend to register with Hashing Ad Space, click the link above to activate your account.";
	                    $ammessage .= "We hope to see you in Hashing Ad Space!";
	                    $ammessage .= "The Hashing Ad Space Team.";
	                    $ammessage .= "You can unsubscribe at any time by clicking Unsubscribe below, or by contacting our support department.";
	                    $ammessage .= "<br><br>See you there!<br><br>" . MAIL_THANK_YOU;
	                    $ammessage .= "<br><br>";
	                    $ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
	                    $ammessage .= '<p style="border-top:1px solid #808080"></p>';
	                    $StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
	                    $ammessage .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($newUserId) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
	                    $body2 = $obj->mailBody($ammessage);
	                    $from2 = FROM_EMAIL_2;
	                    $to2 = $_POST['user_email'];
	                    $subject2 = "Activate your Hashing Ad Space account";
	                    $mamam = $obj->sendMailSES($to2, $subject2, $body2, $from2, SITE_TITLE, $type);

	                    ////////////joining Reward//////////////
	                    $setStat = $obj->selectData(TABLE_SETTINGS, "set_join_asimi_status,set_join_asimi,set_join_ban_imp,set_join_mint_imp", "set_id=1", 1);
	                    if ($setStat['set_join_asimi_status'] == 'Active')
	                    {

	                        if ($setStat['set_join_asimi'] > '0')
	                        {
	                            $JRArr['user_id'] = $newUserId;
	                            $JRArr['wall_type'] = 'j';
	                            $JRArr['wall_asimi'] = $setStat['set_join_asimi'];
	                            $JRArr['wall_created'] = date("Y-m-d");
	                            $JRArr['wall_time'] = date("H:i:s");
	                            $JRArr['wall_modified'] = CURRENT_DATE_TIME;
	                            $obj->insertData(TABLE_USER_WALLET, $JRArr);
	                        }
	                        if (!empty($setStat['set_join_ban_imp']))
	                        {
	                            $hrzArr['user_id'] = $newUserId;
	                            $hrzArr['ic_impression'] = $setStat['set_join_ban_imp'] / 2;
	                            $hrzArr['ic_imp_type'] = 'banhrz';
	                            $hrzArr['ic_allocate_type'] = 'r';
	                            $hrzArr['ic_created'] = CURRENT_DATE_TIME;
	                            $obj->insertData(TABLE_IMPRESSION_CREDIT, $hrzArr);

	                            $sqrArr['user_id'] = $newUserId;
	                            $sqrArr['ic_impression'] = $setStat['set_join_ban_imp'] / 2;
	                            $sqrArr['ic_imp_type'] = 'bansqr';
	                            $sqrArr['ic_allocate_type'] = 'r';
	                            $sqrArr['ic_created'] = CURRENT_DATE_TIME;
	                            $obj->insertData(TABLE_IMPRESSION_CREDIT, $sqrArr);
	                        }
	                        if (!empty($setStat['set_join_mint_imp']))
	                        {
	                            $mintArr['user_id'] = $newUserId;
	                            $mintArr['ic_impression'] = $setStat['set_join_mint_imp'];
	                            $mintArr['ic_imp_type'] = 'minter';
	                            $mintArr['ic_allocate_type'] = 'r';
	                            $mintArr['ic_created'] = CURRENT_DATE_TIME;
	                            $obj->insertData(TABLE_IMPRESSION_CREDIT, $mintArr);
	                        }

	                    }
	                    $obj->monitor_login_ads($newUserId, $ip);

	                    if ($_SESSION['aff_ref_id'])
	                    {
	                        $userAffData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $_SESSION['aff_ref_id'] . "' and user_status<>'Deleted'", 1);
	                        $reg_message = "Hi <b>" . $userAffData['user_first_name'] . ' ' . $userAffData['user_last_name'] . "</b><br>";
	                        $reg_message .= "Congratulations! You have a new Hashing Ad Space referral.<br><br>";
	                        $reg_message .= "Here are their details:<br><br>";
	                        $reg_message .= "Name: " . $_POST['user_first_name'] . "<br><br>";
	                        $reg_message .= "Email : " . $_POST['user_email'] . "<br /><br />";
	                        $reg_message .= "You'll earn commissions on any products they purchase on our site for life! <br /><br />";
	                        $reg_message .= "That's exciting!<br><br>";
	                        $reg_message .= "Tip: Following up with referrals within 24 hours is proven to drastically improve sales conversions.<br /><br />";
	                        $reg_message .= "Make sure your new referral has the information they need to get earning/advertising in Hashing Ad Space. It will result more commissions for you.<br /><br />";
	                        $reg_message .= "Keep up the good work!<br /><br />";
	                        $reg_message .= "<br>Thank You<br>";
	                        $reg_message .= MAIL_THANK_YOU;
	                        $reg_message .= '<p style="border-top:1px solid #808080"></p>';
	                        $StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
	                        $reg_message .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($_SESSION['aff_ref_id']) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
	                        $body = $obj->mailBody($reg_message);
	                        $from = FROM_EMAIL_2;
	                        $to = $userAffData['user_email'];
	                        $subject = "You have a new referral.";
	                        $frfr = $obj->sendMailSES($to, $subject, $body, $from, SITE_TITLE, $type);
	                    }

	                    $_SESSION['messageClass'] = "successClass";
	                    include_once("banner_detail.php");
			            if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
							include_once("stripe_payment.php");
						}
	                }
	            }else{
	            	$_SESSION['messageClass'] = 'errorClass';
					$obj->reDirect('checkout.php');
		    		exit;
	            }
            }else{	
				$_SESSION['messageClass'] = 'errorClass';
				$obj->reDirect('checkout.php');
	    		exit;
            }
            //
        }
    }
}
}
?>

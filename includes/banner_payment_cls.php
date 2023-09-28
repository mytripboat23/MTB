<?php
class banner_payment{
	function createBannerOrder(){
		global $obj;
		$reqbannerType = $_SESSION["ord_banner_type"];
		if(isset($reqbannerType) && $reqbannerType=="leaderboard"){
			$pakID = 17;
		}
		if(isset($reqbannerType) && $reqbannerType=="square"){
			$pakID = 18;
		}
		$sqlp =$obj->selectData(TABLE_PACKAGE,"","pack_id='".$pakID."' and pack_status='Active'",1);
		if (!isset($_SESSION["ord_banner_type"]))
		{
		    $ad_banner_type = "square";
		    $ad_banner_amount = SQUARE_AD_PRICE;
		    $stripe_plan_id = STRIPE_SQUARE_BANNER_PLAN_ID;
		    $banner_title = SQUARE_BANNER_TITLE;
		}
		else
		{
		    $ad_banner_type = $_SESSION["ord_banner_type"];
		    if ($ad_banner_type == "square")
		    {
		        $ad_banner_amount = $sqlp['pack_price'];
		        $stripe_plan_id = STRIPE_SQUARE_BANNER_PLAN_ID;
		        $banner_title = SQUARE_BANNER_TITLE;
		    }
		    else
		    {
		        $ad_banner_amount = $sqlp['pack_price'];
		        $stripe_plan_id = STRIPE_LEADERBOARD_BANNER_PLAN_ID;
		        $banner_title = LEADERBOAD_BANNER_TITLE;
		    }
		}
		$_REQUEST["userid"] =$_SESSION['user']['u_login_id'];
		$_REQUEST["request_banner"] =$_SESSION["ord_banner_type"];
		$_REQUEST["request_banner_amt"] =$ad_banner_amount;
		$_REQUEST["request_banner_plan"] =$stripe_plan_id;
		$_REQUEST["request_banner_title"] =$banner_title;
		$request = "";
		foreach ($_REQUEST as $key => $value)  
		{  
			if($key=="cnumber" ){
				$value = 'XXXX'.substr($value,-4);
			}
			if($key=="cvv" ){
				$value = 'XXX';
			}
			$request .= $comma.$key."=".$value;
			$comma=",";
		}
		$userBannerRequest['request']	= $request;	
		$userBannerRequest['user_id']	= $_SESSION['user']['u_login_id'];
		$userBannerRequest['ip']	= $ip;	
		$orderRequestID = $obj->insertData(TABLE_USER_AD_BANNER_REQUEST,$userBannerRequest);
		//insert order request
		$flagoldTable =1;
		if($flagoldTable){
			$arryOrder["user_id"] =$_SESSION['user']['u_login_id'];
			if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
			    $arryOrder["order_payment_gateway"] ="stripe";
			}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypalpayment"){
			    $arryOrder["order_payment_gateway"] ="paypal";
			}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cryptopayment"){
			    $arryOrder["order_payment_gateway"] ="crypto";
			}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="wallet"){
			    $arryOrder["order_payment_gateway"] ="wallet";
			}else{
			    $arryOrder["order_payment_gateway"] ="cheque";
			}
			$arryOrder["order_subtotal"] = $_REQUEST["request_banner_amt"];
			$arryOrder["order_total"] = $_REQUEST["request_banner_amt"];
			$arryOrder["order_status"] = "Inactive";
			$arryOrder["order_created"] = CURRENT_DATE_TIME;
			$arryOrder["order_date"] = CURRENT_DATE_TIME;
			$arryOrder["order_modified"] = CURRENT_DATE_TIME;
			$arryOrder["order_pstatus"] = "u";
			$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $_SESSION['user']['u_login_id']. "'", 1);
			$arryOrder["order_bill_fname"] = $userData["user_first_name"];
			$arryOrder["order_bill_lname"] = $userData["user_last_name"];
			$arryOrder["order_bill_address1"] = $userData["user_address_1"];
			$arryOrder["order_bill_address2"] = $userData["user_address_2"];
			$arryOrder["order_bill_country"] = $userData["user_country"];
			$arryOrder["order_bill_state"] = $userData["user_state"];
			$arryOrder["order_bill_city"] = $userData["user_city"];
			$arryOrder["order_bill_zip"] = $userData["user_zip"];
			$arryOrder["order_bill_email"] = $userData["user_email"];
			$arryOrder["order_tot_asimi"] = $obj->get_current_asimi_token($_REQUEST["request_banner_amt"]);
			$orderId = $obj->insertData(TABLE_ORDER,$arryOrder);

			$orderDetailArray["order_id"] = $orderId;
			$orderDetailArray["user_id"] = $arryOrder["user_id"];
			$orderDetailArray["pack_id"] = $sqlp['pack_id'];
			$orderDetailArray["pack_title"] = $sqlp['pack_title'];
			$orderDetailArray["pack_quan"] = 1;
			$orderDetailArray["pack_price"] =$sqlp['pack_price'];
			$orderDetailArray["od_total"] =$_REQUEST["request_banner_amt"];
			$orderDetailArray["od_tot_token"] =$obj->get_current_asimi_token($_REQUEST["request_banner_amt"]);
			$orderDetailArray["od_pack_status"] =$sqlp['pack_status'];
			$orderDetailArray["od_tot_token"] =$obj->get_current_asimi_token($_REQUEST["request_banner_amt"]);
			$orderDetailArray["pack_asimi_price"] =$sqlp['pack_asimi_price'];
			$orderDetailArray["od_status"] ="Inactive";
			$orderDetailArray["od_created"] =CURRENT_DATE_TIME;
			$orderDetalId = $obj->insertData(TABLE_ORDER_DETAILS,$orderDetailArray);
			return $orderId;

		}else{
			$arryOrder["order_request_id"] = $orderRequestID ;
			$arryOrder["user_id"] =$_SESSION['user']['u_login_id'];
			if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
			    $arryOrder["payment_method"] ="stripe";
			}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypalpayment"){
			    $arryOrder["payment_method"] ="paypal";
			}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cryptopayment"){
			    $arryOrder["payment_method"] ="crypto";
			}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="wallet"){
			    $arryOrder["payment_method"] ="wallet";
			}else{
			    $arryOrder["payment_method"] ="cheque";
			}
			$arryOrder["sub_total"] = $_REQUEST["request_banner_amt"];
			$arryOrder["total"] = $_REQUEST["request_banner_amt"];
			$arryOrder["order_status"] = "pending";
			$arryOrder["order_date"] = CURRENT_DATE_TIME;
			$arryOrder["created_from"] = "Front";
			$arryOrder["created_on"] = CURRENT_DATE_TIME;
			$arryOrder["banner_title"] = $banner_title;
			$arryOrder["created_on"] = CURRENT_DATE_TIME;
			$arryOrder["status"] = "Inactive";
			$orderId = $obj->insertData(TABLE_USER_AD_BANNER_ORDER,$arryOrder);
		}
		
		return $orderId;
	}
	function insertOrderPayment($orderId,$return_str){
		global $obj;
		$arrayPaymentLog["user_id"] = $_SESSION['user']['u_login_id'];
		$arrayPaymentLog["order_id"] = $orderId;
		if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
			$arrayPaymentLog["payment_method"] = "stripe";
		}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypalpayment"){
			$arrayPaymentLog["payment_method"] = "paypal";
		}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cryptopayment"){
			$arrayPaymentLog["payment_method"] ="crypto";
		}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="wallet"){
			$arrayPaymentLog["payment_method"] ="wallet";
		}else{
			$arrayPaymentLog["payment_method"] ="cheque";
		}
		// $arrayPaymentLog["payment_request"] =$request;
		$arrayPaymentLog["payment_response"] =$return_str ;
		$arrayPaymentLog["transaction_id"] = @$transaction_id;
		$arrayPaymentLog["wallet_id"] = @$wallet_id;
		$arrayPaymentLog["created_date"] = CURRENT_DATE_TIME;
		$arrayPaymentLog["amount"] = @$_REQUEST["request_banner_amt"];
		$orderPaymentID = $obj->insertData(TABLE_BANNER_ORDER_PAYMENT,$arrayPaymentLog);
	}
	function updateOrderDetail($orderId,$subscriptionsId="",$stripeCustomerId=""){
		global $obj;
		$flagoldTable =1;
		if($flagoldTable){
			$updateOrder["order_status"] = "Active";
			$updateOrder["subscription_id"] = @$subscriptionsId;
			$updateOrder["stripe_customer_id"] = @$stripeCustomerId;
			$obj->updateData(TABLE_ORDER, $updateOrder, "order_id='" . $orderId . "'");
			$updateOrderDetail["od_status"] = "Active";
			$obj->updateData(TABLE_ORDER_DETAILS, $updateOrderDetail, "order_id='" . $orderId . "'");
		}else{
			$orderNumber = BANNER_ORDER_PREFIX.$orderId;
			$updateOrder["order_number"] =$orderNumber;
			$updateOrder["status"] = "Active";
			$updateOrder["order_status"] = "paid";
			$updateOrder["payment_status"] = "capture";
			$updateOrder["subscription_id"] = @$subscriptionsId;
			$updateOrder["stripe_customer_id"] = @$stripeCustomerId;
			//$updateOrder["order_end_date"] = date("Y-m-d",strtotime( "+1 month", strtotime( CURRENT_DATE_TIME ) ));
			$obj->updateData(TABLE_USER_AD_BANNER_ORDER, $updateOrder, "id='" . $orderId . "'");
		}
	}
	function addSubscriptionDetail($subscriptionData){
		global $obj;
		$flagoldTable=1;
		if($flagoldTable){
			if(isset($subscriptionData["subscriptionID"]) && $subscriptionData["subscriptionID"] != ""){
				sleep(10);
				$subscriptionID = $subscriptionData["subscriptionID"];
				$stripeOrderDetail = $obj->selectData(TABLE_ORDER, "", "subscription_id='" . $subscriptionID . "'", 1);
				if($stripeOrderDetail["order_id"] && $stripeOrderDetail["order_id"] > 0){
					$arrySubscription["subscription_id"] = $subscriptionID;
					$arrySubscription["stripe_customer_id"] = @$subscriptionData["stripeCustomerId"];
					$arrySubscription["method"] = @$subscriptionData["method"];
					$arrySubscription["order_id"] = $stripeOrderDetail["order_id"];
					$arrySubscription["user_id"] = $stripeOrderDetail['user_id'];
					$arrySubscription["amount"] = $stripeOrderDetail["order_total"];
					$arrySubscription["status"] = "Active";
					$arrySubscription["frequency"] = "1";
					$arrySubscription["subscription_datetime"] = CURRENT_DATE_TIME;
					$arrySubscription["created_date"] = CURRENT_DATE_TIME;
					$orderSubscriptonId = $obj->insertData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION,$arrySubscription);
					return $orderSubscriptonId;
				}
			}
		}else{
			if(isset($subscriptionData["subscriptionID"]) && $subscriptionData["subscriptionID"] != ""){
				sleep(10);
				$subscriptionID = $subscriptionData["subscriptionID"];
				$stripeOrderDetail = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "subscription_id='" . $subscriptionID . "'", 1);
				if($stripeOrderDetail["id"] && $stripeOrderDetail["id"] > 0){
					$arrySubscription["subscription_id"] = $subscriptionID;
					$arrySubscription["stripe_customer_id"] = @$subscriptionData["stripeCustomerId"];
					$arrySubscription["method"] = @$subscriptionData["method"];
					$arrySubscription["order_id"] = $stripeOrderDetail["id"];
					$arrySubscription["user_id"] = $stripeOrderDetail['user_id'];
					$arrySubscription["amount"] = $stripeOrderDetail["total"];
					$arrySubscription["status"] = "Active";
					$arrySubscription["frequency"] = "1";
					$arrySubscription["subscription_datetime"] = CURRENT_DATE_TIME;
					$arrySubscription["created_date"] = CURRENT_DATE_TIME;
					$orderSubscriptonId = $obj->insertData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION,$arrySubscription);
					return $orderSubscriptonId;
				}
			}
		}
	}
	function sendSubscriptionMail($subscriptionData){
		global $obj;
		if(isset($subscriptionData["subscriptionID"]) && $subscriptionData["subscriptionID"] != ""){
			$subscriptionID = $subscriptionData["subscriptionID"];
			$flagoldTable=1;
			if($flagoldTable){
				$stripeOrderDetail = $obj->selectData(TABLE_ORDER, "", "subscription_id='" . $subscriptionID . "'", 1);
				if($stripeOrderDetail["order_id"] && $stripeOrderDetail["order_id"] > 0){
					$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
					$dataDetail = $obj->selectData(TABLE_ORDER_DETAILS,"","order_id='".$stripeOrderDetail["order_id"]."'",1);
					$ammessage = "<b> Hi " . ucfirst($userData["user_first_name"]) . "</b><br><br>";
					$ammessage .= "Thankyou for subscribe Hashing Ad Space banner! <br><br>";
					$ammessage .= "Your subscription successfully created.<br/>";
					$ammessage .= "Your subscription start date:&nbsp;".date("m-d-Y",strtotime(CURRENT_DATE_TIME))."<br/>";;
					$ammessage .= "Your subscription amount:&nbsp;$".$stripeOrderDetail["order_total"]."<br/>";
					$ammessage .= "Your subscription ad banner type:&nbsp;".ucfirst($dataDetail["pack_title"])."<br/>";
					$ammessage .= "We hope to see you in Hashing Ad Space!";
					$ammessage .= "The Hashing Ad Space Team.";
					$ammessage .= "<br><br>See you there!<br><br>" . MAIL_THANK_YOU;
					$ammessage .= "<br><br>";
					$ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
					$ammessage .= '<p style="border-top:1px solid #808080"></p>';
					$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
					$ammessage .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($stripeOrderDetail['user_id']) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
					$body2 = $obj->mailBody($ammessage);
					$from2 = FROM_EMAIL_2;
					$to2 = $userData["user_email"];
					$subject2 = "Your Ad banner subscription created at Hashing Ad Space";
					$mamam = $obj->sendMailSES($to2, $subject2, $body2, $from2, SITE_TITLE, $type);
					$subMail["subscription_success_email_sent"] = "Yes";
					$obj->updateData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION, $subMail, "subscription_id='" . $subscriptionData["subscriptionID"] . "'");


					$myfile2 = @fopen("subscription_success_mail_".date("Y-m-d").".txt", "a+");
					fwrite($myfile2,gmdate('Y-m-d H:i:s')."\n\r");
					fwrite($myfile2,$ammessage."\n\r\n\r");
					fwrite($myfile2,"------------------------------------------------------------------------------\n\r\n\r");
					fclose($myfile2);
				}
			}else{
				$stripeOrderDetail = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "subscription_id='" . $subscriptionID . "'", 1);
				if($stripeOrderDetail["id"] && $stripeOrderDetail["id"] > 0){
					$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
					$ammessage = "<b> Hi " . ucfirst($userData["user_first_name"]) . "</b><br><br>";
					$ammessage .= "Thankyou for subscribe Hashing Ad Space banner! <br><br>";
					$ammessage .= "Your subscription successfully created.<br/>";
					$ammessage .= "Your subscription start date:&nbsp;".date("m-d-Y",strtotime(CURRENT_DATE_TIME))."<br/>";;
					$ammessage .= "Your subscription amount:&nbsp;$".$stripeOrderDetail["total"]."<br/>";
					$ammessage .= "Your subscription ad banner type:&nbsp;".ucfirst($stripeOrderDetail["banner_title"])."<br/>";
					$ammessage .= "We hope to see you in Hashing Ad Space!";
					$ammessage .= "The Hashing Ad Space Team.";
					$ammessage .= "<br><br>See you there!<br><br>" . MAIL_THANK_YOU;
					$ammessage .= "<br><br>";
					$ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
					$ammessage .= '<p style="border-top:1px solid #808080"></p>';
					$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
					$ammessage .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($stripeOrderDetail['user_id']) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
					$body2 = $obj->mailBody($ammessage);
					$from2 = FROM_EMAIL_2;
					$to2 = $userData["user_email"];
					$subject2 = "Your Ad banner subscription created at Hashing Ad Space";
					$mamam = $obj->sendMailSES($to2, $subject2, $body2, $from2, SITE_TITLE, $type);

					$subMail["subscription_success_email_sent"] = "Yes";
					$obj->updateData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION, $subMail, "subscription_id='" . $subscriptionData["subscriptionID"] . "'");
				}
			}
		}
	}
	function addPmtDetail($pmtDetail){
		global $obj;
		if(isset($pmtDetail["transaction_id"]) && $pmtDetail["transaction_id"] != ""){
			sleep(10);
			$flagoldTable=1;
			if($flagoldTable){
				if(isset($pmtDetail["method"]) && $pmtDetail["method"]=="stripe" ){
					$subscription_id = $pmtDetail["subscriptionId"];
					$stripeOrderDetail = $obj->selectData(TABLE_ORDER, "", "subscription_id='" . $pmtDetail["subscriptionId"] . "'", 1);
				}else if(isset($pmtDetail["method"]) && $pmtDetail["method"]=="paypal" ){
					$stripeOrderDetail = $obj->selectData(TABLE_ORDER, "", "subscription_id='" . $pmtDetail["subscriptionId"] . "'", 1);
				}
				if($stripeOrderDetail["order_id"] && $stripeOrderDetail["order_id"] > 0){
					$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
					$arrayAdBannerPayment["user_id"] = $stripeOrderDetail["user_id"];
					$arrayAdBannerPayment["amount"] = $pmtDetail["total_amount"];
					$arrayAdBannerPayment["order_id"] = $stripeOrderDetail["order_id"];
					$arrayAdBannerPayment["payment_method"] = $pmtDetail["method"];
					$arrayAdBannerPayment["payment_response"] = str_replace('"', " " ,$pmtDetail["payment_response"]);
					$arrayAdBannerPayment["transaction_id"] = $pmtDetail["transaction_id"];
					$arrayAdBannerPayment["is_subscription_payment"] = "Yes";
					$arrayAdBannerPayment["created_date"] = CURRENT_DATE_TIME;
					$obj->insertData(TABLE_BANNER_ORDER_PAYMENT,$arrayAdBannerPayment);
					$updateOrder["order_end_date"] = date("Y-m-d",strtotime( "+1 month", strtotime( CURRENT_DATE_TIME ) ));
					$updateOrder["order_pstatus"] = "p";
					$obj->updateData(TABLE_ORDER, $updateOrder, "order_id='" . $stripeOrderDetail["order_id"] . "'");
					$ordData = $obj->selectData(TABLE_LEADERBOARD_BANNER, "", "order_id='" . $stripeOrderDetail['order_id'] . "'", 1);
					if(isset($ordData["order_id"]) && $ordData["order_id"] > 0){
						$lb_end_date["lb_end_date"] = date("Y-m-d",strtotime( "+1 month", strtotime( CURRENT_DATE_TIME ) ));
						$obj->updateData(TABLE_LEADERBOARD_BANNER, $lb_end_date, "order_id='" . $stripeOrderDetail["order_id"] . "'");
					}
				}
			}else{
				if(isset($pmtDetail["method"]) && $pmtDetail["method"]=="stripe" ){
					$subscription_id = $pmtDetail["subscriptionId"];
					$stripeOrderDetail = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "subscription_id='" . $pmtDetail["subscriptionId"] . "'", 1);
				}else if(isset($pmtDetail["method"]) && $pmtDetail["method"]=="paypal" ){
					$stripeOrderDetail = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "subscription_id='" . $pmtDetail["subscriptionId"] . "'", 1);
				}
				if($stripeOrderDetail["id"] && $stripeOrderDetail["id"] > 0){
					$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
					$arrayAdBannerPayment["user_id"] = $stripeOrderDetail["user_id"];
					$arrayAdBannerPayment["amount"] = $pmtDetail["total_amount"];
					$arrayAdBannerPayment["order_id"] = $stripeOrderDetail["id"];
					$arrayAdBannerPayment["payment_method"] = $pmtDetail["method"];
					$arrayAdBannerPayment["payment_response"] = str_replace('"', " " ,$pmtDetail["payment_response"]);
					$arrayAdBannerPayment["transaction_id"] = $pmtDetail["transaction_id"];
					$arrayAdBannerPayment["is_subscription_payment"] = "Yes";
					$arrayAdBannerPayment["created_date"] = CURRENT_DATE_TIME;
					$obj->insertData(TABLE_BANNER_ORDER_PAYMENT,$arrayAdBannerPayment);
					$updateOrder["order_end_date"] = date("Y-m-d",strtotime( "+1 month", strtotime( CURRENT_DATE_TIME ) ));
					$obj->updateData(TABLE_USER_AD_BANNER_ORDER, $updateOrder, "id='" . $stripeOrderDetail["id"] . "'");
				}
			}
		}
	}
	function sendSubscriptionChargeMail($pmtDetail){
		global $obj;
		if(isset($pmtDetail["transaction_id"]) && $pmtDetail["transaction_id"] != ""){
			$flagoldTable=1;
			if($flagoldTable){
				$stripeOrderDetail = $obj->selectData(TABLE_ORDER, "", "subscription_id='" . $pmtDetail["subscriptionId"] . "'", 1);
				if($stripeOrderDetail["order_id"] && $stripeOrderDetail["order_id"] > 0){
					$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
					$userDataDetail = $obj->selectData(TABLE_ORDER_DETAILS, "", "order_id='" . $stripeOrderDetail['order_id'] . "'", 1);
					$ammessage = "<b> Hi " . ucfirst($userData["user_first_name"]) . "</b><br><br>";
					$ammessage .= "Thankyou for subscribe Hashing Ad Space banner! <br><br>";
					$ammessage .= "Your subscription charge amount:&nbsp;$".$stripeOrderDetail["order_total"]."<br/>";
					$ammessage .= "Your subscription ad banner type:&nbsp;".ucfirst($userDataDetail["pack_title"])."<br/>";
					$ammessage .= "We hope to see you in Hashing Ad Space!";
					$ammessage .= "The Hashing Ad Space Team.";
					$ammessage .= "<br><br>See you there!<br><br>" . MAIL_THANK_YOU;
					$ammessage .= "<br><br>";
					$ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
					$ammessage .= '<p style="border-top:1px solid #808080"></p>';
					$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
					$ammessage .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($stripeOrderDetail['user_id']) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
					$body2 = $obj->mailBody($ammessage);
					$from2 = FROM_EMAIL_2;
					$to2 = $userData["user_email"];
					$subject2 = "Your Ad banner subscription charge at Hashing Ad Space";
				}
			}else{
				$stripeOrderDetail = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "subscription_id='" . $pmtDetail["subscriptionId"] . "'", 1);
				if($stripeOrderDetail["id"] && $stripeOrderDetail["id"] > 0){
					$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
					$ammessage = "<b> Hi " . ucfirst($userData["user_first_name"]) . "</b><br><br>";
					$ammessage .= "Thankyou for subscribe Hashing Ad Space banner! <br><br>";
					$ammessage .= "Your subscription charge amount:&nbsp;$".$stripeOrderDetail["total"]."<br/>";
					$ammessage .= "Your subscription ad banner type:&nbsp;".ucfirst($stripeOrderDetail["banner_title"])."<br/>";
					$ammessage .= "We hope to see you in Hashing Ad Space!";
					$ammessage .= "The Hashing Ad Space Team.";
					$ammessage .= "<br><br>See you there!<br><br>" . MAIL_THANK_YOU;
					$ammessage .= "<br><br>";
					$ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
					$ammessage .= '<p style="border-top:1px solid #808080"></p>';
					$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
					$ammessage .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($stripeOrderDetail['user_id']) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
					$body2 = $obj->mailBody($ammessage);
					$from2 = FROM_EMAIL_2;
					$to2 = $userData["user_email"];
					$subject2 = "Your Ad banner subscription charge at Hashing Ad Space";
				}
			}
		}
	}
	function updatePaypalOrder($orderData){
		global $obj;
		if(isset($orderData) && $orderData["orderId"] > 0){
			$flagoldTable=1;
			if($flagoldTable){
				$OrderDetail = $obj->selectData(TABLE_ORDER, "", "order_id='" . $orderData["orderId"] . "'", 1);
				if($OrderDetail["order_id"] && $OrderDetail["order_id"] > 0){
					$updateOrder["order_end_date"] = date("Y-m-d",strtotime( "+1 month", strtotime( CURRENT_DATE_TIME ) ));
					$updateOrder["subscription_id"] = $orderData["subscriptionId"];
					$updateOrder["order_status"] = "Active";
					$obj->updateData(TABLE_ORDER, $updateOrder, "order_id='" . $OrderDetail["order_id"] . "'");
					$updateOrderDetail["od_status"] = "Active";
					$obj->updateData(TABLE_ORDER_DETAILS, $updateOrderDetail, "order_id='" . $OrderDetail["order_id"] . "'");
				}
			}else{
				$OrderDetail = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "id='" . $orderData["orderId"] . "'", 1);
				if($OrderDetail["id"] && $OrderDetail["id"] > 0){
					$updateOrder["order_end_date"] = date("Y-m-d",strtotime( "+1 month", strtotime( CURRENT_DATE_TIME ) ));
					$updateOrder["subscription_id"] = $orderData["subscriptionId"];
					$updateOrder["status"] = "Active";
					$updateOrder["order_status"] = "paid";
					$orderNumber = BANNER_ORDER_PREFIX.$orderData["orderId"];
					$updateOrder["order_number"] =$orderNumber;
					$updateOrder["payment_status"] ='capture';
					$obj->updateData(TABLE_USER_AD_BANNER_ORDER, $updateOrder, "id='" . $OrderDetail["id"] . "'");
				}
			}
		}
	}
	function addWebhookLog($paypload,$from){
		global $obj;
		$arrayWebhookInput["webhook_from"] = $from;
		$arrayWebhookInput["webhook_request"] = str_replace('"', " " ,$paypload);
		$arrayWebhookInput["created_on"] = CURRENT_DATE_TIME;
		$obj->insertData(TABLE_AD_BANNER_ORDER_WEBHOOKLOG, $arrayWebhookInput);
	}
	function checkSubscriptionIdExists($subscription_id){
		global $obj;
		$orderData = $obj->selectData(TABLE_USER_AD_BANNER_ORDER, "", "subscription_id='" . $subscription_id . "'", 1);
		if($orderData["order_id"] > 0){
			return true;
		}else{
			return false;
		}
	}
	function checkSubscriptionMailSent($subscription_id){
		global $obj;
		$subData = $obj->selectData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION, "", "subscription_id='" . $subscription_id . "'", 1);
		if($subData["subscription_success_email_sent"]=="Yes"){
			return true;
		}else{
			return false;
		}
	}
	function checkTransactionIdExists($transaction_id){
		global $obj;
		$trData = $obj->selectData(TABLE_BANNER_ORDER_PAYMENT, "", "transaction_id='" . $transaction_id . "'", 1);
		if($trData["id"]>0){
			return true;
		}else{
			return false;
		}
	}
	function cancelSubscriptionAndSendMail($subscriptionID){
		global $obj;
		if(isset($subscriptionID) && $subscriptionID != ""){
		$stripeOrderDetail = $obj->selectData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION, "", "subscription_id='" . $subscriptionID . "'", 1);
			if($stripeOrderDetail["order_id"] && $stripeOrderDetail["order_id"] > 0){
				$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $stripeOrderDetail['user_id'] . "'", 1);
				$userstatusArr["status"]="Cancel";
				$userstatusArr["subscription_cancel_date"]=CURRENT_DATE_TIME;
			  	$obj->updateData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION, $userstatusArr, "subscription_id='" . $subscriptionID . "'");
				$ammessage = "<b> Hi " . ucfirst($userData["user_first_name"]) . "</b><br><br>";
				$ammessage .= "Your Ad banner subscription successfully cancled! <br><br>";
				$ammessage .= "We hope to see you in Hashing Ad Space!";
				$ammessage .= "The Hashing Ad Space Team.";
				$ammessage .= "<br><br>See you there!<br><br>" . MAIL_THANK_YOU;
				$ammessage .= "<br><br>";
				$ammessage .= '<br><br><p style="font-family: "Lato", sans-serif; font-size:12px; font-weight:normal; color:#939799"><center>If you did not intend to register with Hashing Ad Space, simply ignore this email and you will not be contacted again.</center> </p>';
				$ammessage .= '<p style="border-top:1px solid #808080"></p>';
				$StyleVAr = "font-family: 'Lato', sans-serif; font-size:12px; font-weight:normal; color:#939799; text-align: center; margin:0;";
				$ammessage .= '<p style="' . $StyleVAr . '">Stop Receiving These Emails: <a target="_blank" href="' . FURL . "deactivation.php?uactid=" . $obj->encryptPass($stripeOrderDetail['user_id']) . '">UNSUBSCRIBE</a> <br><br> Hashing Ad Space - 9101 LBJ Freeway #300 <br> Dallas, TX 75243 </p>';
				$body2 = $obj->mailBody($ammessage);
				$from2 = FROM_EMAIL_2;
				$to2 = $userData["user_email"];
				$subject2 = "Your Ad banner subscription cancled at Hashing Ad Space";
				$mamam = $obj->sendMailSES($to2, $subject2, $body2, $from2, SITE_TITLE, $type);
				// $myfile2 = @fopen("subscription_d_mail_".date("Y-m-d").".txt", "a+");
				// fwrite($myfile2,gmdate('Y-m-d H:i:s')."\n\r");
				// fwrite($myfile2,$ammessage."\n\r\n\r");
				// fwrite($myfile2,"------------------------------------------------------------------------------\n\r\n\r");
				// fclose($myfile2);
			}
		}
	}
} 

?>
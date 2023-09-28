<?php
require_once ('../vendor/autoload.php');
$stripe = new \Stripe\StripeClient(SECRET_KEY_STRIPE);
//get customer id of stripe;
$stripeCuId = $obj->selectData(TABLE_USER, "stripe_customer_id", "u_login_id='" . $_SESSION['user']['u_login_id'] . "'", 1);
if(isset($stripeCuId["stripe_customer_id"]) && $stripeCuId["stripe_customer_id"] != ""){
	$stripeCustomerId= $stripeCuId["stripe_customer_id"];
}
$monthYear =explode("/",$_REQUEST["month"]);
$userData = $obj->selectData(TABLE_USER, "", "u_login_id='" . $_SESSION['user']['u_login_id'] . "'", 1);
try
{
	$resToken = $stripe->tokens->create([
	  'card' => [
	    'number' => $_REQUEST["cnumber"],
	    'exp_month' =>$monthYear[0],
	    'exp_year' => "20".$monthYear[1],
	    'cvc' => $_REQUEST["cvv"],
	  ],
	]);
}
catch(Exception $e)
{
	$token_error = $e->getMessage();
    $return_array = [
        "status" => $e->getHttpStatus(),
        "type" => $e->getError()->type,
        "code" => $e->getError()->code,
        "param" => $e->getError()->param,
        "message" => $e->getError()->message,
    ];
   $payment_res="";
   	foreach ($return_array as $key => $value)  
	{  
		$payment_res .= $comma.$key."=".$value;
		$comma=",";
	}
    $return_str = $payment_res;
    //include_once("payment_log.php"); 
    $orderPaymentId = $objBannerPayment->insertOrderPayment($orderID,$return_str);
    $obj->add_message("message", $e->getError()->message);
    $_SESSION['messageClass'] = 'errorClass'; 
    if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==LEADERBOAD_BANNER_TITLE){
		$obj->reDirect('leaderboard_ad_purchase.php');
		exit;
	}
	if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==SQUARE_BANNER_TITLE){
		$obj->reDirect('square_ad_product_purchase.php');
		exit;
	}       
    $obj->reDirect('leaderboard_ad_purchase.php');
    exit;
}
if(empty($token_error) && $resToken){ 
   	try
   	{
        $customer = $stripe->customers->create(['name' => $userData["user_first_name"], 'email' => $userData["user_email"], 'source' => $resToken->id]);
        $stripeCustomerId = $customer->id;
        // $stripeSaveId["stripe_customer_id"] = $stripeCustomerId;
        // $obj->updateData(TABLE_USER, $stripeSaveId, "u_login_id='" . $_SESSION['user']['u_login_id'] . "'");
    }
    catch(Exception $e)
    {
    	$customer_error = $e->getError();
	    $return_array = [
	        "status" => $e->getHttpStatus(),
	        "type" => $e->getError()->type,
	        "code" => $e->getError()->code,
	        "param" => $e->getError()->param,
	        "message" => $e->getError()->message,
	    ];
	   	$payment_res="";
	   	foreach ($return_array as $key => $value)  
		{  
			$payment_res .= $comma.$key."=".$value;
			$comma=",";
		}
	    $return_str = $payment_res;
	    $orderPaymentId = $objBannerPayment->insertOrderPayment($orderID,$return_str);
	   // include_once("payment_log.php"); 
	    $obj->add_message("message", MSG_INVALID_TOKEN_STRIPE);
	    $_SESSION['messageClass'] = 'errorClass';        
	    if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==LEADERBOAD_BANNER_TITLE){
			$obj->reDirect('leaderboard_ad_purchase.php');
			exit;
		}
		if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==SQUARE_BANNER_TITLE){
			$obj->reDirect('square_ad_product_purchase.php');
			exit;
		}
		$obj->reDirect('leaderboard_ad_purchase.php');                 
	    exit;
    }
    if(empty($customer_error) && $customer){
    	try
		{
			$resSubscription = $stripe->subscriptions->create(['customer' => $stripeCustomerId, 'items' => [['price' =>$_REQUEST["request_banner_plan"]], ], ]);
		}
		catch(Exception $e)
		{
			$subscription_error = $e->getMessage();
			$return_array = [
	        "status" => $e->getHttpStatus(),
	        "type" => $e->getError()->type,
	        "code" => $e->getError()->code,
	        "param" => $e->getError()->param,
	        "message" => $e->getError()->message,
		    ];
		    $payment_res="";
		   	foreach ($return_array as $key => $value)  
			{  
				$payment_res .= $comma.$key."=".$value;
				$comma=",";
			}
		    $return_str = $payment_res;
		    $obj->add_message("message", $e->getError()->message);
		    //include_once("payment_log.php"); 
		    $orderPaymentId = $objBannerPayment->insertOrderPayment($orderID,$return_str);
		    $_SESSION['messageClass'] = 'errorClass';  
			if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==LEADERBOAD_BANNER_TITLE){
				$obj->reDirect('leaderboard_ad_purchase.php');
				exit;
			}
			if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==SQUARE_BANNER_TITLE){
				$obj->reDirect('square_ad_product_purchase.php');
				exit;
			}		    
		    $obj->reDirect('leaderboard_ad_purchase.php');       
		    exit;   
		}
		if(empty($subscription_error) && $resSubscription){
			$return_str = json_encode($resSubscription);
			$subscriptionsId = $resSubscription->id;
			$data = json_decode($return_str, TRUE);
			$payment_res="";
		   	foreach ($data as $key => $value)  
			{  
				$payment_res .= $comma.$key."=".$value;
				$comma=",";
			}
		    $return_str = $payment_res;
		    $updatedOrderId = $objBannerPayment->updateOrderDetail($orderID,$subscriptionsId,$stripeCustomerId);
			//include_once("payment_log.php"); 
			
        	//add subscription detail
        	//include_once("subscription_log.php"); 
        	unset($_SESSION["ord_banner_type"]);
			$obj->reDirect('../thank-you.php');
    		exit;
		}
	}
}else{
	if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==LEADERBOAD_BANNER_TITLE){
			$obj->reDirect('leaderboard_ad_purchase.php');
			exit;
	}
	if(isset($_SESSION['ord_banner_type']) && $_SESSION['ord_banner_type'] ==SQUARE_BANNER_TITLE){
		$obj->reDirect('square_ad_product_purchase.php');
		exit;
	}
	$obj->reDirect('leaderboard_ad_purchase.php');   
    exit;
}
?>
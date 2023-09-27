<?php
//log all request and user seesion
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
        $ad_banner_amount = SQUARE_AD_PRICE;
        $stripe_plan_id = STRIPE_SQUARE_BANNER_PLAN_ID;
        $banner_title = SQUARE_BANNER_TITLE;
    }
    else
    {
        $ad_banner_amount = LEADERBOAD_AD_PRICE;
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
$arryOrder["order_request_id"] = $orderRequestID ;
$arryOrder["user_id"] =$_SESSION['user']['u_login_id'];
if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
    $arryOrder["payment_method"] ="stripe";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypal"){
    $arryOrder["payment_method"] ="paypal";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="crypto"){
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
$arryOrder["status"] = "Inactive";
$orderId = $obj->insertData(TABLE_USER_AD_BANNER_ORDER,$arryOrder);

$arrayOrderDetail["order_id"] = $orderId;
$arrayOrderDetail["banner_title"] = $banner_title;
$arrayOrderDetail["banner_price"] = $ad_banner_amount;
$arrayOrderDetail["created_on"] = CURRENT_DATE_TIME;
$orderDetailId = $obj->insertData(TABLE_USER_AD_BANNER_ORDER_DETAIL,$arrayOrderDetail);

//insert order request end
?>
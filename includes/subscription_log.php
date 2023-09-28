<?php
if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
	$arrySubscription["method"] = "stripe";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypal"){
	$arrySubscription["method"]= "paypal";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="crypto"){
	$arrySubscription["method"] ="crypto";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="wallet"){
	$arrySubscription["method"] ="wallet";
}else{
	$arrySubscription["method"] ="cheque";
}
$arrySubscription["subscription_id"] = $subscriptionsId;
$arrySubscription["stripe_customer_id"] = $stripeCustomerId;
$arrySubscription["method"] = "stripe";
$arrySubscription["order_id"] = $orderId;
$arrySubscription["user_id"] = $_SESSION['user']['u_login_id'];
$arrySubscription["amount"] = $_REQUEST["request_banner_amt"];
$arrySubscription["status"] = "Active";
$arrySubscription["frequency"] = "1";
$arrySubscription["subscription_datetime"] = CURRENT_DATE_TIME;
$arrySubscription["created_date"] = CURRENT_DATE_TIME;
$orderSubscriptonId = $obj->insertData(TABLE_AD_BANNER_ORDER_SUBSCRIPTION,$arrySubscription);
?>
<?php
$arrayPaymentLog["user_id"] = $_SESSION['user']['u_login_id'];
$arrayPaymentLog["order_id"] = $orderId;
if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="cardpayment"){
	$arrayPaymentLog["payment_method"] = "stripe";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="paypal"){
	$arrayPaymentLog["payment_method"] = "paypal";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="crypto"){
	$arrayPaymentLog["payment_method"] ="crypto";
}else if(isset($_POST['pmt_method']) && $_POST['pmt_method']=="wallet"){
	$arrayPaymentLog["payment_method"] ="wallet";
}else{
	$arrayPaymentLog["payment_method"] ="cheque";
}
$arrayPaymentLog["payment_request"] =$request;
$arrayPaymentLog["payment_response"] =$return_str;
$arrayPaymentLog["transaction_id"] = @$transaction_id;
$arrayPaymentLog["wallet_id"] = @$wallet_id;
$arrayPaymentLog["created_date"] = CURRENT_DATE_TIME;
$arrayPaymentLog["amount"] = @$_REQUEST["request_banner_amt"];
$orderPaymentID = $obj->insertData(TABLE_BANNER_ORDER_PAYMENT,$arrayPaymentLog);

?>
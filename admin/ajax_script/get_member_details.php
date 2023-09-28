<?php
include("../../includes/connection.php");
$EmailId  = $obj->filter_mysql($_REQUEST['member']);
$typeahead = explode('(',$EmailId); 			
$userEmail = rtrim($typeahead['1'],")");
$userData = $obj->selectData(TABLE_USER,"","user_email='".$userEmail."' and user_status='Active'",1);
$userId = $userData['u_login_id'];
$available_balance 		= $obj->total_available_balance_launch($userId);
$veriFee 				= $obj->get_current_asimi_token(3);	
$tot_stake  			= $obj->get_total_minting_package_by_member($userId);
if($tot_stake > 4)  	$verification_fee = '0.00';
else if($veriFee > 20)  $verification_fee = 20;
else if(20 > $veriFee)  $verification_fee = $veriFee;
$userLD   = $obj->selectData(TABLE_USER_LOGIN,"u_login_id,is_kyc_verified,u_login_user_email","u_login_user_email='".$userEmail."' and u_login_status='Active' and u_suspend_status='n'",1);
 
echo htmlentities($userData['user_first_name']).' '.htmlentities($userData['user_last_name'])."||".$userData['user_address_1']."||".$obj->getCountry($userData['user_country'])."||".$userData['user_city']."||".$available_balance."||".$verification_fee."||".$userData['user_email']."||".$userLD['is_kyc_verified'];
$obj->close_mysql();
?>
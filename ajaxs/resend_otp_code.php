<?php
include("../includes/connection.php");
if (!isset($_SESSION['new_user_id']) || $_SESSION['new_user_id'] == '') {
	exit;
}

$new_user_id = $_SESSION['new_user_id'];



$otpU['uo_status'] = 'Deleted';
$otpD = $obj->updateDataAll(TABLE_USER_OTP,$otpU,"user_id='".$new_user_id."' and uo_status<>'Deleted'"); 


$reg_activation_number = rand(111111,999999);

$userRAN['uo_otp']  = $reg_activation_number;
$userRAN['user_id'] = $new_user_id;
$obj->insertData(TABLE_USER_OTP,$userRAN);


$sqlUL = $obj->selectData(TABLE_USER_LOGIN,"","u_login_id='".$new_user_id."'",1);
$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$new_user_id."'",1);
  
$ammessage   = "<b> Hi ".$userD['user_full_name']."</b><br><br>";			
$ammessage  .= "Your One Time Password (OTP) is ".$reg_activation_number."<br><br>";		 
$ammessage  .= "Thank You for using our Services.<br><br>";	
$ammessage .="<br><br>See you there!<br><br>".MAIL_THANK_YOU;		
$body2 = $obj->mailBody($ammessage);  
$from2 = FROM_EMAIL_2;
$to2   = $sqlUL['u_login_user_email'];			 
$subject2 = "Resending activation OTP";			
$valArr = $obj->sendMail_server($to2,$subject2,$body2,$from2,SITE_TITLE,$type);
	
$_SESSION['messageClass'] = "successClass";	
//$obj->add_message("message","OTP is sent to your registered EMAIL ID");
echo $msg = "OTP is sent to your registered EMAIL ID";
 
//echo $valArr;
$obj->close_mysql();

 

 
 

 
?>
<?php
include "../includes/connection.php";
userSecure();

$log_user_id = $_SESSION['user']['u_login_id'];
$loguserD   = $obj->selectData(TABLE_USER,"","u_login_id='".$log_user_id."'",1);

$pck_id = $_REQUEST['cb_pck'];
$pckD = $obj->selectData( TABLE_PACKAGE, "", "pck_id='" . $pck_id . "'", 1 );
$pckUserD   = $obj->selectData(TABLE_USER,"","u_login_id='".$pckD['user_id']."'",1);

$ammessage   = "<b> Hi ".ucfirst($pckUserD['user_full_name'])."</b><br><br>";			
			$ammessage  .= $loguserD['user_full_name']." has sent you a query regarding your tour - ".$pckD['pck_title']."<br><br>";
			$ammessage  .= "Adults - ".$_REQUEST['cb_adult']."<br><br>";				
			$ammessage  .= "Child - ".$_REQUEST['cb_child']."<br><br>";
			if(isset($_REQUEST['cb_share_phone']) && $_REQUEST['cb_share_phone']=='y')
			{
				$ammessage  .= "Contact No - ".$loguserD['user_phone']."<br><br>";
			}
			$ammessage  .= "Query - ".$_REQUEST['cb_query']."<br><br>";
			$ammessage  .= MAIL_THANK_YOU; 
			
			$body  = $obj->mailBody($ammessage); 	 
			$from  = FROM_EMAIL_2;
			$to    = ADMIN_EMAIL_1;			 
			$subject = "Contact Tour - ".$pckD['pck_title'];			
			$mailTH = $obj->sendMail_server($to,$subject,$body,$from,SITE_TITLE,$type,$loguserD['user_email'],"",$pckUserD['user_email']); 

if($mailTH) echo "1||Your query sent to Tour Agent Successfully!";
else echo "0||There is some issue with teh server. Please try later!";
?>



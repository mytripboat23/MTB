<?php
include "../includes/connection.php";
userSecure();

$log_user_id = $_SESSION['user']['u_login_id'];

$dataP = array();
$dataP["user_display_name"]      = $_POST['us_display_name'];
$dataP["user_bio"]       = $_POST['us_bio'];

$dataP["user_city"]      = $_POST['us_city'];
$dataP["user_from_city"] = $_POST['us_from_city'];
$dataP["user_phone"]     = $_POST['us_phone'];
$dataP["user_website"]   = $_POST['us_website'];
$dataP["user_gender"]    = $_POST['us_gender'];
$dataP["user_dob"]       = $_POST['us_dob'];

if(isset($_POST['us_lang']) && !empty($_POST['us_lang'])){ $dataP["user_lang"]  = ",".implode(",",$_POST['us_lang']).",";}
else
{
	$dataP["user_lang"] = "";
}
if(isset($_POST['us_hobby']) && !empty($_POST['us_hobby'])){ $dataP["user_hobby"] = ",".implode(",",$_POST['us_hobby']).",";}
else
{
	$dataP["us_hobby"]  = "";
}
$opt_val = "";
if(isset($_POST['tour_operator']) && $_POST['tour_operator']=='y')
{
		$opt_val = "ind,";
		if(isset($_POST['opt_type'])){
		  if (is_array($_POST['opt_type'])) {						
			  $opt_val .= implode(",",$_POST['opt_type']);
		  } 
		}
}
$dataP['user_type']  = $opt_val;	

$_SESSION['user']['user_type'] = $opt_val;

if($_SESSION['user_profile_photo']!="") $dataP["user_avatar"]    = $_SESSION['user_profile_photo'];
if($_SESSION['user_banner_photo']!="")  $dataP["user_banner"]    = $_SESSION['user_banner_photo'];

$_SESSION['user_banner_photo'] = "";
$_SESSION['user_profile_photo'] = "";

$obj->updateData(TABLE_USER,$dataP,"u_login_id='".$log_user_id."'");

echo "1||Profile data updated successfully!";
?>



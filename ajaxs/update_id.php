<?php
include "../includes/connection.php";
userSecure();

$log_user_id = $_SESSION['user']['u_login_id'];
$msg = "";

if($_POST['id_num']=="") $msg = "ID number provided blank.";

if($msg=="")
{
	$id_photo = "";
	if($_FILES['id_photo']['tmp_name'])
	{
		list($fileName,$error)=$obj->uploadFile('id_photo', "../".ID, 'jpg,png,jpeg');
		if($error)
		{
			$msg = $error;
			$err=1;
		} else {
			$id_photo = ID.$fileName;				
		}
	}
}

if($msg=="")
{		
	$id_self_photo = "";
	if($_FILES['id_self_photo']['tmp_name'])
	{
		list($fileName,$error)=$obj->uploadFile('id_self_photo', "../".ID, 'jpg,png,jpeg');
		if($error)
		{
			$msg .= $error."<br>";
			$err=1;
		} else {
			$id_self_photo = ID.$fileName;				
		}
	}
}
if($msg=="")
{
	$dataP = array();
	$dataP["user_id_no"]               = $_POST['id_num'];
	$dataP["user_id_photo"]   		   = $id_photo;
	$dataP["user_self_photo"]          = $id_self_photo;


	$obj->updateData(TABLE_USER,$dataP,"u_login_id='".$log_user_id."'");
	
	echo "1||ID details updated successfully!";
}
else
{
	echo "0||".$msg;
}

?>



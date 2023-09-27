<?php
include "../includes/connection.php";

$banner_photo = "";
$msg = "";
if($_FILES['file']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('file', "../".CBANNER, 'jpg,png,jpeg');
			if($error)
			{
				$msg=$error;
				$err=1;
			} else {
				$banner_photo = CBANNER.$fileName;				
			}
		}
		

$str = "";
if($banner_photo!="") { $str = "1||".$banner_photo."||".$msg; $_SESSION['user_banner_photo'] = $fileName; }
else $str = "0|| ||".$msg;

echo $str;
?>



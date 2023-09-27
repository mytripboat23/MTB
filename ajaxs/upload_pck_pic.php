<?php
include "../includes/connection.php";

$st_photos = "";
$msg = "";

//if(isset($_SESSION['pck_photos'][0]) && $_SESSION['pck_photos'][0]!="") $k = count($_SESSION['pck_photos']);
//else $k=0;
$k=0;
for($i=0; $i<$_POST['count'];$i++)
{
		if($_FILES['file_'.$i]['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('file_'.$i, "../".PACKAGE, 'jpg,png,jpeg,webp');
			if($error)
			{
				$msg .= $error;
				$err=1;
			} else {
				
				//$st_photos .= $fileName.",";	
				$_SESSION['pck_photos'][$k] = $fileName;	
				//$k++;
			}
		}
}
	
$st_photos = trim(implode(",",$_SESSION['pck_photos']));	

$str = "";
if($st_photos!="") { $str = "1||".$st_photos."||".$msg;}
else $str = "0|| ||".$msg;

echo $str;
?>



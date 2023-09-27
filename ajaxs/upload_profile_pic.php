<?php
include "../includes/connection.php";

$profile_photo = "";
$msg = "";
if($_FILES['file']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('file', "../".AVATAR_TMP, 'jpg,png,jpeg');
			if($error)
			{
				$msg=$error;
				$err=1;
			} else {
				$profile_photo = $fileName;				
			}
		}

if($profile_photo!='')
{	
	chmod("../".AVATAR_TMP.$profile_photo,0755);
	
	$ext_arr = explode(".",$profile_photo);
    $ext = strtolower($ext_arr[count($ext_arr)-1]);
	$uniqer = substr(md5(uniqid(rand(),1)),0,5);
	$file_name = $uniqer . '_' . date('YmdHis').'.'.$ext;
	$profile_crop_photo = $file_name;
	
	$obj->scaleCrop(FPATH.AVATAR_TMP.$profile_photo,FPATH.AVATAR.$profile_crop_photo,'middle');		
		

	$str = "1||".AVATAR.$profile_crop_photo."||".$msg; $_SESSION['user_profile_photo'] = $profile_crop_photo;
	echo $str;
}
else
{
	 $str = "0|| ||".$msg;
}
?>



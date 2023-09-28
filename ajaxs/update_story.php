<?php
include "../includes/connection.php";
userSecure();

$log_user_id = $_SESSION['user']['u_login_id'];

/*if(!isset($_SESSION['story_photos'][0]) || $_SESSION['story_photos'][0]=="")
{
	echo "0||Please upload atleast one photo";
	exit;
}*/

$status = "";
if(!isset($_SESSION['new_story_id']) && !isset($_SESSION['edit_story_id']))
{
	$dataP = array();
		
	$dataP["ts_title"]      = $_POST['st_title'];
	
	$dataP["ts_start"]      = $_POST['st_start_date'];
	$dataP["ts_end"]        = $_POST['st_end_date'];
	
	$dataP["ts_desc"]      = htmlentities($_POST['st_part_desc']);
	$dataP["ts_photos"]    = implode(",",$_SESSION['story_photos']);
		
	$dataP["user_id"]       = $log_user_id;
		
	$new_story_id = $obj->insertData(TABLE_STORY,$dataP);
		
	$_SESSION['new_story_id'] = $new_story_id;
	$status = "Y";
}	
else
{
	$dataP = array();
		
	$dataP["ts_title"]      = $_POST['st_title'];
	
	$dataP["ts_start"]      = $_POST['st_start_date'];
	$dataP["ts_end"]        = $_POST['st_end_date'];
	
	$dataP["ts_desc"]      = htmlentities($_POST['st_part_desc']);
	$dataP["ts_photos"]    = implode(",",$_SESSION['story_photos']);
		
	$update_status = $obj->updateData(TABLE_STORY,$dataP,"ts_id='".$_SESSION['edit_story_id']."' and user_id='".$log_user_id."'");

}


/*$dataPS = array();
$dataPS["ts_id"]         = $_SESSION['new_story_id'];
$dataPS["tss_title"]     = htmlentities($_POST['st_part_title']);
$dataPS["tss_date"]      = $_POST['st_part_date'];
$dataPS["tss_desc"]      = htmlentities($_POST['st_part_desc']);
$dataPS["tss_photos"]    = implode(",",$_SESSION['story_photos']);
*/
//$tId = $obj->insertData(TABLE_STORY_SUB,$dataPS);

if(isset($_SESSION['new_story_id']) && $_SESSION['new_story_id']!='')
{
	$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$log_user_id."'",1);
				
	$comp_ids = trim($obj->get_user_friend_ids($log_user_id));
	$obj->set_notification($comp_ids,"New Story added by ".$userD['user_display_name'],"story-details.php?tId=".$tId);
	
	
	$_SESSION['story_photos'] = "";
	unset($_SESSION['story_photos']);
}

/*if(!isset($_POST['sagn']))
{
	$_SESSION['new_story_id'] = "";
	unset($_SESSION['new_story_id']);
}*/

/*if(!isset($_SESSION['new_story_id']) || $_SESSION['new_story_id']=="")
{
	if($status=="") echo "1||Story details updated sucecssfully!";
	else echo "1||Story details added sucecssfully!";
}
else
{
	if($status=="") echo "1||Story details updated sucecssfully! Please continue upload next part.";
	else echo "1||Story details added sucecssfully! Please continue upload next part.";
	unset($_SESSION['new_story_id']);
	echo "1||Story created sucecssfully.";
}*/	

if(isset($_SESSION['new_story_id']) && $_SESSION['new_story_id']!="")
{
	unset($_SESSION['new_story_id']);
	echo "1||Story created sucecssfully.";
}
else if(isset($_SESSION['edit_story_id']))
{
	unset($_SESSION['edit_story_id']);
	echo "1||Story updated sucecssfully.";
}

?>



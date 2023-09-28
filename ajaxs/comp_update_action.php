<?php
include "../includes/connection.php";
userSecure();

$log_user_id = $_SESSION['user']['u_login_id'];

$msg = "";
$status = 0;
$action_status = "";

$dataP = array();
$dataC = array();
$comp_id           = $obj->filter_numeric($_POST['comp_id']);
$comp_action       = htmlentities($_POST['comp_action']);

if(!in_array($comp_action,array('accept','reject','follow','unfollow','block','unblock')))
{
	$msg = "Invalid request";
}
else
{
		$comD = $obj->selectData(TABLE_FRIENDS,"","fr_id='".$comp_id."'",1);
		
		if($comD['fr_from_id']==$log_user_id || $comD['fr_to_id']==$log_user_id)
		{
			if($comp_action=='accept' && ($comD['fr_to_id']!=$log_user_id || $comD['fr_status']!='S'))
			{
				$msg = "Invalid request";
			}
			elseif($comp_action=='reject' && ($comD['fr_to_id']!=$log_user_id || $comD['fr_status']!='A'))
			{
				$msg = "Invalid request";
			}
			elseif($comp_action=='follow')
			{
				if($comD['fr_status']!='A' || ( $comD['fr_follow_1']!='u' && $comD['fr_follow_2']!='u'))
				{
					$msg = "Invalid request";
				}
				else if(($comD['fr_follow_1']!='u' && $comD['fr_from_id']==$log_user_id ) || ($comD['fr_follow_2']!='u' && $comD['fr_to_id']==$log_user_id ))
				{
					$msg = "Invalid request";
				}
				else
				{
					if($comD['fr_from_id']==$log_user_id) $dataC['fr_follow_1'] = 'f'; 
					else $dataC['fr_follow_2'] = 'f'; 
					$action_status = "followed your companion";
					$status = 1;
					$obj->updateData(TABLE_FRIENDS,$dataC,"fr_id='".$comp_id."'");
					$msg = "You have successfully ".$action_status;
				}
			}
			elseif($comp_action=='unfollow')
			{
				if($comD['fr_status']!='A' || ( $comD['fr_follow_1']!='f' && $comD['fr_follow_2']!='f'))
				{
					$msg = "Invalid request";
				}
				else if(($comD['fr_follow_1']!='f' && $comD['fr_from_id']==$log_user_id ) || ($comD['fr_follow_2']!='f' && $comD['fr_to_id']==$log_user_id ))
				{
					$msg = "Invalid request";
				}
				else
				{
					if($comD['fr_from_id']==$log_user_id) $dataC['fr_follow_1'] = 'u'; 
					else $dataC['fr_follow_2'] = 'u';
					$action_status = "unfollowed your companion";
					$status = 1;
					$obj->updateData(TABLE_FRIENDS,$dataC,"fr_id='".$comp_id."'");
					$msg = "You have successfully ".$action_status;
				}
			}
			elseif($comp_action=='block')
			{
				if($comD['fr_status']!='A' || ( $comD['fr_block_1']!='u' && $comD['fr_block_2']!='u'))
				{
					$msg = "Invalid request";
				}
				else if(($comD['fr_block_1']!='u' && $comD['fr_from_id']==$log_user_id ) || ($comD['fr_block_2']!='u' && $comD['fr_to_id']==$log_user_id ))
				{
					$msg = "Invalid request";
				}
				else
				{
					if($comD['fr_from_id']==$log_user_id) $dataC['fr_block_1'] = 'b'; 
					else $dataC['fr_block_2'] = 'b'; 
					$action_status = "blocked your companion";
					$status = 1;
					$obj->updateData(TABLE_FRIENDS,$dataC,"fr_id='".$comp_id."'");
					$msg = "You have successfully ".$action_status;
				}
			}
			elseif($comp_action=='unblock' && ($comD['fr_status']!='A' || $comD['fr_block']!='b'))
			{
				if($comD['fr_status']!='A' || ( $comD['fr_block_1']!='b' && $comD['fr_block_2']!='b'))
				{
					$msg = "Invalid request";
				}
				else if(($comD['fr_block_1']!='b' && $comD['fr_from_id']==$log_user_id ) || ($comD['fr_block_2']!='b' && $comD['fr_to_id']==$log_user_id ))
				{
					$msg = "Invalid request";
				}
				else
				{
					if($comD['fr_from_id']==$log_user_id) $dataC['fr_block_1'] = 'u'; 
					else $dataC['fr_block_2'] = 'u'; $action_status = "unblocked your companion";
					$status = 1;
					$obj->updateData(TABLE_FRIENDS,$dataC,"fr_id='".$comp_id."'");
					$msg = "You have successfully ".$action_status;
				}
			}
			else
			{
				$status = 1;
				
				$action_status = '';
				if($comp_action=='accept'){ $dataC['fr_status'] = 'A'; $action_status = "accepted companion request";}
				if($comp_action=='reject'){ $dataC['fr_status'] = 'R'; $action_status = "rejected companion request";}
				
				$obj->updateData(TABLE_FRIENDS,$dataC,"fr_id='".$comp_id."'");
				$msg = "You have successfully ".$action_status;
				
			}
			
		}
		else
		{
			$msg = "Invalid request";
		}
	}



echo $status."||".$msg;
?>



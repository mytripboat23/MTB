<?php
include "../includes/connection.php";

$from_id = $_POST['from'];
$to_id   = $_POST['to'];
$type    = $_POST['type'];

$str = "";

$ex_id = "";
$ex_status = "";
$comRS = $obj->selectData(TABLE_FRIENDS,"","(fr_from_id='".$from_id."' and fr_to_id='".$to_id."') or (fr_from_id='".$to_id."' and fr_to_id='".$from_id."')",1);
if($comRS['fr_id']!="")
{
	$ex_id =  $comRS['fr_id'];
	$ex_status = $comRS['fr_status'];
}

if($type == 'send')
{
	if($ex_id!="")
	{
		$dataF = array();
		$dataF['fr_from_id'] = $from_id;
		$dataF['fr_to_id']   = $to_id;
		$dataF['fr_requested']   = date("Y-m-d H:i:s");
		$dataF['fr_rejected']    = "";
		$dataF['fr_cancelled']   = "";
		$dataF['fr_status']   = 'S';
		
		$obj->updateData(TABLE_FRIENDS,$dataF,"fr_id='".$ex_id."'");	
		$str = "1||Companion request send successfully!";	
	}
	else
	{
		$dataF = array();
		$dataF['fr_from_id'] = $from_id;
		$dataF['fr_to_id']   = $to_id;
		$dataF['fr_requested']   = date("Y-m-d H:i:s");
		$dataF['fr_status']   = 'S';
		
		$obj->insertData(TABLE_FRIENDS,$dataF,"fr_id='".$ex_id."'");		
		$str = "1||Companion request send successfully!";	
	}
	$notiD = array();
	
	$notiD['noti_for_user'] = $to_id;
	$notiD['noti_from_user'] = $from_id;
	$notiD['noti_message'] = "New Companion Requested.";
	$notiD['noti_url'] = "companion.php";
	$obj->insertData(TABLE_NOTIFICATION,$notiD);	
}
else if($type == 'remove')
{
	$action = "";
	if($ex_id!="")
	{
		$dataF = array();
		if($comRS['fr_from_id']==$from_id){ $dataF['fr_status']      = 'C'; $dataF['fr_cancelled']      = date("Y-m-d H:i:s");  $action = 'removed';}
		if($comRS['fr_from_id']==$to_id){ $dataF['fr_status']        = 'R'; $dataF['fr_rejected']      = date("Y-m-d H:i:s"); $action = 'rejected';}
		
		$obj->updateData(TABLE_FRIENDS,$dataF,"fr_id='".$ex_id."'");	
		
		
		$notiDS = $obj->selectData(TABLE_NOTIFICATION,"","noti_for_user='".$to_id."' and noti_from_user='".$from_id."' and noti_url='companion.php' and noti_status='Active'",1);
		if($notiDS['noti_id']!="")
		{
			$notiD = array();
			$notiD['noti_status'] = 'Inactive';
			$obj->updateData(TABLE_NOTIFICATION,$notiD,"noti_id='".$notiDS['noti_id']."'");	
		}
		
		$str = "1||Companion request ".$action." successfully!";		
	}
}



echo $str;
?>



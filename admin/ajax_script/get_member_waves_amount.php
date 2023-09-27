<?php
include("../../includes/connection.php");
$LDate   = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-3,date("Y"))); 
$userId 	  	= $obj->filter_numeric($_REQUEST['userId']);
$wavesBalance 	= $obj->get_member_wave_balance($userId);
$sqlBL	 		= $obj->selectData(TABLE_VERIFIED_ASIMI_WALLET_BALANCE,"vawb_id","user_id='".$userId."' and vawb_date > '".$LDate."'  and vawb_status='Active'",1);
if($sqlBL['vawb_id']) $charges  		= '0.003';
$charges  		= 0;
 
$refdAmt  		= round($wavesBalance-$charges,8); 
 
echo $obj->getUserName($userId)."||".$refdAmt;
$obj->close_mysql();
?>
<?php
include("../../includes/connection.php");

$sip  = $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['ip']));

$sqlLM = $obj->selectData(TABLE_MONITORING_LOGINS,"distinct user_id","monl_ip='".$sip."'");
while($dataLM=mysqli_fetch_assoc($sqlLM))
{ 
	$userR = $obj->selectData(TABLE_USER,"u_login_id","user_referrer='".$dataLM['user_id']."'",1);
	if($userR['u_login_id']=='')
	{
		$uLogU['u_suspend_status'] = 'y';
		$obj->updateData(TABLE_USER_LOGIN,$uLogU,"u_login_id='".$dataLM['user_id']."'");	
	}	
}

$sqlAM = $obj->selectData(TABLE_MONITORING_ADCLICKS,"distinct user_id","monad_ip='".$sip."'");
while($dataAM=mysqli_fetch_assoc($sqlAM)){ 
{ 
	$userR = $obj->selectData(TABLE_USER,"u_login_id","user_referrer='".$dataAM['user_id']."'",1);
	if($userR['u_login_id']=='')
	{
		$uLogU['u_suspend_status'] = 'y';
		$obj->updateData(TABLE_USER_LOGIN,$uLogU,"u_login_id='".$dataAM['user_id']."'");
	}		
}
echo "suspended";
$obj->close_mysql();
?>
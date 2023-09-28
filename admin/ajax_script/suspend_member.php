<?php
include("../../includes/connection.php");
$userId  = $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['user_id']));
$uLogU['u_suspend_status'] = 'y';
$obj->updateData(TABLE_USER_LOGIN,$uLogU,"u_login_id='".$userId."'");
echo "suspended";
$obj->close_mysql();
?>
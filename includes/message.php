<?
$login_message = $obj->get_message("login_msg");
$message = $obj->get_message("message");
$ad_message = $obj->get_message("ad_message",$msg);
$update_msg = $obj->get_message("update_msg",$msg);
$update_message = $obj->get_message("update_message",$msg);
$send_message = $obj->get_message("send_message",$msg);
$obj->remove_message("login_msg");
$obj->remove_message("message");
$obj->remove_message("update_msg");
$obj->remove_message("ad_message");
$obj->remove_message("update_message");
$obj->remove_message("send_message");

$mes_class=$_SESSION['mesClass'];
$_SESSION['mesClass']='';
?>
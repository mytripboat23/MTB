<?php
include "../includes/connection.php";

$pck_id    = $_POST['pck_id'];
$user_id   = $_POST['user_id'];

$str = "";

$result = $obj->set_unset_pck_like($pck_id,$user_id);

if($result) echo $result."||Request completed successfully||".$obj->get_pck_like_count($pck_id);
else echo "0||Invalid Request";
?>



<?php
include "../includes/connection.php";

$ts_id     = $_POST['ts_id'];
$user_id   = $_POST['user_id'];

$str = "";

$result = $obj->set_unset_story_like($ts_id,$user_id);

if($result) echo $result."||Request completed successfully||".$obj->get_story_like_count($ts_id);
else echo "0||Invalid Request";
?>



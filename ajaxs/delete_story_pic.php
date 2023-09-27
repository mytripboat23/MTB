<?php
include "../includes/connection.php";

$st_photos = "";
$msg = "";

$pos = $_POST['pos'];

$_SESSION['story_photos'][$pos] = "";

	
$st_photos = implode(",",$_SESSION['story_photos']);
$st_photos = trim(str_replace(",,",",",$st_photos),",");

$_SESSION['story_photos'] = explode(",",$st_photos);


$str = "";
//if($st_photos!="") { $str = "1||".$st_photos."||";}
//else $str = "0|| || Invalid Request";
$str = "1||".$st_photos."||";

echo $str;
?>



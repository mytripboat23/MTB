<?php
include "../includes/connection.php";

$st_photos = "";
$msg = "";

$pos = $_POST['pos'];

$_SESSION['pck_photos'][$pos] = "";

	
$st_photos = implode(",",$_SESSION['pck_photos']);
$st_photos = trim(str_replace(",,",",",$st_photos),",");

$_SESSION['pck_photos'] = explode(",",$st_photos);


$str = "";
//if($st_photos!="") { $str = "1||".$st_photos."||";}
//else $str = "0|| || Invalid Request";
$str = "1||".$st_photos."||";

echo $str;
?>



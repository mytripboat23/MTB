<?php
include("includes/connection.php");
unset($_SESSION['user']);
unset($_SESSION['loginToken']);
unset($_SESSION['user_request_page']);  
unset($_SESSION['userData']); 
unset($_SESSION); 
session_destroy();
if ($_GET['ip_err'] == 1) {
	//header("location:login.php?ip_err=1");
	header("location:".FURL."login.php?ip_err=1");
} else {
	//header("location:login.php");
	header("location:".FURL."login.php");
}
?>
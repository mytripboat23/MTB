<?php
include("../includes/connection.php");
unset($_SESSION[ADMIN_SESSION_NAME]);
unset($_SESSION['admin_request_page']);
//session_destroy();
header("location:index.php");

?>
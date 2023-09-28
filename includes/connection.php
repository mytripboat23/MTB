<?php
ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(1);
/*$ip = getenv('REMOTE_ADDR')?:
getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED');*/
$ip = '';
if (!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
else $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
if ($ip!='43.251.175.214' && $ip!='165.90.106.239') {
	//header("Location:");
	//exit;
}


if (!session_id()) {
	session_start();
}
header("Access-Control-Allow-Origin: *");


$www_not = 0;
if (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.') $www_not = 1;
if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
   $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}
/*
if ($www_not) {
   $redirect1 = 'https://www.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect1);
   exit();
}*/


require_once("configure.php");
require_once("allconfig.php");
require_once(CLASSES."class_db_connect.php");
require_once(CLASSES."class_main_function.php");
require_once(CLASSES."class_gen_function.php");
//require_once(CLASSES."class_formvalidator.php");
require_once(CLASSES."class.phpmailer.php");
require_once(CLASSES."SMTP.php");
require_once(CLASSES."Exception.php");
require_once(CLASSES."class_paging.php");

$obj = new gen_function;

global $obj;
$today = date("Y-m-d");

require_once("functions.php");
require_once("extra_functions.php");

//require_once("thumbCreation.php");
$admin_info=$obj->selectData(TABLE_ADMIN,"admin_email,admin_phone","",1);
$admin_email = $admin_info['admin_email'];
$admin_phone = $admin_info['admin_phone'];
define('ADMIN_EMAIL',$admin_email);
define('ADMIN_PHONE',$admin_phone);
$settings = $obj->selectData(TABLE_SETTINGS,"","set_id='1'",1);
define('FROM_EMAIL',$settings['set_from_email']); 
$curPage = basename($_SERVER['PHP_SELF']);
$pageName = $curPage;
global $curPage;

/////////// filtering inputs//////////////////

//$_POST = $obj->filter_mysql_array($_POST);
//$_GET = $obj->filter_mysql_array($_GET);
//$_REQUEST = $obj->filter_mysql_array($_REQUEST);
//$_COOKIE = $obj->filter_mysql_array($_COOKIE);
//$_SESSION = $obj->filter_mysql_array($_SESSION);
?>
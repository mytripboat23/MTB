<?php
function loadTemplate()
{
	$no_of_args=func_num_args();
	$template_path=TEMPLATE_PATH;
	if($no_of_args==2)
	{
		$template_path=func_get_arg(1);
	}
	$template_name=func_get_arg(0);
	$file_path=$template_path.$template_name;
	//$path_parts = pathinfo($file_path);
	//echo $path_parts["extension"];
	if(file_exists($file_path)&&!is_dir($file_path))
	{
		$content=file_get_contents($file_path);	
	}
	else
	{
		$content="File does not exist";
	}
	return $content;
}

function pre($data)
{
	print"<pre>";
	print_r($data);
	print"</pre>";
}

function showDropdown($arr,$selectedVal="",$type=0)// type=0 for no key, type=1 for use key
{
	if($type==1)
	{
		$optArr=$arr;
	}
	else
	{
		foreach($arr as $key_a=>$val_a)
		{
			$optArr[$val_a]=$val_a;
		}
	}

	$output='';

	if(is_array($optArr))
	{

		foreach($optArr as $key=>$val)
		{
			if($key==$selectedVal)
			{
				$selected=" selected=\"selected\"";
			}
			else
			{
				$selected="";
			}

			$output.='<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
		}

	}
	return $output;
}



function showStatus($selVal='')
{

	$status=array("Active","Inactive");
	$opt=showDropdown($status,$selVal);
	return $opt;
}

function checkUserActive()
{
	global $obj;	 
	$data=$obj->selectData(TABLE_USER_LOGIN,"","u_login_id='".$obj->filter_mysql($_SESSION['user']['u_login_id'])."'",1);
	if($data['u_login_status'] !='Active')
	{		 
		$obj->reDirect("logout.php");
		exit;
	}
}


function loginSecure()
{
	$_SESSION['user_request_path'] = basename($_SERVER['REQUEST_URI']);
	if(!isLogged())
	{	
		exit;
	} 
	  
} 



function userSecure()
{
	global $obj;
	$_SESSION['user_request_path'] = FURL.str_replace(".php","",$_SERVER['REQUEST_URI']);
	if(!isLogged())
	{		
		//$_SESSION['messageClass'] = "errorClass";	
		//$obj->add_message("message","Please login to access the other pages.");
		$obj->reDirect("login.php");
		
		exit;
	}
	checkUserActive();
}



function isLogged()
{
	if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!="")
	{
		return true;
	}
	else
	{
		return false;
	}
}


function getCurrentUser()
{
	return $_SESSION['user']['u_login_id'];
}

function userAuth()
{
	global $obj;
	//$_SESSION['user_request_path']=basename($_SERVER['REQUEST_URI']);
	if(isLogged())
	{		
		if(isset($_SESSION['user_request_path']) && $_SESSION['user_request_path']!='') $obj->reDirect($_SESSION['user_request_path']);	
		else $obj->reDirect("dashboard.php");	
		exit;
	}
}




function animate()

{
	//$content='<META http-equiv="Page-Enter" CONTENT="RevealTrans(Duration=1,Transition=12)">';
	return $content;
}

function str_replace_array($key,$rep,$str)
{

	preg_match_all("/".$key."/",$str,$matches);
	for($i=0;$i<count($matches[0]);$i++)
	{
		$old_str=substr($str,0,strpos($str,$key)+strlen($key));
		$new_str=str_replace($key,$rep[$i],$old_str);
		$str=str_replace($old_str,$new_str,$str);
	}
	return $str;
}


function checkAdminPermission()
{
	global $obj;	 
	$data=$obj->selectData(TABLE_ADMIN,"","admin_id='".$obj->filter_mysql($_SESSION['admin']['admin_id'])."'",1);
	 
	
	//pre($data);
	if($data['admin_type'] == 's')
	{	 
		$curPage = $obj->filter_mysql(basename($_SERVER['PHP_SELF']));		
		$dataP = $obj->selectData(TABLE_ALL_PAGES,"","page_status='Active' and (page_name like '%".$curPage."%')",1);
		$pageId = $dataP['page_id'];		
		$dataxcP = $obj->selectData(TABLE_SUB_ADMIN_PAGES,"","sap_status='Active' and FIND_IN_SET('".$pageId."', page_id) and admin_id='".$obj->filter_mysql($_SESSION[ADMIN_SESSION_NAME]['admin_id'])."'",1);
		
	 	//$sqlAP=$obj->selectData(TABLE_ADMIN_PAGES,"","ap_status='Active' and admin_id='".$_SESSION['admin']['admin_id']."' and ( ap_page1 like '%".$curPage."%' or ap_page2 like '%".$curPage."%' or ap_page3 like '%".$curPage."%' or ap_page4 like '%".$curPage."%')",1);	

		if(empty($dataxcP['sap_id']))
		{
			$_SESSION['messageClass'] = "errorClass";	
			$obj->add_message("message","Access Denied. You don't have permissions to access this page.");
			$obj->reDirect("dashboard.php");		
			exit;
		}		 
	}
}

function adminSecure()
{
	global $obj;
	$_SESSION['admin_request_page']=$_SERVER['REQUEST_URI'];
	if(!isset($_SESSION[ADMIN_SESSION_NAME]))
	{
		$obj->reDirect("index.php");

	}
	ipAdminCheck();
	$curPage = basename($_SERVER['PHP_SELF']);
	if($curPage != 'dashboard.php'){
		checkAdminPermission();
	}
		

}

function ipAdminCheck() {
	global $obj;
	$ip = getenv('REMOTE_ADDR')?:
	getenv('HTTP_CLIENT_IP')?:
	getenv('HTTP_X_FORWARDED_FOR')?:
	getenv('HTTP_X_FORWARDED')?:
	getenv('HTTP_FORWARDED_FOR')?:
	getenv('HTTP_FORWARDED');
	if ($_SESSION[ADMIN_SESSION_NAME]['ip'] != $ip) {
		$_SESSION['messageClass'] = "errorClass";	
		$obj->add_message("ALmessage","Unauthorize access! Please login again.");
		$obj->reDirect("logout.php");		
		exit;
	}
}

function clientSecure()
{
	global $obj;
	$_SESSION['user_request_path']=basename($_SERVER['REQUEST_URI']);
	if(!isset($_SESSION['client']))
	{
		$obj->reDirect("login.php");
	}
}


function getCurrentAdminId()
{
	return $_SESSION[ADMIN_SESSION_NAME]['admin_id'];
}

function runIt($code="")
{
	$code="?>".$code."<?";
	eval($code);
}



function supportExtension($filename,$s_array=array())
{
	if(count($s_array)<=0||(!is_array($s_array)))
	{
		$s_array=array("jpg","jpeg","gif","bmp","flv","swf","png");
	}

	$file_ext=strtolower(end(explode(".",$filename)));
	if(in_array($file_ext,$s_array))
	{
		return true;
	}
	return false;
}



function uploadFile($fieldName,$path="",$name="")
{

	$out['error']=0;
	$out['error_name']="";
	if($name)
	{
		$newname=$name;
	}
	else
	{
		$newname=time().rand(1000,100000000);
	}
	if(!$_FILES[$fieldName]['name'])
	{
		$out['error']=1;
		$out['error_name']="file not uploaded";
	}
	else
	{

		$fileExt=strtolower(end(explode(".",$_FILES[$fieldName]['name'])));
		$newFileName=$newname.".".$fileExt;
		$target=$path.$newFileName;
		if(move_uploaded_file($_FILES[$fieldName]['tmp_name'],$target))
		{
			$out['file_name']=$newFileName;
		}
		else
		{
			$out['error']=1;
			$out['error_name']="error occured while uploading";
		}
	}
	return $out;
}

class fileUpload
{

	var $field_name;
	var $filename;
	var $path;
	var $name;
	var $file_type_supported;
	var $error;
	function upload()
	{
		if(!$_FILES[$this->field_name]['name'])
		{
			$this->error="file not uploaded";
			return false;
		}
		if(is_array($this->file_type_supported))
		{
			if(!supportExtension($_FILES[$this->field_name]['name'],$this->file_type_supported))
			{
				$this->error="file extension not supported";
				return false;
			}
		}
		if($this->name)
		{

			$newname=$this->name;
		}

		else

		{

			$newname=time().rand(1000,100000000);

		}

		$fileExt=strtolower(end(explode(".",$_FILES[$this->field_name]['name'])));
		$newFileName=$newname.".".$fileExt;
		$target=$this->path.$newFileName;
		if(move_uploaded_file($_FILES[$this->field_name]['tmp_name'],$target))
		{
			$this->filename=$newFileName;
			return true;
		}
		else
		{
			$this->error="error occured while uploading";
			return false;
		}

	}

}

function imageURL($image_path,$base_path="")

{

	$url=$image_path;

	if(!file_exists($image_path))

	{

		//$url=pathinfo($image_path,PATHINFO_DIRNAME)."/".NOT_FOUND_IMG;

		//$url=IMAGES.NOT_FOUND_IMG;		

		if(is_dir($image_path))
		{
			$url=$image_path.NOT_FOUND_IMG;
		}
		else
		{
			$url=$base_path.IMAGES.NOT_FOUND_IMG;
		}
	}
	else if(is_dir($image_path))
	{
		$url=$image_path.NOT_FOUND_IMG;
		//$url=IMAGES.NOT_FOUND_IMG;
	}
	return $url;
}

function showThumb($imagePath,$width=100,$height=100,$extra="",$thumbBase="")
{

	$imageExt=strtolower(end(explode(".",$imagePath)));
	if($thumbBase=="../")
	{
		$pathBack="";
	}
	else
	{
		$pathBack="../";
	}

	$path=$thumbBase."includes/thumb/phpThumb.php?src=../".$pathBack.imageURL($imagePath,$thumbBase)."&w=".$width."&h=".$height;

	if($imageExt=="gif")
	{
		$path.="&f=gif";
	}

	$imgset="<img src=\"".$path."\" ".$extra.">";

	return $imgset;

}



function random_float ($min,$max) {

   return ($min+lcg_value()*(abs($max-$min)));

}



class paging

{

	var $res;

	var $rcd_num;

	var $limit;

	var $pageno;

	function paging($table_name,$field,$condition,$limit)

	{

		global $obj;

		$pageno=$_REQUEST['pageno'];

		$rowsUser=$obj->selectData($table_name,'count(*) as c',$condition);

		$countUser=mysqli_fetch_assoc($rowsUser);

		$rcd_num=$countUser['c'];

		if($rcd_num > 0)

		{

			 if(empty($pageno))

			 $pageno=1;		

			 

			 $offset=$limit*($pageno-1);

			  /* page no recalculate */

			 if($pageno>1)

			 {

			 	if(!$obj->selectData($table_name,$field,$condition,1,"","",$offset.",1"))

				{

					if($rcd_num%$limit)

					{

						$last_page=$rcd_num/$limit+1;

					}

					else

					{

						$last_page=$rcd_num/$limit;

					}

					$pageno=(int)$last_page;

					$offset=$limit*($pageno-1);	

				}	

			 }

			 /* eof page no recalculate */

			$rowsUser = $obj->selectData($table_name,$field,$condition,"","","",$offset.",".$limit);

		}

		$this->res=$rowsUser;

		$this->rcd_num=$rcd_num;

		$this->limit=$limit;

		$this->pageno=$pageno;

	}

	function page_list($lnkParam="",$lnkScr="",$yahoopaging=1,$nopageshow="")

	{

		global $obj;

		$obj->pagewise($this->rcd_num,$this->limit,$this->pageno,$lnkParam,$lnkScr,$yahoopaging,$nopageshow);

	}

}



function gotoPage($url)

{

	?>
<script>

	window.location='<?=$url?>';

	</script>
<?php

}



function keyToSearchArray($arr)

{

	foreach($arr as $key=>$val)

	{

		$opt[]="%%".strtoupper($key)."%%";

	}

	return $opt;

}

function isEmail($string)

{

	$email=trim($string);

	if(!preg_match ("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $email))

	{

   		return false;

	}

	return true;	

}



function emailUsed($email)
{
	global $obj;
	$user=$obj->selectData(TABLE_USER,"","user_email='".$obj->filter_mysql($email)."' and user_status!='Deleted'",1);
	return $user;
}



function userExists($username)
{
	global $obj;
	$user=$obj->selectData(TABLE_USER,"","user_name='".$obj->filter_mysql($username)."' and user_status!='Deleted'",1);
	return $user;
}



function getUser($userId)
{
	global $obj;
	$user=$obj->selectData(TABLE_USER,"","user_id='".$obj->filter_mysql($userId)."'",1);
	return $user;
}



function deldir($dir) // Delete the directory
{
  $current_dir = opendir($dir);
  while($entryname = readdir($current_dir)){
     if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
        deldir("${dir}/${entryname}");
     }elseif($entryname != "." and $entryname!=".."){
        unlink("${dir}/${entryname}");
     }
  }

  closedir($current_dir);
  rmdir(${dir});
}



function numberArray($from,$to)
{
	if($from>$to)
	{
		for($i=$from;$i>=$to;$i--)
		{
			$a[$i]=$i;
		}
	}
	else
	{
		for($i=$from;$i<=$to;$i++)
		{
			$a[$i]=$i;
		}
	}
	return $a;
}



function siteMail($to,$to_name,$subject,$message,$from="",$from_name="",$mail_template="email_main.tpl")

{ 

	//$from=$from?$from:SITE_MAIL_ADDRESS;"admin@ssiteam.co.uk"

	$from=$from?$from:"support@myfriendishot.com";

	$from_name=$from_name?$from_name:SITE_NAME;

	

	$mail             = new PHPMailer();

	$search		=array("%%MESSAGE%%","%%FURL%%");

	$replace	=array($message,FURL);

	$body        	  = str_replace($search,$replace,loadTemplate($mail_template));

	

	/*

	$mail->From       = $from;

	$mail->FromName   = $from_name;

	$mail->Subject    = $subject;

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($body);

	if($to_name)

	{

		$mail->AddAddress($to);

	}

	else

	{

		$mail->AddAddress($to,$to_name);

	}

	$mail->Send();*/

	$headers = "MIME-Version: 1.0\r\n"; 

	$headers.= "Content-type: text/html; charset=iso-8859-1\r\n";

	$headers.="From: ".$from_name."<".$from."> .\n";

	$headers.="Reply-To: $from \n";//echo $to;echo "-f" .$from;

	ini_set("sendmail_from",$from);

	//$sent = @mail($to, $subject, $body, $headers, "-f" .$from);

	//echo $body;

	$sent = @mail($to,$subject,$body,$headers);

}

function highlight_this($search,$subject,$openTag,$closeTag)
{
	$callback_function=" \$r='".$openTag."'.\$matches[0].'".$closeTag."'; return \$r;";
	return preg_replace_callback('/'.$search.'/i',create_function('$matches',$callback_function),$subject);
}


function genPassword()
{

	global $obj;

	$passLen=6;

	$passChars=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",

					 "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",

					 0,1,2,3,4,5,6,7,8,9);

	$new_pass="";

	for($i=0;$i<$passLen;$i++)

	{

		$new_pass.=$passChars[array_rand($passChars)];

	}

	return $new_pass;

}



function formatDate($date,$format="")
{
	if($format=="")
	{
		$format="d.m.Y";
	}
	return date($format,strtotime($date));
}



function formatDateTime($time,$format="")
{

	if($format=="")

	{

		$format="d/m/y h:i:s a";

	}

	return date($format,strtotime($time));

}

function showHowMuchAgo($dateTime)

{

	$data_timestamp=strtotime($dateTime);

	$current_timestamp=TIME;

	(int)$time_diff=$current_timestamp-$data_timestamp;

	(int)$minute=60;

	$hour=$minute*60;

	$day=$hour*24;

	$month=$day*30;

	$year=$month*12;

	$no_of_year=(int)($time_diff/$year);

	$no_of_month=(int)($time_diff/$month);

	$no_of_day=(int)($time_diff/$day);

	$no_of_hour=(int)($time_diff/$hour);

	$no_of_minute=(int)($time_diff/$minute);

	

	$out="";

	if($no_of_year)

	{

		$s=$no_of_year>1?"s":"";

		$out=$no_of_year." year".$s;

	}

	else if($no_of_month)

	{

		$s=$no_of_month>1?"s":"";

		$out=$no_of_month." month".$s;

	}

	else if($no_of_day)

	{

		$s=$no_of_day>1?"s":"";

		$out=$no_of_day." day".$s;

	}

	else if($no_of_hour)

	{

		$s=$no_of_hour>1?"s":"";

		$out=$no_of_hour." hour".$s;

	}

	else if($no_of_minute)

	{

		$s=$no_of_minute>1?"s":"";

		$out=$no_of_minute." minute".$s;

	}

	else

	{

		$out="0 minute";

	}

	return $out;

}



function getCountryList()
{
	global $obj;
	$country_array=array();
	$res=$obj->selectData(TABLE_COUNTRY,"countries_id,countries_name","");
	while($data=mysqli_fetch_assoc($res))
	{
		$country_array[$data['countries_id']]=$data['countries_name'];
	}
	return $country_array;
}



function getCountryName($country_id)
{
	global $obj;
	$country_array=array();

	$data=$obj->selectData(TABLE_COUNTRY,"countries_name","countries_id='".$obj->filter_mysql($country_id)."'",1);

	if($data)

	{

		return $data['countries_name'];

	}

}

 

function generateOrderKey(){

	$orderKey1=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

	$key1="";

	for($i=0;$i<2;$i++)

	{

		$key1 .= $orderKey1[array_rand($orderKey1)];

	}

	return $key1.time();

}


function alphaSpace($string){
		return preg_match('/^[a-zA-Z ]*$/', $string) ? 1 : 0;
	}
	

function getOrdinal($number)
	{		 
		$digit = abs($number) % 10;
		$ext = 'th';
		$ext = ((abs($number) %100 < 21 && abs($number) %100 > 4) ? 'th' : (($digit < 4) ? ($digit < 3) ? ($digit < 2) ? ($digit < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
		return $number.$ext;
    }




?>

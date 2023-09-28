<?php

class gen_function extends main_function {
	var $conn;
	function __construct()
	{
		$this->conn = parent::main_function();
		parent::setMysqlKeyword("now()");
	}
	function close_mysql()
	{
		parent::close_mysql_connection();
	}
	function short_description($descr,$noc="")
	{
		$sdescr=strip_tags($descr);
		if(empty($noc))$noc=200;
		if(trim($sdescr)<>'')
		{
			if(strlen($sdescr)>$noc)
				return substr($sdescr, 0, $noc).".....";
			else
				return $sdescr;
		}
		else
		return $sdescr;
	}
	
	function short_description_cw($descr,$noc="100")
	{
		
		if(strlen(strip_tags(html_entity_decode($descr)))>$noc)
		{
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$noc);
			$pos   = strrpos($descr," ");
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$pos).'...';							
		}
		else
		{
			$descr = strip_tags(html_entity_decode($descr));						
		}
		
		return $descr;
	}
	
	function fetch_content($id,$type='full',$char=200)
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='full')
		{
			return html_entity_decode($resCon['content_descr']);
		}
		else
		{
			return $this->short_description(html_entity_decode($resCon['content_descr']),$char);			
		}
	}
	
	function fetch_short_content($id,$type='full',$char=200)
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='full')
		{
			return html_entity_decode($resCon['content_short_desc']);
		}
		else
		{
			return $this->short_description(html_entity_decode($resCon['content_short_desc']),$char);			
		}
	}
	
	function fetch_content_title($id,$type='n')
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='n')
		{
			return $resCon['content_header'];
		}
		else
		{
			$data = explode(" ",$resCon['content_header']);
			$title = '<span>'.$data[0].'</span>';
			for($i=1;$data[$i]!='';$i++)
			{
				$title .= " ".$data[$i];
			}
			return $title;
		}			
	}
	
	function fetch_content_second_title($id,$type='n')
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		if($type=='n')
		{
			return $resCon['content_header2'];
		}
		else
		{
			$data = explode(" ",$resCon['content_header2']);
			$title = '<span>'.$data[0].'</span>';
			for($i=1;$data[$i]!='';$i++)
			{
				$title .= " ".$data[$i];
			}
			return $title;
		}			
	}
	
	function fetch_content_page($id)
	{
		$resCon=parent::selectData(TABLE_CONTENT,"","content_status='Active' and content_id='".$this->filter_mysql($id)."'",1);
		return $resCon['content_page'];
					
	}
	function fetch_content_banner($id){
			$res=parent::selectData(TABLE_CONTENT,"content_banner","content_id='".$this->filter_mysql($id)."'",1);
			$banner=$res['content_banner'];
			return($banner);
	}
	
	function short_descr($descr,$noc="")
	{
		
		if(strlen(strip_tags(html_entity_decode($descr)))>$noc)
		{
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$noc);
			$pos   = strrpos($descr," ");
			$descr = substr(strip_tags(html_entity_decode($descr)),0,$pos).'...';							
		}
		else
		{
			$descr = strip_tags(html_entity_decode($descr));						
		}
		
		return $descr;
	}
	
	function fetch_box_content($fld,$type='n')
	{
		$resBox=parent::selectData(TABLE_SETTINGS,"","set_id='1'",1);
		if($type=='n')
		{
			return html_entity_decode($resBox[$fld]);
		}
		else
		{
			$data = explode(" ",$resBox[$fld]);
			$dataC = '<span>'.$data[0].'</span>';
			for($i=1;$data[$i]!='';$i++)
			{
				$dataC .= " ".$data[$i];
			}
			return $dataC;
		}
	}

	///////////for redirect url////////
	function reDirect($url)
	{
		@header("Location: ".$url);
		exit;
	}
	function go_To($url)
	{
		echo "<script>window.location.href='$url'</script>";
		exit;
	}
	///////////for redirect url////////
	
	
	function filterData($data,$strip=1)
	{
		$fdata=parent::data_prepare($data,0);
		if(!$strip)
			return $fdata;
		return stripslashes($fdata);
	}
	function filterData_array($datagiven)
	{
		foreach ($datagiven as $key=>$value)
		{
			if(is_array($value))
			{
				$data[$key]=$this->filterData_array($value);
			}
			else
			{
				$data[$key]=$this->filterData($value);
			}
		}
		return $data;
	}
	
	function filter_numeric($data)
	{
		$dataF = preg_replace('/[^0-9]/', '',$data);
		return $dataF;
	}
	function filter_alphabet($data)
	{
		$dataF = preg_replace('/[^a-zA-Z]/','',$data);
		return $dataF;
	}
	function filter_alphanum($data)
	{
		$dataF = preg_replace('/[^a-zA-Z0-9]/', '',$data);
		return $dataF;
	}
	function filter_mysql($data)
	{
		$dataF = mysqli_real_escape_string($this->conn,$data);
		return $dataF;
	}
	
	
	function filter_mysql_array($datagiven)
	{
		foreach ($datagiven as $key=>$value)
		{
			if(is_array($value))
			{
				$data[$key]=$this->filter_mysql_array($value);
			}
			else
			{
				$data[$key]=$this->filter_mysql($value);
			}
		}
		return $data;
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
	
	function password_validation($pass)
	{
		$errors = "";
		if (strlen($pass) < 8 || strlen($pass) > 20) {
			return $errors = "Password should be min 8 characters and max 20 characters";
		}
		if (!preg_match("/\d/", $pass)) {
			return $errors = "Password should contain at least one digit";
		}
		if (!preg_match("/[A-Z]/", $pass)) {
			return $errors = "Password should contain at least one Capital Letter";
		}
		if (!preg_match("/[a-z]/", $pass)) {
			return $errors = "Password should contain at least one small Letter";
		}
		if (!preg_match("/\W/", $pass)) {
			return $errors = "Password should contain at least one special character";
		}
		if (preg_match("/\s/", $pass)) {
			return $errors = "Password should not contain any white space";
		}
	}

	function otp_num_validation($otp)
	{
		if(preg_match('/^[0-9]{6}+$/', $otp)) 
		{
			return false;
		} else {
			return true;
		}
	}
	
	function isValidDate($date, $format= 'Y-m-d'){
    	return $date == date($format, strtotime($date));
	}

	
	function selectDate($sel_val,$year,$month)
	{
		switch($month)
		{
			case '1': case '3': case '5': case '7': case '8': case '10': case '12':
				$no_of_days=31;
				break;
			case '4': case '6': case '9': case '11':
				$no_of_days=30;
				break;
			case '2':
				$no_of_days=28;
				if((($month%4==0)&&($month%100!=0))||($month%400==0))
				{
					$no_of_days=29;
				}
				break;
			default:
			echo "Wrong data";
			break;
		}
		for($i=1;$i<=$no_of_days;$i++)
		{
			$options.="<option value='".$i."'";
			if($sel_val==$i){ $options.=" selected"; } $options.=">".$i."</option>"; 
		}
		return $options;
	}
	function selectMonth($sel_val)// used
	{
		//$options="<option value='0'>Select</option>";
		$options.="<option value='1'";
		if($sel_val=='1'){ $options.=" selected"; } $options.=">1</option>"; 
		$options.="<option value='2'";
		if($sel_val=='2'){ $options.=" selected"; } $options.=">2</option>";
		$options.="<option value='3'";
		if($sel_val=='3'){ $options.=" selected"; } $options.=">3</option>";
		$options.="<option value='4'";
		if($sel_val=='4'){ $options.=" selected"; } $options.=">4</option>";
		$options.="<option value='5'";
		if($sel_val=='5'){ $options.=" selected"; } $options.=">5</option>";
		$options.="<option value='6'";
		if($sel_val=='6'){ $options.=" selected"; } $options.=">6</option>";
		$options.="<option value='7'";
		if($sel_val=='7'){ $options.=" selected"; } $options.=">7</option>";
		$options.="<option value='8'";
		if($sel_val=='8'){ $options.=" selected"; } $options.=">8</option>";
		$options.="<option value='9'";
		if($sel_val=='9'){ $options.=" selected"; } $options.=">9</option>";
		$options.="<option value='10'";
		if($sel_val=='10'){ $options.=" selected"; } $options.=">10</option>";
		$options.="<option value='11'";
		if($sel_val=='11'){ $options.=" selected"; } $options.=">11</option>";
		$options.="<option value='12'";
		if($sel_val=='12'){ $options.=" selected"; } $options.=">12</option>";
		return $options;
	}
	function selectYear($sel_val) // used
	{
		$year=date('Y')+50;
		if(!$sel_val)
		{
			$sel_val==date('Y');
		}
		//$options="<option value='0'>Select</option>";
		for($i=0;$i<100;$i++)
		{
			$options.="<option value=".$year;
			if($year==$sel_val){  $options.=" selected"; } 
			$options.=">".$year."</option>";
			$year--;
			
		}
		return $options;
	}
	
	///////////////////////
	
	function mailBody($bodypart)
	{
		$data='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>'.SITE_TITLE.'</title>
</head>
<body>
<table width="600" border="0" cellspacing="1" cellpadding="3" align="center" style="border:1px solid #d6d6d6; font:normal 12px/16px Arial, Helvetica, sans-serif; color:#818181;">
  <tr>
    <td align="left" valign="top" style="height:50px; border-bottom:3px solid #eeefef;background-color:#f9f9f9; text-align: center"><img width="400" src="'.FURL.'img/logo.png"/>
	</td>
  </tr>
  <tr>
    <td align="left" valign="top" style="padding:10px 20px 0px 20px; color:#4b4b4b;"><table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td align="left" valign="top">'.$bodypart.'</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="middle" style="height:50px; background-color:#4F595B; color:#fff">Copyright &copy;'.date("Y").' '.SITE_TITLE.', All Rights Reserved.</td>
  </tr>
</table>
</body>
</html>';
		return $data;
	}
	
	
	function sendMail_server($to="", $subject="", $body="",$from="",$fromname="",$type="",$replyto="",$bcc="",$cc="")
	{
		if(empty($type))
		{
			$type="html";
		}
		if($type=="plain")
		{
			$body = strip_tags($body);
		}
		if($type=="html")
		{
			$body = "<font face='Verdana, Arial, Helvetica, sans-serif'>".$body."</font>";
		}
		/* To send HTML mail*/ 
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers.= "Content-type: text/".$type."; charset=iso-8859-1\r\n";
		/* additional headers */ 
		//$headers .= "To: <".$to.">\r\n"; 
		if(!empty($from))
		{
			$headers .= "From: ".$fromname." <".$from.">\r\n";
		}
		
		if(!empty($replyto))
		{
			$headers .= "Reply-To: <".$replyto.">\r\n"; 
		}
		if(!empty($cc))
		{
			$headers .= "Cc: ".$cc."\r\n";
		}
		if(!empty($bcc))
		{
			$headers .= "Bcc: ".$bcc."\r\n";
		}
		if(@mail($to, $subject, $body, $headers))
		{
			return 1;
		}
		else
		{
			return $headers;
		}
	}
	
	
	function sendMail($to="", $subject="", $body="",$from="",$fromname="",$type="",$replyto="",$bcc="",$cc="")
	{
		if(empty($type))
		{
			$type="html";
		}
		if($type=="plain")
		{
			$body = strip_tags($body);
		}
		if($type=="html")
		{
			$body = "<font face='Verdana, Arial, Helvetica, sans-serif'>".$body."</font>";
		}
		
		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465; 
		
		$mail->IsHTML(true); 
		
		$mail->Username = GUSER;  
		$mail->Password = GPWD;           
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		//$mail->Send();
		if(!$mail->Send()) 
		{
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return $error;
		} else {
			$error = 'Message sent!';
			return $error;
		}
		
		
		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);
		
		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = GUSER;                     // SMTP username
			$mail->Password   = GUSER;                               // SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
			$mail->Port       = 587;                                    // TCP port to connect to
		
					
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Here is the subject';
			$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
				
			}
	
	
	
	function sendMailSES($to="", $subject="", $body="",$from="",$fromname="",$type="",$replyto="",$bcc="",$cc="")
	{
		if(empty($type))
		{
			$type="html";
		}
		if($type=="plain")
		{
			$body = strip_tags($body);
		}
		if($type=="html")
		{
			$body = "<font face='Verdana, Arial, Helvetica, sans-serif'>".$body."</font>";
		}
		
		global $error;
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		//$mail->Host = 'email-smtp.us-west-2.amazonaws.com';
		//$mail->Port = 465; 
		$mail->Host = 'smtp.mailgun.org';
		$mail->Port = 465; 
		
		$mail->IsHTML(true); 
		
		//$mail->Username = 'AKIAIVHXVBQNSMZFG5JA';  
		//$mail->Password = 'ArIRjbKOWScDqxztgyqp2aINnIhTeb4iqSsg8R+HoDXD';
		//$mail->Username = 'postmaster@mail.hashingadspace.com';  
		$mail->Password = '404a33cefe529d324f47c624c8de0c58-ef80054a-df27a4ba';           
		$mail->SetFrom($from, $from_name);
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);
		if(!empty($bcc))
		{
			$mail->addBcc($bcc);
		}
		//$mail->Send();
		if(!$mail->Send()) {
			$error = 'Mail error: '.$mail->ErrorInfo; 
			return $error;
		} else {
			$error = 'Message sent!';
			return $error;
		}
		
	}


//////////////////////FILE UPLOAD/////////////////////////////
function uploadFile($file_id, $folder="", $types="",$rename="") {

	$file_title = $_FILES[$file_id]['name'];
	$file_tmp = $_FILES[$file_id]['tmp_name'];
	
    if(!$file_title) return array('','No file specified');
    //Get file extension
    $ext_arr = explode(".",basename($file_title));
    $ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
	
   if(!empty($types))
   { 
     $all_types = explode(",",strtolower($types));
		if($types) 
		{
			if(in_array($ext,$all_types));
			else {
				$result = "'".$file_title."' is not a valid file."; //Show error if any.
				return array('',$result);
			}
		}
	}

    //Not really uniqe - but for all practical reasons, it is
	if(!empty($rename))
	{
	 	$file_name=$rename.'.'.$ext; 
	}
	else
	{
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . date('YmdHis').'.'.$ext;//Get Unique Name
	}
    //Where the file must be uploaded to
    if($folder) $folder .= '/';//Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    $result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($file_tmp, $uploadfile)) {
        $result = "Cannot upload the file '".$file_title."'"; //Show error if any.
        if(!file_exists($folder)) {
            $result .= " : Folder don't exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } 
        $file_name = '';
        
    } else {
        if(!$_FILES[$file_id]['size']) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = "Empty file found - please use a valid file."; //Show the error message
        } else {
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }

    return array($file_name,$result);
}

function uploadMultyFile($name,$tmp_name,$size, $folder="", $types="",$rename="") {

	$file_title = $name;
	$file_tmp = $tmp_name;
	
    if(!$file_title) return array('','No file specified');
    //Get file extension
    $ext_arr = split("\.",basename($file_title));
    $ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension
	
   if(!empty($types))
   { 
     $all_types = explode(",",strtolower($types));
		if($types) 
		{
			if(in_array($ext,$all_types));
			else {
				$result = "'".$file_title."' is not a valid file."; //Show error if any.
				return array('',$result);
			}
		}
	}

    //Not really uniqe - but for all practical reasons, it is
	if(!empty($rename))
	{
	 	$file_name=$rename.'.'.$ext; 
	}
	else
	{
		$uniqer = substr(md5(uniqid(rand(),1)),0,5);
		$file_name = $uniqer . '_' . date('YmdHis').'.'.$ext;//Get Unique Name
	}
    //Where the file must be uploaded to
    if($folder) $folder .= '/';//Add a '/' at the end of the folder
    $uploadfile = $folder . $file_name;

    $result = '';
    //Move the file from the stored location to the new location
    if (!move_uploaded_file($file_tmp, $uploadfile)) {
        $result = "Cannot upload the file '".$file_title."'"; //Show error if any.
        if(!file_exists($folder)) {
            $result .= " : Folder don't exist.";
        } elseif(!is_writable($folder)) {
            $result .= " : Folder not writable.";
        } 
        $file_name = '';
        
    } else {
        if(!$size) { //Check if the file is made
            @unlink($uploadfile);//Delete the Empty file
            $file_name = '';
            $result = "Empty file found - please use a valid file."; //Show the error message
        } else {
            chmod($uploadfile,0777);//Make it universally writable.
        }
    }

    return array($file_name,$result);
}
//////////////////////FILE UPLOAD/////////////////////////////

	
function get_page_name($path='')
{
	$page_path = ($path != "") ? $path : $_SERVER['HTTP_REFERER']; 
	$url_parts = parse_url($page_path);
	$tmp_path = explode("/",$url_parts['path']); //pre($tmp_path);
	$page_name = array_pop($tmp_path);
	$page_name = !empty($page_name) ? $page_name : "index.php";
	$page_name .= ($url_parts['query'] != "") ? "?".$url_parts['query'] : "";
	$page_name .= ($url_parts['fragment'] != "") ? "#".$url_parts['fragment'] : "";
	return $page_name;
}
 

function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}

	function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	
	function randCode($limit)
	{
		$rand=rand();
		$rand1=md5($rand);
		$pass = substr($rand1, 0, $limit);
		return $pass;
	}
	
	function encryptPass($strPass){
		$strPass=trim($strPass);
		$basePass=base64_encode($strPass);
		$revPass=strrev($basePass);
		//echo $oriPass=base64_decode(strrev($revPass));
		$first4=$this->randCode(4);
		$last4=$this->randCode(4);
		$enc_revPass=$first4.$revPass.$last4;
		return $enc_revPass;
	}
	function retrievePass($enc_revPass){
		$pass=substr($enc_revPass,4);
		$last4=substr($pass,-4,4);
		$pass1=str_replace($last4,"",$pass);
		$revPass=strrev($pass1);
		$oriPass=base64_decode($revPass);
		return $oriPass;
		//echo "<br>".$oriPass;
	}
	
	

	
	function getUserSelected($selval)
	{	
		$res=parent::selectData(TABLE_USER,"","user_id<>0 and user_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['u_login_id']."'";
			if($row['u_login_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['user_full_name']."</option>";
		}
		return $str;
		
	}
	
	
	function languageSelect($selval)
	{	
		$selvals = explode(",",trim($selval,","));
		$res=parent::selectData(TABLE_LANGUAGE,"","lang_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['lang_id']."'";
			if(in_array($row['lang_id'],$selvals,true))
			{
				$str.=' selected';
			}
			$str.=">".$row['lang_title']."</option>";
		}
		return $str;
	}
	
	function hobbySelect($selval)
	{	
		$selvals = explode(",",trim($selval,","));
		$res=parent::selectData(TABLE_HOBBIES,"","hobby_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['hobby_id']."'";
			if(in_array($row['hobby_id'],$selvals,true))
			{
				$str.=' selected';
			}
			$str.=">".$row['hobby_title']."</option>";
		}
		return $str;
	}
	
	function tagsSelect($selval)
	{	
		$selvals = explode(",",trim($selval,","));
		$res=parent::selectData(TABLE_TAGS,"","tag_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['tag_title']."'";
			if(in_array($row['tag_title'],$selvals,true))
			{
				$str.=' selected';
			}
			$str.=">".$row['tag_title']."</option>";
		}
		return $str;
	}
	  
	 
	function build_Nlevel_tree_dropdown($tableAttr,$selval=0,$oldID=0,$depth=0,$ids=0,$opttag=0)
	{
		if(!is_array($tableAttr))
		{
			return false;
		}
		else
		{
			$table=$tableAttr[0];
			if(defined($table))
				$table=constant($table);
			else
				$table=$table;
			$parent_id=$tableAttr[1];
			$child_id=$tableAttr[2];
			$child_value=$tableAttr[3];
			$cond=$tableAttr[4];
		}
		$exclude=array();
		
		$child_query = parent::selectData($table,"",$parent_id."=".$oldID." ".$cond);
		while ( $child = mysqli_fetch_array($child_query) )
		{
			if($child[$child_id]!=$child[$parent_id])
			{
				$space ="";
				if($depth>$this->dep_lavel)
				{
					$this->dep_lavel=$depth;
				}
				for ( $c=0;$c<$depth;$c++ )
				{ 
					$space.= "--"; 
				}
				
				$selected="";
				if($selval==$child[$child_id]) $selected='selected';
				if(!$ids){
					if($child[$parent_id]==0 && $opttag==1){
						$tempTree.= "<optgroup label='".$child[$child_value]."'>";
						}else{
						$tempTree.= "<option value='".$child[$child_id]."' ".$selected.">".$space.$child[$child_value] . "</option>";
						}
					}else{
					$tempTree.= ",".$child[$child_id];
					}
				$depth++; 
				$tempTree.= $this->build_Nlevel_tree_dropdown($tableAttr,$selval,$child[$child_id],$depth,$ids,$opttag); 
				if($child[$parent_id]==0 && $opttag==1){
				$tempTree.="</optgroup>";
				}
				$depth--; 
				array_push($exclude, $child[$child_id]);
			}
		}
		return $tempTree;
	}
 
	function putMetaTags()
	{
		//$res=parent::selectData(TABLE_CONTENT,"","content_id='".$id."'",1);
		$settings = parent::selectData(TABLE_SETTINGS,"","set_id='1'",1);
		
		$meta_title=$settings['set_meta_title'];
		$meta_description=$settings['set_meta_description'];
		$meta_keywords=$settings['set_meta_keywords'];
		$meta_summary=$settings['set_meta_summary'];
		$meta_author=$settings['set_meta_author'];
		
		
		$meta_content .= '<title>'.$meta_title.'</title>';
		$meta_content  .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';	
		$meta_content .= '<meta name="DESCRIPTION" content="'.$meta_description.'" />';
		$meta_content .= '<meta name="summary" content="'.$meta_summary.'" />';
		$meta_content .= '<meta name="Author" content="'.$meta_author.'">';
		$meta_content .= '<meta name="KEYWORDS" content="'.$meta_keywords.'" />';
		return $meta_content;
	}


	function putContent($id)
	{
		$res=parent::selectData(TABLE_CONTENT,"content_descr","content_id='".$this->filter_mysql($id)."'",1);
		$content=$res['content_descr'];
		return html_entity_decode($content);
	}
	
	function putContentHeader($id){
			$res=parent::selectData(TABLE_CONTENT,"content_header","content_id='".$this->filter_mysql($id)."'",1);
			$header=$res['content_header'];
			return($header);
	}
	
	function putContentTitle($id){
			$res=parent::selectData(TABLE_CONTENT,"content_title","content_id='".$this->filter_mysql($id)."'",1);
			$header=$res['content_title'];
			return($header);
	}
	
		
	function putPageSmallDesc($id)
	{
		$res=parent::selectData(TABLE_CONTENT,"content_small_descr","content_id='".$this->filter_mysql($id)."'",1);
		$smalldesc = nl2br(html_entity_decode($res['content_small_descr']));
		return ($smalldesc);
	}
	
	
	
	function putDefaultMetaTags($id=1)
	{
	
		$res=parent::selectData(TABLE_DEFAULT_META,"","def_meta_id='".$this->filter_mysql($id)."'",1);
		$meta_title=$res['def_meta_title'];
		$meta_description=$res['def_meta_description'];
		$meta_keywords=$res['def_meta_keywords'];
		$meta_summary=$res['def_meta_summary'];
		$meta_author=$res['def_meta_author'];
		
		$meta_content  = '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />';	
		$meta_content .= '<meta name="DESCRIPTION" content="'.$meta_description.'" />';
		$meta_content .= '<meta name="summary" content="'.$meta_summary.'" />';
		$meta_content .= '<meta name="Author" content="'.$meta_author.'">';
		$meta_content .= '<meta name="KEYWORDS" content="'.$meta_keywords.'" />';
		$meta_content .= '<title>'.$meta_title.'</title>';
		return $meta_content;
	}
	
	

	function getOnlyText($content,$length=100){
		 return(substr(strip_tags(preg_replace("/<.*?>/", "",html_entity_decode($content))),0,$length)."");
	}

	function ShowMessageFront($message_var='message',$message_class='MsgClass',$unset=1){
		if($_SESSION[$message_var]){
			echo '<label class="'.$_SESSION[$message_class].'">'.$_SESSION[$message_var].'</label>';
			if($unset == 1){
			$_SESSION[$message_var]=""; 
			$_SESSION[$message_class]="";
			}
		}
	}

	
	function nLevelPageSelected($selected_id,$current_id,$parent_id=0,$space=""){
	
		$res = parent::selectData(TABLE_CONTENT,"","content_status<>'Deleted' and `parent_id`='".$this->filter_mysql($parent_id)."'");
		while($row = mysqli_fetch_array($res)){ 
			if($current_id != $row['content_id']){
		?>
			<option value="<?= $row['content_id'];?>" <?= $selected_id==$row['content_id']?'selected="selected"':'';?>><?= $space.$row['content_title'];?></option>
		<?php } 
		
			$this->nLevelPageSelected($selected_id,$current_id,$row['content_id'],$space .= "--");
			$len = strlen($space);
			$space = substr($space,0,$len-2);
		}
		
	
	}

	
	function get_user_full_name($user_id)
	{
		$user_id  = $this->filter_mysql($this->filter_numeric($user_id));
		if($user_id == 0){
			return('Admin');
		}
		$res = parent::selectData(TABLE_USER,""," u_login_id='".$user_id."'",1);
		return ($res['user_full_name']);
	}
	
	
	
	function getPackageTitle($packId){
		$res = parent::selectData(TABLE_PACKAGE,"pack_title","pack_status='Active' and pack_id='".$this->filter_mysql($packId)."'",1);
		return($res['pack_title']);
	}
	
	function selectProduct($proId)
	{
		$lists=parent::selectData(TABLE_PRODUCT,"","pro_status='A'");
		$ret = '';
		while($res = mysqli_fetch_array($lists))
		{
			$ret .= '<option value="'.$res['pro_id'].'" '.($res['pro_id'] == $proId?'selected="selected"':'').'>'.$res['pro_title'].'</option>';
		}
		return($ret);
	}
	
	function getUserAvatar($user_id)
	{
		$res = parent::selectData(TABLE_USER,"user_avatar","user_status='Active' and u_login_id='".$this->filter_mysql($user_id)."'",1);
		return($res['user_avatar']==""?"no_avatar.png":$res['user_avatar']);
	}

	function getImageThumb($fol=IMAGES,$pic='',$title='',$class='',$width='50',$height='50',$path='')
	{
		if(is_file($path.$fol.$pic))
		{
			$img_string = '<img src="'.$path.'thumb/phpThumb.php?src=../'.$fol.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		else
		{
			//$fol=IMAGES;
			$pic=NOT_FOUND_IMG;
			$img_string = '<img src="'.$path.'thumb/phpThumb.php?src=../'.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		return $img_string;
	}
	
	function getImageThumbUrl($fol=IMAGES,$pic='',$title='',$class='',$width='50',$height='50',$path='')
	{
		if(is_file($path.$fol.$pic))
		{
			$img_string = '<img src="'.FURL.$path.'thumb/phpThumb.php?src=../'.$fol.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		else
		{
			//$fol=IMAGES;
			$pic=NOT_FOUND_IMG;
			$img_string = '<img src="'.FURL.$path.'thumb/phpThumb.php?src=../'.$pic.'&amp;hp='.$height.'&amp;wl='.$width.'" alt="'.$title.'" class="'.$class.'" border="0">';
		}
		return $img_string;
	}
	
	
	function getPageName($content_id){
	$res = parent::selectData(TABLE_CONTENT,"content_title","content_id='".$this->filter_mysql($content_id)."' and content_status='Active'",1);
	return $res['content_title'];
	}

	function check_email_address($email) 
	{
		  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
			return false;
		  }
		
		  $email_array = explode("@", $email);
		  $local_array = explode(".", $email_array[0]);
		  for ($i = 0; $i < sizeof($local_array); $i++) 
		  {
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i]))
			{
			  return false;
			}
  }

  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) 
	{
        return false; 
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) 
	{
      if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i])) 
	  {
        return false;
      }
    }
  }
  return true;
}

function getUserAddress($user_id){
	$res = parent::selectData(TABLE_USER,"","u_login_id='".$this->filter_mysql($user_id)."' and user_status='Active'",1);
	$ret = 'Address: '.$res['user_address'];
	$ret .= '<br>';
	$ret .= '<strong>State :</strong> '.$res['user_state']."   ";
	$ret .= '<strong>City :</strong> '.$res['user_city'];
	$ret .= '<br>';
	$ret .= '<strong>Zip Code:</strong> '.$res['user_zip'];
	return $ret;
}


function add_message($msgvar,$message)
	{
		$_SESSION[$msgvar] .= $message."<br>";
	}
	
	function get_message($msgvar)
	{
		return $_SESSION[$msgvar];
	}
	
	function remove_message($msgvar)
	{
		$_SESSION[$msgvar] = "";
		$_SESSION['messageClass'] = "";		
		
	}
	function display_message($msgvar)
	{
		$message = '';
		if($this->get_message($msgvar))
		{
			if($_SESSION['messageClass']=='successClass')
			{
			$message = '<div class="alert alert-block alert-success">
              <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              <i class="ace-icon fa fa-check green"></i> '.$this->get_message($msgvar).'</div>';
			}
			elseif($_SESSION['messageClass']=='infoClass')
			{
			$message = '<div class="alert alert-block alert-info">
              <i class="fa fa-comment"></i> <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              '.$this->get_message($msgvar).'</div>';
			}
			else
			{
				$message = '<div class="alert alert-block alert-danger">
              <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              '.$this->get_message($msgvar).'</div>';
			}
		}
		$this->remove_message($msgvar);
		return $message;
	}
	
	function getFieldValueByFieldName($table,$field_get,$field_name,$field_val){
		$data=parent::selectData($table,$field_get,"$field_name='".$this->filter_mysql($field_val)."'",1);
		return($data[$field_get]);
	}
	





function dateFormat($date){
	return(date('jS M, Y',strtotime($date)));
}
function dateFormatDb($date){
	$dt = explode("/",$date);
	$newDate = $dt[2]."-".$dt[0]."-".$dt[1];
	return $newDate;
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
	
	function isURL($url)
	{ 
		if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
			return true;	
		} else {
			return false;
		}
	}


	function getUserEmailFromAll($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$resS=parent::selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."'",1);
		return $resS['u_login_user_email'];	 
	}
	
	
	function getUserEmail($userId)
	{
		$userId  = $this->filter_mysql($this->filter_numeric($userId));
		$resS=parent::selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."' and u_login_status='Active'",1);
		return $resS['u_login_user_email'];	 
	}
	
	
	function timestampdiff($qw,$saw)
	{
	 	$datetime1 = new DateTime($qw);
		$datetime2 = new DateTime($saw);
		$interval = $datetime1->diff($datetime2);
		return $interval->format('%i');
	}
	
	function subAdminSelect($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));

		$str.= "<option value=''>--Select Sub Admin--</option>";
		$res=parent::selectData(TABLE_ADMIN,"","admin_type='s' and admin_status='Active'","");
		while($row=mysqli_fetch_array($res))
		{
			$str.="<option value='".$row['admin_id']."'";
			if($row['admin_id']==$selval)
			{
				$str.=' selected';
			}
			$str.=">".$row['admin_name']."</option>";
		}
		return $str;
		
	}
	
	function getAdminName($admin_id)
	{
		$admin_id  = $this->filter_mysql($this->filter_numeric($admin_id));

		$res = parent::selectData(TABLE_ADMIN,"","admin_status='Active' and admin_id='".$admin_id."'",1);
		return($res['admin_name']);
	}
	

	
	function getAllPages($page_id)
	{	
		
		$resww = parent::selectData(TABLE_ALL_PAGES,"","page_id<>0 and page_status='Active'","","page_id");
	 
		$pageId = explode(",",$page_id);
		while($row=mysqli_fetch_array($resww))
		{
			$str.="<div class='col-xs-10 col-sm-4'><input type='checkbox' name='page_id[]' id='page_id' value='".$row['page_id']."'";
			if(in_array($row['page_id'],$pageId))
			{
				$str.=' checked';
			}
			$str.="> ".$row['page_title']."</div>";
		}
		return $str;
		
	}
	
	
	function getAllPagesName($page_id)
	{	
		$resww = parent::selectData(TABLE_ALL_PAGES,"","page_id in (".$this->filter_mysql($page_id).") and page_status='Active'","");
		while($row=mysqli_fetch_array($resww))
		{
			$str .= $row['page_title']." | ";
			//$str.="<span>".$row['page_title']."</span>";
		}
		return $str;
		 
	}


	function get_date_time_difference($end)
	{
		$start = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d")+5,date("Y")));
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );
				//return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
				
				return "".$days." days ".$hours." hrs ".$minutes." min ".$diff." sec";
				
			}
			else
			{
				return "Ending date/time is earlier than the start date/time";
			}
		}
		else
		{
			return "Invalid date/time data detected";
		}
	}

	function get_date_time_difference_st($end)
		{
			$start = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d")+4,date("Y")));
			$uts['start']      =    strtotime( $start );
			$uts['end']        =    strtotime( $end );
			if( $uts['start']!==-1 && $uts['end']!==-1 )
			{
				if( $uts['end'] >= $uts['start'] )
				{
					$diff    =    $uts['end'] - $uts['start'];
					if( $days=intval((floor($diff/86400))) )
						$diff = $diff % 86400;
					if( $hours=intval((floor($diff/3600))) )
						$diff = $diff % 3600;
					if( $minutes=intval((floor($diff/60))) )
						$diff = $diff % 60;
					$diff    =    intval( $diff );
					//return( array('years'=>$years,'months'=>$months,'days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
					
					return "".$days." days ".$hours." hrs ".$minutes." min ".$diff." sec";
					
				}
				else
				{
					return "Ending date/time is earlier than the start date/time";
				}
			}
			else
			{
				return "Invalid date/time data detected";
			}
		}

	
	function get_gmail_user_cleartext($email_id)
	{
		$email_filter = $this->filter_mysql($email_id);
		
		$email = isset($email_filter) ? strtolower(trim($email_filter))  : '';
		list ($emailuser, $emaildomain) = array_pad(explode("@", $email, 2), 2, null);
		list ($emailuser, $emailidentifier) = array_pad(explode("+", $emailuser, 2), 2, null);
		$gmail_user_cleartext = str_replace(".", "", $emailuser);
		
		return $gmail_user_cleartext;
	}
	
	
	function getcleantext($string) 
	{	 
		$string = preg_replace('/\s+/', '-', $string); // 1
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // 2
		$string = trim($string, "-"); // 3
		$string = strtolower($string); // 4
		$stringr = preg_replace('/--+/', '-', $string); // 5
		return $stringr;	 
		
	}

	
	function checkPagePermission($page_name)
	{ 
		$dataP = parent::selectData(TABLE_ALL_PAGES,"","page_status='Active' and (page_name like '%".$this->filter_mysql($page_name)."%')",1);
		$pageId = $dataP['page_id'];
		
		$dataxcP = parent::selectData(TABLE_SUB_ADMIN_PAGES,"","sap_status='Active' and admin_id='".$_SESSION['admin']['admin_id']."' and FIND_IN_SET('".$pageId."', page_id)",1);
		if($_SESSION['admin']['admin_type'] == 's')
		{	
			if(empty($dataxcP['sap_id'])) {
				return 0;
			} else {
				return 1;
			}	
		} 
		else
		{
			return 1;
		}
		
		
		/*
			$data = parent::selectData(TABLE_ADMIN_PAGES,"","ap_status='Active' and admin_id='".$_SESSION['admin']['admin_id']."' and ( ap_page1 like '%".$page_name."%' or ap_page2 like '%".$page_name."%' or ap_page3 like '%".$page_name."%' or ap_page4 like '%".$page_name."%')",1);
		 
			if($_SESSION['admin']['admin_type'] == 's')
			{	
				if(empty($data['ap_id'])) {
					return 0;
				} else {
					return 1;
				}	
			} 
			else
			{
				return 1;
			}
		
		*/
	
				
	}
	
	function get_pack_title($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_PACKAGE,"","pck_status='Active' and pck_id='".$selval."'",1);
		return $resS['pck_title'];
	}
		
	function get_package_selected($cId)
	{
		$cId  = $this->filter_mysql($this->filter_numeric($cId));
		$lists=parent::selectData(TABLE_PACKAGE,"","pak_status='Active'","");	
		
		while($res = mysqli_fetch_array($lists))
		{
			$ret  .= '<option value="'.$res['pck_id'].'" '.($res['pck_id'] == $cId?'selected="selected"':'').'>'.$res['pck_title'].'</option>';
		}
		return($ret);
	}
	
	function get_story_title($selval)
	{
		$selval  = $this->filter_mysql($this->filter_numeric($selval));
		$resS=parent::selectData(TABLE_STORY,"","ts_status='Active' and ts_id='".$selval."'",1);
		return $resS['ts_title'];
	}
	
	
	function get_total_package_comments($pId)
	{
		$pId  = $this->filter_mysql($this->filter_numeric($pId));
		
		$sqlPCC = parent::selectData(TABLE_PACKAGE_COMMENT,"count(pc_id) as totalPCC","pck_id = '".$uId."' and pc_status<>'Deleted'",1);
		if($sqlPCC['totalPCC']) return $sqlPCC['totalPCC'];
		else return 0;
	}
	
	function get_total_story_comments($pId)
	{
		$pId  = $this->filter_mysql($this->filter_numeric($pId));
		
		$sqlSCC = parent::selectData(TABLE_STORY_COMMENT,"count(tsc_id) as totalSCC","ts_id = '".$pId."' and tsc_status<>'Deleted'",1);
		if($sqlSCC['totalSCC']) return $sqlPCC['totalSCC'];
		else return 0;
	}
	
	
	
	/*function get_total_likes()
	{	
		$resTL=parent::selectData(TABLE_PACKAGE_LIKE,"count(pl_id) as totalL","pl_status='Active'",1);
		return $resTL['totalL'];
	}*/
	
	function get_total_reviews()
	{	
		$resTR=parent::selectData(TABLE_PACKAGE_REVIEW,"count(pr_id) as totalR","pr_status='Active'",1);
		return $resTR['totalR'];
	}
	
	function get_top_liked_packages($count=8)
	{	
		$top_rated_pck_ids = "";
		$res=parent::selectData(TABLE_PACKAGE_LIKE." as pl inner join ".TABLE_PACKAGE." as pk on pl.pck_id=pk.pck_id","pl.pck_id,count(pl.pl_id) as totalL","pl.pl_status='Active' and pk.pck_status='Active' and (pk.pck_start_dt > '".date("Y-m-d")."' or ( pk.pck_month >= '".date("n")."' and pk.pck_year = '".date("Y")."') or ( pk.pck_year > '".date("Y")."')) ","","totalL desc","pck_id","0,".$count);
		while($row=mysqli_fetch_array($res))
		{
			$top_rated_pck_ids .= $row['pck_id'].",";
		}
		if(rtrim($top_rated_pck_ids,",")!="") return rtrim($top_rated_pck_ids,",");	
		else return "0";	
	}
	
	function get_top_rated_packages($count=8)
	{	
		$top_rated_pck_ids = "";
	   $res=parent::selectData(TABLE_PACKAGE_REVIEW." as pr inner join ".TABLE_PACKAGE." as pk on pr.pck_id=pk.pck_id","pk.pck_id,count(pr.pr_id) as totalR, sum(pr.pr_rate) as sumR, sum(pr.pr_rate)/count(pr.pr_id) as avg","pr.pr_status='Active' and pk.pck_status='Active'","","avg desc","pk.pck_id","0,".$count);
		while($row=mysqli_fetch_array($res))
		{
			$top_rated_pck_ids .= $row['pck_id'].",";
		}
		if(rtrim($top_rated_pck_ids,",")!="") return rtrim($top_rated_pck_ids,",");	
		else return "0";	
	}
	
	function get_top_tours($count=8)
	{	
		 $top_tours = "";
		 $cnt = 0;
		 $sqlP = parent::selectData(TABLE_PACKAGE." p, ".TABLE_USER_LOGIN." ul","","p.tour_top = 'y' and ul.u_suspend_status='n' and p.pck_status='Active' and ul.u_login_id=p.user_id","","","","0,$count");
		 while($resP = mysqli_fetch_array($sqlP))
		 {
		 	$top_tours .= $resP['pck_id'].",";
			$cnt++;
		 }
			$top_tours = rtrim($top_tours,",");
			if($top_tours == "") $top_tours = "0";
			
		$top_rated_pck_ids = "";
	   $res=parent::selectData(TABLE_PACKAGE_REVIEW." as pr inner join ".TABLE_PACKAGE." as pk on pr.pck_id=pk.pck_id","pk.pck_id,count(pr.pr_id) as totalR, sum(pr.pr_rate) as sumR, sum(pr.pr_rate)/count(pr.pr_id) as avg","pr.pr_status='Active' and pk.pck_status='Active' and pk.pck_id not in (".$top_tours.")","","avg desc","pk.pck_id","0,".$count-$cnt);
		while($row=mysqli_fetch_array($res))
		{
			$top_rated_pck_ids .= $row['pck_id'].",";
		}
		$top_rated_pck_ids = rtrim($top_rated_pck_ids,",");
		$top_tours = $top_tours.",".$top_rated_pck_ids;
		if(rtrim($top_tours,",")!="") return rtrim($top_tours,",");	
		else return "0";	
	}
	
	function get_package_rating($pckId)
	{	
		$pck_rating = 0;
	    $res=parent::selectData(TABLE_PACKAGE_REVIEW,"pck_id,count(pr_id) as totalR, sum(pr_rate) as sumR, sum(pr_rate)/count(pr_id) as avg","pr_status='Active' and pck_id='".$pckId."'",1,"avg desc","pck_id");
		if(isset($res['avg']) && $res['avg']!="") $pck_rating = $res['avg'];
		
		return $pck_rating;	

	}
	
	function get_max_posted_user($count=3)
	{	
	   $max_posted_user_ids = "";
	   $res=parent::selectData(TABLE_PACKAGE,"pck_id,user_id,count(user_id) as totalUP","pck_status='Active' and user_id>0","","totalUP desc","user_id","0,".$count);
		while($row=mysqli_fetch_array($res))
		{
			$max_posted_user_ids .= $row['user_id'].",";
		}
		if(rtrim($max_posted_user_ids,",")!="") return rtrim($max_posted_user_ids,",");	
		else return "0";	
	}
	
	function get_top_agents($count=3)
	{	
		
		 $top_agents = "";
		 $cnt = 0;
		 $sqlP = parent::selectData(TABLE_USER." u, ".TABLE_USER_LOGIN." ul","","u.user_top = 'y' and u.user_status='Active' and ul.u_login_status='Active' and ul.u_suspend_status='n' and u.u_login_id=ul.u_login_id");
		 while($resP = mysqli_fetch_array($sqlP))
		 {
		 	$top_agents .= $resP['u_login_id'].",";
			$cnt++;
		 }
		$top_agents = rtrim($top_agents,",");
		if($top_agents == "") $top_agents = "0";
	    $max_posted_user_ids = "";
		if($count>$cnt)
		{
	    $res=parent::selectData(TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." as ul","p.pck_id,p.user_id,count(p.user_id) as totalUP","p.pck_status='Active' and p.user_id>0 and p.user_id not in (".$top_agents.") and p.user_id=ul.u_login_id and ul.u_suspend_status='n'","","totalUP desc","p.user_id","0,".$count-$cnt);
		while($row=mysqli_fetch_array($res))
		{
			$max_posted_user_ids .= $row['user_id'].",";
		}
		 $max_posted_user_ids = rtrim($max_posted_user_ids,",");
		} 
		 $top_agents =  $top_agents.",".$max_posted_user_ids;
		if($top_agents!="") return trim($top_agents,",");	
		else return "0";	
	}
	
	function get_top_agents_by_pck_likes()
	{	
	   $top_agents = "";
	   $res=parent::selectData(TABLE_PACKAGE." p INNER JOIN ".TABLE_PACKAGE_LIKE." pl on p.pck_id=pl.pck_id","p.pck_id,p.user_id,count(pl.pl_id) as total_likes","pl.pl_status='Active' and p.pck_status='Active'","","total_likes desc","p.user_id");
		while($row=mysqli_fetch_array($res))
		{
			$top_agents .= $row['user_id'].",";
		}
		if(rtrim($top_agents,",")!="") return rtrim($top_agents,",");	
		else return "0";	
	}
	
	function total_package_posted_by_user($userId)
	{	
	   $total_package_posted_by_user = 0;
	   $res=parent::selectData(TABLE_PACKAGE,"count(pck_id) as totalPPU","pck_status='Active' and user_id='".$userId."'",1);
	   $total_package_posted_by_user = $res['totalPPU'];
	   
	   return $total_package_posted_by_user;
	}
	
	function completed_tours_by_user($userId)
	{	
	   $total_package_posted_by_user = 0;
	   $res=parent::selectData(TABLE_PACKAGE,"count(pck_id) as totalPPU","pck_status='Active' and user_id='".$userId."' and pck_end_dt <= '".date("Y-m-d")."'",1);
	   $total_package_posted_by_user = $res['totalPPU'];
	   
	   return $total_package_posted_by_user;
	}
	function ongoing_tours_by_user($userId)
	{	
	   $total_package_posted_by_user = 0;
	   $res=parent::selectData(TABLE_PACKAGE,"count(pck_id) as totalPPU","pck_status='Active' and user_id='".$userId."' and pck_end_dt >= '".date("Y-m-d")."' ",1);
	   $total_package_posted_by_user = $res['totalPPU'];
	   
	   return $total_package_posted_by_user;
	}
	
	function total_package_posted()
	{
	   $total_package_posted = 0;
	   $res=parent::selectData(TABLE_PACKAGE,"count(pck_id) as totalPP","pck_status='Active'",1);
	   $total_package_posted = $res['totalPP'];
	   
	   return $total_package_posted;
	}
	
	function user_package_rating($userId)
	{
		$top_rated_pck_ids = "";
	    $res=parent::selectData(TABLE_PACKAGE_REVIEW." pr INNER JOIN ".TABLE_PACKAGE." as pp ON pp.pck_id=pr.pck_id","count(pr.pr_id) as totalR, sum(pr.pr_rate) as sumR, sum(pr.pr_rate)/count(pr.pr_id) as rating","pr.pr_status='Active' and pp.user_id='".$userId."' ",1,"","pp.pck_id");
		return $res['rating'];
		
	}
	
	function package_included_list($pIncL)
	{
		$pIncList = "";
		$pInc = trim($pIncL,",");
		if($pInc=="") $pInc = "0";
		$sqlF = parent::selectData(TABLE_FACILITY_INC,"","faci_status='Active' and faci_id in (".$pInc.")");
		while($resF = mysqli_fetch_array($sqlF))
		{
			$pIncList .= $resF['faci_title'].",";
		} 
		return rtrim($pIncList,",");
	}
	
	function package_not_included_list($pIncL)
	{
		$pIncList = "";
		$pInc = trim($pIncL,",");
		if($pInc=="") $pInc = "0";
		$sqlF = parent::selectData(TABLE_FACILITY_INC,"","faci_status='Active' and faci_id not in (".$pInc.")");
		while($resF = mysqli_fetch_array($sqlF))
		{
			$pIncList .= $resF['faci_title'].",";
		} 
		return rtrim($pIncList,",");
	}
	
	function get_user_follower($userId)
	{
		$followers = $this->get_user_follower_ids($userId);
		if($followers=="") return "0";
		else return count(explode(",",$active_friend_list));
	}
	
	function get_user_follower_ids($userId)
	{
		$followers = "";
		$sqlF = parent::selectData(TABLE_FRIENDS,"","fr_status='S' and fr_to_id = '".$userId."'");
		while($resF = mysqli_fetch_array($sqlF))
		{			
			$followers .= $resF['fr_from_id'].",";
		}
		
		return $followers = trim($this->filter_active_users(trim($followers ,",")));
	}
	
	function get_user_friends($userId)
	{
		$active_friend_list = $this->get_user_friend_ids($userId);
		if($active_friend_list=="") return "0";
		else return count(explode(",",$active_friend_list));
	}
	
	function get_user_friend_ids($userId)
	{
		$comp_ids = "";
		$sqlF = parent::selectData(TABLE_FRIENDS,"","fr_status='A' and (fr_to_id = '".$userId."' or fr_from_id = '".$userId."')","");
		while($resF = mysqli_fetch_array($sqlF))
		{
			if($resF['fr_from_id'] == $userId) $comp_ids .= $resF['fr_to_id'].",";
			else if($resF['fr_to_id'] == $userId)   $comp_ids .= $resF['fr_from_id'].",";
		}
		$comp_ids = trim($this->filter_active_users(trim($comp_ids ,",")));
		
		return $comp_ids;
	}
	
	function companion_request_status($user_id_1,$user_id_2)
	{
		$sqlF = parent::selectData(TABLE_FRIENDS,"","(fr_from_id = '".$user_id_1."' and  fr_to_id = '".$user_id_2."') or (fr_from_id = '".$user_id_2."' and fr_to_id = '".$user_id_1."')",1);
		return $sqlF['fr_status'];
	}
	
	function get_languages($langs)
	{
		$landList = "";
		$langS = trim($langs,",");
		if($langS!="")
		{
			$sqlL = parent::selectData(TABLE_LANGUAGE,"","lang_status='Active' and lang_id in (".$langS.")");
			while($resL = mysqli_fetch_array($sqlL))
			{
				$landList .= $resL['lang_title'].", ";
			}
		}
		return rtrim($landList,", ");
		
	}
	
	function get_user_photos($user_id)
	{
		 $photos = "";
		 $sqlP = parent::selectData(TABLE_PACKAGE,"","user_id='".$user_id."' and pck_status='Active'");	
		 while($resP = mysqli_fetch_array($sqlP))	
		 {
		 	if($resP['pck_photo']!="") $photos .= PACKAGE.$resP['pck_photo'].",";
		 }
		 //$photos = trim($photos,",");
		 $sqlS = parent::selectData(TABLE_STORY,"","user_id='".$user_id."' and ts_status='Active'");	
		 while($resS = mysqli_fetch_array($sqlS))	
		 {
		 	$sqlSS = parent::selectData(TABLE_STORY_SUB,"","ts_id='".$resS['ts_id']."'");	
		 	while($resSS = mysqli_fetch_array($sqlSS))	
			{
				$images = explode(",",$resSS['tss_photos']);
				for($k=0;isset($images[$k]),$images[$k]!="";$k++)
				{
		 			$photos .= STORY.$images[$k].",";
				}	
		 	}
		 }
		 $photos = trim($photos,",");
		 
		 return $photos;
		 
	}
	
	function valid_auth_user($userId)
	{
		$userLD=parent::selectData(TABLE_USER_LOGIN,"","u_login_id='".$userId."' and u_login_status='Active'",1);
		$userD=parent::selectData(TABLE_USER,"","u_login_id='".$userId."' and user_status='Active'",1);
		if(!isset($userD['u_login_id']) || !isset($userLD['u_login_id']))
		{
			$this->add_message("message","Invalid Request!");
			$this->reDirect("dashboard.php");
		}
	}
	
	function scaleCrop($src, $dest,  $anchor){
        if(!file_exists($dest) && is_file($src) && is_readable($src)){
            $srcSize = getimagesize($src);
            $srcW = $srcSize[0];
            $srcH = $srcSize[1];
			
			if($srcW >= $srcH)
			{
				$destW = $srcH;
				$destH = $srcH;
			}
			else
			{
				$destW = $srcW;
				$destH = $srcW;
			}
            $srcRatio = $srcW / $srcH;
            $destRatio = $destW / $destH;
            $img = (imagecreatefromjpeg($src));
            $imgNew = imagecreatetruecolor($destW, $destH);

            if ($srcRatio < $destRatio){
                $scale = $srcW / $destW;
            }
            elseif($srcRatio >= $destRatio){
                $scale = $srcH / $destH;
            }
            $srcX = ($srcW - ($destW * $scale)) / 2;
            if($anchor = 'middle'){
                $srcY = ($srcH - ($destH * $scale)) / 2;
            }
            elseif($anchor = 'top'){
                $srcY = 0;
            }
            elseif($anchor = 'bottom'){
                $srcY = $srcH - ($destH * $scale);
            }
            if($srcX < 0){$srcX = 0;};
            if($srcY < 0){$srcY = 0;};
            imagecopyresampled($imgNew, $img, 0, 0, $srcX, $srcY, $destW, $destH, $destW * $scale, $destH * $scale);
            imagejpeg($imgNew, $dest, 70);
            imagedestroy($img);
            imagedestroy($imgNew);
        }
        return $dest;
    }
	
	function get_non_permitted_comps($user_id)
	{
		$np_comps = "";
	    $comQuery = parent::selectData(TABLE_FRIENDS,"","((fr_from_id='".$user_id."' and fr_follow_1='u') or (fr_to_id='".$user_id."' and fr_follow_2='u')) or  ((fr_from_id='".$user_id."' and fr_block_2='b') or (fr_to_id='".$user_id."' and fr_block_1='b')) and fr_status='A' ","");
			while($comRes = mysqli_fetch_array($comQuery))	
			{
				if($comRes['fr_follow_1'] == 'u' && $comRes['fr_from_id']=$user_id)
				{
				 	$np_comps .= $comRes['fr_to_id'].",";
				}
				elseif($comRes['fr_follow_2'] == 'u' && $comRes['fr_to_id']=$user_id)
				{
					$np_comps .= $comRes['fr_from_id'].",";
				}
				elseif($comRes['fr_block_1'] == 'b' && $comRes['fr_to_id']=$user_id)
				{
					$np_comps .= $comRes['fr_from_id'].",";
				}
				elseif($comRes['fr_block_2'] == 'b' && $comRes['fr_from_id']=$user_id)
				{
					$np_comps .= $comRes['fr_to_id'].",";
				}
				$np_comps = trim($np_comps,",");
		 	}
			
			if($np_comps=="") return "0";
			else return $np_comps;
	}
	
	function comp_profile_view_permission($log_user_id,$comp_id)
	{
		$comPPData = parent::selectData(TABLE_FRIENDS,"","((fr_from_id='".$log_user_id."' and fr_block_2='b') or (fr_to_id='".$log_user_id."' and fr_block_1='b')) and fr_status='A' ",1);
		if(isset($comPPData['fr_id']) && $comPPData['fr_id']!='')
		{
			$this->add_message("message","You are not allowed to access this page!");
			$this->reDirect("dashboard.php");
		}
	}
	
	function set_notification($comp_id,$message_template,$page)
	{
		//$echo $comp_id."=".$message_template."=".$page;
		$comp_ids = explode(",",$comp_id);
		
		for($i=0; $comp_ids[$i]!='';$i++)
		{
			$notiD = array();
			
			$notiD['noti_for_user'] = $comp_ids[$i];
			$notiD['noti_from_user'] = $_SESSION['user']['u_login_id'];
			$notiD['noti_message'] = $message_template;
			$notiD['noti_url'] = $page;
			parent::insertData(TABLE_NOTIFICATION,$notiD);
		}	
	}
	
	function unset_notification($user_id,$type)
	{
		$notiU = array();		
		$notiU['noti_status'] = 'Inactive';
		
		if($type=='companion.php')
		{
			//parent::deleteData(TABLE_NOTIFICATION,"noti_url='".$type."' and noti_for_user = '".$user_id."'");
		}
		else
		{
			//parent::deleteData(TABLE_NOTIFICATION,"noti_url='".$type."' and noti_for_user = '".$user_id."'");
		}
	}
	
	function get_pck_like_status($pck_id,$user_id)
	{
		$pckL = parent::selectData(TABLE_PACKAGE_LIKE,"","user_id='".$user_id."' and pck_id='".$pck_id."' and pl_status='Active'",1);
		if(isset($pckL['pl_id']) && $pckL['pl_id']!='')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function get_pck_like_count($pck_id)
	{
		$pckL = parent::selectData(TABLE_PACKAGE_LIKE,"count(pl_id) as total_like","pck_id='".$pck_id."' and pl_status='Active'",1);
		return $pckL['total_like'];
	}
	
	function set_unset_pck_like($pck_id,$user_id)
	{
		$result = 0;
		$pckL = parent::selectData(TABLE_PACKAGE_LIKE,"","user_id='".$user_id."' and pck_id='".$pck_id."' and pl_status='Active'",1);
		if(isset($pckL['pl_id']) && $pckL['pl_id']!='')
		{
			$result  = parent::deleteData(TABLE_PACKAGE_LIKE,"pl_id = '".$pckL['pl_id']."'");
			
			$pckD  = parent::selectData(TABLE_PACKAGE,"","pck_status='Active' and pck_id='".$pck_id."'",1);
			$type = "tour-details.php?tId=".$pck_id;
			$this->unset_notification($pckD['user_id'],$type);
			
			return $result;
		}
		else
		{
			$dataPL = array();
			$dataPL['user_id'] = $user_id;
			$dataPL['pck_id']  = $pck_id;
			$result  = parent::insertData(TABLE_PACKAGE_LIKE,$dataPL);
			
			$pckD  = parent::selectData(TABLE_PACKAGE,"","pck_status='Active' and pck_id='".$pck_id."'",1);
			$userD = parent::selectData(TABLE_USER,"","u_login_id = '".$user_id."'",1);
			
			
			$message_template = $userD['user_full_name']." likes ".$pckD['pck_title'];
			$this->set_notification($pckD['user_id'],$message_template,"tour-details.php?tId=".$pck_id);			
			
			return $result;
		}
	}
	
	
	function get_story_like_status($ts_id,$user_id)
	{
		$storyL = parent::selectData(TABLE_STORY_LIKE,"","user_id='".$user_id."' and ts_id='".$ts_id."' and sl_status='Active'",1);
		if(isset($storyL['sl_id']) && $storyL['sl_id']!='')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function get_story_like_count($ts_id)
	{
		$storyL = parent::selectData(TABLE_STORY_LIKE,"count(sl_id) as total_like","ts_id='".$ts_id."' and sl_status='Active'",1);
		return $storyL['total_like'];
	}
	
	function set_unset_story_like($ts_id,$user_id)
	{
		$result = 0;
		$storyL = parent::selectData(TABLE_STORY_LIKE,"","user_id='".$user_id."' and ts_id='".$ts_id."' and sl_status='Active'",1);
		if(isset($storyL['sl_id']) && $storyL['sl_id']!='')
		{
			$result = parent::deleteData(TABLE_STORY_LIKE,"sl_id = '".$storyL['sl_id']."'");
			
			$storyD  = parent::selectData(TABLE_STORY,"","ts_status='Active' and ts_id='".$ts_id."'",1);
			$type = "story-details.php?tId=".$ts_id;
			$this->unset_notification($storyD['user_id'],$type);
			
			return $result;
		}
		else
		{
			$dataSL = array();
			$dataSL['user_id'] = $user_id;
			$dataSL['ts_id']  = $ts_id;
			$result = parent::insertData(TABLE_STORY_LIKE,$dataSL);
			
			$storyD  = parent::selectData(TABLE_STORY,"","ts_status='Active' and ts_id='".$ts_id."'",1);
			$userD = parent::selectData(TABLE_USER,"","u_login_id = '".$user_id."'",1);
			
			
			$message_template = $userD['user_full_name']." likes ".$storyD['ts_title'];
			$this->set_notification($storyD['user_id'],$message_template,"story-details.php?tId=".$ts_id);	
			
			
			return $result;
		}
	}
	
	function get_total_likes($user_id)
	{
		$total_pck_like   = 0;
		$total_story_like = 0;
		$pck_ids = "";
		$ts_ids = "";
		$sqlP=parent::selectData(TABLE_PACKAGE,"","pck_status='Active' and user_id='".$user_id."'");
		while($resP = mysqli_fetch_array($sqlP))
		{
			$pck_ids .= $resP['pck_id'].",";
		}
		$pck_ids = trim($pck_ids,",");
		if($pck_ids=="") $pck_ids = "0";
		
		$pckL = parent::selectData(TABLE_PACKAGE_LIKE,"count(pl_id) as total_like","pck_id in (".$pck_ids.") and pl_status='Active'",1);
		$total_pck_like = $pckL['total_like'];
		
		$sqlS=parent::selectData(TABLE_STORY,"","ts_status='Active' and user_id='".$user_id."'");
		while($resS = mysqli_fetch_array($sqlS))
		{
			$ts_ids .= $resS['ts_id'].",";
		}
		$ts_ids = trim($ts_ids,",");
		if($ts_ids=="") $ts_ids = "0";
		
		$storyL = parent::selectData(TABLE_STORY_LIKE,"count(sl_id) as total_like","ts_id in (".$ts_ids.") and sl_status='Active'",1);
		$total_story_like =  $storyL['total_like'];
		
		return ($total_pck_like+$total_pck_like);
	}
	
	function total_unread_notification($user_id)
	{
		$notiCount = parent::selectData(TABLE_NOTIFICATION,"count(noti_id) as total_notification","noti_for_user = '".$user_id."'",1);
		if($notiCount['total_notification']!="" && $notiCount['total_notification']!=0) return $notiCount['total_notification'];
		else return "";
	}
	
	
	function filter_active_users($users)
	{
		$filter_list = "";
		$userL = trim($users,",");
		if($userL!="")
		{
			$sqlUserAL = parent::selectData(TABLE_USER_LOGIN." as ul inner join ".TABLE_USER." as u on u.u_login_id=ul.u_login_id","","ul.u_login_status='Active' and u.user_status='Active' and ul.u_login_id IN (".$userL.")");
			while($resUserAL = mysqli_fetch_array($sqlUserAL))
			{
				$filter_list .= $resUserAL['u_login_id'].",";
			}
		}
		
		return trim($filter_list,",");
	}
	
	function hide_price($price)
	{
		$len = strlen($price);
		$new_price = "";
		for($i=0;$i<$len;$i++)
		{
			if($i<1) $new_price .= $price[$i];
			else $new_price .= '*';
		}
		if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!='' ) return $price;
		else return $new_price;
	}
	
	function total_story_posted_by_user($userId)
	{	
	   $total_story_posted_by_user = 0;
	   $res=parent::selectData(TABLE_STORY,"count(ts_id) as totalSC","ts_status='Active' and user_id='".$userId."'",1);
	   $total_story_posted_by_user = $res['totalSC'];
	   
	   return $total_story_posted_by_user;
	}
	
	function rand_Pass($upper = 1, $lower = 5, $numeric = 3, $other = 1) 
	{ 
    
		$pass_order = Array(); 
		$passWord = ''; 
	
		//Create contents of the password 
		for ($i = 0; $i < $upper; $i++) { 
			$pass_order[] = chr(rand(65, 90)); 
		} 
		for ($i = 0; $i < $lower; $i++) { 
			$pass_order[] = chr(rand(97, 122)); 
		} 
		for ($i = 0; $i < $numeric; $i++) { 
			$pass_order[] = chr(rand(48, 57)); 
		} 
		for ($i = 0; $i < $other; $i++) { 
			$pass_order[] = chr(rand(35, 38)); 
		} 
	
		//using shuffle() to shuffle the order
		shuffle($pass_order); 
	
		//Final password string 
		foreach ($pass_order as $char) { 
			$passWord .= $char; 
		} 
		return $passWord; 
	} 
	
	function getDataURI($imagePath) 
	{
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$type = $finfo->file($imagePath);
		return 'data:' . $type . ';base64,' . base64_encode(file_get_contents($imagePath));
	}

	
}

?>
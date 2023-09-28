<?php 
require_once("../includes/connection.php");

  

		$dataES = $obj->selectData(TABLE_SEND_ENTERNAL_EMAIL,"","see_status='P' order by see_id desc",1);	
		
		$user_ids1 = trim($dataES['user_id'],",");
		$user_ids2 = explode(",",$user_ids1);
		$total_user = count($user_ids2);			
		$userID = $obj->getRestMemberIds($user_ids2[$total_user-1]);
		$dataw = explode(",",$userID);
		$userData =  $dataw;
				 
		// pre($totot); exit;	

		$dataEF = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$dataES['ef_id']."'",1);
	for ($x = 0; $userData[$x]!=''; $x++) {
		
		$userD = $obj->selectData(TABLE_USER,"","user_status='Active' and u_login_id='".$userData[$x]."'",1);	 
		$reset_password_link = "<a href='".FURL."reset_password.php?uactid=".$obj->encryptPass($userData[$x])."'>Click Here </a>";
		$click_to_activate_account = "<a href='".FURL."activation.php?uactid=".$obj->encryptPass($userData[$x])."'>CLICK HERE TO ACTIVATE YOUR ACCOUNT </a> ";
		
		$fname = $userD['user_first_name'];
		$lname = $userD['user_last_name'];
		$dob = $userD['user_dob'];
		$country = $userD['user_country'];
		$state = $userD['user_state'];
		$city = $userD['user_city'];
		$zip = $userD['user_zip'];
		$address = $userD['user_address_1'];	
		$wallet_address = $userD['user_wallet_address'];	
		
		$member_name =   str_replace('%MEMBER_NAME%', $userD['user_first_name'], $dataEF['ef_email_formet']);			
		$reset_password =   str_replace('%RESET_LINK%', $reset_password_link, $member_name);			
		$registration_link =   str_replace('%CLICK_TO_ACTIVATE_ACCOUNT%', $click_to_activate_account, $reset_password);			
		$first_name =   str_replace('%FNAME%', $fname, $registration_link);
		$last_name =   str_replace('%LNAME%', $lname, $first_name);
		$date_of_birth =   str_replace('%DOB%', $dob, $last_name);
		$get_country =   str_replace('%COUNTRY%', $country, $date_of_birth);
		$get_state =   str_replace('%STATE%', $state, $get_country);
		$get_city =   str_replace('%CITY%', $city, $get_state);
		$get_zip_code =   str_replace('%ZIPCODE%', $zip, $get_city);
		$address =   str_replace('%ADDRESS%', $address, $get_zip_code);
		$get_body =   str_replace('%WALLET_ADDRESS%', $wallet_address, $address);
		  
		$body = html_entity_decode($get_body);	 
		
		$from = FROM_EMAIL;
		$to   = $userEmail;	  
		$subject = "".$dataEF['ef_title']." - ".$userD['user_first_name'];		     
	//	$obj->sendMail($to,$subject,$body,$from,SITE_TITLE,$type);		
	 
	}  
	 
		if($dataES['user_id'] == '0') {
			$arrU['user_id']= ",".$userID.",";	
		} else if(count($userData) && $userData[0]!='') {
			$arrU['user_id']= ",".$user_ids1.','.$userID.",";		
		}		
		
		if(count($userData)<5) $arrU['see_status'] = 'S';	 
		$obj->updateData(TABLE_SEND_ENTERNAL_EMAIL,$arrU,"see_id='".$dataES['see_id']."'");
	
	//$obj->add_message("message",ADD_SUCCESSFULL);
	//$_SESSION['messageClass'] = 'successClass';
	//$obj->reDirect($return_url);			 
		 
	 
		
 


?>

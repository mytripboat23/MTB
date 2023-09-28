<?php
include('includes/connection.php');
include('includes/config-google-login.php');
$login_button = '';
if ( isset( $_GET[ "prompt" ] ))
{

  $token = $google_client->fetchAccessTokenWithAuthCode( $_GET[ "code" ] );
  
  if ( !isset( $token[ 'error' ] ) )
  {
    $google_client->setAccessToken( $token[ 'access_token' ] );
    $_SESSION[ 'access_token' ] = $token[ 'access_token' ];
    $google_service = new Google_Service_Oauth2( $google_client );
    $data = $google_service->userinfo->get();
	
   
	     $ulogD = $obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$data['email']."' and u_login_status <> 'Deleted'",1);
		 if($ulogD['u_login_id']=='')	
		 {			

				$email = isset($data['email']) ? strtolower(trim($data['email']))  : '';
				list ($emailuser, $emaildomain) = array_pad(explode("@", $email, 2), 2, null);

				$gmail_cleartext = $obj->get_gmail_user_cleartext($data['email']);
				$sqlUGS = $obj->selectData(TABLE_USER_LOGIN,"","u_login_gmail_cleartext='".$gmail_cleartext."' and u_login_status<>'Deleted'",1);
				if($sqlUGS['u_login_id'])
				{
						$sqlUS1=$obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$sqlUGS['u_login_user_email']."' and u_suspend_status='y'",1);
						if($sqlUS1)
						{
							$obj->add_message("message","Your account has been suspended. Please contact support for details.");
						}
						else
						{
							$loginAtmp['u_login_attempt']  = $user_login['u_login_attempt']+1;
							$loginAtmp['u_login_failed']   = 0;
							$loginAtmp['u_last_login']     = CURRENT_DATE_TIME;	
			
							$obj->updateData(TABLE_USER_LOGIN,$loginAtmp,"u_login_id='".$user_login['u_login_id']."'");	
							
								$_SESSION['session_login_time']   = time();			
								$_SESSION['user'] = $sqlUS1;
								$_SESSION['user']['ip'] = $ip;
								$_SESSION['user']['notification'] = 'n';
								$obj->reDirect("dashboard");   
								exit;
						}
				}
				else
				{

						$password = $obj->rand_Pass();
					
						
						$userlogArr = array();
						$userlogArr['u_login_password']   		= password_hash($password,PASSWORD_DEFAULT);
						$userlogArr['u_login_user_email'] 		= $obj->filter_mysql($data['email']);
						$userlogArr['u_login_gmail_cleartext'] 	= $gmail_cleartext;
						$userlogArr['u_login_created']	  		= CURRENT_DATE_TIME;
						$userlogArr['u_login_modified']   		= CURRENT_DATE_TIME;	
						$userlogArr['u_login_attempt']    		= 0;
						$userlogArr['u_pass_update']   	  		= CURRENT_DATE_TIME;
						$userlogArr['u_login_status']   		= "Active";
						$userlogArr['u_login_recent_password']  = $userlogArr['u_login_password'];
					
						$newUserId = $obj->insertData(TABLE_USER_LOGIN,$userlogArr);
						
						$_SESSION['new_user_id']    		= $newUserId;	
						$_SESSION['new_user_email'] 		= $obj->filter_mysql($data['email']);			
						$userlArr['u_login_id']      		= $newUserId;
						//$userlArr['user_referrer']   		= $obj->filter_mysql($_SESSION['aff_ref_id']);
						$userlArr['user_email']      		= $obj->filter_mysql($data['email']);
						$userlArr['user_full_name'] 		= $obj->filter_mysql($data['name']);
						$userlArr['user_display_name'] 		= $obj->filter_mysql($data['name']);
						
						$userlArr['user_oauth_provider'] 	= "google";
						$userlArr['user_oauth_uid'] 		    = $data['id'];

						$userlArr['user_reg_ip']    		= $ip;
						$userlArr['user_reg_browser']    	= $_SERVER['HTTP_USER_AGENT'];
						$userlArr['user_created']    		= CURRENT_DATE_TIME;
						$userlArr['user_modified']   		= CURRENT_DATE_TIME;
						$userlArr['user_status']   			= "Active";		
						
						$obj->insertData(TABLE_USER,$userlArr);

						$user_login = $obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$data['email']."' and u_login_status<>'Deleted'",1);

								$_SESSION['session_login_time']   = time();			
								$_SESSION['user'] = $user_login;
								$_SESSION['user']['ip'] = $ip;
								$_SESSION['user']['notification'] = 'n';
								$obj->reDirect("dashboard");   
								exit;
				}
						

	
			}			
			else
			{

					     $user_login = $obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$data['email']."' and u_login_status='Active'",1);
						 if(isset($user_login['u_login_id']) && $user_login['u_login_id']!='')
						 {
							 $userD = $obj->selectData(TABLE_USER,"","u_login_id='".$user_login['u_login_id']."' and user_oauth_provider = 'google' and user_oauth_uid!='' and user_status='Active'",1);
							 if(isset($userD['user_id']) && $userD['user_id']!='')
							 {
								$_SESSION['session_login_time']   = time();			
								$_SESSION['user'] = $user_login;
								$_SESSION['user']['ip'] = $ip;
								$_SESSION['user']['notification'] = 'n';
								$obj->reDirect("dashboard");   
								exit;
							 }
							 else
							 {
							 	$obj->add_message("message","Please login with registered email id and password.");	
								$obj->reDirect("login"); 
							 }	
						 }	
						 else
						 {
						 		$obj->add_message("message","Your account is Inactive.");	
								$obj->reDirect("login"); 
						 }	

			}
	   }
	   else
	   {
	   		$obj->add_message("message","Login Attempt Failed. Please try again.");	
			$obj->reDirect("login");      
	   }
	   	 
}
else
{
	$obj->reDirect("login");     
}

if ( !isset( $_SESSION[ 'access_token' ] ) )
{
    $obj->reDirect("login");     
}


?>
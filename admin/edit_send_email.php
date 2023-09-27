<?php
require_once("../includes/connection.php");
adminSecure();
$seeId = $obj->filter_mysql($obj->filter_numeric($_REQUEST['seeId']));

 
if(isset($_REQUEST['return_url']))
{
	$return_url = urldecode($_REQUEST['return_url']);
}
else
{
	$return_url='show_send_email.php';
}

$mode="add";
$table_caption="Send New Email";
$jqueryReq = 'true';

	if($seeId!="")
	{
		$mode="edit";
		$table_caption="Update Email Details";
		$data = $obj->selectData(TABLE_SEND_ENTERNAL_EMAIL,"","see_id='".$seeId."'",1);
	}
	else
	{
		$data = $_POST;
	}

if($_POST)
{      
	if(!$err)
	{		
		 
		$userAc = $obj->selectData(TABLE_USER_LOGIN,"count(u_login_id) as totuser","u_login_status <>'Deleted'",1);
		$tot_user = $userAc['totuser'];

		$userInac = $obj->selectData(TABLE_USER_LOGIN,"count(u_login_id) as tot_inactiveu","u_login_status ='Registered'",1);
		$tot_inactive_user = $userInac['tot_inactiveu'];

		$becomAff = $obj->selectData(TABLE_BECOME_AFFILIATE,"count(baff_id) as tot_baff","baff_status ='Active'",1);
		$tot_affiliate = $becomAff['tot_baff']; 
		 
		 
		 
		$choose_format = $_POST['choose_format']; // e=Existing  n= New Format

		if($_POST['member_type'] == 'all'){			
			$_POST['user_id'] = 0;
			$_POST['user_type'] = 'u';
		}		
		if($_POST['member_type'] == 'sales'){			
			$_POST['user_id'] = 0;		
			$_POST['user_type'] = 'a';
		}
		if($_POST['member_type'] == 'inactive'){			
			$_POST['user_id'] = 0;		
			$_POST['user_type'] = 'i';
		}
		if($_POST['choose_format'] == 'n'){			
			$_POST['ef_id']= 0;
		}
				
		if($_POST['member_type']== 'single') 
		{
			 	
			$typeahead = explode('(',$_POST['typeahead']); 			
			$userEmail = rtrim($typeahead['1'],")");
			
			$_SESSION['messageClass'] = "errorClass";
			if($userEmail =="") {$obj->add_message("message","Please enter valid name or email Id.");}
			
			if($obj->get_message("message")=="")
			{ 
				if($_POST['ef_id'] != '0') 
				{
					$dataEF = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$obj->filter_mysql($obj->filter_numeric($_POST['ef_id']))."'",1);
					$send_email_format = $dataEF['ef_email_formet'];
				} 
				else 
				{
					$send_email_format = $obj->filter_mysql($_POST['email_msg']);
				}
				
				$userData = $obj->selectData(TABLE_USER,"","user_email='".$userEmail."' and user_status='Active'",1);
				$AffData = $obj->selectData(TABLE_USER,"","user_referrer='".$userData['u_login_id']."' and user_status='Active'",1);
				 
				//pre($AffData); exit;
				
				$_POST['user_id'] = $userData['u_login_id'];
			 
				$reset_password_link = "<a href='".FURL."reset_password.php?uactid=".$obj->encryptPass($userData['u_login_id'])."'>Click Here </a>";
				$click_to_activate_account = "<a href='".FURL."activation.php?uactid=".$obj->encryptPass($userData['u_login_id'])."'>CLICK HERE TO ACTIVATE YOUR ACCOUNT </a> ";
				
				$affname = $AffData['user_first_name'];
				$affemail = $AffData['user_email'];
				$fname = $userData['user_first_name'];
				$lname = $userData['user_last_name'];
				$dob = $userData['user_dob'];
				$country = $obj->getCountry($userData['user_country']);
				$state = $userData['user_state'];
				$city = $userData['user_city'];
				$zip = $userData['user_zip'];
				$address = $userData['user_address_1'];	
				$wallet_address = $userData['user_wallet_address'];	
				$full_name = $userData['user_first_name'].' '.$userData['user_last_name'];	
				$email = $userData['user_email'];	
					
				$member_name =   str_replace('%MEMBER_NAME%', $userData['user_first_name'], $send_email_format);			
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
				$wallet_address =   str_replace('%WALLET_ADDRESS%', $wallet_address, $address);
				$full_name =   str_replace('%NAME%', $full_name, $wallet_address);
				$email =   str_replace('%EMAIL%', $email, $full_name);
				$affname =   str_replace('%AFFNAME%', $affname, $email);
				$get_body =   str_replace('%AFFEMAIL%', $affemail, $affname);
				  
				$body = html_entity_decode($get_body);	 
				
				$from = FROM_EMAIL_2;
				$to   = $userEmail;	  
				$subject = "".$dataEF['ef_subject']." - ".$userData['user_first_name'];		     
			  	$obj->sendMailSES($to,$subject,$body,$from,SITE_TITLE,$type);
						
				$seeD['see_status']    =  'S';			
				$seeD['user_id']       =  $obj->filter_mysql($obj->filter_numeric($_POST['user_id']));	
				$seeD['see_created']   =  CURRENT_DATE_TIME;			
				$seeD['member_type']   =  $obj->filter_mysql($_POST['member_type']);			
				$seeD['choose_format'] =  $obj->filter_mysql($_POST['choose_format']);
				if($seeD['choose_format']=='n')
				{
					$seeD['email_msg']     =  $obj->filter_mysql($_POST['email_msg']);				
				}
				else
				{
					$seeD['ef_id']         =  $obj->filter_mysql($obj->filter_numeric($_POST['ef_id']));
				}
				
				$obj->insertData(TABLE_SEND_ENTERNAL_EMAIL,$_POST);
				$obj->add_message("message",'Your email is successfully sent to '.$userEmail);
				$_SESSION['messageClass'] = 'successClass';
			}
		}
 
		if($_POST['member_type'] == 'all') 
		{
			$seeD['see_created']   =  CURRENT_DATE_TIME;			
			$seeD['member_type']   =  $obj->filter_mysql($_POST['member_type']);			
			$seeD['choose_format'] =  $obj->filter_mysql($_POST['choose_format']);
			if($seeD['choose_format']=='n')
			{
				$seeD['email_msg']     =  $obj->filter_mysql($_POST['email_msg']);				
			}
			else
			{
				$seeD['ef_id']         =  $obj->filter_mysql($obj->filter_numeric($_POST['ef_id']));
			}
			
			
			
			$obj->insertData(TABLE_SEND_ENTERNAL_EMAIL,$seeD);
			$obj->add_message("message",'This message will be sent to '.$tot_user.' number of email addresses.');
			$_SESSION['messageClass'] = 'successClass';
		}
		
		if($_POST['member_type'] == 'sales') 
		{
			$seeD['see_created']   =  CURRENT_DATE_TIME;			
			$seeD['member_type']   =  $obj->filter_mysql($_POST['member_type']);			
			$seeD['choose_format'] =  $obj->filter_mysql($_POST['choose_format']);
			if($seeD['choose_format']=='n')
			{
				$seeD['email_msg']     =  $obj->filter_mysql($_POST['email_msg']);				
			}
			else
			{
				$seeD['ef_id']         =  $obj->filter_mysql($obj->filter_numeric($_POST['ef_id']));
			}
			$obj->insertData(TABLE_SEND_ENTERNAL_EMAIL,$seeD);	
			
			$obj->add_message("message",'This message will be sent to '.$tot_affiliate.' number of email addresses.');
			$_SESSION['messageClass'] = 'successClass';
		}
		
		if($_POST['member_type'] == 'inactive') 
		{
			$seeD['see_created']   =  CURRENT_DATE_TIME;			
			$seeD['member_type']   =  $obj->filter_mysql($_POST['member_type']);			
			$seeD['choose_format'] =  $obj->filter_mysql($_POST['choose_format']);
			if($seeD['choose_format']=='n')
			{
				$seeD['email_msg']     =  $obj->filter_mysql($_POST['email_msg']);				
			}
			else
			{
				$seeD['ef_id']         =  $obj->filter_mysql($obj->filter_numeric($_POST['ef_id']));
			}
			$obj->insertData(TABLE_SEND_ENTERNAL_EMAIL,$seeD);	
			$obj->add_message("message",'This message will be sent to '.$tot_inactive_user.' number of email addresses.');
			$_SESSION['messageClass'] = 'successClass';
		}	

 
		$obj->reDirect($return_url);			 
			 
		 
		
	}
}


$dataEF = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$data['ef_id']."'",1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("page_includes/common.php");?>
</head>
<body class="no-skin">
<?php include("page_includes/top_navbar.php");?>
<div class="main-container" id="main-container">
  <?php include("page_includes/sidebar.php");?>
  <div class="main-content">
    <div class="breadcrumbs" id="breadcrumbs">
      <script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	  </script>
      <ul class="breadcrumb">
        <li> <i class="ace-icon fa fa-home home-icon"></i> <a href="dashboard.php">Home</a> </li>
        <li> <a href="show_news.php">Send Email</a></li>
		<li class="active"><?//=$data['ef_title'];?></li>
      </ul>
      <!-- /.breadcrumb -->
      <?php /*?><div class="nav-search" id="nav-search">
        <form class="form-search" action="" method="get">
          <span class="input-icon">
          <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
          <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
        </form>
      </div><?php */?>
      <!-- /.nav-search -->
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> <?=$table_caption;?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
			  
                <form class="form-horizontal" role="form" name="efEdit" id="efEdit" method="post" action="" enctype="multipart/form-data">            
			 
				  
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Type</label>
					<div class="col-sm-9">
						<select name="member_type" id="member_type" onChange="changeSelection(this.value)" class="col-xs-10 col-sm-5">		
							<option value="all">All Member</option>
							<option value="single">Single Member</option>
							<option value="sales">Sales Affiliates only</option>
							<option value="inactive">Inactive Members</option>
							
						</select>	
					</div>
				</div>
				 
				 
				  <div class="form-group" id="fld_single" style="display: none;">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Member : </label>
                    <div class="col-sm-9"> 						 
						<input type="text" name="typeahead" id="typeahead" class="typeahead col-xs-10 col-sm-5" onBlur="checkMemName(this.value)" autocomplete="off" spellcheck="false" placeholder="Type name or email">
						
						<label id="typeahead-error" class="error" for="typeahead"></label> 
						
						<span style="color: red;" id="typeahead_error_msg"></span>
					      
						
                    </div>	
					
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> </label>			 
                  </div>
				
			 
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Format Type</label>
					<div class="col-sm-9">
						<select name="choose_format" id="choose_format" onChange="changeFormat(this.value)" class="col-xs-10 col-sm-5">							
							<option value="n">New Format</option>
							<option value="e">Existing</option>		
						</select>	
					</div>
				</div>
				
			 
				<div class="form-group" id="email_format_id" style="display: none;">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Choose Format</label>
					<div class="col-sm-9">
						<select name="ef_id" id="ef_id" onChange="changeEmailFormat(this.value)" class="col-xs-10 col-sm-5">							 
							<option>Select Email Format</option>
							<?//=$obj->getEmailFormat();?>
						</select>				
					</div>
					
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
					<div class="col-sm-9">
					<p> <label id="ef_id-error" style="display: none;" class="error" for="ef_id">Please select email format</label></p>
					</div>
				</div>
				
			 
				<div class="form-group" id="email_format_new">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email Format </label>
					<div class="col-sm-9">
					<textarea id="email_msg" name="email_msg" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['email_msg']);?></textarea>
					<?php /* 	
						require_once(FCKPATH.'fckeditor.php');
						$oFCKeditor = new FCKeditor('email_msg') ;
						$oFCKeditor->BasePath	=FCKPATH.'' ;
						//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
						$oFCKeditor->Height	= 300 ;
						$oFCKeditor->Width	= 700 ;	
						$oFCKeditor->Value	=html_entity_decode($dataEF['ef_email_formet']);								
						$oFCKeditor->Create(); 
					*/ ?>
						
					</div>	
					<div class="col-sm-3"></div>
					<div class="col-sm-6">
					<table class="table table-bordered table-hover">
						 
							<tbody>
							<h4><strong>You can use this variable to add new email format.</strong></h4>
							<tr>
							  <td>Member Name :</td>
							  <td><strong>%MEMBER_NAME%</strong></td>
							</tr>
							<tr>
							  <td>Reset Password Link : </td>
							  <td><strong>%RESET_LINK%</strong></td>
							</tr>
							<tr>
							  <td>Registration Link : </td>
							  <td><strong>%CLICK_TO_ACTIVATE_ACCOUNT%</strong></td>
							</tr>
							<tr>
							  <td>First Name : </td>
							  <td><strong>%FNAME%</strong></td>
							</tr>
							<tr>
							  <td>Last Name : </td>
							  <td><strong>%LNAME%</strong></td>
							</tr>
							<tr>
							  <td>Date Of Birth :</td>
							  <td><strong>%DOB%</strong></td>
							</tr>
							<tr>
							  <td>Country :</td>
							  <td><strong>%COUNTRY%</strong></td>
							</tr>
							<tr>
							  <td>State :</td>
							  <td><strong>%STATE%</strong></td>
							</tr>
							<tr>
							  <td>City :</td>
							  <td><strong>%CITY%</strong></td>
							</tr>
							<tr>
							  <td>Zip Code :</td>
							  <td><strong>%ZIPCODE%</strong></td>
							</tr>
							<tr>
							  <td>Address :</td>
							  <td><strong>%ADDRESS%</strong></td>
							</tr>
							<tr>
							  <td>Wallet Address :</td>
							  <td><strong>%WALLET_ADDRESS%</strong></td>
							</tr>
						  </tbody>
						</table>
					

					</div>
			 </div>
					
				 
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
					<input type="submit" name="submit" value="Send" class="btn btn-info">
					&nbsp; &nbsp; &nbsp; </div>
				</div>
				  
				  
                </form>
              </div>
              <!-- /.span -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
            <!-- PAGE CONTENT ENDS -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.page-content-area -->
    </div>
    <!-- /.page-content -->
  </div>
  <!-- /.main-content -->
  <?php include("page_includes/footer.php");?>
</div>
<?php include("page_includes/dashboard_footer_script.php");?>

<!-------$("#settings-form").submit(function(e){ }-------->

<script src="typeahead.min.js"></script>
<script>
	$(document).ready(function(){
		$('input.typeahead').typeahead({
			name: 'typeahead',
			remote:'emailresearch.php?key=%QUERY',
			limit : 10
		});
	});
</script>
 
<script type="text/javascript" src="js/jquery.validate.js"></script>		
 
<script language="javascript" type="text/javascript">	
	$().ready(function() {		 
		 $("#efEdit").validate({
				rules: 
				{					
					ef_id: "required",
					typeahead: "required"
				},
				messages: 
				{				
					ef_id: "Please select email format",
					typeahead: "Please type valid name or email Id."
				}	
				
			});
			// $('#typeahead_error_msg').hide();
	});			
</script>

<script language="javascript" type="text/javascript">
	
 function checkMemName(value) {
	 $('#typeahead-error').hide();
	jQuery.ajax({
		type: "POST",				 
		url: "get_member_email.php",
		data:{value:value},
		cache: false,				
		success: function(msg)	{
		
			if(msg == 0) {				
				document.getElementById('typeahead').value = '';	
				$('#typeahead_error_msg').html("Please type valid name or email Id.");					  	  		
			} else {
				$('#typeahead_error_msg').hide();
			}
			
				 	  
		}
	});
	 
 } 
 
 
 function changeEmailFormat(ef_id) {
	jQuery.ajax({
	type: "POST",				 
	 url: "get_email_format_type.php",
	 data:{ef_id:ef_id},
	cache: false,				
		success: function(html)	{
			document.getElementById('email_format_existing').innerHTML=html;		 	  
		}
	});
	 
 } 
	
 function changeSelection(member_type){
	//  alert(member_type);
	
	jQuery.ajax({
	type: "POST",				 
	 url: "get_email_format.php",
	 data:{member_type:member_type},
	cache: false,				
		success: function(html)	{
			document.getElementById('ef_id').innerHTML=html;		 	  
		}
	});
	
	
	
	if(member_type=='single')
	{
		$('#fld_single').show();
		 
	}
	else if(member_type=='all')
	{
		$('#fld_single').hide();
		 
	}
	else if(member_type=='sales')
	{
		$('#fld_single').hide();
		 
	}
	 
 } 
 
 function changeFormat(choose_format){
	// alert(member_type);
	if(choose_format=='e')
	{
		$('#email_format_id').show();
		$('#email_format_existing').show();
		$('#email_format_new').hide();
		 
	}
	else if(choose_format=='n')
	{
		$('#email_format_id').hide();
		$('#email_format_existing').hide();
		$('#email_format_new').show();
		 
	}
	 
 }
</script>



 
</body>
</html>
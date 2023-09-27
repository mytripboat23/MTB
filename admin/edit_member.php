<?php
require_once("../includes/connection.php");
adminSecure();
$u_login_id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['uId']));
if(isset($_REQUEST['return_url']))
{
	$return_url = urldecode($_REQUEST['return_url']);
}
else
{
	$return_url='show_members.php';
	//$return_url = urldecode($_REQUEST['return_url']);
}

$mode="add";
$table_caption="Add New Member";
$jqueryReq = 'true';

	if($u_login_id!="")
	{
		$mode="edit";
		$table_caption="Update Member Details";
		 
		$data = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","","ul.u_login_id=u.u_login_id and u.u_login_id='".$u_login_id."'",1);  
	}
	else
	{
		$data = $_POST;
		 
	}

if($_POST)
{
		
	if(!$err)
	{
		 
		
		if($u_login_id)
		{
			$data = $obj->selectData(TABLE_USER,"","u_login_id='".$u_login_id."' and user_status='Active'",1);
			$postEmail = $obj->selectData(TABLE_USER_LOGIN,"","u_login_id !='".$u_login_id."' and u_login_user_email='".$obj->filter_mysql($_POST['u_login_user_email'])."' and u_login_status<>'Deleted'",1);
			if($postEmail['u_login_id'])
			{
				$obj->add_message("message",'Email Already exists.');
				$_SESSION['messageClass'] = 'errorClass';
			}
			else
			{ 
		
				 
				$uloArr = array();	
				if(!empty($_POST['u_login_passwordd'])) $uloArr['u_login_password'] = password_hash($_POST['u_login_passwordd'],PASSWORD_DEFAULT); 		
				$uloArr['u_login_user_email']    	= $obj->filter_mysql($_POST['u_login_user_email']);		
				$uloArr['u_login_modified']  		= CURRENT_DATE_TIME;
				$uloArr['u_login_status']			= $_POST['u_login_status'];		
				$obj->updateData(TABLE_USER_LOGIN,$uloArr,"u_login_id='".$u_login_id."'"); 
				
				$uArrU = array();	
				$uArrU['user_dob'] 				= date('Y-m-d', strtotime($_POST['user_dob']));
				$uArrU['user_first_name'] 		= $obj->filter_mysql($_POST['user_first_name']);
				$uArrU['user_last_name'] 		= $obj->filter_mysql($_POST['user_last_name']);				
				$uArrU['user_email']    		= $obj->filter_mysql($_POST['u_login_user_email']);				
				$uArrU['user_addess_1'] 		= $obj->filter_mysql($_POST['user_addess_1']);
				$uArrU['user_city'] 			= $obj->filter_mysql($_POST['user_city']);
				$uArrU['user_zip'] 				= $obj->filter_mysql($_POST['user_zip']);
				$uArrU['user_state'] 			= $obj->filter_mysql($_POST['user_state']);
				$uArrU['user_country'] 			= $obj->filter_mysql($_POST['user_country']);
				$uArrU['user_status']			= $obj->filter_mysql($_POST['u_login_status']);	

				$obj->updateData(TABLE_USER,$uArrU,"u_login_id='".$u_login_id."'");
				
				$obj->add_message("message",UPDATE_SUCCESSFULL);
				$_SESSION['messageClass'] = 'successClass';
				$obj->reDirect($return_url);
			}	
			
			
		}
		else
		{
			 
			if($obj->selectData(TABLE_USER_LOGIN,"","u_login_user_email='".$obj->filter_mysql($_POST['u_login_user_email'])."' and u_login_status<>'Deleted'",1))
			{
				$obj->add_message("message",'Email Already exists.');
				$_SESSION['messageClass'] = 'errorClass';
			}
			else
			{
				$loguArr = array();	
				$loguArr['u_login_user_email'] 	= $obj->filter_mysql($_POST['u_login_user_email']);
				$loguArr['u_login_password']    = password_hash($_POST['u_login_passwordd'],PASSWORD_DEFAULT);
				$loguArr['u_login_created']		= CURRENT_DATE_TIME;
				$loguArr['u_login_modified']	= CURRENT_DATE_TIME;
				$loguArr['u_login_status']		= $obj->filter_mysql($_POST['u_login_status']);				
				$newUserId = $obj->insertData(TABLE_USER_LOGIN,$loguArr);	
				
				$uArr = array();	
				$uArr['user_first_name'] 		= $obj->filter_mysql($_POST['user_first_name']);
				$uArr['user_last_name'] 		= $obj->filter_mysql($_POST['user_last_name']);
				$uArr['user_email'] 			= $obj->filter_mysql($_POST['u_login_user_email']);				
				$uArr['user_addess_1'] 			= $obj->filter_mysql($_POST['user_addess_1']);
				$uArr['user_city']		 		= $obj->filter_mysql($_POST['user_city']);
				$uArr['user_zip'] 				= $obj->filter_mysql($_POST['user_zip']);
				$uArr['user_state'] 			= $obj->filter_mysql($_POST['user_state']);
				$uArr['user_country'] 			= $obj->filter_mysql($_POST['user_country']);
				$uArr['u_login_id'] 			= $newUserId;
				$uArr['user_dob'] 				= date('Y-m-d', strtotime($_POST['user_dob']));
				$uArr['user_status']			= $obj->filter_mysql($_POST['u_login_status']);
				$uArr['user_created']			= CURRENT_DATE_TIME;
				$uArr['user_modified']			= CURRENT_DATE_TIME;
				$obj->insertData(TABLE_USER,$uArr);				
				
				$obj->add_message("message",ADD_SUCCESSFULL);
				$_SESSION['messageClass'] = 'successClass';
				$obj->reDirect($return_url);
			}

		}
		
	}
}

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
        <li class="active">Dashboard</li>
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
			
			<h2><?=$data['user_first_name'];?></h2>
		
			
            <div class="hr hr32 hr-dotted"></div>
			<?php include("page_includes/member_tabs.php");?>
            <div class="row">
              <div class="col-xs-12">
               
				<form class="form-horizontal" role="form" name="userEdit" id="userEdit" method="post" action="" enctype="multipart/form-data">
				 <div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <strong>Account Details:</strong> </label></div>                  
				  
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> First Name </label>
					<div class="col-sm-9">
					  <input type="text" id="user_first_name" placeholder="Member First Name" class="col-xs-10 col-sm-5" name="user_first_name" value="<?=htmlentities($data['user_first_name']);?>" validate="required:true"/>
					</div>
				</div>
				
				 <div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Last Name </label>
					<div class="col-sm-9">
					  <input type="text" id="user_last_name" placeholder="Member Last Name" class="col-xs-10 col-sm-5" name="user_last_name" value="<?=htmlentities($data['user_last_name']);?>" validate="required:true"/>
					</div>
				</div>
				
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email </label>
					<div class="col-sm-9">
				<input type="text" id="u_login_user_email" placeholder="Email" class="col-xs-10 col-sm-5" name="u_login_user_email"  value="<?=htmlentities($data['u_login_user_email']);?>"  />
					</div>
				</div>
				   
				 
                 
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password</label>
					<div class="col-sm-9">
					  <input type="password" id="u_login_passwordd" placeholder="Password" class="col-xs-10 col-sm-5" name="u_login_passwordd"  value="" validate="required:true"/>
					</div>
				</div>		

				<div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Date of Birth </label>
                    <div class="col-sm-9">
                      <input type="text" id="user_dob" placeholder="Date of Birth" class="col-xs-10 col-sm-5" name="user_dob" value="<?=htmlentities($data['user_dob']);?>" validate="required:true"/>
                    </div>
                  </div>
			 
				<div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address </label>
                    <div class="col-sm-9">
                      <input type="text" id="user_addess_1" placeholder="Address" class="col-xs-10 col-sm-5" name="user_address_1" value="<?=htmlentities($data['user_address_1']);?>" validate="required:true"/>
                    </div>
                 </div>
				  
				  
				   
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> City </label>
                    <div class="col-sm-9">
                      <input type="text" id="user_city" placeholder="City" class="col-xs-10 col-sm-5" name="user_city" value="<?=htmlentities($data['user_city']);?>" validate="required:true"/>
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> State </label>
                    <div class="col-sm-9">
                      <input type="text" id="user_state" placeholder="State" class="col-xs-10 col-sm-5" name="user_state" value="<?=htmlentities($data['user_state']);?>" validate="required:true"/>
                    </div>
                  </div>
			  
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Country </label>
                    <div class="col-sm-9">                       
					<select class="col-xs-10 col-sm-5" id="user_country" name="user_country">
						<?=$obj->countrySelect(htmlentities($data['user_country']));?>
					</select>
                    </div>
                  </div>  
				  
				  
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Zip </label>
                    <div class="col-sm-9">
                      <input type="text" id="user_zip" placeholder="Zip" class="col-xs-10 col-sm-5" name="user_zip" value="<?=htmlentities($data['user_zip']);?>" validate="required:true"/>
                    </div>
                  </div>
				  
				  
				   
				<?php /* 				
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone </label>
                    <div class="col-sm-9">
                      <input type="text" id="user_phone" placeholder="Phone" class="col-xs-10 col-sm-5" name="user_phone" value="<?=$data['user_phone'];?>" validate="required:true"/>
                    </div>
                  </div>
				   */ ?>
				   
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
				<div class="col-sm-9">
				<select name="u_login_status" class="col-xs-10 col-sm-5">
				  <option value="Registered" <?=$data['u_login_status']=="Registered"?'selected="selected"':'';?>>Registered</option>
				  <option value="Active"  <?=$data['u_login_status']=="Active"?'selected="selected"':'';?>>Active</option>
					<option value="Inactive"  <?=$data['u_login_status']=="Inactive"?'selected="selected"':'';?>>Inactive</option>
				</select>	
				</div>
				</div>
				  
				<div class="clearfix form-actions">
				<div class="col-md-offset-3 col-md-9">
				  <input type="submit" name="submit" value="Save" class="btn btn-info">
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
 
<script>
	$(function() {
		$( "#user_dob" ).datepicker({
			changeYear: true ,
			yearRange : 'c-75:c0',
			changeMonth: true ,	 
			dateFormat: 'yy-mm-dd',	 
			todayBtn: 'linked'
		});
	});
</script>

<script type="text/javascript" src="js/jquery.validate.js"></script>	 
<script language="javascript" type="text/javascript">	
$().ready(function() {
	$("#userEdit").validate({
			rules: {
				// user_first_name: "required",
				// user_last_name: "required",	
				u_login_user_email: "required",
				u_login_username: "required",				
				//u_login_password: "required"
			},
			messages: {
				// user_first_name: "Please enter your firstname",
				// user_last_name: "Please enter your lastname",
				u_login_user_email: "Please enter your email",
				u_login_username: "Please enter your username",		
				//u_login_password: "Please enter password"
			}
		});
});			
</script>
</body>
</html>
<?php
require_once("../includes/connection.php");
adminSecure();
$admin_id = $obj->filter_mysql($obj->filter_numeric($_REQUEST['uId']));
if(isset($_REQUEST['return_url']))
{
	$return_url = urldecode($_REQUEST['return_url']);
}
else
{
	//$return_url='show_member.php';
	$return_url = urldecode($_REQUEST['return_url']);
}

$mode="add";
$table_caption="Add New Sub Admin";
$jqueryReq = 'true';

	if($admin_id!="")
	{
		$mode="edit";
		$table_caption="Update Sub Admin Details";
		$data = $obj->selectData(TABLE_ADMIN,"","admin_id='".$admin_id."' and admin_status='Active'",1);
		 
	}
	else
	{
		$data = $_POST;
		 
	}

if($_POST)
{		
	if(!$err)
	{		 
		if($admin_id)
		{	
			if(!empty(htmlentities($_POST['admin_passwordd']))) 
			{ 
			$options = ['cost' => 12];
			$arrSI['admin_password']  = password_hash($_POST['admin_passwordd'],PASSWORD_BCRYPT, $options);
			} 
			$arrSI['admin_name']  		= $obj->filter_mysql($_POST['admin_name']);
			$arrSI['admin_email']  		= $obj->filter_mysql($_POST['admin_email']);
			$arrSI['admin_username']  	= $obj->filter_mysql($_POST['admin_username']);
			$arrSI['admin_phone']  		= $obj->filter_mysql($_POST['admin_phone']);
			$arrSI['admin_created']		= CURRENT_DATE_TIME;
			$arrSI['admin_modified']	= CURRENT_DATE_TIME;			
			$obj->updateData(TABLE_ADMIN,$arrSI,"admin_id='".$admin_id."'");			
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($return_url);
		}
		else
		{
			 
			if($obj->selectData(TABLE_ADMIN,"","admin_email='".$obj->filter_mysql($_POST['admin_email'])."' and admin_status<>'Deleted'",1))
			{
				$obj->add_message("message",'Email Already exists.');
				$_SESSION['messageClass'] = 'errorClass';
			}
			else
			{
				$arrSU['admin_name']  		= $obj->filter_mysql($_POST['admin_name']);
				$arrSU['admin_email']  		= $obj->filter_mysql($_POST['admin_email']);
				$arrSU['admin_username']  	= $obj->filter_mysql($_POST['admin_username']);
				$arrSU['admin_phone']  		= $obj->filter_mysql($_POST['admin_phone']);
				
				$arrSU['admin_password']  	= password_hash($_POST['admin_passwordd'],PASSWORD_DEFAULT);	 
				$arrSU['admin_created']		= CURRENT_DATE_TIME;
				$arrSU['admin_modified']	= CURRENT_DATE_TIME;				
				$obj->insertData(TABLE_ADMIN,$arrSU);
				
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
            <div class="hr hr32 hr-dotted"></div>
			
            <div class="row">
              <div class="col-xs-12">
               
				<form class="form-horizontal" role="form" name="userEdit" id="userEdit" method="post" action="" enctype="multipart/form-data">
				
				<div class="form-group"><label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <strong>Account Details:</strong> </label></div>                  
				  
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>
					<div class="col-sm-9">
					  <input type="text" id="admin_name" placeholder="Sub Admin name" class="col-xs-10 col-sm-5" name="admin_name" value="<?=$data['admin_name'];?>" validate="required:true"/>
					</div>
				</div>
				
				 
				
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email </label>
					<div class="col-sm-9">
				<input type="text" id="admin_email" placeholder="Email" class="col-xs-10 col-sm-5" name="admin_email"  value="<?=$data['admin_email'];?>" <?php if($data['admin_email']!=""){?>readonly="true"<?php }?>/>
					</div>
				</div>
				
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> User Name </label>
					<div class="col-sm-9">
				<input type="text" id="admin_username" placeholder="Username" class="col-xs-10 col-sm-5" name="admin_username"  value="<?=$data['admin_username'];?>" />
					</div>
				</div>
				   
				 
                 
				<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Password</label>
					<div class="col-sm-9">
					  <input type="password" id="admin_password" placeholder="Password" class="col-xs-10 col-sm-5" name="admin_passwordd" value="" validate="required:true"/>
					</div>
				</div>		
 
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone </label>
					<div class="col-sm-9">
						<input type="text" id="admin_phone" placeholder="Phone" class="col-xs-10 col-sm-5" name="admin_phone" value="<?=$data['admin_phone'];?>" validate="required:true"/>
					</div>
				</div>
				   
				   
				   
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
					<div class="col-sm-9">
						<select name="admin_status" class="col-xs-10 col-sm-5">				  
							<option value="Active"  <?= $data['admin_status']=="Active"?'selected="selected"':'';?>>Active</option>
							<option value="Inactive"  <?= $data['admin_status']=="Inactive"?'selected="selected"':'';?>>Inactive</option>
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
				admin_name: "required",
				user_last_name: "required",	
				admin_email: "required",
				admin_username: "required",				
				//admin_password: "required"
			},
			messages: {
				admin_name: "Please enter your name",
				user_last_name: "Please enter your lastname",
				admin_email: "Please enter your email",
				admin_username: "Please enter your username",		
				//admin_password: "Please enter password"
			}
		});
});			
</script>
</body>
</html>
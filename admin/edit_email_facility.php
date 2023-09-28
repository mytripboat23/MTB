<?php
require_once("../includes/connection.php");
adminSecure();
$ef_id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['efId']));
if(isset($_REQUEST['return_url']))
{
	$return_url = urldecode($_REQUEST['return_url']);
}
else
{
	$return_url='show_email_facility.php';
}

$mode="add";
$table_caption="Add New Email Format";
$jqueryReq = 'true';

	if($ef_id!="")
	{
		$mode="edit";
		$table_caption="Update Email Format";
		$data = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$ef_id."'",1);
	}
	else
	{
		$data = $_POST;
	}

if($_POST)
{ 
	if(!$err)
	{

		if($ef_id)
		{
			$emailArr['ef_title'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['ef_title'])));
			$emailArr['ef_email_formet'] 	= $obj->filter_mysql(addslashes(stripslashes($_POST['ef_email_formet'])));
			$emailArr['ef_modified']		= CURRENT_DATE_TIME;
			$obj->updateData(TABLE_EMAIL_FACILITY,$emailArr,"ef_id='".$ef_id."'");
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($return_url);
		}
		else
		{	
			$emailArru['ef_title'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['ef_title'])));
			$emailArru['ef_email_formet'] 	= $obj->filter_mysql(addslashes(stripslashes($_POST['ef_email_formet'])));
			$emailArru['ef_modified']		=CURRENT_DATE_TIME;
			$obj->insertData(TABLE_BANNER_ADS,$emailArru);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($return_url);			 
			 
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
        <li> <a href="#">Email Format</a></li>
		<li class="active"><?=$data['ef_title'];?></li>
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
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title: </label>
					<div class="col-sm-9">
					  <input type="text" id="ef_title" name="ef_title" required placeholder="Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['ef_title']);?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email Formet </label>
					<div class="col-sm-9">
					<textarea id="ef_email_formet" name="ef_email_formet" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['ef_email_formet']);?></textarea>
					</div>
					
					<?php 	//echo html_entity_decode($data['ef_email_formet']);
						/* 
						require_once(FCKPATH.'fckeditor.php');
						$oFCKeditor = new FCKeditor('ef_email_formet') ;
						$oFCKeditor->BasePath	=FCKPATH.'' ;
						//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
						$oFCKeditor->Height	= 500 ;
						$oFCKeditor->Width	= 700 ;	
						$oFCKeditor->Value	=html_entity_decode($data['ef_email_formet']);								
						$oFCKeditor->Create();
						*/ 
					?>
					
					<div class="col-sm-3"></div>
					<div class="col-sm-6">
					<table class="table table-bordered table-hover">
					 
						<tbody>
						<h4><strong>You can use this variable to add new email format.</strong></h4>
						<tr>
						  <td>Name :</td>
						  <td><strong>%NAME%</strong></td>
						</tr>
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
				  
				
				<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
					<div class="col-sm-9">
						<select name="ef_status" class="col-xs-10 col-sm-5">
							<option value="Active"  <?=$data['ef_status']=="Active"?'selected="selected"':'';?>>Active</option>
							<option value="Inactive"  <?=$data['ef_status']=="Inactive"?'selected="selected"':'';?>>Inactive</option>
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
		<script type="text/javascript" src="js/jquery.validate.js"></script>		
	<!--	<script type="text/javascript" src="js/jquery_metadata.js"></script>
		<script language="javascript" type="text/javascript">		
		jQuery.metadata.setType("attr", "validate");		
		</script>-->	
		<script language="javascript" type="text/javascript">	
		$().ready(function() {
			$("#bannerAdsEdit").validate({
					rules: 
					{
						ef_title: "required",
						ef_code: "required"
					},
					messages: 
					{
						ef_first_name: "Please enter Videos Title",
						ef_code: "Please enter Video Code"
				});
		});			
</script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
 <link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
 <script>
$(function() {
$( "#ef_date" ).datepicker({
	dateFormat: 'yy-mm-dd'
});
$("#ef_time").timepicker({
	timeFormat: 'H:m:s'
});

});
</script>
</body>
</html>
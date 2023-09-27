<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);
 

$redirect_url="show_sub_admin_pages.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['cId']));
$mode="add";
$table_caption="Add New Sub Admin Pages";
$data=$obj->selectData(TABLE_SUB_ADMIN_PAGES,"","sap_id='".$id."' and sap_status<>'Deleted'",1);
 
  
if($data)
{
	$mode="edit";
	$table_caption="Update Sub Admin Pages";
}
if($_POST)
{
 
	switch($mode)
	{
		case 'add':

		if(!$err){
			
			if($obj->selectData(TABLE_SUB_ADMIN_PAGES,"","admin_id='".$obj->filter_mysql($obj->filter_numeric($_POST['admin_id']))."' and sap_status<>'Deleted'",1))
			{
				$obj->add_message("message",'Sub Admin Already exists.');
				$_SESSION['messageClass'] = 'errorClass';
			}
				else
			{ 			
				$arrA['page_id'] = "".$obj->filter_mysql(implode(",",$_POST['page_id']))."";
				$arrA['admin_id'] = $obj->filter_mysql($_POST['admin_id']);
				$arrA['sap_modified'] = CURRENT_DATE_TIME;
 				$arrA['sap_created'] = CURRENT_DATE_TIME;
				$com_id=$obj->insertData(TABLE_SUB_ADMIN_PAGES,$arrA);
				$obj->add_message("message",ADD_SUCCESSFULL);
				$_SESSION['messageClass'] = 'successClass';
				$obj->reDirect($redirect_url);
			}
		}
		break;
		
		case 'edit':
		
		if(!$err){	 
				$arrAU['admin_id'] = $obj->filter_mysql($_POST['admin_id']);	
				$arrAU['page_id'] = "".$obj->filter_mysql(implode(",",$_POST['page_id']))."";			 
				$arrAU['sap_modified'] = CURRENT_DATE_TIME;
				$obj->updateData(TABLE_SUB_ADMIN_PAGES,$arrAU,"sap_id='".$id."'");			 
				$obj->add_message("message",UPDATE_SUCCESSFULL);
				$_SESSION['messageClass'] = 'successClass';
				$obj->reDirect($redirect_url);
			 
		}
		break;
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
        <li class="active">Set Admin Pages</li>
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i><?=$table_caption;?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                <form class="form-horizontal" role="form" name="pagetEdit" id="pagetEdit" method="post" action="" enctype="multipart/form-data">
                  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Sub Admin </label>
                    <div class="col-sm-9">					   
						<select id="admin_id" name="admin_id" class="col-xs-10 col-sm-5">
							<?=$obj->subAdminSelect($data['admin_id']);?>
						</select>
                    </div>
                  </div>
				  
				  
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page </label>
					
						<div class="col-sm-9"><?=$obj->getAllPages($data['page_id']);?></div>
					 
                  </div>
				  
				 
				 <?php /* ?>
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
                    <div class="col-sm-9">
					<select name="page_status" class="col-xs-10 col-sm-5">
					  <option value="Active" <?=$data['page_status']=="Active"?'selected="selected"':'';?>>Active</option>
					  <option value="Inactive" <?=$data['page_status']=="Inactive"?'selected="selected"':'';?>>Inactive</option>
					</select>	
                    </div>
                  </div>
				  <?php */ ?>
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


<script language="javascript" type="text/javascript">	
	$().ready(function() {
		$("#pagetEdit").validate({
				rules: {
					admin_id: "required"					 
				},
				messages: {
					admin_id: "Please select Sub Admin."						 
				}
			});
	});			
</script>	





	
</body>
</html>
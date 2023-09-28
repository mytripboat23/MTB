<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_non_facility.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id = $obj->filter_mysql($obj->filter_numeric($_REQUEST['fId']));
$mode="add";
$table_caption="Add New Non Facility";
$data=$obj->selectData(TABLE_FACILITY_EXC,"","face_id='".$id."' and face_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit Non Facility";
}
if($_POST)
{  

	switch($mode)
	{
		case 'add':

		if(!$err){
			$arrA['face_title']   = $obj->filter_mysql($_POST['face_title']); 		 
			$arrA['face_added']   = CURRENT_DATE_TIME;
			$arrA['face_updated'] = CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_FACILITY_EXC,$arrA);
			$obj->add_message("message","Non Facility added successfully!");
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$arrU['face_title'] = $obj->filter_mysql($_POST['face_title']); 
			$arrU['face_updated'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_FACILITY_EXC,$arrU," face_id='".$id."'");		 	
			$obj->add_message("message","Non Facility updated successfully!");
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
        <li class="active">Non Facility</li>
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
                <form class="form-horizontal" role="form" name="contentEdit" id="contentEdit" method="post" action="" enctype="multipart/form-data">
                
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Non Facility </label>
                    <div class="col-sm-9">
					  <input type="text" id="face_title" name="face_title" placeholder="Facility" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['face_title']);?>" />
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
</body>
</html>
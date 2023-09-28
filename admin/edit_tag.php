<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_tags.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id = $obj->filter_mysql($obj->filter_numeric($_REQUEST['fId']));
$mode="add";
$table_caption="Add New Tag";
$data=$obj->selectData(TABLE_TAGS,"","tag_id='".$id."' and tag_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit Tag";
}
if($_POST)
{  

	switch($mode)
	{
		case 'add':

		if(!$err){
			$arrA['tag_title']   = $obj->filter_mysql($_POST['tag_title']); 
			$arrA['tag_status']   = $_POST['tag_status']; 		 
			$arrA['tag_added']   = CURRENT_DATE_TIME;
			$arrA['tag_updated'] = CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_TAGS,$arrA);
			$obj->add_message("message","Tag added successfully!");
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$arrU['tag_title'] = $obj->filter_mysql($_POST['tag_title']); 
			$arrU['tag_status']   = $_POST['tag_status']; 
			$arrU['tag_updated'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_TAGS,$arrU," tag_id='".$id."'");		 	
			$obj->add_message("message","Tag updated successfully!");
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
        <li class="active">Tags</li>
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
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Tag </label>
                    <div class="col-sm-9">
					  <input type="text" id="tag_title" name="tag_title" placeholder="Tag" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['tag_title']);?>" />
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
                    <div class="col-sm-9">
						<select name="tag_status" class="col-xs-10 col-sm-5">					  
							<option value="Active"  <?= htmlentities($data['tag_status'])=="Active"?'selected="selected"':'';?>>Active</option>
							<option value="Inactive"  <?= htmlentities($data['tag_status'])=="Inactive"?'selected="selected"':'';?>>Inactive</option>
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
</body>
</html>
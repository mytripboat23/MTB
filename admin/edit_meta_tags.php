<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_meta_tags.php";
if($return_url)
{
	$redirect_url=$return_url;
}
$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['qId']));
$mode="add";
$table_caption="Add New Meta Tags";
$data=$obj->selectData(TABLE_META_TAGS,"","meta_id='".$id."' and meta_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit Meta Tags Details";
}
if($_POST)
{ 
	switch($mode)
	{
		case 'add':

		if(!$err){
			$arrA['meta_title'] = $obj->filter_mysql($_POST['meta_title']);
			$arrA['meta_description'] = $obj->filter_mysql($_POST['meta_description']);
			$arrA['meta_keywords'] = $obj->filter_mysql($_POST['meta_keywords']);
			$arrA['meta_author'] = $obj->filter_mysql($_POST['meta_author']);
			$arrA['meta_created'] = CURRENT_DATE_TIME;
			$arrA['meta_modified'] = CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_META_TAGS,$arrA);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$arrU['meta_title'] = $obj->filter_mysql($_POST['meta_title']);
			$arrU['meta_description'] = $obj->filter_mysql($_POST['meta_description']);
			$arrU['meta_keywords'] = $obj->filter_mysql($_POST['meta_keywords']);
			$arrU['meta_author'] = $obj->filter_mysql($_POST['meta_author']);
			$arrU['meta_modified'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_META_TAGS,$arrU," meta_id='".$id."'");
		 	
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
        <li class="active">Meta Tags</li>
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
		<?php
			$myvalue = $data['meta_page'];
			$arr = explode('.',trim($myvalue));
			 $fword =  $arr[0]; 
			 
		?>
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i><?=$table_caption;?> >> <?=$fword;?></small> </h1>
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
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Title </label>
                    <div class="col-sm-9">
					  <input type="text" id="meta_title" name="meta_title" placeholder="Meta Title" class="col-xs-10 col-sm-5" value="<?=$data['meta_title'];?>" />
                    </div>
                  </div>
				 
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description</label>
                    <div class="col-sm-9">
					  <input type="text" id="meta_description" name="meta_description" placeholder="Meta Description" class="col-xs-10 col-sm-5" value="<?=$data['meta_description'];?>" />
                    </div>
                  </div>
				  
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Keywords</label>
                    <div class="col-sm-9">
					  <input type="text" id="meta_keywords" name="meta_keywords" placeholder="Meta Keywords" class="col-xs-10 col-sm-5" value="<?=$data['meta_keywords'];?>" />
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Author</label>
                    <div class="col-sm-9">
					  <input type="text" id="meta_author" name="meta_author" placeholder="Meta Author" class="col-xs-10 col-sm-5" value="<?=$data['meta_author'];?>" />
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
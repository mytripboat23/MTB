<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_contents.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['cId']));
$mode="add";
$table_caption="Add New Content";
$data=$obj->selectData(TABLE_CONTENT,"","content_id='".$id."' and content_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit Content Details";
}
if($_POST)
{

		if($_FILES['cphoto1']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('cphoto1', "../".CONTENT, 'gif,jpg,png,jpeg,pdf');
			if($error)
			{
				$msg=$error;
				$err=1;
			}
			else
			{
				$_POST['content_banner']=$fileName;
			}
		}
		

	switch($mode)
	{
		case 'add':

		if(!$err){
			$arrA['content_header'] = $obj->filter_mysql($_POST['content_header']); 
			$arrA['content_descr'] = $obj->filter_mysql($_POST['content_descr']);
			$arrA['content_added'] = CURRENT_DATE_TIME;
			$arrA['content_modified'] = CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_CONTENT,$arrA);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$arrU['content_header'] = $obj->filter_mysql($_POST['content_header']); 
			$arrU['content_descr'] = $obj->filter_mysql($_POST['content_descr']);
			$arrU['content_modified'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_CONTENT,$arrU,"content_id='".$id."'");
			//$tab_ids=$_POST['tab_ids'];
					
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
        <li class="active">CMS</li>
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
                 
				 <?php  /*
				 
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Page Title </label>
                    <div class="col-sm-9">
					  <input type="text" id="content_title" name="content_title" placeholder="Content Title" class="col-xs-10 col-sm-5" value="<?=$data['content_title'];?>" />
                    </div>
                  </div>
				 
				 */ ?>
				  
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Content Heading </label>
                    <div class="col-sm-9">
                      <input type="text" id="content_header" name="content_header" placeholder="Content Heading" class="col-xs-10 col-sm-5" value="<?=$data['content_header'];?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Content Description </label>
                    <div class="col-sm-9">
					<textarea id="content_descr" name="content_descr" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['content_descr']);?></textarea>
					<?php	/*
					require_once(FCKPATH.'fckeditor.php');
					$oFCKeditor = new FCKeditor('content_descr') ;
					$oFCKeditor->BasePath	=FCKPATH.'' ;
					//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
					$oFCKeditor->Height	= 500 ;
					$oFCKeditor->Width	= 700 ;	
					$oFCKeditor->Value	=html_entity_decode($data['content_descr']);								
					$oFCKeditor->Create(); 
					*/ ?>      
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
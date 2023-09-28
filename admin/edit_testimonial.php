<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_testimonial.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['cId']));
$mode="add";
$table_caption="Add New Testimonial";
$data=$obj->selectData(TABLE_TESTIMONIAL,"","test_id='".$id."' and test_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit testimonial Details";
}
if($_POST)
{

		if($_FILES['cphoto1']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('cphoto1', "../".TESTIMONIAL, 'gif,jpg,png,jpeg,pdf');
			if($error)
			{
				$msg=$error;
				$err=1;
				$obj->add_message("message",$msg);
			}
			else
			{
				$testAr['test_image']=$fileName;
			}
		}
		

	switch($mode)
	{
		case 'add':

		if(!$err){
			 
			$testAr['test_title'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['test_title'])));
			$testAr['test_desc'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['test_desc'])));
			$testAr['test_by'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['test_by'])));
			$testAr['test_from'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['test_from'])));
			$testAr['test_added'] 		= CURRENT_DATE_TIME;
			$testAr['test_modified'] 	= CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_TESTIMONIAL,$testAr);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$testAr['test_title'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['test_title'])));
			$testAr['test_desc'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['test_desc'])));
			$testAr['test_by'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['test_by'])));
			$testAr['test_from'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['test_from'])));
			$testAr['test_modified'] 	= CURRENT_DATE_TIME;
			$obj->updateData(TABLE_TESTIMONIAL,$testAr," test_id='".$id."'");					
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
        <li class="active">Term & Condition</li>
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
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Title </label>
                    <div class="col-sm-9">
					  <input type="text" id="test_title" name="test_title" placeholder="Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['test_title']);?>" />
                    </div>
                  </div>
				  
 
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Description </label>
                    <div class="col-sm-9">
					<textarea id="test_desc" name="test_desc" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['test_desc']);?></textarea>
					<?php 	/*
						require_once(FCKPATH.'fckeditor.php');
						$oFCKeditor = new FCKeditor('test_desc') ;
						$oFCKeditor->BasePath	=FCKPATH.'' ;
						//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
						$oFCKeditor->Height	= 300 ;
						$oFCKeditor->Width	= 700 ;	
						$oFCKeditor->Value	=html_entity_decode($data['test_desc']);								
						$oFCKeditor->Create(); 
					*/ ?>      
                    </div>
                  </div>
                 
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Testimonial By </label>
                    <div class="col-sm-9">
					  <input type="text" id="test_by" name="test_by" placeholder="John Doe" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['test_by']);?>" />
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Address </label>
                    <div class="col-sm-9">
					  <input type="text" id="test_from" name="test_from" placeholder="Address" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['test_from']);?>" />
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Image: </label>
                    <div class="col-sm-9">
					<input type="file" name="cphoto1" id="cphoto1" class="col-xs-10 col-sm-5" >
					<?php if($data['test_image']){?>
					<div class="clear"></div>
					<?=$obj->getImageThumb(TESTIMONIAL,$data['test_image'],$data['test_image'],'','200','75','../');?>
					<?php }?>
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
<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_announcement.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['cId']));
$mode="add";
$table_caption="Add Announcement";
$data=$obj->selectData(TABLE_ANNOUNCEMENT,"","ann_id='".$id."' and ann_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit Announcement Details";
}
if($_POST)
{

		 

	switch($mode)
	{
		case 'add':

		if(!$err){
			$_POST['ann_title'] = $obj->filter_mysql($_POST['ann_title']);
			$_POST['ann_descr'] = $obj->filter_mysql($_POST['ann_descr']);
			$_POST['ann_created'] = CURRENT_DATE_TIME;
			$_POST['ann_modified'] = CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_ANNOUNCEMENT,$_POST);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$_POST['ann_title'] = $obj->filter_mysql($_POST['ann_title']);
			$_POST['ann_descr'] = $obj->filter_mysql($_POST['ann_descr']);
			$_POST['ann_modified'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_ANNOUNCEMENT,$_POST," ann_id='".$id."'");
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
        <li class="active">Announcement</li>
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
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title </label>
                    <div class="col-sm-9">
						<input type="text" id="ann_title" name="ann_title" placeholder="Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['ann_title']);?>" />
                    </div>
                  </div>
				   
		 
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description </label>
                    <div class="col-sm-9">
					<textarea id="ann_descr" name="ann_descr" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['ann_descr']);?></textarea>
						<?php /*	
							require_once(FCKPATH.'fckeditor.php');
							$oFCKeditor = new FCKeditor('ann_descr') ;
							$oFCKeditor->BasePath	=FCKPATH.'' ;
							//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
							$oFCKeditor->Height	= 300 ;
							$oFCKeditor->Width	= 700 ;	
							$oFCKeditor->Value	=html_entity_decode($data['ann_descr']);								
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
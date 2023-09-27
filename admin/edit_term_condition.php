<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_term_condition.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['cId']));
$mode="add";
$table_caption="Add New Term & Condition";
$data=$obj->selectData(TABLE_TERM_COND,"","tc_id='".$id."' and tc_status<>'Deleted'",1);
  
if($data)
{
	$mode="edit";
	$table_caption="Edit Term & Conditions Details";
}
if($_POST)
{


	switch($mode)
	{
		case 'add':

		if(!$err){
			$testAr['tc_title'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['tc_title'])));
			$testAr['tc_header'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['tc_header'])));
			$testAr['tc_desc'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['tc_desc'])));
			$testAr['tc_added'] 		= CURRENT_DATE_TIME;
			$testAr['tc_modified'] 		= CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_TERM_COND,$testAr);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			$testAru['tc_title'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['tc_title'])));
			$testAru['tc_header'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['tc_header'])));
			$testAru['tc_desc'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['tc_desc'])));
			$testAru['tc_modified'] 	= CURRENT_DATE_TIME;
			$obj->updateData(TABLE_TERM_COND,$testAru," tc_id='".$id."'");
			  	
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
                  <?php /* ?>
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Package: </label>
                    <div class="col-sm-9">
					<select name="pack_id" class="col-xs-10 col-sm-5">
					   <?=$obj->get_package_selected($data['pack_id']);?>
					</select>	
                    </div>
                  </div>
				  <?php */ ?>
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Title </label>
                    <div class="col-sm-9">
					  <input type="text" id="tc_title" name="tc_title" placeholder="Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['tc_title']);?>" />
                    </div>
                  </div>
			 
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Heading </label>
                    <div class="col-sm-9">
                      <input type="text" id="tc_header" name="tc_header" placeholder="Heading" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['tc_header']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Description </label>
                    <div class="col-sm-9">
					<textarea id="tc_desc" name="tc_desc" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['tc_desc']);?></textarea>
					<?php /*  	
					require_once(FCKPATH.'fckeditor.php');
					$oFCKeditor = new FCKeditor('tc_desc') ;
					$oFCKeditor->BasePath	=FCKPATH.'' ;
					//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
					$oFCKeditor->Height	= 500 ;
					$oFCKeditor->Width	= 700 ;	
					$oFCKeditor->Value	=html_entity_decode($data['tc_desc']);								
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
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
//$tabs=$obj->selectData(TABLE_CONTENT_TABS,"","content_id='".$id."' and tab_status='Active' order by tab_order","");

if($data)
{
	$mode="edit";
	$table_caption="Edit Content Details";
}
if($_POST)
{

		if($_FILES['cbimage']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('cbimage', "../".CBANNER, 'gif,jpg,png,jpeg,pdf');
			if($error)
			{
				$msg=$error;
				$err=1;
			}
			else
			{
				$arrA['content_banner']=$fileName;
				$arrU['content_banner']=$fileName;
			}
		}
		

	switch($mode)
	{
		case 'add':

		if(!$err){
			$arrA['content_header'] = $obj->filter_mysql($_POST['content_header']); 
			$arrA['content_descr']  = htmlentities($_POST['content_descr']);
			
			$arrA['content_m_title']   = $obj->filter_mysql($_POST['content_m_title']);
			$arrA['content_m_desc']    = $obj->filter_mysql($_POST['content_m_desc']);
			$arrA['content_m_keyword'] = $obj->filter_mysql($_POST['content_m_keyword']);
			$arrA['content_m_author']  = $obj->filter_mysql($_POST['content_m_author']);
			
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
			$arrU['content_descr']  = htmlentities($_POST['content_descr']);
			
			$arrA['content_m_title']   = $obj->filter_mysql($_POST['content_m_title']);
			$arrA['content_m_desc']    = $obj->filter_mysql($_POST['content_m_desc']);
			$arrA['content_m_keyword'] = $obj->filter_mysql($_POST['content_m_keyword']);
			$arrA['content_m_author']  = $obj->filter_mysql($_POST['content_m_author']);
			
			$arrU['content_modified'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_CONTENT,$arrU," content_id='".$id."'");
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
                 
					<?php /*   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Content Page  </label>
                    <div class="col-sm-9">
					  <input type="text" id="content_page" name="content_page" placeholder="Content Title" class="col-xs-10 col-sm-5" value="<?=$data['content_page'];?>" />
                    </div>
                  </div>  

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
                      <input type="text" id="content_header" name="content_header" placeholder="Content Heading" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['content_header']);?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Content Description </label>
                    <div class="col-sm-9">
					<textarea id="content_descr" name="content_descr" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['content_descr']);?></textarea>
       
                    </div>
                  </div>
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Banner </label>
                    <div class="col-sm-9">
                      <input type="file" id="cbimage" class="col-xs-10 col-sm-5" name="cbimage"/>
					  </div>
					   <div class="col-sm-9">
					   <?php if(isset($data['content_banner'])){?>
			 	 			<br><img src="../<?=CBANNER?><?=$data['content_banner']?>" width="250">
			  		   <?php }?>
                    </div>
                  </div>
               	  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Title </label>
                    <div class="col-sm-9">
					  <input type="text" id="content_m_title" name="content_m_title" placeholder="Meta Title" class="col-xs-10 col-sm-5" value="<?=$data['content_m_title'];?>" />
                    </div>
                  </div>
				 
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Description</label>
                    <div class="col-sm-9">
					  <input type="text" id="content_m_desc" name="content_m_desc" placeholder="Meta Description" class="col-xs-10 col-sm-5" value="<?=$data['content_m_desc'];?>" />
                    </div>
                  </div>
				  
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Keywords</label>
                    <div class="col-sm-9">
					  <input type="text" id="content_m_keyword" name="content_m_keyword" placeholder="Meta Keywords" class="col-xs-10 col-sm-5" value="<?=$data['content_m_keyword'];?>" />
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Author</label>
                    <div class="col-sm-9">
					  <input type="text" id="content_m_author" name="content_m_author" placeholder="Meta Author" class="col-xs-10 col-sm-5" value="<?=$data['content_m_author'];?>" />
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
<script src="../ckeditor/ckeditor.js"></script>
<script language="javascript">
    window.onload = function() {
        CKEDITOR.replace('content_descr');
    };
</script>	
</body>
</html>
<?php
session_start();
include("../includes/connection.php");


adminSecure(); 
$return_url=urldecode($_REQUEST['return_url']);
$redirect_url="show_article.php";
if($return_url)
{
	$redirect_url=$return_url;
}
 
$id= $obj->filter_numeric($_REQUEST['cId']);
$mode="add";
$table_caption="Add New Article";
$data=$obj->selectData(TABLE_ARTICLE,"","ar_id='".$id."' and ar_status<>'Deleted'",1);

if($_SESSION[ADMIN_SESSION_NAME]['admin_id'] !='1' && $_SESSION[ADMIN_SESSION_NAME]['admin_id'] !='16' && $data['ar_id'] != '' && $data['admin_id'] != $_SESSION[ADMIN_SESSION_NAME]['admin_id'])
{
	$obj->add_message("message","Access Denied. You don't have permissions to access this article.");
	$obj->reDirect($redirect_url);
}

  
if($data)
{
	$mode="edit";
	$table_caption="Edit Article Details";
}
if($_POST)
{
	$ar_title = strtolower($_POST['ar_title']);			 
	$get_texturl = $obj->getcleantext($ar_title);				
	$checkurl = $get_texturl;				
	$counturl = $obj->get_article_urlcount($ar_title);				 
	$counttot = $counturl+1;				 
	if(empty($counturl))  $newurl = $get_texturl;				
	else  $newurl = $get_texturl."_".$counttot;	
	
	switch($mode)
	{
		case 'add':

		if(!$err)
		{
			 $ar_descr 			= str_replace(array('\r\n','\r', '\n', ' rn '), "", $_POST['ar_descr']); 
			 $ar_short_descr 	= str_replace(array('\r\n','\r', '\n', ' rn '), "", $_POST['ar_short_descr']); 
			 
			$arrA['ar_url'] 			= $newurl;
			$arrA['ar_title'] 			= $obj->filter_mysql($_POST['ar_title']);
			$arrA['ar_keywords'] 		= $obj->filter_mysql($_POST['ar_keywords']);
			$arrA['ar_author'] 			= $obj->filter_mysql($_POST['ar_author']);
			$arrA['ar_descr'] 			= $ar_descr;
			$arrA['ar_short_descr'] 	= $obj->filter_mysql($ar_short_descr);	
			$arrA['ar_pstatus'] 		= $obj->filter_mysql($_POST['ar_pstatus']);			 
			$arrA['admin_id'] 			= $_SESSION[ADMIN_SESSION_NAME]['admin_id'];
			$arrA['ar_image'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['ar_image'])));	
			$arrA['ar_list_image'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['ar_list_image'])));	
			$arrA['ar_post_image'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['ar_post_image'])));	
			$arrA['ar_date'] 			= CURRENT_DATE_TIME;
			$arrA['ar_modified'] 		= CURRENT_DATE_TIME;
			$com_id = $obj->insertData(TABLE_ARTICLE,$arrA);
			
			 
			 
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err)
		{
			//$texturl = $obj->getcleantext($_POST['ar_url']);
			 
			$replace_ar_descr 	= str_replace(array('\r\n','\r', '\n', ' rn '), "", $_POST['ar_descr']); 
			$ar_descrChk 		= str_replace(" rn ","",$replace_ar_descr);
			$check_descr 		= str_replace(["\r\n", "\n", "\r", " rn ", " "], " ", $ar_descrChk); 
			$ar_descr 			= str_replace(" rn ","",$check_descr);
			
			$ar_short_descr2   	= str_replace(" rn ","",$_POST['ar_short_descr']);
			$check_short_descr 	= str_replace([" rn ", "\r\n", "\n", "\r", " "], " ", $ar_short_descr2); 
			$ar_short_descr    	= str_replace(" rn ","",$check_short_descr);
					
			$arrU['ar_url'] 			= $newurl;
			$arrU['ar_title'] 			= $obj->filter_mysql($_POST['ar_title']);
			$arrU['ar_keywords'] 		= $obj->filter_mysql($_POST['ar_keywords']);
			$arrU['ar_author'] 			= $obj->filter_mysql($_POST['ar_author']);
			$arrU['ar_descr'] 			= $ar_descr;
			$arrU['ar_short_descr'] 	= $ar_short_descr;	
			$arrU['ar_pstatus'] 		= $obj->filter_mysql($_POST['ar_pstatus']);		
			$arrU['ar_image'] 			= $obj->filter_mysql(addslashes(stripslashes($_POST['ar_image'])));	
			$arrU['ar_list_image'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['ar_list_image'])));	
			$arrU['ar_post_image'] 		= $obj->filter_mysql(addslashes(stripslashes($_POST['ar_post_image'])));	
			$arrU['ar_modified'] 		= CURRENT_DATE_TIME;			
			$obj->updateData(TABLE_ARTICLE,$arrU,"ar_id='".$id."'");
		 
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
<script type="text/javascript" src="../tiny/tiny/tinymce.min.js"></script>
<script type="text/javascript" src="../tiny/tiny/tinymce-data.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
        <li class="active">Article</li>
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
					  <input type="text" id="ar_title" name="ar_title" placeholder="Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['ar_title']);?>" />
                    </div>
                  </div>
					 <?php 
					 
						$check_descr 	= str_replace(["\r\n", "\n", "\r", " rn ", " "], " ", html_entity_decode($data['ar_descr']));
						$ar_descr2 		= str_replace("\r\n","",$check_descr);
						$ar_descr 		= str_replace("rn","",$ar_descr2);
						
						$check_short_descr = str_replace([" rn ", "\r\n", "\n", "\r", " "], " ", html_entity_decode($data['ar_short_descr']));
						$ar_short_descr2   = str_replace("\r\n","",$check_short_descr);
						$ar_short_descr    = str_replace(" rn ","",$ar_short_descr2);
					 ?>
				 
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Short Description </label>
                    <div class="col-sm-9">
					<textarea id="ar_short_descr" name="ar_short_descr" placeholder="Short Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 100px;">
					<?=html_entity_decode($ar_short_descr);?></textarea> 
                    </div>
                  </div>
				 
				 
                  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Description </label>
                    <div class="col-sm-9">
					<!----<input type='button' value='Toggle Editor' id='but_toggle'>------>
					<textarea id="editor" name="ar_descr" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 75%; height: 480px;">
					<?=html_entity_decode($data['ar_descr']);?> </textarea>
					 
                    </div>
                  </div>
                
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Keywords </label>
                    <div class="col-sm-9">
					  <input type="text" id="ar_keywords" name="ar_keywords" placeholder="Keywords" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['ar_keywords']);?>" />
                    </div>
                  </div>
				  
				  
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Background Image URL </label>
                    <div class="col-sm-9">
                      <input type="text" id="ar_image" class="col-xs-10 col-sm-5" value="<?=$data['ar_image'];?>" name="ar_image"/>
					  </div>					   
                  </div>
				  
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Post Image URL </label>
                    <div class="col-sm-9">
                      <input type="text" id="ar_post_image" class="col-xs-10 col-sm-5" value="<?=$data['ar_post_image'];?>" name="ar_post_image"/>
					  </div>					   
                  </div>
				  
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Main Page Image URL </br><small>(150*150)</small></label>
                    <div class="col-sm-9">
                      <input type="text" id="ar_list_image" class="col-xs-10 col-sm-5" value="<?=$data['ar_list_image'];?>" name="ar_list_image"/>
					  </div>					   
                  </div>
				  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Author  </label>
                    <div class="col-sm-9">
					  <input type="text" id="ar_author" name="ar_author" placeholder="Author name" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['ar_author']);?>" />
                    </div>
                  </div>
				  
				  <div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
					<div class="col-sm-9">
						<select name="ar_pstatus" class="col-xs-10 col-sm-5">
						  <option value="published" <?=$data['ar_pstatus']=="published"?'selected="selected"':'';?>>Published</option>
						  <option value="unpublished" <?=$data['ar_pstatus']=="unpublished"?'selected="selected"':'';?>>Unpublished</option>
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
<script language="javascript" type="text/javascript">	
	$("#contentEdit").validate({
		rules: {
			//ar_title: { required: true		  },
			ar_title: "required",
			ar_short_descr: "required",
			ar_descr: "required",
			ar_keywords: "required",
			ar_author: "required" 
		},
		messages: {
			//ar_title: { required: "Please enter title"	  },
			ar_title: "Please enter title",	
			ar_short_descr: "Please enter short description",
			ar_descr: "Please enter Description",
			ar_keywords: "Please enter keywords",
			ar_author: "Please enter Author name" 
		}
	});
	
</script>


 

	<script>
$(document).ready(function(){ 
  addTinyMCE();  
  $('#but_toggle').click(function(){  
   if(tinyMCE.get('editor')){   
     tinymce.remove('#editor');
   }else{    
     addTinyMCE();
   }
 
  });
});
 
function addTinyMCE(){ 
  tinymce.init({
    selector: '#editor',
    themes: 'modern',
    plugins: ["media link image"],
    images_upload_url: 'postAcceptor.php',
    images_file_types: 'jpeg,jpg,png',
    images_upload_credentials: true,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
	insert_button_items: 'image link | inserttable',
    height: 200
  });
}  
</script>

</body>
</html>
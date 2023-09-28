<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_tr_story.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['sId']));

$mode="add";
$table_caption="Add New Travel Story";
$data=$obj->selectData(TABLE_STORY,"","ts_id='".$id."' and ts_status<>'Deleted'",1);
if($data)
{
	$mode="edit";
	$table_caption="Edit Travel Story Details";
}

if($_POST)
{	
	
	switch($mode)
	{
		case 'add':
		
		if($_FILES['sphoto']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('sphoto', "../".STORY, 'jpg,png,jpeg');
			if($error)
			{
				$msg=$error;
				$err=1;
			} else {
				$sArr['ts_photo'] = $fileName;				
			}
		}

		if(!$err)
		{
			$sArr['ts_title'] 		= $obj->filter_mysql(htmlentities($_POST['ts_title']));			
			$sArr['ts_story'] 		= htmlentities($_POST['ts_story']);
			
			$sArr['ts_status'] 		= $obj->filter_mysql($_POST['ts_status']); 
			$sArr['ts_added']  		= CURRENT_DATE_TIME;
			$sArr['ts_updated'] 	= CURRENT_DATE_TIME;
			
			$st_id=$obj->insertData(TABLE_STORY,$sArr);
			$obj->add_message("message","Story added successfully!");
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if($_FILES['sphoto']['tmp_name'])
		{
			list($fileName,$error)=$obj->uploadFile('sphoto', "../".STORY, 'jpg,png,jpeg');
			if($error)
			{
				$msg=$error;
				$err=1;
			} 
			else 
			{
				$stArr['ts_photo'] = $fileName;				
			}
		}
		if(!$err)
		{
			$stArr['ts_title'] 		= $obj->filter_mysql(htmlentities($_POST['ts_title'])); 
			$stArr['ts_desc'] 		= htmlentities($_POST['ts_desc']);
			
			$stArr['ts_status'] 	= $obj->filter_mysql($_POST['ts_status']); 
			$stArr['ts_updated'] 	= CURRENT_DATE_TIME;
			
			
			$obj->updateData(TABLE_STORY,$sArr,"ts_id='".$id."'");			
			$obj->add_message("message","Story updated successfully!");
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
        <li> <a href="show_package.php">Story</a></li>
		<li class="active"><?=$data['ts_title'];?></li>
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
          <h1> Package <small> <i class="ace-icon fa fa-angle-double-right"></i><?=$table_caption;?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                <form class="form-horizontal" role="form" name="storyEdit" id="storyEdit" method="post" action="" enctype="multipart/form-data">
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title :</label>
                    <div class="col-sm-9">
                     <input type="text" id="ts_title" name="ts_title" placeholder="Story Title" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['ts_title']);?>" />
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Description </label>
                    <div class="col-sm-9">					
					<textarea id="ts_story" name="ts_story" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['ts_story']);?></textarea>
					
					
                    </div>
                  </div>
				  

				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Photo</label>
                    <div class="col-sm-9">
                      <input type="file" id="sphoto" class="col-xs-10 col-sm-5" name="sphoto"/>
					  </div>
					   <div class="col-sm-9">
					   <?php if($data['ts_photo']){?>
			 	 			<img src="<?="../".STORY.$data['ts_photo'];?>" width="200">
			  		   <?php }?>
                    </div>
                  </div>
				  
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Status </label>
                    <div class="col-sm-9">
						<select name="ts_status" class="col-xs-10 col-sm-5">					  
							<option value="Active"  <?= htmlentities($data['ts_status'])=="Active"?'selected="selected"':'';?>>Active</option>
							<option value="Inactive"  <?= htmlentities($data['ts_status'])=="Inactive"?'selected="selected"':'';?>>Inactive</option>
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
		$().ready(function() {
			$("#packEdit").validate({
					rules: {
						 
						ts_title: "required",						
						ts_validityy: 
							{
								required:true,
								number:true
							}
					},
					messages: {
						 
						ts_title: "Please enter Package title",
						ts_validityy: {
							required:"Please enter Valid Days",
							number:"Please enter a proper Valid Days!"
						}
					}
				});
		});			
</script>	
</script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
 <link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
 <script>
$(function() {
$("#ts_start_dt").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
          $("#ts_end_dt").datepicker("option","minDate", selected)
        }
    });

    $("#ts_end_dt").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
           $("#ts_start_dt").datepicker("option","maxDate", selected)
        }
    });
	
	$("#ts_start_tm").timepicker({
	 	  timeFormat: 'h:mm tt'
});

});
</script>
</body>
</html>
<?php
session_start();
include("../includes/connection.php");
adminSecure();
$table_caption = "Set Announcement Setting";
if($_POST['SetGoogleAuth'])
{
		$arrAE['set_announcement'] = $obj->filter_mysql($_POST['set_announcement']);
		$obj->updateData(TABLE_SETTINGS,$arrAE,"set_id='1'");
		$obj->add_message("message","Announcement Setting ".UPDATE_SUCCESSFULL);
		$_SESSION['messageClass'] = 'successClass';
}
$site_set=$obj->selectData(TABLE_SETTINGS,"","set_id=1",1);
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
        <li class="active">Announcement Setting</li>
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
                <form class="form-horizontal" role="form" name="googleAuth" id="googleAuth" method="post" action="">
                  
				   <div class="form-group">
                    <label class="col-md-4 control-label no-padding-right" for="form-field-1"> Announcement Required:</label>
                    <div class="col-md-4">
                      	<select name="set_announcement"  class="nav-search-input" id="set_announcement" >
							<option value="n" <?php if($site_set['set_announcement']=='n'){?> selected="selected"<?php }?>>No</option>
							<option value="y" <?php if($site_set['set_announcement']=='y'){?> selected="selected"<?php }?>>Yes</option>
						</select>
                    </div>
                  </div>
				  
				     <?php /*?>
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Twitter:</label>
                    <div class="col-sm-9">
                      <input type="text" id="twitter_link" name="twitter_link" placeholder="Twitter" class="col-xs-10 col-sm-5" value="<?=$site_set['twitter_link'];?>" validate="required:true" />
                    </div>
                  </div>
				  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Google Plus:</label>
                    <div class="col-sm-9">
                      <input type="text" id="google_plus_link" name="google_plus_link" placeholder="Google Plus" class="col-xs-10 col-sm-5" value="<?=$site_set['google_plus_link'];?>" validate="required:true" />
                    </div>
                  </div>
				  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Linked In:</label>
                    <div class="col-sm-9">
                      <input type="text" id="linked_in_link" name="linked_in_link" placeholder="Linked In" class="col-xs-10 col-sm-5" value="<?=$site_set['linked_in_link'];?>" validate="required:true" />
                    </div>
                  </div>
				  
				<div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Tumblr:</label>
                    <div class="col-sm-9">
                      <input type="text" id="tumblr_link" name="tumblr_link" placeholder="Tumblr" class="col-xs-10 col-sm-5" value="<?=$site_set['tumblr_link'];?>" validate="required:true" />
                    </div>
                  </div>
				  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Pin It:</label>
                    <div class="col-sm-9">
                      <input type="text" id="pin_it_link" name="pin_it_link" placeholder="Pin It" class="col-xs-10 col-sm-5" value="<?=$site_set['pin_it_link'];?>" validate="required:true" />
                    </div>
                  </div>
				  <?php */?>
				  
				    
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="SetGoogleAuth" value="Save" class="btn btn-info">
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
</body>
</html>
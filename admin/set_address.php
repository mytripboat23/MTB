<?php
session_start();
include("../includes/connection.php");
adminSecure();
$table_caption = "Set Address";
if($_POST['SetAddress'])
{
	$arrAE['set_address_1'] 	= $obj->filter_mysql($_POST['set_address_1']);
	$arrAE['set_city'] 			= $obj->filter_mysql($_POST['set_city']);
	$arrAE['set_state'] 		= $obj->filter_mysql($_POST['set_state']);
	$arrAE['set_country'] 		= $obj->filter_mysql($_POST['set_country']);
	$arrAE['set_zip'] 			= $obj->filter_mysql($_POST['set_zip']);
	$obj->updateData(TABLE_SETTINGS,$arrAE,"set_id='1'");
	$obj->add_message("message","Address ".UPDATE_SUCCESSFULL);
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
        <li class="active">Social Links</li>
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
                <form class="form-horizontal" role="form" name="socialLinks" id="socialLinks" method="post" action="">
                  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_address_1" name="set_address_1" placeholder="Address" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_address_1']);?>" validate="required:true" />
                    </div>
                  </div>
				  
				  <?php /* ?>
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address 2:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_address_2" name="set_address_2" placeholder="Address 2" class="col-xs-10 col-sm-5" value="<?=$site_set['set_address_2'];?>" validate="required:true" />
                    </div>
                  </div>
				  <?php */ ?>
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> City:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_city" name="set_city" placeholder="City" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_city']);?>" validate="required:true" />
                    </div>
                  </div>
				  
				 
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> State:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_state" name="set_state" placeholder="State" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_state']);?>" validate="required:true" />
                    </div>
                  </div>
				  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Country:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_country" name="set_country" placeholder="Country" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_country']);?>" validate="required:true" />
                    </div>
                  </div>
				  
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Zip:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_zip" name="set_zip" placeholder="Zip Code" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_zip']);?>" validate="required:true" />
                    </div>
                  </div>
				 
				   
			 
				  
				  
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="SetAddress" value="Save" class="btn btn-info">
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
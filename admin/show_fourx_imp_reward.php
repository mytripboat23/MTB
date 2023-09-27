<?php
session_start();
include("../includes/connection.php");
adminSecure();
$table_caption = "4X Impression Reward";
if($_POST['Set4ImpReward'])
{
		$arrAE['set_4x_reward'] = $obj->filter_mysql($_POST['set_4x_reward']);
		$obj->updateData(TABLE_SETTINGS,$arrAE,"set_id='1'");
		$obj->add_message("message","4X Impression Reward Setting ".UPDATE_SUCCESSFULL);
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
        <li class="active">4X Impression Reward </li>
      </ul>
      <!-- /.breadcrumb -->
     
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
                <form class="form-horizontal" role="form" name="4xReward" id="4xReward" method="post" action="">
                  
				   <div class="form-group">
                    <label class="col-md-4 control-label no-padding-right" for="form-field-1"> 4X Impression Reward:</label>
                    <div class="col-md-4">
                      	<select name="set_4x_reward"  class="nav-search-input" id="set_4x_reward" >
							<option value="n" <?php if($site_set['set_4x_reward']=='n'){?> selected="selected"<?php }?>>No</option>
							<option value="y" <?php if($site_set['set_4x_reward']=='y'){?> selected="selected"<?php }?>>Yes</option>
						</select>
                    </div>
                  </div>
				   
				    
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="Set4ImpReward" value="Save" class="btn btn-info">
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
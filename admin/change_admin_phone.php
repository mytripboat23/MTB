<?php
session_start();
include("../includes/connection.php");
adminSecure();
$table_caption = "Change Phone";
if($_POST['adminPhoneChange'])
{
	if($_POST['admin_phone_num']=="")
	{
		$obj->add_message("message","Please enter an email address!");
    	$_SESSION['messageClass'] = 'errorClass';
	}
	if($obj->get_message("message")=="")
	{
		$arrAE['admin_phone'] = $obj->filter_mysql($_POST['admin_phone_num']);
		$obj->updateData(TABLE_ADMIN,$arrAE,"admin_id='1'");
		$obj->add_message("message",UPDATE_SUCCESSFULL);
		$_SESSION['messageClass'] = 'successClass';
	}	
}
$admin_details=$obj->selectData(TABLE_ADMIN,"","admin_id=1",1);
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
        <li class="active">Change Admin Phone</li>
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> <?=$table_caption;?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                <form class="form-horizontal" role="form" name="adminPhone" id="adminPhone" method="post" action="">
                  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email ID:</label>
                    <div class="col-sm-9">
                      <input type="text" id="admin_phone_num" name="admin_phone_num" placeholder="phone" class="col-xs-10 col-sm-5" value="<?=htmlentities($admin_details['admin_phone']);?>" validate="required:true" />
                    </div>
                  </div>
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="adminPhoneChange" value="Save" class="btn btn-info">
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
	<!--	<script type="text/javascript" src="js/jquery_metadata.js"></script>
		<script language="javascript" type="text/javascript">		
		jQuery.metadata.setType("attr", "validate");		
		</script>-->	
		<script language="javascript" type="text/javascript">	
		$().ready(function() {
			$("#adminPhone").validate({
					rules: {
						admin_phone_num: {
										required: true
								   }
					},
					messages: {
							admin_phone_num: {
										required: "Please enter your email address"
								      }
					}
				});
		});			
</script>
</body>
</html>
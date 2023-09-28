<?php
session_start();
include("../includes/connection.php");
adminSecure();
$table_caption = "Change From Email ID";
if($_POST['fromEmailChange'])
{
	if($_POST['from_email_id']=="")
	{
		$obj->add_message("message","Please enter an email address!");
    	$_SESSION['messageClass'] = 'errorClass';
	}
	if($obj->get_message("message")=="")
	{
		$arrAE['set_from_email'] = $obj->filter_mysql($_POST['from_email_id']);
		$obj->updateData(TABLE_SETTINGS,$arrAE,"set_id='1'");
		$obj->add_message("message",UPDATE_SUCCESSFULL);
		$_SESSION['messageClass'] = 'successClass';
	}	
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
        <li class="active">From Email ID</li>
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
                <form class="form-horizontal" role="form" name="fromEmail" id="fromEmail" method="post" action="">
                  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">From Email ID:</label>
                    <div class="col-sm-9">
                      <input type="text" id="from_email_id" name="from_email_id" placeholder="Email ID" class="col-xs-10 col-sm-5" value="<?=$site_set['set_from_email'];?>" validate="required:true" />
                    </div>
                  </div>
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="fromEmailChange" value="Save" class="btn btn-info">
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
			$("#fromEmail").validate({
					rules: {
						from_email_id: {
										required: true,
										email: true
								   }
					},
					messages: {
							from_email_id: {
										required: "Please enter your email address",
										email:    "Please enter a valid email address"
								      }
					}
				});
		});			
</script>
</body>
</html>
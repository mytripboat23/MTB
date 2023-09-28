<?php
session_start();
include("../includes/connection.php");
adminSecure();
$table_caption = "Change Phone";
 
if($_POST['setPhoneChange'])
{
	if($_POST['set_phone']=="")
	{
		$obj->add_message("message","Please enter a phone number!");
    	$_SESSION['messageClass'] = 'errorClass';
	}
	if($obj->get_message("message")=="")
	{
		$arrAE['set_phone'] 	= $obj->filter_mysql($_POST['set_phone']);
		$arrAE['set_fax'] 		= $obj->filter_mysql($_POST['set_fax']);
		$arrAE['set_website'] 	= $obj->filter_mysql($_POST['set_website']);
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
        <li class="active">Change Phone</li>
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
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone Number:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_phone" name="set_phone" placeholder="Phone Number" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_phone']);?>" validate="required:true" />
                    </div>
                  </div>
				  
				   <?php /*?> <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Fax:</label>
                    <div class="col-sm-9">
                      <input type="text" id="set_fax" name="set_fax" placeholder="Fax" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_fax']);?>" validate="required:true" />
                    </div>
                  </div>
				  
				    <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">Website:</label>
                    <div class="col-sm-9">
                      <input type="url" id="set_website" name="set_website" placeholder="Website" class="col-xs-10 col-sm-5" value="<?=htmlentities($site_set['set_website']);?>" validate="required:true" />
                    </div>
                  </div><?php */?>
				  
				  
				  
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="setPhoneChange" value="Save" class="btn btn-info">
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
						admin_phone: {
										required: true
								   }
					},
					messages: {
							admin_phone: {
										required: "Please enter your phone number"
								      }
					}
				});
		});			
</script>
</body>
</html>
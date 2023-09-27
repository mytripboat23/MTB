<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url = urldecode($_REQUEST['return_url']);
if(isset($_POST['adminPassChange']))
{
	$_SESSION['messageClass'] = 'errorClass';
	if($_POST['admin_old_pass']=="") $obj->add_message("message","Please enter Old Password!");
	if($_POST['admin_new_pass']=="") $obj->add_message("message","Please enter New Password!");
	if($_POST['admin_re_pass']=="") $obj->add_message("message","Please re enter New Password!");
	if($_POST['admin_new_pass']!=$_POST['admin_re_pass']) $obj->add_message("message","New Password and Retypr Password should be same!");
	
	if($obj->get_message("message")=="")
	{
		$admin_id=$_SESSION[ADMIN_SESSION_NAME]['admin_id'];
		$old_password = $_POST['admin_old_pass'];
		$new_password = $_POST['admin_new_pass'];
		$pass_matched=$obj->selectData(TABLE_ADMIN,"","admin_id='".$admin_id."'",1);
		if(password_verify($old_password,$pass_matched['admin_password']))
		{
			$newPass['admin_password'] = password_hash($_POST['admin_new_pass'],PASSWORD_DEFAULT);
			$obj->updateData(TABLE_ADMIN,$newPass,"admin_id='".$admin_id."'");
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($return_url);
		}
		else
		{
			$obj->add_message("message",PASSWORD_MISMATCH);
			$_SESSION['messageClass'] = 'errorClass';
			$obj->reDirect($return_url);
		}
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
        <li class="active">Change Password</li>
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
                <form class="form-horizontal" role="form" name="adminPass" id="adminPass" method="post" action="">
                  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Old Password:</label>
                    <div class="col-sm-9">
                      <input type="password" id="admin_old_pass" name="admin_old_pass" placeholder="" class="col-xs-10 col-sm-5" />
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Password:</label>
                    <div class="col-sm-9">
                      <input type="password" id="admin_new_pass" name="admin_new_pass" placeholder="" class="col-xs-10 col-sm-5" />
                    </div>
                  </div>
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Retype Password:</label>
                    <div class="col-sm-9">
                      <input type="password" id="admin_re_pass" name="admin_re_pass" placeholder="" class="col-xs-10 col-sm-5"  />
                    </div>
                  </div>
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="adminPassChange" value="Save" class="btn btn-info">
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
			$("#adminPass").validate({
					rules: {
						admin_old_pass: {
										required: true
								   },
					    admin_new_pass: {
										required: true
								   },
						admin_re_pass: {
										required: true,
										equalTo: '#admin_new_pass'
								   }
					},
					messages: {
							admin_old_pass: {
										required: "Please enter your old password"
								      },
							admin_new_pass: {
										required: "Please enter your new password"
								      },
							admin_re_pass: {
										required: "Please re enter your new password",
										equalTo:  "New Password & Retype Password should be same"
								      }
					}
				});
		});			
</script>
</body>
</html>
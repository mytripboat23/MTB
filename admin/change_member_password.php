<?php
session_start();
include("../includes/connection.php");
adminSecure();

$u_login_id = $obj->filter_numeric($_REQUEST['uId']);
if(empty($u_login_id))
{
	 $obj->reDirect('show_members.php');
}
else
{
	$data = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","","ul.u_login_id=u.u_login_id and u.u_login_id='".$u_login_id."' and ul.u_login_status<>'Deleted' and u.user_status<>'Deleted'",1);  
	if(!isset($data['u_login_id']) || $data['u_login_id']=='')
	{
		 $obj->reDirect('show_members.php');
	}
}


if(isset($_POST['userPassChange']))
{
	$_SESSION['messageClass'] = 'errorClass';
	if($_POST['user_new_password']=="") $obj->add_message("message","Please enter Password!");

	
	if($obj->get_message("message")=="")
	{

			$password = password_hash($_POST['user_new_password'],PASSWORD_DEFAULT);
			$dataU['u_login_password'] = $password;
		
			$obj->updateData(TABLE_USER_LOGIN,$dataU,"u_login_id='".$u_login_id."'");
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect('show_members.php');	
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
        <li class="active">Change Password (<?php echo $data['u_login_user_email'];?>)</li>
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
                <form class="form-horizontal" role="form" name="userPass" id="userPass" method="post" action="">

				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> New Password:</label>
                    <div class="col-sm-9">
                      <input type="password" id="user_new_password" name="user_new_password" placeholder="" class="col-xs-10 col-sm-5"  />
                    </div>
                  </div>
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="userPassChange" value="Save" class="btn btn-info">
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
			$("#userPass").validate({
					rules: {
						user_naew_pass: {
										required: true
								   }
					},
					messages: {
							admin_old_pass: {
										required: "Please enter password"
								      }
					}
				});
		});			
</script>
</body>
</html>
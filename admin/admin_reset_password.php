<?php include("../includes/connection.php");
$admin_id = $obj->filter_mysql(preg_replace("/[^0-9]/", "", $obj->retrievePass($_REQUEST['uactid']))); 
 
 if(isset($_POST['adminPassChange']))
{	
	$_SESSION['messageClass'] = 'errorClass';	
	if($_POST['admin_new_pass']=="") $obj->add_message("message","Please enter New Password!");
	if($_POST['admin_re_pass']=="") $obj->add_message("message","Please re enter New Password!");
	if($_POST['admin_new_pass']!=$_POST['admin_re_pass']) $obj->add_message("message","New Password and Retype Password should be same!");
	
	if($obj->get_message("message")=="")
	{	
		//$new_pass = md5($_POST['admin_new_pass']);
		 
		$admin_login=$obj->selectData(TABLE_ADMIN,"","admin_id=".$admin_id."",1);
		if($admin_login)
		{				 
			$aData['admin_password']  	= password_hash($_POST['admin_new_pass'],PASSWORD_DEFAULT);	 
			$aData['admin_modified']	= CURRENT_DATE_TIME;			
			$obj->updateData(TABLE_ADMIN,$aData,"admin_id='".$admin_id."'");
			
			$obj->add_message("ALmessage",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect('index.php');				 
			 
		}
		else
		{
			$obj->add_message("ALmessage",PASSWORD_MISMATCH);
			$_SESSION['messageClass'] = 'errorClass';
		}
	}	
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php include("page_includes/login_common.php");?>
	<style>
	.change_msg li {
    font-size: 11px;
}
	</style>
	</head>
	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class="ace-icon fa "></i>
									<span class="red">Super</span>
									<span class="white" id="id-text2">Admin</span>
								</h1>
							<h4 class="blue" id="id-company-text">&copy; Hashing Ad Space</h4>
								 
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Please Set Your New Passowrd
											</h4>

											<div class="space-6"></div>
											<?php echo $obj->display_message("ALmessage");?>
											<?php echo $obj->display_message("message");?>
												<form class="form-horizontal" role="form" name="adminPass" id="adminPass" method="post" action="">

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" id="admin_new_pass" name="admin_new_pass" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" id="admin_re_pass" name="admin_re_pass" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													
													<div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
													<input type="submit" name="adminPassChange" value="Save" class="btn btn-info">
													&nbsp; &nbsp; &nbsp; </div>
													</div>

												</form>
            								
										 
											
										</div><!-- /.widget-main -->

									
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								</div><!-- /.position-relative -->

							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

		<!-- <![endif]-->
		

		<!--[if IE]>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<![endif]-->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript" src="js/jquery_validation.js"></script>		
		
	</body>
</html>
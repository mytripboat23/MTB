<?php include("../includes/connection.php");

if(isset($_SESSION[ADMIN_SESSION_NAME])||!isset($_SESSION['admin_request_page']))
{
	$_SESSION['admin_request_page']="dashboard.php";
}
$options = [
    'cost' => 12,
];
//echo password_hash("Secure@23", PASSWORD_BCRYPT, $options);

if(isset($_POST['Login']))
{
	if(isset($_POST['g-recaptcha-response']))
        $captcha=$_POST['g-recaptcha-response'];
        if(!$captcha){
          $obj->add_message("ALmessage","Please check the captcha form!");
        }
        $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfXgQwTAAAAAIS1g5rRHZd-rVQyUZu054lICJwS&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		if($obj->get_message("ALmessage")=="")
		{
			if($response['success'] == false)
			{			 
			   $obj->add_message("ALmessage","Wrong Captcha information!");
			}
		}		
	if($obj->get_message("ALmessage")=="")
	{
		$admin_user_name = $obj->filterData($_POST['user_name']);		 
		$admin_password  = $_POST['user_pass'];		
		$admin_login=$obj->selectData(TABLE_ADMIN,"","BINARY admin_username='".$admin_user_name."'",1);
		 
		if($admin_login)
		{

			//if($admin_password==$admin_login['admin_password'])
			if(password_verify($admin_password,$admin_login['admin_password']))
			{

				$_SESSION[ADMIN_SESSION_NAME]=$admin_login;	
				$_SESSION[ADMIN_SESSION_NAME]['ip'] = $ip;		
			}
			else
			{
				$obj->add_message("ALmessage",INVALID_LOGIN);
			}
		}
		else
		{
			$obj->add_message("ALmessage",INVALID_LOGIN);
		}
	}
}


if(isset($_SESSION[ADMIN_SESSION_NAME]))
{
	$obj->reDirect($_SESSION['admin_request_page']);
}
$display_fp_box = false;

if($_POST['forgot_pass']=='Send Me')
{
	if(isset($_POST['g-recaptcha-response']))
	$captcha=$_POST['g-recaptcha-response'];
	if(!$captcha){
	  $obj->add_message("ALmessage","Please check the captcha form!");
	}
	$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfXgQwTAAAAAIS1g5rRHZd-rVQyUZu054lICJwS&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	if($obj->get_message("ALmessage")=="")
	{
		if($response['success'] == false)
		{			 
		   $obj->add_message("ALmessage","Wrong Captcha information!");
		}
	}
	if($obj->get_message("ALmessage")=="")
	{	
		$admin_email_id = $obj->filterData($_POST['admin_email_id']);
		$admin_auth=$obj->selectData(TABLE_ADMIN,"admin_id,admin_email","BINARY admin_email='".$admin_email_id."'",1);
	 
		if($admin_auth)
		{
			//$newPass = md5($obj->generateRandomString(5).rand(11,99));
			//$arrAU['admin_password'] = $newPass;		
			//$obj->insertData(TABLE_ADMIN,$arrAU,"admin_id='".$admin_auth['admin_id']."'");
			
				 
			$admin_fp_mail  = "<b>Dear Administrator</b>,<br><br> Your login password reset link provided below..<br><br>";
			
			$admin_fp_mail .= "Please <a href='".FURL."admin/admin_reset_password.php?uactid=".$obj->encryptPass($admin_auth['admin_id'])."'>Click Here </a> to change your password <br>";		
			$admin_fp_mail .="<br><br>Thank You<br>".MAIL_THANK_YOU;
		
			$body = $obj->mailBody($admin_fp_mail); 
			
			$from = FROM_EMAIL_2;
			$to   = $admin_email_id;
			$subject = 'Forgot Admin Password';
			
			$obj->sendMailSES($to,$subject,$body,$from,SITE_NAME,$type);
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("AFPmessage","Password is sent to your email id. Please check your email!");
			
			$display_fp_box = true;
			
		}
		else
		{
			$obj->add_message("AFPmessage","Invalid Email Id!");
			$display_fp_box = true;
		}
	}
	
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php include("page_includes/login_common.php");?>
	 <script src='https://www.google.com/recaptcha/api.js'></script>
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
								<h4 class="blue" id="id-company-text">&copy; <?php echo SITE_TITLE;?></h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Please Enter Your Information
											</h4>

											<div class="space-6"></div>
											<?php echo $obj->display_message("ALmessage");?>
											<form id="admin_login" action="index.php" method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="user_name" id="user" type="text" class="form-control" placeholder="Username" validate="required:true" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="user_pass" id="pass" class="form-control" placeholder="Password"  validate="required:true" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
													
													
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<div class="g-recaptcha" data-sitekey="6LfXgQwTAAAAAJB4bSKv2sy7APKY_odE1j1aFef9"></div>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> Remember Me</span>
														</label>

														<input type="submit" name="Login" value="Login">
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											<?php /*?><div class="social-or-login center">
												<span class="bigger-110">Or Login Using</span>
											</div>

											<div class="space-6"></div>

											<div class="social-login center">
												<a class="btn btn-primary">
													<i class="ace-icon fa fa-facebook"></i>
												</a>

												<a class="btn btn-info">
													<i class="ace-icon fa fa-twitter"></i>
												</a>

												<a class="btn btn-danger">
													<i class="ace-icon fa fa-google-plus"></i>
												</a>
											</div><?php */?>
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													I forgot my password
												</a>
											</div>

											<div>
												<a href="#" data-target="#signup-box" class="user-signup-link">
													<?php /*?>I want to register
													<i class="ace-icon fa fa-arrow-right"></i><?php */?>
												</a>
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
									
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p> Enter your email and to receive instructions </p>
											
											<?=$obj->display_message("AFPmessage");?>

											<form id="admin_forgot_pass" name="admin_forgot_pass" action="index.php" method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="admin_email_id" id="admin_email_id" class="form-control" placeholder="Email" validate="required:true,email:true" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<div class="g-recaptcha" data-sitekey="6LceAhYTAAAAAAn1UR1NnuzJDyvsb508Evh72gT3"></div>
														</span>
													</label>

													<?php /*?><div class="clearfix">
														<button type="button" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div><?php */?>
													<input type="submit" name="forgot_pass" value="Send Me">
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												New User Registration
											</h4>

											<div class="space-6"></div>
											<p> Enter your details to begin: </p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													<label class="block">
														<input type="checkbox" class="ace" />
														<span class="lbl">
															I accept the
															<a href="#">User Agreement</a>
														</span>
													</label>

													<div class="space-24"></div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Reset</span>
														</button>

														<button type="button" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Register</span>

															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Back to login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

							<?php /*?><div class="navbar-fixed-top align-right">
								<br />
								&nbsp;
								<a id="btn-login-dark" href="#">Dark</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-blur" href="#">Blur</a>
								&nbsp;
								<span class="blue">/</span>
								&nbsp;
								<a id="btn-login-light" href="#">Light</a>
								&nbsp; &nbsp; &nbsp;
							</div><?php */?>
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
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			<?php 
			if($display_fp_box)
			{	
			?>			
				var target = $('#forgot-box');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			<?php
			}
			?>
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
			 $('#btn-login-dark').on('click', function(e) {
				$('body').attr('class', 'login-layout');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-light').on('click', function(e) {
				$('body').attr('class', 'login-layout light-login');
				$('#id-text2').attr('class', 'grey');
				$('#id-company-text').attr('class', 'blue');
				
				e.preventDefault();
			 });
			 $('#btn-login-blur').on('click', function(e) {
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				e.preventDefault();
			 });
			 
			});
		</script>
		<script type="text/javascript" src="<?=FURL?>admin/js/jquery-1.6.2.min.js"></script>	
		<script type="text/javascript" src="<?=FURL?>admin/js/jquery_validation.js"></script>		
		<script type="text/javascript" src="<?=FURL?>admin/js/jquery_metadata.js"></script>
		<script language="javascript" type="text/javascript">		
		jQuery.metadata.setType("attr", "validate");		
		</script>	
		<script language="javascript" type="text/javascript">	
		$(document).ready(function(){	
		$("#admin_login").validate();	
		});	
	</script>
	<script language="javascript" type="text/javascript">	
		$(document).ready(function(){	
		$("#admin_forgot_pass").validate();	
		});	
	</script>
	</body>
</html>
<?php include("includes/connection.php");
userAuth();
include("includes/all_form_check.php");

$reset_pass_user_id = $_SESSION['reset_pass_user_id'];

if(!isset($reset_pass_user_id) || $reset_pass_user_id=="")
{
	$obj->reDirect("reset_password.php");	 
}
?>
<!doctype html>
<html lang="en">
<head>
<?php include("page_includes/registration_head.php");?>
<style>
  .material-symbols-outlined {
    font-variation-settings:
    'FILL' 1,
    'wght' 400,
    'GRAD' 0,
    'opsz' 48
  }
</style>
</head>
<body>
<header>
  <!-- ...header start.... -->
   <?php include("page_includes/header.php");?>
</header>
<!-- ...header end.... -->
<main>
<!-- ...body start... -->
<div class="main_body log_pages">
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-8 d-flex log_txt_atra">
      <div class="brand_area"><img src="img/web_text.png"></div>
      <!-- <p>Are you a Travel agent?</p>
      <p>Want to sell your tour package with us.</p> -->
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 log_box">
      <div class="log_form_holder">
	  <?=stripslashes($obj->display_message("message"));?>
        <form name="resetPasswordFinal" id="resetPasswordFinal" action="" method="post">
          <div class="log_form_row">
            <input class="form-control" id="set_pass" name="set_pass" type="password" placeholder="Password" autocomplete="off" value="" onKeyUp="return password_validation(this.value,'set_pass','set_pass_message')">
			 <span class="messages" id="set_pass_message"></span> 
          </div>
          <div class="log_form_row">
            <input class="form-control" id="set_re_pass" name="set_re_pass" type="password" placeholder="Confirm password" autocomplete="off" onKeyUp="return cpassword_validation(this.value,$('#set_pass').val(),'Password','Confirm Password','set_re_pass','set_re_pass_message')">
			 <span class="messages" id="set_re_pass_message"></span> 
          </div>
		   <div class="log_form_row">
            <input class="form-control" id="otp_val" name="otp_val" type="text" placeholder="OTP" onKeyUp="return otp_run_validation(this.value,'otp_val','otp_val_message')">
			 <span class="messages" id="otp_val_message"></span>
          </div>
          <?php /*?><div class="log_form_row">
            <p id="reset_time">120 Sec</p>
          </div><?php */?>
          <div class="log_form_row"> <input type="submit" class="btn" name="resetPassFinal" value="Reset" onClick="return ResetPass_validation_2();"> </div>
        </form>
      </div>
    </div>
  </div>
</div>
</main>
<footer  class="footer_bottom">
    <?php include("page_includes/footer.php");?>
</footer>
<script src="js/reset_password.js"></script>
<script src="js/common.js"></script>
<?php include("page_includes/registration_footer_script.php");?>
</body>
</html>

<?php include("includes/connection.php");
include("includes/all_form_check.php");
$new_user_id = $_SESSION['new_user_id'];

if(!isset($new_user_id) || $new_user_id=="")
{
	$obj->reDirect("registration.php");	 
}
?>
<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
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
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-8 d-flex ">
      <div class="log_txt_atra">
        <div class="brand_area"><img src="img/web_text.png"></div>
        <!-- <p>Are you a Travel agent?</p> -->
        <!-- <p>Want to sell your tour package with us.</p> -->
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 ">
    <div class="log_box">
	<?=$obj->display_message("message");?>
        <div class="log_form_holder">
          <form name="otpVerification" id="otpVerification" method="post">
            <div class="log_form_row">
              <input class="form-control" id="otp_val" name="otp_val" type="text" placeholder="OTP" onKeyUp="return otp_run_validation(this.value,'otp_val','otp_val_message')">
        <span class="messages" id="otp_val_message"></span>
            </div>
            <div class="log_form_row">
              <p id="reset_time">120 Sec</p>
            </div>
            <div class="log_form_row">
              <input type="submit" class="btn" name="otpVerify" value="Verify" onClick="return otp_validation();">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</main> 
<!--<a class="reset_otp" href="#">Reset</a>-->
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<script src="js/verification.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script language="javascript">
var configUrl = '<?=FURL;?>ajaxs/';
</script>
<?php include("page_includes/registration_footer_script.php");?>
</body>
</html>
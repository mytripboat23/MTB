<?php include("includes/connection.php");
userAuth();
include("includes/all_form_check.php");

$_SESSION['resetPToken'] = "RP".date("YHmids");
?>
<!doctype html>
<html lang="en">
<head>
<?php include("page_includes/registration_head.php");?>
<script src='https://www.google.com/recaptcha/api.js'></script>
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
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-8 d-flex">
        
        <div class="log_txt_atra">
          
          <div class="brand_area"><img src="img/web_text.png"></div>
          <!-- <p>Are you a Travel agent?</p>
          <p>Want to sell your tour package with us.</p> -->
        </div>
      
      </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-4">
          <div class="log_box">
          <h2>Forgot Password</h2>
          <div class="log_form_holder">
          <?=stripslashes($obj->display_message("message"));?>
          <div class="txt_content_log mb-3">Please enter your Email Address to receive the Varification Code</div>
              <form name="resetPassword" id="resetPassword" action="" method="post">
          <input id="reset_pass_token" name="reset_pass_token" type="hidden" value="<?=$_SESSION['resetPToken'];?>" >
                <div class="log_form_row">
                  <input class="form-control" id="reg_email_id" name="reg_email_id" type="email" placeholder="Email" autocomplete="off" value="" onKeyUp="email_exist_validation(this.value,'reg_email_id','reg_email_id_message')">
            <span class="messages" id="reg_email_id_message"></span>
                </div>
            <?php /*?><label class="block clearfix">
                    <span class="block input-icon input-icon-right">   
                      <div class="g-recaptcha" data-sitekey="6LfXgQwTAAAAAJB4bSKv2sy7APKY_odE1j1aFef9" data-callback="recaptchaCallback"></div>
              <span class="messages" id="g-recaptcha_message"></span>
                    </span>    
                </label><?php */?>
                <div class="log_form_row">  <input type="submit" class="btn" name="resetPass" value="Reset" onClick="return ResetPass_validation_1();"> </div>

                <div class="text-center txt_content_log">Already have an account? <a href="login" class="link_txt">Login</a> </div>
              </form>
            </div>
          </div>
          
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

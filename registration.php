<?php include("includes/connection.php");
userAuth();
include("includes/all_form_check.php");

$_SESSION['regToken'] = "R".date("YHmids");

?>
<!doctype html>
<html lang="en">
<head>
<?php include("page_includes/registration_head.php");?>
<script src='https://www.google.com/recaptcha/api.js'></script>
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
            <p> We are World’s 1st Travel Social networking site. Where We are Connecting Travellers With Tour Operators.</p>
            <p>Search for your dream tourist destination and choose your travel package and operator at the same time.<br>
              You can choose a tour package in your budget, also you can ask to customize your tour from the operator.</p>
            <p>Compare tour packages with multiple options.</p>
            <p>User Registration FREE . <br>
              Note: We are not a travel agent, we are a Travel Networking Site. </p>
            <p>Contact Directly with millions of B2B and B2C tour operators and grow your business.</p>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
          <div class="log_box">
            <h2>Registration</h2>
            <div class="log_form_holder">
              <?=stripslashes($obj->display_message("message"));?>
              <form name="userRegistration" id="userRegistration" method="post" autocomplete="off">
                <input id="reg_token" name="reg_token" type="hidden" value="<?=$_SESSION['regToken'];?>" >
                <div class="log_form_row">
                  <input class="form-control" id="user_full_name" name="user_full_name" type="text" placeholder="Full Name" autocomplete="off" value="" onKeyUp="full_name_validation(this.value,'user_full_name','user_full_name_message')">
                  <span class="messages" id="user_full_name_message"></span> </div>
                <div class="log_form_row">
                  <input class="form-control" id="user_email_id" name="user_email_id" type="text" placeholder="Email" autocomplete="off" value="" onKeyUp="email_exist_validation(this.value,'user_email_id','user_email_message')">
                  <span class="messages" id="user_email_message"></span> </div>
                <div class="log_form_row">
                  <input class="form-control" id="user_dob" name="user_dob" type="text" placeholder="Date of birth" autocomplete="off" readonly="true" onChange="required_validation(this.value,'Date of birth','user_dob','user_dob_message')">
                  <span class="messages" id="user_dob_message"></span> </div>
                <div class="log_form_row">
                  <input class="form-control" id="user_passw" name="user_passw" type="password" placeholder="Password" autocomplete="off" value="" onKeyUp="return password_validation(this.value,'user_passw','user_pw_message')">
                  <span class="messages" id="user_pw_message"></span> </div>
                <div class="log_form_row">
                  <input class="form-control" id="user_cpassw" name="user_cpassw" type="password" placeholder="Confirm password" autocomplete="off" onKeyUp="return cpassword_validation(this.value,$('#user_passw').val(),'Password','Confirm Password','user_cpassw','user_cpw_message')">
                  <span class="messages" id="user_cpw_message"></span> </div>
                <div class="log_form_row">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="tour_operator" name="tour_operator" value="y" onClick="set_opt()">
                    <label class="form-check-label" for="tour_operator"> I'm a tour Operator. </label>
                  </div>
                </div>
                <div class="log_form_row" id="opt_biz_type" style="display:none">
                  <label>Business Type:</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="b2b" id="opt_btb" name="opt_type[]">
                    <label class="form-check-label" for="opt_btb"> B2B </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" value="b2c" id="opt_btc" name="opt_type[]">
                    <label class="form-check-label" for="opt_btc"> B2C </label>
                  </div>
                </div>
                <div class="log_form_row" id="" >
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="user_agree" name="user_agree" value="y">
                    <label class="form-check-label" for="agree"> I agree to <a href="<?php echo FURL?>terms" target="_blank">Terms and conditions</a>. </label>
                    <span class="messages" id="user_agree_message"></span> </div>
                </div>
                <div class="log_form_row">
                  <input type="submit" class="btn" name="SignUp" value="Register" onClick="return signUp_validation();">
                </div>
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
<script src="js/registration.js"></script>
<script src="js/common.js"></script>
<script language="javascript">
function set_opt()
{
	var isChecked = $("#tour_operator").is(":checked");
	if(isChecked)
	{
		$('#opt_biz_type').show();
	}
	else
	{
		 $("#opt_ind").prop("checked", false);
		  $("#opt_btb").prop("checked", false);
		   $("#opt_btc").prop("checked", false);
		$('#opt_biz_type').hide();
	}
}
</script>
<?php include("page_includes/registration_footer_script.php");?>
</body>
</html>

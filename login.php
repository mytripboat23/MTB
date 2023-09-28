<?php
include( "includes/connection.php" );
include( "includes/config-google-login.php" );
userAuth();
include( "includes/all_form_check.php" );
?>
<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<?php include("page_includes/registration_head.php");?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48
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
<!--
             <p>Are you a Travel agent?</p>
            <p>Want to sell your tour package with us.</p>  
-->
			  
			<p>  We are World’s 1st Travel Social networking site. Where We are Connecting Travellers With Tour Operators.</p>

<p>Search for your dream tourist destination and choose your travel package and operator at the same time.<br>
You can choose a tour package in your budget, also you can ask to customize your tour from the operator.</p>

<p>Compare tour packages with multiple options.</p>

<p>User Registration FREE . <br>
Note: We are not a travel agent, we are a Travel Networking Site. </p>

<p>Contact Directly with millions of B2B and B2C tour operators and grow your business.</p>

			  
			  
            
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 ">
          <div class="log_box">
            <h2>Login</h2>
            <?=$obj->display_message("message");?>
            <?php $_SESSION['loginToken'] = "L".date("YHmids"); ?>
            <div class="log_form_holder">
              <form name="loginTrip" id="loginTrip" action="" method="post">
                <input id="login_token" name="login_token" type="hidden" value="<?=$_SESSION['loginToken'];?>" >
                <div class="log_form_row ">
                  <input class="form-control " id="login_email" name="login_email" type="text" placeholder="Email ID" autocomplete="off" onKeyUp="email_exist_validation(this.value,'login_email','login_email_message')">
                  <span class="messages" id="login_email_message"></span> </div>
                <div class="log_form_row">
                  <input class="form-control " id="login_pw" name="login_pw" type="password" placeholder="Password" autocomplete="off" onKeyUp="return password_validation(this.value,'login_pw','login_pw_message')">
                  <span class="messages" id="login_pw_message"></span> </div>
                <div class="log_form_row">
                  <input type="submit" class="btn" name="SignIn" value="Login" onClick="return SignIn_validation();">
                </div>
                <div class="log_form_row">
                  <div class="mb-3"><a href="reset_password" class="link_txt">Forgot password ?</a></div>
                  <div class="txt_content_log">New user? <a href="registration" class="link_txt">Register free with MTB</a></div>
                </div>
              </form>
            </div>
            <div class="social_medialogin"> <a href="<?php echo $google_client->createAuthUrl();?>" class=""> <img src="https://onymos.com/wp-content/uploads/2020/10/google-signin-button-1024x260.png" alt="google_login" class="img-fluid" style="max-width: 250px;"> </a> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<script src="js/login.js"></script> 
<script src="js/common.js"></script>
<?php include("page_includes/registration_footer_script.php");?>
</body>
</html>

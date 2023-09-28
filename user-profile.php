<?php include("includes/connection.php");
userSecure();
include("includes/all_form_check.php");
$log_user_id = $_SESSION['user']['u_login_id'];
$user_id      = $obj->filter_numeric($_REQUEST['uId']);
$obj->valid_auth_user($user_id);
if($log_user_id!=$user_id)
{
	$obj->comp_profile_view_permission($log_user_id,$user_id);
}


$userPD       = $obj->selectData(TABLE_USER,"","u_login_id='".$user_id."'",1);

if($userPD['user_avatar']!="" || $userPD['user_avatar']!=NULL) $upphoto = AVATAR.$userPD['user_avatar'];
else $upphoto = NO_USER_PHOTO; 

if($userPD['user_banner']!="" || $userPD['user_banner']!=NULL) $upbanner = CBANNER.$userPD['user_banner'];
else $upbanner = NO_BANNER; 



$searchTSql  = $obj->selectData(TABLE_PACKAGE,"","pck_status='Active'","","pck_id desc");
?>
<!doctype html>
<html lang="en">
<head>
<?php include("page_includes/registration_head.php");?>
</head>
<body>
<header>
  <!-- ...header start.... -->
   <?php include("page_includes/search_header.php");?>
</header>
<!-- ...header end.... -->
<main>
  <!-- ...body start... -->
  <div class="main_body">
    <div class="container">
      <div class="row">
        <div class="let_body col-xs-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
          <div class="lt_first_column">
            <!-- ...................btn top section start............ -->
           
            <!-- ...................btn top section end............ -->
			<?=stripslashes($obj->display_message("message"));?>
		   <?php include("page_includes/dash_body_nav.php");?>
			
            <picture class="top_banner">
              <img src="<?=$upbanner;?>">
              <img src="<?=$upbanner;?>" class="blur">
            </picture>
            <div class="profile_list_info">
              <div class="profile_single_box">
                <h5>Contact info</h5>
                <ul>
                  <li > <span class="material-symbols-outlined himself_ico">home</span>
                    <div>
                      <div>Lives in <span class="bold_txt"><?=$userPD['user_city'];?></span></div>
                      <div class="small_label">Live</div>
                    </div>
                  </li>
                  <li > <span class="material-symbols-outlined himself_ico">home_pin</span>
                    <div>
                      <div> <span class="bold_txt"><?=$userPD['user_from_city'];?></span></div>
                      <div class="small_label">From</div>
                    </div>
                  </li>
                  <li > <span class="material-symbols-outlined himself_ico">mail</span>
                    <div>
                      <div> <span class="bold_txt"><?php if($log_user_id!=$user_id){?><?='*****'.substr($userPD['user_email'],5);?><?php }else{ ?><?php echo $userPD['user_email'];?><?php }?></span></div>
                      <div class="small_label">Email</div>
                    </div>
                  </li>
                  <li > <span class="material-symbols-outlined himself_ico">call</span>
                    <div>
                      <div><span class="bold_txt"><?php if($log_user_id!=$user_id){?><?='*****'.substr($userPD['user_phone'],5);?><?php }else{ ?><?php echo $userPD['user_phone'];?><?php }?></span></div>
                      <div class="small_label">Mobile</div>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="profile_single_box">
                <h5>Website and social media</h5>
                <ul>
                  <li > <span class="material-symbols-outlined himself_ico">globe_asia</span>
                    <div>
                      <div> <span class="bold_txt"><?=$userPD['user_website'];?></span></div>
                      <div class="small_label">Website</div>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="profile_single_box">
                <h5>Basic Info</h5>
                <ul>
                  <li > <span class="material-symbols-outlined himself_ico">person</span>
                    <div>
                      <div> <span class="bold_txt"><?php if($userPD['user_gender']=='m'){?>Male<?php }else{ ?>Female<?php }?></span></div>
                      <div class="small_label">Gender</div>
                    </div>
                  </li>
                  <li > <span class="material-symbols-outlined himself_ico">cake</span>
                    <div>
                      <div><span class="bold_txt"><?php if($userPD['user_dob']!=NULL){?><?=date("d/m/Y",strtotime($userPD['user_dob']));?><?php }?></span></div>
                      <div class="small_label">Date of birth</div>
                    </div>
                  </li>
                  <li > <span class="material-symbols-outlined himself_ico">language</span>
                    <div>
                      <div><span class="bold_txt"><?=$obj->get_languages($userPD['user_lang']);?></span></div>
                      <div class="small_label">Language</div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="lt_second_column">
            <!-- ................Tor operator.............. -->
             <?php include("page_includes/dash_left_sidebar.php");?>
          </div>
          <div class="clear"></div>
        </div>
        <aside class="right_body col-xs-12 col-sm-4 col-md-3 col-lg-3 col-xl-3">
          
          <?php include("page_includes/sidebar_right.php");?> 
        </aside>
      </div>
    </div>
  </div>
</main>
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<?php /*?><script type="text/javascript" src="js/jquery.validate.js"></script><?php */?>		
<script src="js/profile.js"></script>
<?php include("page_includes/profile_footer_script.php");?>
<?php include("page_includes/dash_comment_script.php");?>
<!-- ............popup scripts........... -->
</body>
</html>

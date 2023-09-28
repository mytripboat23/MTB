<?php include("includes/connection.php");
userSecure();
include("includes/all_form_check.php");


$log_user_id = $_SESSION['user']['u_login_id'];
$user_id     = $_SESSION['user']['u_login_id'];
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
        <div class="let_body col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-9">
          <div class="lt_first_column"> 
            <!-- ...................btn top section start............ -->
            
            <?=stripslashes($obj->display_message("message"));?>
		   <?php include("page_includes/dash_body_nav.php");?>
            <!-- ...................btn top section end............ -->
            
            <h3 class="main_bold_heading"> My Companion </h3>
            <!-- ............single block start................ -->
            <div class="companions_list">
              <ul>
			  <?php
			  $comQuery = $obj->selectData(TABLE_FRIENDS,"","(fr_from_id='".$log_user_id."' or fr_to_id='".$log_user_id."') and fr_status in ('S','A')","");
			  while($comData = mysqli_fetch_array($comQuery))
			  {
			  	$com_user = "";
				if($comData['fr_from_id']==$log_user_id) $com_user = $comData['fr_to_id'];
				else $com_user = $comData['fr_from_id'];
				
				$comPD       = $obj->selectData(TABLE_USER,"","u_login_id='".$com_user."'",1);
			  	if($comPD['user_avatar']!="" || $comPD['user_avatar']!=NULL) $upphoto = AVATAR.$comPD['user_avatar'];
			  	else $upphoto = NO_USER_PHOTO; 
			  ?>
                <li> 
					<figure>
					<img src="img/person_img.png" alt="">
					</figure>
					
                 
					<div class="com_details">
						<h6><a href="user-details?uId=<?=$com_user;?>"><?=$comPD['user_full_name'];?></a></h6>
						<div class="btn-group rounded-3" role="group" aria-label="Companion buttons">
						<?php if($comData['fr_status']=='S'){?>
							<button type="button" class="btn btn-dark">Join</button>
							<button type="button" class="btn btn-danger">Reject</button>
						 <?php }else{?>	
							<button type="button" class="btn btn-light">Unfollow</button>
							<button type="button" class="btn btn-dark">Block</button>
						 <?php }?>	
				   </div>
					</div>
					
                </li>
                
			 <?php }?>	
              </ul>
            </div>
            <!-- .................single block end................. --> 
          </div>
          <div class="lt_second_column"> 
           	<?php include("page_includes/dash_left_sidebar.php");?>
          </div>
          <div class="clear"></div>
        </div>
        <aside class="right_body col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
           <?php include("page_includes/sidebar_right.php");?>
        </aside>
      </div>
    </div>
  </div>
</main>
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<?php include("page_includes/profile_footer_script.php");?>
  <?php include("page_includes/dash_comment_script.php");?>
</body>
</html>

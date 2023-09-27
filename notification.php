<?php
include( "includes/connection.php" );
userSecure();
include( "includes/all_form_check.php" );

$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
$user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
?>
<!doctype html>
<html lang="en">
<head>
<?php include("page_includes/registration_head.php");?>
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">-->
	
	<style>
		.notification_holder .notice_list a .image_holder{width:42px;}
	</style>
	
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
            <?=stripslashes($obj->display_message("message"));?>
            <?php include("page_includes/dash_body_nav.php");?>
            <div class="create_single_pack">
              <h3 class="heading_bold top_none">Notification </h3>
              <section class="notification_holder">
                
				  
				    <div class="list-group list-group-flush notice_list"> 
	  
	  <?php
	    $log_user_id = $_SESSION['user']['u_login_id'];
	  	$notiSql = $obj->selectData(TABLE_NOTIFICATION,"","noti_status='Active' and noti_for_user='".$log_user_id."'","","noti_id desc");
		while($notiD = mysqli_fetch_array($notiSql))
		{
				$userD   = $obj->selectData(TABLE_USER,"","u_login_id='".$notiD['noti_from_user']."'",1);
				if($userD['user_avatar']!="") $uphoto = AVATAR.$userD['user_avatar'];
				else $uphoto = NO_USER_PHOTO; 
	  ?>
						
 <a href="<?=str_replace(".php","",$notiD['noti_url'])?>" class="list-group-item list-group-item-action bg-transparent p-0 border-0" >
	 <div class="alert alert-warning" role="alert">
      <div class="d-flex align-items-center">
        <div class="flex-shrink-0 image_holder"> <img src="<?=$uphoto;?>" alt="..." class="rounded-circle w-100"> </div>
        <div class="flex-grow-1 ms-3"><?=$notiD['noti_message']?></div>
      </div>
  </div>
	     </a> 

	  <?php }?>
	  </div>
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
				  
              </section>
            </div>
          </div>
          <div class="lt_second_column">
            <?php include("page_includes/dash_left_sidebar.php");?>
          </div>
          <div class="clear"></div>
          <!-- <div class="related_item">
            <?php include("page_includes/related_tours.php");?>
          </div> -->
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
<script src="js/career.js"></script> 
<script src="js/common.js"></script>
<?php include("page_includes/profile_footer_script.php");?>
</body>
</html>

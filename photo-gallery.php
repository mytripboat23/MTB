<?php
include( "includes/connection.php" );
userSecure();
include( "includes/all_form_check.php" );


$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
$user_id = $obj->filter_numeric( $_REQUEST[ 'uId' ] );
$obj->valid_auth_user( $user_id );
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
            
            <?=stripslashes($obj->display_message("message"));?>
            <?php include("page_includes/dash_body_nav.php");?>
            <!-- ...................btn top section end............ -->
            
            <h3 class="main_bold_heading mb-4"> Photo Gallery </h3>
            <!-- ............single block start................ -->
			  
			  <style>
/*				  .grid{display: flex; justify-content: space-between; flex-wrap: wrap;}*/
			  .grid-item { width:210px; margin-bottom:16px; border-radius: 12px; overflow: hidden; box-shadow: inset 0 0 15px 0 #ccc;
 }
			  </style>
            <div class="companions_list">
              <?php
              $photos = explode( ",", $obj->get_user_photos( $user_id ) );
              if ( $photos[ 0 ] != "" ) {
                ?>
              <section class="diary_gallery">
<!--                <div class="row row-cols-1 row-cols-md-3 g-4">-->
					
					
					<div class="grid">
  

                  <?php
                  for ( $m = 0; isset( $photos ), $photos[ $m ] != ""; $m++ ) {

                    ?>
					<div class="grid-item">
					<a  data-fancybox  data-src="<?=$photos[$m]?>" > <img src="<?=$photos[$m]?>" class="card-img-top " alt="..."> </a>
					</div>
					
               <?php /*?>   <div class="col ">
                    <div class="card "> <a  data-fancybox
  data-src="<?=$photos[$m]?>"
  data-caption="Hello world"> <img src="<?=$photos[$m]?>" class="card-img-top " alt="..."> </a> </div>
                  </div><?php */?>
                  <?php

                  }
                  ?>
                </div>
              </section>
              <?php
              }
              ?>
            </div>
            <!-- .................single block end................. --> 
          </div>
          <div class="lt_second_column">
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
<?php include("page_includes/profile_footer_script.php");?>
<?php include("page_includes/dash_comment_script.php");?>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
/>
	
	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

	
<script>
	 Fancybox.bind('[data-fancybox]', {
//
      });  
	
	
	
	
	$('.grid').masonry({
  // options
  itemSelector: '.grid-item',
  columnWidth: 210,
  gutter: 16

});
	</script>
</body>
</html>

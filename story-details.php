<?php
include( "includes/connection.php" );
userSecure();
include( "includes/all_form_check.php" );


$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];


if ( isset( $_REQUEST[ 'tId' ] ) && $_REQUEST[ 'tId' ] != "" ) {
  $ts_id = $obj->filter_numeric( $_REQUEST[ 'tId' ] );
}
if ( $ts_id != "" ) {
  $tsData = $obj->selectData( TABLE_STORY, "", "ts_id='" . $ts_id . "'", 1 );
  $user_id = $tsData[ 'user_id' ];
  /*if($tsData['ts_id']!="")
  {
  	$tssQuery  = $obj->selectData(TABLE_STORY_SUB,"","ts_id='".$ts_id."'","","ts_id asc");
  }
  else
  {*/
  if ( !isset( $tsData[ 'ts_id' ] ) || $tsData[ 'ts_id' ] == "" ) {
    $_SESSION[ 'messageClass' ] = "errorClass";
    $obj->add_message( "message", "Invalid Request!" );
    $obj->redirect( "dashboard.php" );
    exit;
  }
  //}
}
$obj->unset_notification( $log_user_id, "story-details.php?tId=" . $ts_id );
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
            <?php include("page_includes/dash_body_nav.php");?>
            <!-- ..........showcase area start............ --> 
             <section class="pack_info">
              <div class="">
                <button class="btn pack_jist">Trip highlight<span class="material-symbols-outlined">expand_more</span></button>
              </div>
              <ul class="pack_jist-field">
                <li> <span><img src="img/tour_name.png" alt=""></span>
                  <div>
                    <h4><?=$tsData['ts_title'];?></h4>
                  </div>
                </li>
                <li> <span><img src="img/start_date.png" alt=""></span>
                  <div>
                    <h4><?=date("F d, Y",strtotime($tsData['ts_start']));?> - <?=date("F d, Y",strtotime($tsData['ts_end']));?></h4>
                  </div>
                </li>
<!--
                <li> <span><img src="img/duration_tour.png" alt=""></span>
                  <div>
                    <h4>15 Days - 14 Nights</h4>
                  </div>
                </li>
                <li> <span><img src="img/group_ico.png" alt=""></span>
                  <div>
                    <h4>10 Persons</h4>
                  </div>
                </li>
-->
              </ul>
            </section>  
            <!-- ..........showcase area end............ --> 
            
            <!--
			  <style>
				  
				  
				  #story_details_image_gallery {
					  display: flex;
					  flex-wrap: wrap;
					  justify-content: space-between;
				  }
				  
				  #story_details_image_gallery picture{
					 display: block;
					      width: 180px;
    height: 120px;
					  overflow: hidden;
					  border-radius: 12px;
					 margin-bottom: 8px;
					  
				  }
				  
				  #story_details_image_gallery picture:first-child{
					  height: 400px;
					  width: calc(100% - 200px);
				  }
				  
				  
			  </style>
-->
            <style>
				#story_details_image_gallery{
				display: flex;
    background: #f1f1f1;
    align-items: center;
    justify-content: center;
    padding: 16px 0 0;
    margin-bottom: 16px;
    border-radius: 12px;
				}
				  .grid{ width:89% }
			  .grid-item { width:140px; height: 140px; margin-bottom:20px; border-radius: 12px; overflow: hidden; box-shadow: inset 0 0 15px 0 #ccc; }

			  .grid-item a{ display: block;width:100%; height: 100%;}
			  .grid-item a img{ width:100%; height: 100%;}
			  .grid-item:first-child{width:440px; height:300px;}
			  .grid-item:nth-child(2){width:140px; height:140px;}
			  .grid-item:nth-child(3){width:140px; height:140px;}

			  </style>
            <section id="story_details_image_gallery" >
				<div class="grid">
              <?php
              $sImages = array();
              $sImages = explode( ",", trim( $tsData[ 'ts_photos' ], "," ) );
              for ( $k = 0; isset( $sImages[ $k ] ), $sImages[ $k ] != ""; $k++ ) {
                ?>
              <!--                    <div class="picture_profile_story"><img src=""> <img src="<?=STORY.$sImages[$k];?>" class="blur"> </div>-->
              
              <picture class="grid-item"><a  data-fancybox  data-src="<?=STORY.$sImages[$k];?>" > <img src="<?=STORY.$sImages[$k];?>"></a></picture>
              <?php }?>
              
              <!-- <picture> <img src="img/package_banner.jpg">
                <div class="img_button"> <a href="#"><img src="img/img_agent.png">Images by Agent</a> <a href="#"><img src="img/img_travel.png">Images by traveller</a> </div>
              </picture> --> 
           </div>
			  
			  
			  </section>
			  
			  <section>
			  
			  
			   
              <?php echo html_entity_decode(html_entity_decode($tsData['ts_desc']));?>
			  
			  
			  </section>
			  
			  
			  
            <div class="">
             <?php /*?> <h3 class="heading_bold mt-0">
                <?=$tsData['ts_title'];?>
              </h3>
              <div class="pre_tour_date">
                <?=date("F d, Y",strtotime($tsData['ts_start']));?>
                -
                <?=date("F d, Y",strtotime($tsData['ts_end']));?>
                &nbsp; </div>
              <?php echo html_entity_decode(html_entity_decode($tsData['ts_desc']));?><?php */?>
              <?php /*?>
<section>
  <ul class="day_list">
    <li> <?php echo html_entity_decode(html_entity_decode($tsData['ts_desc']));?>
      <?php
                    $sImages = array();
                    $sImages = explode( ",", trim( $tsData[ 'ts_photos' ], "," ) );
                    for ( $k = 0; isset( $sImages[ $k ] ), $sImages[ $k ] != ""; $k++ ) {
                      ?>
      <div class="picture_profile_story"><img src="<?=STORY.$sImages[$k];?>"> <img src="<?=STORY.$sImages[$k];?>" class="blur"> </div>
      <?php }?>
    </li>
  </ul>
</section>
              <?php */?>
              <?php /*?>
<section class="comment">
  <div class="row">
    <div class="col-sm-12">
      <ul class="first_label">
        <li> <a href="#" class="reply_person">
            <picture></picture>
          </a>
          <div class="replyer_names">
            <h4>Ashok Bhandari</h4>
            <p>cursus libero. Proin ultrices lacus id turpis accumsan eleifend. Suspendisse efficitur metus ipsum, pellentesque eros. Praesent pulvinar non felis . </p>
            <div class="reply_comment_holder">
              <div class="reply_txt"> <span class="date">May 12, 2022</span> <span><a href="#"><img src="img/reply_ico.png"></a></span> <span class="material-symbols-outlined"> <a href="#" class="more">more_horiz</a> </span> </div>
              <div class="like_area"> <span class="material-symbols-outlined"> <a href="#">thumb_up</a> </span> </div>
            </div>
          </div>
          <ul class="second_label">
            <li> <a href="#" class="reply_person">
                <picture></picture>
              </a>
              <div class="replyer_names">
                <h4>Ashok Bhandari</h4>
                <p>cursus libero. Proin ultrices lacus id turpis accumsan eleifend. Suspendisse efficitur metus ipsum, pellentesque eros. Praesent pulvinar non felis . </p>
                <div class="reply_comment_holder">
                  <div class="reply_txt"> <span class="date">May 12, 2022</span> <span><a href="#"><img src="img/reply_ico.png"></a></span> <span class="material-symbols-outlined"> <a href="#" class="more">more_horiz</a> </span> </div>
                  <div class="like_area"> <span class="material-symbols-outlined"> <a href="#">thumb_up</a> </span> </div>
                </div>
              </div>
            </li>
          </ul>
        </li>
        <li> <a href="#" class="reply_person">
            <picture></picture>
          </a>
          <div class="replyer_names write_comment">
            <input type="text" placeholder="Write your comment">
            <a href="#" class="more_setting"> <span class="material-symbols-outlined"> more_vert </span> </a> </div>
        </li>
      </ul>
    </div>
  </div>
</section>
              <?php */?>
            </div>
          </div>
          <div class="lt_second_column">
            <?php include("page_includes/dash_left_sidebar.php");?>
          </div>
          <div class="clear"></div>
          <?php /*?>
<div class="related_item">
  <h3>Related Tours</h3>
  <ul>
    <li> <a href="listing.html"> <img class="item_img" src="img/tour_img.jpg">
        <div class="person_img"><img src="img/person_img.png" alt="..."></div>
        <div class="item_content">
          <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div>
          <h4>Darjeeling Tour</h4>
          <p>Share your travel experience and create your travel story....</p>
        </div>
      </a> </li>
    <li> <a href="listing.html"> <img class="item_img" src="img/tour_img.jpg">
        <div class="person_img"><img src="img/person_img.png" alt="..."></div>
        <div class="item_content">
          <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div>
          <h4>Darjeeling Tour</h4>
          <p>Share your travel experience and create your travel story....</p>
        </div>
      </a> </li>
    <li> <a href="listing.html"> <img class="item_img" src="img/tour_img.jpg">
        <div class="person_img"><img src="img/person_img.png" alt="..."></div>
        <div class="item_content">
          <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div>
          <h4>Darjeeling Tour</h4>
          <p>Share your travel experience and create your travel story....</p>
        </div>
      </a> </li>
    <li> <a href="listing.html"> <img class="item_img" src="img/tour_img.jpg">
        <div class="person_img"><img src="img/person_img.png" alt="..."></div>
        <div class="item_content">
          <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div>
          <h4>Darjeeling Tour</h4>
          <p>Share your travel experience and create your travel story....</p>
        </div>
      </a> </li>
  </ul>
</div>
          <?php */?>
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
  columnWidth: 140,
  gutter: 14,
});
	</script>
</body>
</html>

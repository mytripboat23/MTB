<?php
include "includes/connection.php";
$log_user_id = $_SESSION[ 'user' ]['u_login_id'];
?>
<!doctype html>
<html lang="en">
<head>
<?php include "page_includes/home_head.php";?>
	
</head>
<body>
<header> 
  <!-- ...header start.... -->
  <?php include "page_includes/home_header.php";?>
	
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
</header>
<!-- ...header end.... -->
<main style="margin-bottom: 0;">
  <?php include "page_includes/home_banner2.php";?>
	
  <div> 
    <!-- ...................top tour start................... -->
    <section class="top_tour_box_holder">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="heading_holder">
              <h2>Tranding tour for you </h2>
<!--              <h4>We are exploring the world of travel and let you choose your destination</h4>-->
              <h4>Choose your dream <strong class="" style="color: #f26051;">tour package in your budget</strong>, also you can ask to customize your tour from leading tour operators or company.</h4>
				<h4><strong class="" style="color: #f26051;">Register Now FREE!</strong> Find Your dream destinations from multiple leading tour operators or company and ask for customised any tour. </h4>
            </div>
          </div>
        </div>
        <style>
			  picture{position:absolute; top:0;left:0; width:100%; height:100%;background-size: cover;
    background-position: center;}
		  </style>
        <div class="row popbox_sec">
          <?php
          $top_liked_ids = $obj->get_top_tours(28);
          $tLPSql = $obj->selectData( TABLE_PACKAGE, "", "pck_id in (" . $top_liked_ids . ")   and pck_status='Active'", "", "rand()", "", "0,28" );
          while ( $tLPRes = mysqli_fetch_object( $tLPSql ) ) {
            $pstart = strtotime( $tLPRes->pck_start_dt );
            $pend = strtotime( $tLPRes->pck_end_dt );

            $duration = "";
            if ( date( "m", $pstart ) == date( "m", $pend ) )$duration = date( "d", $pstart ) . " - " . date( "d", $pend ) . " " . date( "M", $pend );
            else $duration = date( "d", $pstart ) . " " . date( "M", $pstart ) . " - " . date( "d", $pend ) . " " . date( "M", $pend );
			  
			  
			  //trilokesh
			       $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $tLPRes->user_id . "'", 1 );
                  if ( $userD[ 'user_avatar' ] != "" ) $uphoto = AVATAR . $userD[ 'user_avatar' ];
                  else $uphoto = NO_USER_PHOTO;
			  //end
			  
            ?>
          <div class=" col-sm-6 col-md-4 col-lg-3 g-5">
            <div class="card">
				<div class="price_holder_home" data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="left" <?php if(!isset($_SESSION['user']['u_login_id']) || $_SESSION['user']['u_login_id']=='' ){?>data-bs-title="Login to see the deal price"<?php }?>>
					
					
					
					<?php echo $obj->getCurrency($tLPRes->pck_curr);?> : <?=$obj->hide_price($tLPRes->pck_price);?>
					
					
					<?php if($tLPRes->pck_foreign_price >0){?>
					
					<span style="color:#f26051" >For foreigner <?php echo $obj->getCurrency($tLPRes->pck_foreign_curr);?> : <?=$obj->hide_price($tLPRes->pck_foreign_price);?> </span>
					 
				<?php }?>
					
					
					
					
					
				</div>
              <div class="img_holder"> <a href="tour-details?tId=<?=$tLPRes->pck_id?>">
                <?php /*?><picture style="background-image: url(<?=PACKAGE.$tLPRes->pck_photo?>);"></picture><?php */?>
				<?php if(file_exists(PACKAGE_OPT.$tLPRes->pck_photo)){?>
                <picture style='background-image: url("<?=$obj->getDataURI(PACKAGE_OPT.$tLPRes->pck_photo);?>");'></picture>
				<?php }else{?>
				<picture style='background-image: url("<?=$obj->getDataURI(PACKAGE.$tLPRes->pck_photo);?>");'></picture>
				<?php }?>
                <?php /*?><img src="<?=$obj->getDataURI(PACKAGE.$tLPRes->pck_photo);?>" alt="<?=$tLPRes->pck_title;?>" class="card-img" ><?php */?>
                </a>
                <div class="card-img-overlay">
                  <div class="overlay_description">
                    <div class="title_box"> <a href="tour-details?tId=<?=$tLPRes->pck_id?>">
                      <h4 class="card-title">
                        <?=$tLPRes->pck_title;?>
                      </h4>
                      <h5><img src="img/calender_small_ico.png"><?php echo $duration;?></h5>
                      </a> </div>
					   <div class="person_img" style="max-width: 42px ;border-radius: 25%;overflow: hidden" data-bs-custom-class="tooltip_blue"  data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="<?=$userD['user_display_name'];?>"><img style="max-width: 42px" src="<?=$obj->getDataURI($uphoto);?>" alt="<?=$userD['user_display_name'];?>"></div>
<!--                    <a data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?=$obj->get_pck_like_count($tLPRes->pck_id);?>" id="pckl_<?=$tLPRes->pck_id?>"  class="like_fid <?php if($obj->get_pck_like_status($tLPRes->pck_id,$log_user_id)){?>active<?php }?>" <?php if($log_user_id!=""){if($tLPRes->user_id!=$log_user_id){ ?>onclick="set_unset_pck_like('<?=$tLPRes->pck_id?>','<?=$log_user_id?>')"<?php }?> href="javascript:void(0);" <?php }else{?>  href="login" <?php }?>> <span class="material-symbols-outlined"> favorite </span> </a> -->
					
					
					</div>
                </div>
              </div>
            </div>
          </div>
          <?php
          }
          ?>
        </div>

		
		<h5 class="text-center mt-4">Like these ? Want to see more tours ?<br>
 <strong class="" style="color: #f26051;">Register and find more! with price and tour details. </strong> and contact tour operators or company for booking.  </h5>
		
		</div>
    </section>
    <!-- ...................Agents start................... -->
    <section class="top_user_home">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="heading_holder">
              <h2>Leading Agents</h2>
              <h4>We are <strong class="" style="color: #f26051;">World’s 1st Travel networking platform </strong>where you can <strong class="" style="color: #f26051;">directly contact with B2B and B2C tour operators or company</strong>. Who can guide or suggest you the best tour for you. you can ask any tour operators or company to customise your tour. </h4>
				<h4><strong class="" style="color: #f26051;">Register Now FREE!</strong> Find many leading tour operators or company </h4>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="user_item">
              <ul class="topagents">
                <?php
                $top_posted_users = $obj->get_top_agents( 8 );
                if ( $top_posted_users != "0" ) $userSql = $obj->selectData( TABLE_USER, "", "u_login_id in (" . $top_posted_users . ") ", "", "rand()" );
                else $userSql = $obj->selectData( TABLE_USER." as u, ".TABLE_USER_LOGIN." ul", "", "u.user_status='Active' and ul.u_suspend_status='n'", "", "rand()" );
                while ( $userD = mysqli_fetch_object( $userSql ) ) {
                  if ( isset( $userD->user_avatar ) && $userD->user_avatar != "" )$uphoto = AVATAR . $userD->user_avatar;
                  else $uphoto = NO_USER_PHOTO;
                  ?>
                <li> <a href="user-profile?uId=<?=$userD->u_login_id?>" data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="top"
						data-bs-title="<?=$userD->user_full_name;?>">
                  <div class="rounded-circle" >
                    <?php /*?>                    
					<picture style="background-image: url(<?=$uphoto?> );
                    "></picture>
					<?php */?>
                    <picture style='background-image: url("<?=$obj->getDataURI($uphoto)?>");'></picture>
                    <?php /*?>  <img src="<?=$obj->getDataURI($uphoto)?>" alt="<?=$userD->user_display_name;?>" class="img-fluid"> <?php */?>
                  </div>
                  <div class="user_name">
                    <?=$userD->user_full_name;?>
                  </div>
                  </a> </li>
                <?php
                }
                ?>
              </ul>
            </div>
          </div>
        </div>
		  <h5 class="text-center mt-4"> Want to <strong class="" style="color: #f26051;">Join the operators</strong> list and share your tour with global travelers ?<br>
 <strong class="" style="color: #f26051;">Register and List your tour today.</strong>  </h5>
      </div>
    </section>
    <!-- ...................Agents end................... --> 
    <!-- .........top package start............ -->
    <section class="related_tour_home">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="heading_holder">
              <h2>Hot Tour Deals by Global Operators </h2>
              <h4> Multiple top tour packages provided by leading agents of India. You can choose and compare tour packages. </h4>
				<h4>Lots of Travel destinations waiting for you. <strong class="" style="color: #f26051;">Register Now FREE!</strong> and choose multiple package from multiple leading tour operators or company and ask for customised any tour. </h4>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="related_item">
              <div class="row rel_holder">
                <?php
                //$top_rated_ids  = $obj->get_top_rated_packages();
                $tRPSql = $obj->selectData( TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." as ul ", "", "p.pck_top ='y' and  p.pck_status='Active'  and p.user_id=ul.u_login_id and ul.u_suspend_status='n'", "", "rand()", "", "0,20" );
                while ( $tRPRes = mysqli_fetch_object( $tRPSql ) ) {
                  $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $tRPRes->u_login_id . "'", 1 );
                  if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
                  else $uphoto = NO_USER_PHOTO;
                  ?>
                <div class="col-sm-6 col-md-4 col-lg-3 ">
                  <div class="box">
					  
					 	<div class="price_holder_home" data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="left" <?php if(!isset($_SESSION['user']['u_login_id']) || $_SESSION['user']['u_login_id']=='' ){?>data-bs-title="Login to see the deal price"<?php }?>><?php echo $obj->getCurrency($tRPRes->pck_curr);?> : <?=$obj->hide_price($tRPRes->pck_price);?>
					  
					  
					  	<?php if($tLPRes->pck_foreign_price >0){?>
					
					<span style="color:#f26051" >For foreigner <?php echo $obj->getCurrency($tLPRes->pck_foreign_curr);?> : <?=$obj->hide_price($tLPRes->pck_foreign_price);?> </span>
					 
				<?php }?>
					  
					  
					  
					  
					  
					  </div>
					  
					  <a href="tour-details?tId=<?=$tRPRes->pck_id?>">
                    <?php /*?> <picture style="background-image: url(<?=PACKAGE.$tRPRes->pck_photo?>);"></picture><?php */?>
					<?php if(file_exists(PACKAGE_OPT.$tRPRes->pck_photo)){?>
                    <picture style='background-image: url("<?=$obj->getDataURI(PACKAGE_OPT.$tRPRes->pck_photo)?>");'></picture>
					<?php }else{ ?>
					 <picture style='background-image: url("<?=$obj->getDataURI(PACKAGE.$tRPRes->pck_photo)?>");'></picture>
					<?php }?> 
                    <?php /*?><img class="item_img" src="<?=$obj->getDataURI(PACKAGE.$tRPRes->pck_photo)?>"><?php */?>
                    <div class="person_img" data-bs-custom-class="tooltip_blue"  data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?=$userD['user_display_name'];?>"><img src="<?=$obj->getDataURI($uphoto);?>" alt="<?=$userD['user_display_name'];?>"></div>
                    <div class="item_content">
                      <?php /*?> <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div><?php */?>
                      <h4>
                        <?=$tRPRes->pck_title?> 
						  <?php /*?><?php echo $tRPRes->pck_id;?><?php */?>
                      </h4>
                      <p>
                        <?=$obj->short_description_cw($tRPRes->pck_desc);?>
                      </p>
                    </div>
                    </a> </div>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      <h5 class="text-center mt-4 mb-4">Like these tours ? Want to see more tours ?<br>
 <strong class="" style="color: #f26051;">Register and find more! with price and tour details. </strong> and contact leading tour operators or company for booking.  </h5>
		</div>
    </section>
    
    <!-- ................offering paralax start................................. -->
    <section class="offer_paralex_holder"> 
      <!--
		<figure style="position: absolute; width: 100%;z-index: 1; top: 0;left: 0;height: 100%">
				<video src="video/home_faq.mp4" class="object-fit-cover" autoplay style="width: 100%;opacity: .3;"></video>	
		</figure>
-->
      
      <div class="container" >
        <div class="row">
			<div class="col-md-12 col-xl-6">
			
<h2 class="white_txt_head">Who we are !!</h2>	
				<h6> We are <span class="text-warning" >World’s 1st Travel Social networking site.</span> Where We are Connecting Travellers With Tour Operators.</h6>
				

<p>Search for your dream tourist destination and choose your travel package and operator at the same time.<br>
You can choose a tour package in your budget, also you can ask to customize your tour from the operator.</p>

<p>Compare tour packages with multiple options.</p>

<p>User Registration FREE. </p>
<p class="text-warning">Note: We are not a travel agent, we are Travel Networking Site. </p>

<p>Contact Directly with millions of B2B and B2C tour operators and grow your business.</p>

			 <p>Contact us <a href="mailto:support@mytripboat.com">support@mytripboat.com</a></p>
				
			
			 <p>Download Mobile app
<a href="https://play.google.com/store/apps/details?id=com.aegissol.mytripboat" target="_blank">
				 <img class="android"  style="max-width: 200px" src="img/gp.webp">
				 </a></p>
				
			
			</div>
          <div class="col-md-12 col-xl-6">
            <div class="">
              <h2 class="white_txt_head">What Can We Offer For You</h2>
              <h6  class="text-warning"> Find Interesting Places </h6>
              <p>We are here to help you to choose your travel destination in one search with thousand of tour package. Are you planing a trip? share with your family, friends, group, and others. let them know the plan and join you.</p>
              <p>This site is one stop destination for tour and travelers</p>
              <h6  class="text-warning"> Add your tour listing </h6>
              <p>Create your package and share with the world. let the world be your companion.</p>
              <h6  class="text-warning"> Share your travel story </h6>
              <p>We all have story for our travel experience , lets share with world. let world know about your tour exprience and wow the world . Make your tour and travel spot famous with your story.</p>
              <h6  class="text-warning">So, what are you waiting for, start your tour boat today, and let world travel with you.</h6>
             
            </div>
			 
          </div>
			
			<div class="col-12">
			
			 	 <div class=""> 
            <h4 class="text-center text-white">Follow us:</h4>
            <div class="social_box">
              <ul class="nav justify-content-center">
                <li class="nav-item"> <a class="nav-link" href="https://www.facebook.com/mytripboat" target="_blank"> <img src="img/app_facebook_logo_media_popular_icon.png" alt="" style="width:36px"> </a> </li>
                <li class="nav-item"> <a class="nav-link" href="https://twitter.com/mytripboat" target="_blank"> <img src="img/app_logo_media_popular_social_icon.png" alt="" style="width: 36px"> </a> </li>
                <li class="nav-item"> <a class="nav-link" href="https://www.instagram.com/mytripboat" target="_blank"> <img src="img/app_instagram_logo_media_popular_icon.png" alt="insta" style="width: 36px"> </a> </li>
                <li class="nav-item"> <a class="nav-link" href="https://youtu.be/glat6iUBtFQ" target="_blank"> <img src="img/youtube_ico.png" alt="" style="width: 36px"> </a> </li>
              </ul>
            </div>
          </div>
			
			</div>
			
        </div>
        <div class="row review_box">
          <div class="col-6 col-md-3"> <span class="material-symbols-outlined ico_review"> thumb_up </span>
            <div>
              <h5>1M +</h5>
              <p> Travelers </p>
            </div>
          </div>
          <div class="col-6 col-md-3"> <span class="material-symbols-outlined ico_review"> reviews </span>
            <div>
              <h5>99K +</h5>
              <p> Reviews</p>
            </div>
          </div>
          <div class="col-6 col-md-3"> <span class="material-symbols-outlined ico_review"> business_center </span>
            <div>
              <h5>1000 +</h5>
              <p>B2B Agents </p>
            </div>
          </div>
          <div class="col-6 col-md-3"> <span class="material-symbols-outlined ico_review"> business_center </span>
            <div>
              <h5>5K +</h5>
              <p>B2C Agents </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ................offering paralax end................................. --> 
    
    <!-- ...................Story start................... -->
    <?php /*?><section class="top_story_home">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="heading_holder">
              <h2>Post Your Travel Story with The World</h2>
              <h4>Our user creating tour packages for you and you can create your package too..</h4>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="story_item">
              <div class="row story_holder g-4">
                <?php
                $tSSql = $obj->selectData( TABLE_STORY." as s, ".TABLE_USER_LOGIN." as ul", "", "s.ts_status='Active' and s.ts_desc!='' and s.user_id=ul.u_login_id and ul.u_suspend_status='n'", "", "rand()", "", "0,8" );
                while ( $tSRes = mysqli_fetch_object( $tSSql ) ) {
                  //$tssData = $obj->selectData( TABLE_STORY_SUB, "", "ts_id='" . $tSRes->ts_id . "'", 1, "tss_id asc" );
                  //print_r($tssData );

                  $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $tSRes->user_id . "'", 1 );
                  if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
                  else $uphoto = NO_USER_PHOTO;
                  ?>
                <div class="col-sm-6 col-md-4 col-lg-3 ">
                  <div class="story"> <a href="story-details?tId=<?=$tSRes->ts_id?>" >
                    <div class="person_img" data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="<?=$userD['user_full_name'];?>"><img src="<?=$obj->getDataURI($uphoto);?>" alt="<?=$userD[ 'user_display_name'];?>" class="img-fluid"></div>
                    <div class="name_stry_hold">
                      <h5>
                        <?=$userD['user_full_name'];?>
                      </h5>
                     
                    </div>
                    <p>
                      <?=$obj->short_description_cw(html_entity_decode($tSRes->ts_desc,50));?>
                    </p>
                    </a>
                    <div class="tour_area"> <a href="#" class="d-flex"> <span class="material-symbols-outlined"> pin_drop </span>
                      <?=substr($tSRes->ts_title,0,20);?>
                      </a> 
						<a data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?=$obj->get_story_like_count($searchTData->ts_id);?>"
						   
						   id="tsl_<?=$tSRes->ts_id?>" <?php if($obj->get_story_like_status($tSRes->ts_id,$log_user_id)){?> class="rating_sec d-flex active"<?php }else{?> class="rating_sec d-flex" <?php }?>
					  <?php if($log_user_id!=""){ if($tSRes->user_id!=$log_user_id){ ?>onclick="set_unset_story_like('<?=$tSRes->ts_id?>','<?=$log_user_id?>')"<?php }?>href="javascript:void(0);"<?php }else{ ?>href="login"<?php }?> >
							<span class="material-symbols-outlined"> thumb_up </span> 
							 </a>
					  </div>
                  </div>
                </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><?php */?>
    <!-- ...................story end................... --> 
    <!-- ................paralax start................................. -->
    <section class="paralex_holder">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="heading_holder">
              <h2 class="white_txt_head">Just search and tour........</h2>
              <h4>The site is the platform which will provide user hazel free Tour and travel.</h4>
            </div>
          </div>
        </div>
        <div class="row block_box">
          <div class=" col-sm-3 ">
            <div class="card"> <img src="img/search_paral_1.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">The tourist guide is the platform for those who want to explore the world. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3 ">
            <div class="card"> <img src="img/search_paral_2.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">The site provide user to explore thousand of tour with maximum benefit. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3 ">
            <div class="card"> <img src="img/search_paral_3.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">We are here to help people who want to plan their own travel with our site. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3 ">
            <div class="card"> <img src="img/search_paral_4.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">Everyone now can create or introduces tour plan, and invite people to join the tour. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3">
            <div class="card"> <img src="img/search_paral_5.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">The site provide everyone to customize there tour plan and what they provided in the tour plan. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3">
            <div class="card"> <img src="img/search_paral_6.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">The traveler can Book travel package, hotel, train, flight, etc. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3 ">
            <div class="card"> <img src="img/search_paral_7.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">Also user can arrange custom tour and invite there friend or share the tour to join other people for join the tour. </p>
              </div>
            </div>
          </div>
          <div class=" col-sm-3">
            <div class="card"> <img src="img/search_paral_8.png" class="card-img-top" alt="...">
              <div class="card-body">
                <p class="card-text">Every successful booking user will earn TOKEN POINT. Which can be share to other user for booking. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ................paralax end................................. --> 
    <!-- ...................top tour end................... --> 
  </div>
</main>
	<!-- Button trigger modal -->


<!--
<div class="modal fade" id="intro" tabindex="-1" aria-labelledby="introLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <a href="https://www.mytripboat.com/login" title="register"><img src="img/adv/intro_banner_2.webp" class="img-fluid" alt="register"></a>
      </div>

    </div>
  </div>
</div>
-->
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<?php include("page_includes/index_footer_script.php");?>

<!--
	<script type="text/javascript">
    window.onload = () => {
        $('#intro').modal('show');
    }
</script>	
-->
	
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
	$(document).ready(function(){
  $('.topagents').slick({
infinite: true,
	  speed: 300,
	  slidesToShow: 8,
        slidesToScroll: 1,
	   autoplay: true,
  autoplaySpeed: 2000,
	  arrows:false,
	   centerMode: true,
	    variableWidth: true,
	    responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 6,
        slidesToScroll: 3,
       
      }
    },
    {
      breakpoint: 800,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 491,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
	  
	  
	  
	  
  });
});
	</script>
	
	<link rel="stylesheet" href="dist/plugin/jquery.vidbacking.css" type="text/css">

<script src="dist/plugin/jquery.vidbacking.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function(){
            $('#home_new_banner').vidbacking({
                'masked': true
            });
        });
    </script>
</body>
</html>

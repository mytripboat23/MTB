<?php include("includes/connection.php");
userSecure();
include("includes/all_form_check.php");


$log_user_id  = $_SESSION['user']['u_login_id'];
$user_id      = $log_user_id;

$np_comps = $obj->get_non_permitted_comps($log_user_id);

$searchTSql  = $obj->selectData(TABLE_STORY." as s, ".TABLE_USER_LOGIN." ul","","s.ts_status='Active' and s.user_id NOT IN(".$np_comps.") and ul.u_login_id=s.user_id and ul.u_suspend_status='n'","","s.ts_id desc","","0,6");
?>
<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
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
            <?=stripslashes($obj->display_message("message"));?>
            <?php include("page_includes/dash_body_nav.php");?>
            <?php
		    $last_story_id = 0;
			while($searchTData = mysqli_fetch_object($searchTSql))
			{
				//$storyD1 = $obj->selectData(TABLE_STORY_SUB,"","ts_id = '".$searchTData->ts_id."'",1,"tss_id asc");
				$sImages = explode(",",trim($searchTData->ts_photos,","));
			
					$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$searchTData->user_id."'",1);
					if($userD['user_avatar']!="") $uphoto = $userD['user_avatar'];
					else $uphoto = 'noimage.jpg';
					

			?>
            <!-- ............single block start................ -->
            <div class="single_list_tour full_height story_listing">
              <div class="tour_content">
                <div class="tour_upper">
                  <div href="#" class="operater profile_listing"> <a href="user-datils?uId=<?=$userD['u_login_id'];?>"><img src="<?=AVATAR.$uphoto;?>" width="60"></a>
                    <div class="like_area"> <a href="javascript:void(0);" id="tsl_<?=$searchTData->ts_id?>" <?php if($obj->get_story_like_status($searchTData->ts_id,$log_user_id)){?> class="active"<?php }?>
					  <?php if($searchTData->user_id!=$log_user_id){?>onclick="set_unset_story_like('<?=$searchTData->ts_id?>','<?=$log_user_id?>')"<?php }?>> <span class="material-symbols-outlined"> favorite </span> </a> </div>
                  </div>
                  <div class="tour_info">
                    <div class="tour_about">
                      <h4><a href="story-details?tId=<?=$searchTData->ts_id?>">
                        <?=$searchTData->ts_title;?>
                        </a></h4>
                       <div class="tour_conti_detail"> 
                          <div class="tour_date">
                            <?=date("F d, Y",strtotime($searchTData->ts_start));?>
                            -
                            <?=date("F d, Y",strtotime($searchTData->ts_end));?>
                          </div>
                       </div>
                    </div>

                    <div class="tour_conti_detail">
                      <div class="tour_price">
                        <!--<div class="down_arrow_people">
                          <span class="material-symbols-outlined">
                            share
                            </span>
                          <div class="see_pop">
                            <h4>Who can see and reply</h4>
                            <p>Choose who can reply to the comment</p>
                            
                            <ul class="who_see_list">
                              <li class="public_show"><a href="#">Public<span class="material-symbols-outlined">public</span></a></li>
                              <li class="friend_show"><a href="#">Friend<span class="material-symbols-outlined">group</span></a></li>
                            </ul>
                          </div>
                        </div>-->
                      <?php /*?> <div class="pre_tour_date"><?=date("F d, Y",strtotime($searchTData->ts_start));?> - <?=date("F d, Y",strtotime($searchTData->ts_end));?></div> <?php */?>
                      </div>
                      <div class="prefference">
                        <p class="follow_by">Followed by : <strong id="story_count_<?=$searchTData->ts_id?>">
                          <?=$obj->get_story_like_count($searchTData->ts_id);?>
                          </strong></p>
                      </div>
                    </div>
                  </div>
                  
              </div>
              <div class="tour_bottom">
                <p>
                  <?=$obj->short_description_cw(html_entity_decode($searchTData->ts_desc,50));?>
                </p>
                <!-- .....................Picture section start........................... -->
                <div class="picture_profile_story">
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
				  .grid{ width:100%; height: 30rem !important;}
			  .grid-item {height: 100% !important; margin-bottom:20px; border-radius: 12px; overflow: hidden; box-shadow: inset 0 0 15px 0 #ccc; }

			  .grid-item a{ display: flex; width:100%;  height: 100%;}
			  .grid-item a img{ max-width: inherit;
    max-height: inherit;
    height: inherit;
    width: inherit;
    object-fit: cover;}
			  .grid-item:first-child{width:100%; height:100% !important;}

			  </style>
                  <div class="grid">
                  <?php //for($k=0;isset($sImages[$k]),$sImages[$k]!="";$k++){
				  if(isset($sImages[0])){
				  ?>
                  
                  <picture class="grid-item"><a  data-fancybox  data-src="<?=STORY.$sImages[0];?>" > <img src="<?=STORY.$sImages[0];?>"></a></picture>
                  <?php }?>
                      
                      <!-- <picture> <img src="img/package_banner.jpg">
                        <div class="img_button"> <a href="#"><img src="img/img_agent.png">Images by Agent</a> <a href="#"><img src="img/img_travel.png">Images by traveller</a> </div>
                      </picture> --> 
                  </div>


                  

                  
                </div>
              </div>
            </div>
          </div>
          <?php
			$last_story_id = $searchTData->ts_id;
			}
			?>
          <!-- .................single block end................. -->
        </div>
        <input type="hidden" id="ldsi" value="<?=$last_story_id;?>">
        <input type="hidden" id="lstatus" value="stop">
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

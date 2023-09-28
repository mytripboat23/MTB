<?php
include "../includes/connection.php";
userSecure();

$last_story_id = $_POST['last_story_id'];

$np_comps = $obj->get_non_permitted_comps($log_user_id);

$searchTSql  = $obj->selectData(TABLE_STORY." as s, ".TABLE_USER_LOGIN." ul","","s.ts_status='Active' and s.user_id NOT IN(".$np_comps.") and ul.u_login_id=s.user_id and ul.u_suspend_status='n' and s.ts_id < '".$last_story_id."'","","s.ts_id desc","","0,4");

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
			echo "||".$last_story_id;
			?>



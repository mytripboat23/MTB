<?php
include "../includes/connection.php";
userSecure();

$last_story_id = $_POST['last_story_id'];

$np_comps = $obj->get_non_permitted_comps($log_user_id);

$searchTSql = $obj->selectData(TABLE_STORY,"","ts_status='Active' and user_id NOT IN(".$np_comps.") and ts_id < '".$last_story_id."'","","ts_id desc","","0,4");

			while($searchTData = mysqli_fetch_object($searchTSql))
			{
				//$storyD1 = $obj->selectData(TABLE_STORY_SUB,"","ts_id = '".$searchTData->ts_id."'",1,"tss_id asc");
				$sImages = explode(",",trim($searchTData->ts_photos,","));
			
					$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$searchTData->user_id."'",1);
					if($userD['user_avatar']!="") $uphoto = $userD['user_avatar'];
					else $uphoto = 'noimage.jpg';
					

			?>
            <!-- ............single block start................ -->
            <div class="single_list_tour full_with_list full_height">
              <div class="tour_content">
                <div class="tour_upper">
                  <div href="#" class="operater profile_listing"> <a href="user-datils?uId=<?=$userD['u_login_id'];?>"><img src="<?=AVATAR.$uphoto;?>" width="60"></a>
                    <div class="like_area"> <a href="javascript:void(0);" id="tsl_<?=$searchTData->ts_id?>" <?php if($obj->get_story_like_status($searchTData->ts_id,$log_user_id)){?> class="active"<?php }?>
					  <?php if($searchTData->user_id!=$log_user_id){?>onclick="set_unset_story_like('<?=$searchTData->ts_id?>','<?=$log_user_id?>')"<?php }?>> <span class="material-symbols-outlined"> favorite </span> </a> </div>
                  </div>
                  <div class="tour_about">
                    <h4><a href="story-details?tId=<?=$searchTData->ts_id?>">
                      <?=$searchTData->ts_title;?>
                      </a></h4>
                    <div class="tag_friend_prof">
                      <?=date("F d, Y",strtotime($searchTData->ts_start));?>
                      -
                      <?=date("F d, Y",strtotime($searchTData->ts_end));?>
                    </div>
                  </div>
                  <div>
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
                     <?php /*?> <div class="pre_tour_date"><?=date("F d, Y",strtotime($searchTData->ts_start));?> - <?=date("F d, Y",strtotime($searchTData->ts_end));?></div> <?php */?></div>
                  <div class="prefference">
                    <p class="follow_by">Followed by : <strong id="story_count_<?=$searchTData->ts_id?>">
                      <?=$obj->get_story_like_count($searchTData->ts_id);?>
                      </strong></p>
                  </div>
                </div>
              </div>
              <div class="tour_bottom">
                <p>
                  <?=$obj->short_description_cw(html_entity_decode($searchTData->ts_desc,50));?>
                </p>
                <!-- .....................Picture section start........................... -->
                <div class="picture_profile_story">
                  <?php //for($k=0;isset($sImages[$k]),$sImages[$k]!="";$k++){
				  if(isset($sImages[0])){
				  ?>
                  <img src="<?=STORY.$sImages[0];?>">
                  <?php }?>
                </div>
              </div>
            </div>
          </div>
          <?php
			$last_story_id = $searchTData->ts_id;
			}
			echo "||".$last_story_id;
			?>



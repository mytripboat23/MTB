<?php
include( "includes/connection.php" );
userSecure();
include( "includes/all_form_check.php" );


$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
$user_id = $obj->filter_numeric( $_REQUEST[ 'uId' ] );
$obj->valid_auth_user( $user_id );

if ( $log_user_id != $user_id ) {
  $obj->comp_profile_view_permission( $log_user_id, $user_id );
}


$searchTSql = $obj->selectData( TABLE_STORY, "", "ts_status='Active' and user_id='" . $user_id . "'", "", "ts_id desc" );
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
            $i = 0;
            while ( $searchTData = mysqli_fetch_object( $searchTSql ) ) {
              //$storyD1 = $obj->selectData(TABLE_STORY_SUB,"","ts_id = '".$searchTData->ts_id."'",1,"tss_id asc");
              $sImages = explode( ",", trim( $searchTData->ts_photos, "," ) );

              $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $searchTData->user_id . "'", 1 );
              if ( $userD[ 'user_avatar' ] != "" )$uphoto = $userD[ 'user_avatar' ];
              else $uphoto = 'noimage.jpg';


              ?>
            <!-- ............single block start................ -->
            <div class="single_list_tour full_height story_listing">
              <div class="tour_content">
                <div class="tour_upper">
                  <div href="#" class="operater profile_listing"> <a href="user-profile?uId=<?=$userD['u_login_id'];?>"><img src="<?=AVATAR.$uphoto;?>" width="60"></a>
                    <div class="like_area"> <a href="#"> <span class="material-symbols-outlined"> favorite </span> </a> </div>
                  </div>
                  <div class="tour_info">
                    <div class="tour_about">
                      <h4><a href="story-details?tId=<?=$searchTData->ts_id?>">
                        <?=$searchTData->ts_title;?>
                        </a> </h4>
                        <div class="tour_conti_detail"> 
                      <div class="tour_date">
                          <?=date("F d, Y",strtotime($searchTData->ts_start));?>
                          -
                          <?=date("F d, Y",strtotime($searchTData->ts_end));?>
                        </div>
                      </div>
                      <!--<div class="tag_friend_prof">
                        <div class="small_check">
                          <span>Oli</span>
                          <span>Ramesh</span>
                          <span>Nirmal</span>
                          </div>
                      </div>--> 
                      
                    </div>
                    <div class="tour_conti_detail">
                        <div class="tour_price"> 
                          <!-- <div class="down_arrow_people">
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
                          <?php /*?>                      <div class="pre_tour_date"><?=date("F d, Y",strtotime($searchTData->ts_start));?> - <?=date("F d, Y",strtotime($searchTData->ts_end));?> & nbsp;
    </div>
                          <?php */?>
                          <?php if($log_user_id==$user_id){?>
                          <a href="create-travel-story?tId=<?=$searchTData->ts_id?>" class="edit_tour"><span class="material-symbols-outlined">edit</span> </a>
                          <?php }?>
                        </div>
                        <div class="prefference">
                          <?php /*?> <p class="pref_by">Preffered by : <strong>110</strong></p><?php */?>
                          <p class="follow_by">Followed by : <strong id="pck_count_<?=$searchTData->ts_id;?>">
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
                  <?php //echo html_entity_decode(html_entity_decode($searchTData->ts_desc));?>
                  
                  <!-- .....................Picture section start........................... -->
                  <div class="picture_profile_story">
                    <?php //for($k=0;isset($sImages[$k]),$sImages[$k]!="";$k++){
                    if ( isset( $sImages[ 0 ] ) ) {
                      ?>
                    <img src="<?=STORY.$sImages[0];?>">
                    <?php }?>
                  </div>
                  <!-- .....................Picture section end........................... --> 
                  
                  <!-- .....................comment section start........................... --> 
                  
                  <!-- ................................comment section end......................... --> 
                </div>
              </div>
            </div>
            <?php
            $i++;
            }
            ?>
            <?php if($i==0){?>
            <div class="alert alert-primary" role="alert"> You do not have any story. Please <a href="create-travel-story">click here</a> to create your story. </div>
            <?php }?>
            <!-- .................single block end................. --> 
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
<?php include("page_includes/profile_footer_script.php");?>
<?php include("page_includes/dash_comment_script.php");?>
</body>
</html>

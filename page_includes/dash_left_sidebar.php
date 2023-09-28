<?php
$userD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $user_id . "'", 1 );
$userPD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $user_id . "'", 1 );

if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
else $uphoto = NO_USER_PHOTO;

if ( $userPD[ 'user_avatar' ] != "" || $userPD[ 'user_avatar' ] != NULL )$upphoto = AVATAR . $userPD[ 'user_avatar' ];
else $upphoto = NO_USER_PHOTO;

if ( $userPD[ 'user_banner' ] != "" || $userPD[ 'user_banner' ] != NULL )$upbanner = CBANNER . $userPD[ 'user_banner' ];
else $upbanner = NO_BANNER;


if ( $log_user_id == $user_id ) {

  ?>
<!-- ................Tor operator.............. -->
<section class="operator_personal top_none">
  <div class="approved_tick <?php if($userD['user_profile_vf']=='n'){?>text-muted<?php }?>" type="button" <?php if($userD['user_profile_vf']=='n'){?>data-bs-toggle="modal" data-bs-target="#verification_modal"<?php }?>><span class="material-symbols-outlined">new_releases</span></div>
  <div class="upload_imag_result">
    <div class="imageupload"  style=" background-image: url(<?=$uphoto;?>); background-repeat: no-repeat;" > </div>
    <label class="bottom-semi-circle" data-bs-target="#reg_modal" data-bs-toggle="modal"  style="cursor: pointer;" > <span class="material-symbols-outlined"> add_a_photo </span> </label>
  </div>
  <h3><a href="user-profile?uId=<?=$userD['user_id']?>">
    <?=$userD['user_display_name'];?>
    </a></h3>
	 <h3> 
	<?php if(strpos($userPD['user_type'],'ind')!== false){?><span class="badge rounded-pill text-bg-dark">Tour Oparator </span><?php }?>
	<?php if(strpos($userPD['user_type'],'b2b')!== false){?><span class="badge rounded-pill text-bg-primary">B2B</span><?php }?>
	<?php if(strpos($userPD['user_type'],'b2c')!== false){?><span class="badge rounded-pill text-bg-warning">B2C</span><?php }?>
	</h3>
	
	

	
  <div class="travel_operator_hike">
    <div class="rating_number"> <span>
      <?=$obj->get_user_friends($userD['user_id']);?>
      </span> Companions </div>
    <div class="follow_by_number"> <span class="">
      <?=$obj->get_user_follower($userD['user_id']);?>
      </span> Followers </div>
  </div>
  <p>
    <?=$userD['user_bio'];?>
  </p>
  <h4 class="address_operator">
    <?=$userD['user_city'];?>
  </h4>
  <ul class="hobis">
    <?php
    $hobby_ids = trim( $userD[ 'user_hobby' ], "," );
    if ( $hobby_ids == "" )$hobby_ids = "0";
    $sqlH = $obj->selectData( TABLE_HOBBIES, "", "hobby_id in (" . $hobby_ids . ")" );
    while ( $resH = mysqli_fetch_object( $sqlH ) ) {
      ?>
    <li>
      <?=$resH->hobby_title;?>
    </li>
    <?php }?>
  </ul>
  <div class="button_area"> <a href="#reg_modal" data-bs-toggle="modal"  class="edit_profile add_comanion_btn"><span class="material-symbols-outlined">edit</span> Edit profile </a> <a href="#offcanvasnotification" data-bs-toggle="offcanvas" aria-controls="offcanvasnotification" class="add_comanion_btn position-relative noitfication text-center"> <span class="material-symbols-outlined"> notifications_active </span> <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger noti_txt"><?php echo $obj->total_unread_notification($log_user_id);?><span class="visually-hidden">unread messages</span> </span> </a> </div>
</section>
<?php }else{ ?>
<section class="operator_personal top_none">
  <div class="approved_tick <?php if($userD['user_profile_vf']=='n'){?>text-muted<?php }?>"><span class="material-symbols-outlined">new_releases</span></div>
  <div class="upload_imag_result">
    <div class="imageupload" style=" background-image: url(<?=$uphoto;?>); background-repeat: no-repeat;"> </div>
    <?php /*?> <label class="bottom-semi-circle point_cursor" for="profileImageFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label><?php */?>
  </div>
  <h3><a href="user-profile?uId=<?=$userD['user_id']?>">
    <?=$userD['user_full_name'];?> 
    </a></h3>
	<h3> 
	<?php if(strpos($userPD['user_type'],'ind')!== false){?><span class="badge rounded-pill text-bg-dark">Tour Oparator </span><?php }?>
	<?php if(strpos($userPD['user_type'],'b2b')!== false){?><span class="badge rounded-pill text-bg-primary">B2B</span><?php }?>
	<?php if(strpos($userPD['user_type'],'b2c')!== false){?><span class="badge rounded-pill text-bg-warning">B2C</span><?php }?>
	</h3>
  <div class="travel_operator_hike">
    <div class="rating_number"> <span>
      <?=$obj->get_user_friends($userD['user_id']);?>
      </span> Companions </div>
    <div class="follow_by_number"> <span class="">
      <?=$obj->get_user_follower($userD['user_id']);?>
      </span> Followers </div>
  </div>
  <p>
    <?=$userD['user_bio'];?>
  </p>
  <h4 class="address_operator">
    <?=$obj->getFieldValueByFieldName(TABLE_CITIES,"city_title","city_id",$userD['user_city']);?>
  </h4>
  <?php if($userD['user_hobby']!="" && $userD['user_hobby']!=NULL){?>
  <ul class="hobis">
    <?php
    $hobby_ids = trim( $userD[ 'user_hobby' ], "," );
    if ( $hobby_ids == "" )$hobby_ids = "0";
    $sqlH = $obj->selectData( TABLE_HOBBIES, "", "hobby_id in (" . $hobby_ids . ")" );
    while ( $resH = mysqli_fetch_object( $sqlH ) ) {
      ?>
    <li>
      <?=$resH->hobby_title;?>
    </li>
    <?php }?>
  </ul>
  <?php }?>
  <div class="button_area">
    <?php
	if($log_user_id!='')
	{
    $com_status = $obj->companion_request_status( $log_user_id, $user_id );
    if ( $com_status == '' || $com_status == 'R' || $com_status == 'C' ) {
      ?>
    <a href="javascript:void(0)" class="add_comanion_btn" onclick="send_companion_request('<?=$log_user_id?>','<?=$user_id?>')"> <span class="material-symbols-outlined"> person_add </span> Companion Request </a>
    <?php }else{?>
    <a href="javascript:void(0)" class="add_comanion_btn" onclick="remove_companion_request('<?=$log_user_id?>','<?=$user_id?>')"> <span class="material-symbols-outlined"> person_add </span> Remove Companion </a>
    <?php }}?>
    <!-- <a class="mail" href="#">Mail</a>--> </div>
</section>
<?php }?>
<!-- ................list.............. -->
<section class="personal_list">
  <ul>
    <?php /*?>
<li> <img src="img/star_ico.png"> Rating: <span>
    <?=number_format($obj->user_package_rating($user_id),1);?>
  </span> </li>
    <?php */?>
    <li> <img src="img/complete_ico.png"> Complete: <span> <a href="tours?uId=<?php echo $user_id;?>&type=completed">
      <?=$obj->completed_tours_by_user($user_id);?>
      Tours</a></span> </li>
    <!-- <li> <img src="img/recomend_ico.png"> Recomended: <span> 50?</span> </li>-->
    <li> <img src="img/follow_ico.png"> Follow: <span>
      <?=$obj->get_total_likes($user_id);?>
      </span> </li>
    <li> <img src="img/running_ico.png"> Ongoing: <span> <a href="tours?uId=<?php echo $user_id;?>&type=ongoing">
      <?=$obj->ongoing_tours_by_user($user_id);?>
      Tours</a> </span> </li>
  </ul>
</section>
<section class="profile_list_menu">
  <ul>
    <?php if($log_user_id == $user_id ){?>
    <li><a href="user-profile?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> About me</a></li>
    <li><a href="tours?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Tours</a></li>
    <li><a href="companion?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Companions</a></li>
    <!--<li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My club</a></li>-->
    <li><a href="user-stories?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Stories</a></li>
    <?php }else{ ?>
    <li><a href="user-profile?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> Profile</a></li>
    <li><a href="tours?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> Tours</a></li>
    <li><a href="companion?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span>Companions</a></li>
    <!-- <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> Clubs</a></li>-->
    <li><a href="user-stories?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> Stories</a></li>
    <?php }?>
  </ul>
</section>
<?php
if ( $obj->get_user_friends( $userD[ 'user_id' ] ) ) {
  if ( $log_user_id == $user_id )$clink = 'companion';
  else $clink = 'companion?uId=' . $user_id;
  ?>
<section class="companios">
  <h3>Companions <a class="all_link" href="<?=$clink ;?>">All</a></h3>
  <ul>
    <?php
    $sqlF = $obj->selectData( TABLE_FRIENDS, "", "fr_status='A' and (fr_to_id = '" . $user_id . "' or fr_from_id = '" . $user_id . "')" );
    while ( $resF = mysqli_fetch_object( $sqlF ) ) {
      if ( $resF->fr_to_id != $user_id )$fr_id = $resF->fr_to_id;
      else $fr_id = $resF->fr_from_id;

      $sqlFAU = $obj->selectData( TABLE_USER_LOGIN . " as ul inner join " . TABLE_USER . " as u on u.u_login_id=ul.u_login_id", "", "ul.u_login_status='Active' and u.user_status='Active' and ul.u_login_id='" . $fr_id . "'", 1 );
      if ( isset( $sqlFAU[ 'u_login_id' ] ) && $sqlFAU[ 'u_login_id' ] != '' ) {

        $userD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $fr_id . "'", 1 );
        if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
        else $uphoto = NO_USER_PHOTO;
        ?>
    <li><a href="user-profile?uId=<?=$userD['u_login_id'];?>" class="add_post_opert"><img src="<?=$uphoto?>" width="72"></a></li>
    <?php
    }
    }
    ?>
  </ul>
</section>
<?php }?>
<?php
$photos = explode( ",", $obj->get_user_photos( $user_id ) );
if ( $photos[ 0 ] != "" ) {
  ?>
<section class="diary_gallery">
  <h3>Photos <a class="all_link" href="photo-gallery?uId=<?=$user_id;?>">All</a></h3>
  <ul>
    <?php
    for ( $m = 0; isset( $photos ), $photos[ $m ] != ""; $m++ ) {
      if ( $m < 9 ) {
        ?>
    <li>
      <picture> <a href="photo-gallery?uId=<?=$user_id;?>"><img src="<?=$photos[$m]?>" alt="">
        <?php if($m==8){ if(count($photos)>9){?>
        <span>+<?php echo count($photos)-9;?></span>
        <?php }}?>
        </a></picture>
    </li>
    <?php
    }
    }
    ?>
  </ul>
</section>
<?php
}
?>
<?php if($userD['user_profile_vf']=='n'){?>
<!-- Verification Modal-->
<div class="modal fade-scale pop_box" id="verification_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="verification_modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="verification_modalLabel"> Request Profile Verification</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form name="updateId" id="updateId" action="" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <h3> Please verified your profile and get uptodated. </h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
          <h5>You can submit any your ID card with image and a self image</h5>
          <div class="mt-2">
            <div class="col-sm-12 form_row_holder mb-3">
              <label for="floatingInput1">ID Number<sup>*</sup> </label>
              <input type="text" class="form-control" id="id_num" name="id_num" placeholder="XXXXXXXX">
            </div>
            <div class="devide_box">
              <div class="col-sm-6 form_row_holder" >
                <picture class="image_upload_block">
                  <label class="input-group-text" for="id_photo">Upload ID image</label>
                  <input type="file" class="" id="id_photo" name="id_photo" title=".jpg .jpeg .png .gif">
                  <span>.jpg .jpeg .png .gif</span> </picture>
              </div>
              <div class="col-sm-6 form_row_holder">
                <picture class="image_upload_block">
                  <label class="input-group-text" for="id_self_photo">Upload self image</label>
                  <input type="file" class="" id="id_self_photo" name="id_self_photo" title=".jpg .jpeg .png .gif">
                  <span>.jpg .jpeg .png .gif</span> </picture>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer"> 
          <!-- <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button> -->
          <input type="submit" id="update_id_details" name="update_id_details" value="Submit" class="btn btn-primary" />
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Verification Modal end -->
<?php }?>


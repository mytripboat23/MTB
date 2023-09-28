<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>-->
<style>
/*mobile menu new start*/

.offcanvas-title a {
    color: #fff;
}
#mobile_menu_holder .nav-item a {
    display: flex;
    justify-content: center;
    flex-direction: column;
}
#mobile_menu_holder .nav-item a .but_name {
    font-size: 1.2rem;
    white-space: nowrap;
}
#mobile_menu_holder {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    /*			min-height: 100px;*/
    background: #890d0d;
    z-index: 9999;
}
#mobile_menu_holder .nav .nav-item .nav-link {
    font-size: 36px;
    color: aliceblue;
}
.offcanvas-body {
    height: 94vh;
}
.offcanvas-header {
    background: #f26051;
    color: #fff;
}
.offcanvas-header .btn-close {
    background: #fff;
    color: #f26051;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    justify-content: center;
    display: flex;
    align-items: center;
    opacity: 1;
}
.notice_list a {
    font-size: 1.5rem;
    padding: 1rem;
}
.notice_list a .image_holder {
    max-width: 62px;
}
	
	
/*mobile menu new end*/
</style>


<!--- after login menu -->
<?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=''){?>
<div id="mobile_menu_holder" class=" d-block d-md-none">
  <ul class="nav nav-pills nav-fill nav-justified">
    <!--        <li class="nav-item"> <a href="<?=FURL;?>" class="nav-link" title="Home"><span class="material-symbols-outlined" > home </span> <span class="but_name">Home</span></a> </li>-->
    <li class="nav-item"> <a href="<?=FURL;?>dashboard" class="nav-link" title="Search Tour"><span class="material-symbols-outlined"> travel_explore </span><span class="but_name">Tour list</span></a> </li>
    <li class="nav-item"> <a href="<?=FURL;?>stories" class="nav-link" title="Search Story"><span class="material-symbols-outlined">frame_inspect</span><span class="but_name">Story</span></a> </li>
    <li class="nav-item"> <a href="<?=FURL;?>create-package" class="nav-link" title="Create Package"><span class="material-symbols-outlined"> widgets </span><span class="but_name">Create package</span></a> </li>
    <li class="nav-item"> <a href="#offcanvasnotification" data-bs-toggle="offcanvas" aria-controls="offcanvasnotification" class="nav-link" title="Notification"><span class="material-symbols-outlined"> notifications_active </span><span class="but_name">Notification</span></a> </li>
    <li class="nav-item"> <a href="#offset_user_profile" data-bs-toggle="offcanvas" aria-controls="offset_user_profile" class="nav-link" title="My account"><span class="material-symbols-outlined"> account_circle </span><span class="but_name">Account</span></a> </li>
  </ul>
</div>
<?php }else{ ?>
<div id="mobile_menu_holder" class=" d-block d-md-none">
  <ul class="nav nav-pills nav-fill nav-justified">
    <li class="nav-item"> <a href="<?=FURL;?>" class="nav-link" title="Home"><span class="material-symbols-outlined" > home </span><span class="but_name">Home</span></a> </li>
    <li class="nav-item"> <a href="<?=FURL;?>stories" class="nav-link" title="Search Story"><span class="material-symbols-outlined">frame_inspect</span><span class="but_name">Story</span></a> </li>
    <li class="nav-item"> <a href="<?=FURL;?>dashboard" class="nav-link" title="Search Tour"><span class="material-symbols-outlined"> travel_explore </span><span class="but_name">Tour list</span></a> </li>
    <li class="nav-item"> <a href="<?=FURL;?>create-package" class="nav-link" title="Create Package"><span class="material-symbols-outlined"> widgets </span><span class="but_name">Create package</span></a> </li>
    <li class="nav-item"> <a href="<?=FURL;?>login" class="nav-link" title="Login"><span class="material-symbols-outlined"> account_circle </span><span class="but_name">Account</span></a> </li>
  </ul>
</div>
<?php }?>
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="1" id="offcanvasnotification" aria-labelledby="offcanvasnotificationLabel">
  <div class="offcanvas-header">
    <h2 class="offcanvas-title" id="offcanvasnotificationLabel"><a href="<?=FURL;?>notification">Notifications</a></h2>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"> <span class="material-symbols-outlined"> close </span> </button>
  </div>
  <div class="offcanvas-body">
    <div class="list-group list-group-flush notice_list">
      <?php
      $log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
      $notiSql = $obj->selectData( TABLE_NOTIFICATION, "", "noti_status='Active' and noti_for_user='" . $log_user_id . "'", "", "noti_id desc" );
      while ( $notiD = mysqli_fetch_array( $notiSql ) ) {
        $userD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $notiD[ 'noti_from_user' ] . "'", 1 );
        if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
        else $uphoto = NO_USER_PHOTO;
        ?>
      <a href="<?=$notiD['noti_url']?>" class="list-group-item list-group-item-action text-bg-light" >
      <div class="d-flex align-items-center">
        <div class="flex-shrink-0 image_holder"> <img src="<?=$uphoto;?>" alt="..." class="rounded-circle w-100"> </div>
        <div class="flex-grow-1 ms-3">
          <?=$notiD['noti_message']?>
        </div>
      </div>
      </a>
      <?php }?>
    </div>
  </div>
</div>
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="1" id="offset_user_profile" aria-labelledby="offset_user_profileLabel">
  <div class="offcanvas-body">
    <?php
    if ( $log_user_id == $user_id ) {
      $userD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $log_user_id . "'", 1 );
      if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
      else $uphoto = NO_USER_PHOTO;
      ?>
    <section class="operator_personal top_none">
      <div class="approved_tick <?php if($userD['user_profile_vf']=='n'){?>text-muted<?php }?>" type="button" <?php if($userD['user_profile_vf']=='n'){?>data-bs-toggle="modal" data-bs-target="#verification_modal"<?php }?>><span class="material-symbols-outlined">new_releases</span></div>
      <div class="upload_imag_result">
        <form >
          <div class="imageupload"  style=" background-image: url(<?=$uphoto;?>); background-repeat: no-repeat;" > 
            <!--                    <input type="file" name="profileImageFile" id="profisleImageFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}">--> 
          </div>
          <!--                  <label class="bottom-semi-circle point_cursor" for="profisleImageFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>-->
        </form>
      </div>
      <h3><a href="user-profile?uId=<?=$userD['user_id']?>">
        <?=$userD['user_display_name'];?>
        </a></h3>
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
        $sqlH = $obj->selectData( TABLE_HOBBIES, "", "hobby_id in (" . $hobby_ids . ")" );
        while ( $resH = mysqli_fetch_object( $sqlH ) ) {
          ?>
        <li>
          <?=$resH->hobby_title;?>
        </li>
        <?php }?>
      </ul>
      <?php }?>
      <div class="button_area"> <a href="#" data-bs-toggle="modal" data-bs-target="#reg_modal" class="edit_profile add_comanion_btn"> <span class="material-symbols-outlined">edit</span> Edit profile </a>
        <?php /*?> <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" class="add_comanion_btn position-relative noitfication text-center"> <span class="material-symbols-outlined"> notifications_active </span> <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"> 99+ <span class="visually-hidden">unread messages</span> </span> </a><?php */?>
      </div>
    </section>
    <?php
    } else {
      $userD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $user_id . "'", 1 );
      if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
      else $uphoto = NO_USER_PHOTO;
      ?>
    <section class="operator_personal top_none">
      <div class="approved_tick <?php if($userD['user_profile_vf']=='n'){?>text-muted<?php }?>"><span class="material-symbols-outlined">new_releases</span></div>
      <div class="upload_imag_result">
        <div class="imageupload" style=" background-image: url(<?=$uphoto;?>); background-repeat: no-repeat;"> </div>
      </div>
      <h3><a href="user-profile?uId=<?=$userD['user_id']?>">
        <?=$userD['user_full_name'];?>
        </a></h3>
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
        $com_status = $obj->companion_request_status( $log_user_id, $user_id );
        if ( $com_status == '' || $com_status == 'R' || $com_status == 'C' ) {
          ?>
        <a href="javascript:void(0)" class="add_comanion_btn" onclick="send_companion_request('<?=$log_user_id?>','<?=$user_id?>')"> <span class="material-symbols-outlined"> person_add </span> Companion </a>
        <?php }else{?>
        <a href="javascript:void(0)" class="add_comanion_btn" onclick="remove_companion_request('<?=$log_user_id?>','<?=$user_id?>')"> <span class="material-symbols-outlined"> person_add </span> Remove Companion </a>
        <?php }?>
        
        <!--<a class="mail" href="#">Mail</a>--> </div>
    </section>
    <?php }?>
    
    <!-- ................list.............. -->
    <section class="personal_list">
      <ul>
        <?php /*?><li> <img src="img/star_ico.png"> Rating: <span><?=number_format($obj->user_package_rating($user_id),1);?></span> </li><?php */?>
        <li> <img src="img/complete_ico.png"> Complete: <span>
          <?=$obj->completed_tours_by_user($user_id);?>
          Tours</span> </li>
        <li> <img src="img/follow_ico.png"> Follow: <span>
          <?=$obj->get_total_likes($user_id);?>
          </span> </li>
        <li> <img src="img/running_ico.png"> Ongoing: <span>
          <?=$obj->ongoing_tours_by_user($user_id);?>
          </span> </li>
      </ul>
    </section>
    <?php if($obj->get_user_friends($userD['user_id'])){?>
    <section class="companios">
      <h3>Companions <a class="all_link" href="companion?uId=<?=$user_id;?>">All</a></h3>
      <ul>
        <?php
        $sqlF = $obj->selectData( TABLE_FRIENDS, "", "fr_status='A' and (fr_to_id = '" . $user_id . "' or fr_from_id = '" . $user_id . "')" );
        while ( $resF = mysqli_fetch_object( $sqlF ) ) {
          if ( $resF->fr_to_id != $user_id )$fr_id = $resF->fr_to_id;
          else $fr_id = $resF->fr_from_id;

          $userD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $fr_id . "'", 1 );
          if ( $userD[ 'user_avatar' ] != "" )$uphoto = AVATAR . $userD[ 'user_avatar' ];
          else $uphoto = NO_USER_PHOTO;
          ?>
        <li><a href="user-profile?uId=<?=$userD['u_login_id'];?>" class="add_post_opert"><img src="<?=$uphoto?>"></a></li>
        <?php
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
          <picture> <a href="#"><img src="<?=$photos[$m]?>" alt="">
            <?php if($m==8){ if(count($photos)>9){?>
            <span>+<?php echo count($photos)-9;?></span>
            <?php }}?>
            </a> </picture>
        </li>
        <?php
        }
        }
        ?>
      </ul>
    </section>
    <?php }?>
    <section class="profile_list_menu">
      <ul>
        <?php if($log_user_id == $user_id ){?>
        <li><a href="user-profile?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> About me</a></li>
        <li><a href="tours?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Tours</a></li>
        <!--                <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My club</a></li>-->
        <li><a href="user-stories?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Stories</a></li>
        <?php }else{ ?>
        <li><a href="user-profile?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> About me</a></li>
        <li><a href="tours?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Tours</a></li>
        <!--                <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My club</a></li>-->
        <li><a href="user-stories?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> My Stories</a></li>
        <?php }?>
        <li><a href="faq"><span><img src="img/about_me_ico.png" alt=""></span>FAQ</a></li>
        <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
        <li><a href="logout"><span><img src="img/about_me_ico.png" alt=""></span> Logout</a></li>
        <?php }else{?>
        <li><a href="login"> <span><img src="img/about_me_ico.png" alt=""></span>Login</a></li>
        <?php }?>
      </ul>
    </section>
  </div>
</div>

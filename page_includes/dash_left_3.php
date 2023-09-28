<?php
$user_id = $_SESSION['user']['u_login_id'];
$userD   = $obj->selectData(TABLE_USER,"","u_login_id='".$user_id."'",1);

				if($userD['user_avatar']!="") $uphoto = AVATAR.$userD['user_avatar'];
				else $uphoto = NO_USER_PHOTO; 
				
				 
?>
     <!-- ................Tor operator.............. -->
            <section class="operator_personal top_none">
              <div class="approved_tick"><span class="material-symbols-outlined">new_releases</span></div>
              <div class="upload_imag_result">
                <div class="imageupload" style=" background-image: url(<?=$uphoto;?>); background-repeat: no-repeat;">
                  <form name="uploadProfilePhoto" id="uploadProfilePhoto" action="#" method="post" enctype="multipart/form-data">
                    <input type="file" name="profileImageFile" id="profileImageFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}">
                  </form>
                </div>
                <label class="bottom-semi-circle point_cursor" for="profileImageFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>
              </div>
              <h3><a href="user-profile?uId=<?=$userD['user_id']?>"><?=$userD['user_full_name'];?></a></h3>
              <div class="travel_operator_hike">
                <div class="rating_number"> <span><?=$obj->get_user_friends($userD['user_id']);?></span> Companions </div>
                <div class="follow_by_number"> <span class=""><?=$obj->get_user_follower($userD['user_id']);?></span> Followers </div>
              </div>
              <p><?=$userD['user_bio'];?></p>
              <h4 class="address_operator"><?=$obj->getFieldValueByFieldName(TABLE_CITIES,"city_title","city_id",$userD['user_city']);?></h4>
			  <?php if($userD['user_hobby']!="" && $userD['user_hobby']!=NULL){?>
              <ul class="hobis">
			  <?php
			  	$hobby_ids = trim($userD['user_hobby'],",");
			    $sqlH = $obj->selectData(TABLE_HOBBIES,"","hobby_id in (".$hobby_ids.")");
				while($resH = mysqli_fetch_object($sqlH))
				{
			  ?>
                <li><?=$resH->hobby_title;?></li>
			  <?php }?>
              </ul>
			  <?php }?>
              <div class="button_area"> <a href="create_package.html" class="add_comanion_btn"> <span class="material-symbols-outlined"> person_add </span> Companion </a> <a class="mail" href="#">Mail</a> </div>
            </section>
            <!-- ................list.............. -->
            <section class="personal_list">
              <ul>
				<li> <img src="img/star_ico.png"> Rating: <span><?=number_format($obj->user_package_rating($user_id),1);?></span> </li>
				<li> <img src="img/complete_ico.png"> Complete: <span> <?=$obj->completed_tours_by_user($user_id);?> Tours</span> </li>
				<li> <img src="img/recomend_ico.png"> Recomended: <span> 50?</span> </li>
				<li> <img src="img/follow_ico.png"> Follow: <span> 1000?</span> </li>
				<li> <img src="img/running_ico.png"> Ongoing: <span> <?=$obj->ongoing_tours_by_user($user_id);?></span> </li>
              </ul>
            </section>
			<?php if($obj->get_user_friends($userD['user_id'])){?>
            <section class="companios">
              <h3>Companions <a class="all_link" href="#">All</a></h3>
              <ul>
			   <?php 
			    $sqlF = $obj->selectData(TABLE_FRIENDS,"","fr_status='A' and (fr_to_id = '".$user_id."' or fr_from_id = '".$user_id."')");
			  	while($resF = mysqli_fetch_object($sqlF))
				{ 
					if($resF->fr_to_id!=$userId) $fr_id = $resF->fr_to_id;
					else $fr_id = $resF->fr_from_id;
					
				$userD   = $obj->selectData(TABLE_USER,"","u_login_id='".$fr_id."'",1);
				if($userD['user_avatar']!="") $uphoto = AVATAR.$userD['user_avatar'];
				else $uphoto = NO_USER_PHOTO; 
			   ?>
                <li><a href="user-profile?uId=<?=$userD['u_login_id'];?>" class="add_post_opert"><img src="<?=$uphoto?>" width="72"></a></li>
				<?php
				}
				?>
              </ul>
            </section>
			<?php }?>
            <section class="diary_gallery">
              <h3>Photos <a class="all_link" href="#">All</a></h3>
              <ul>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
                <li> <a href="#">
                  <picture> <img src="img/tour_img.jpg" alt=""> <span class="material-symbols-outlined"> favorite </span> </picture>
                  </a> </li>
              </ul>
            </section>
            <section class="profile_list_menu">
              <ul>
                <li><a href="user-profile?uId=<?=$user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> About me</a></li>
                <li><a href="my-tours"><span><img src="img/about_me_ico.png" alt=""></span> My Tours</a></li>
                <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My club</a></li>
                <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My Stores</a></li>
              </ul>
            </section>
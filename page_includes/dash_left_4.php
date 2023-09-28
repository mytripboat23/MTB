<?php
$log_user_id = $_SESSION['user']['u_login_id'];
$user_id     = $_REQUEST['uId'];
$userD       = $obj->selectData(TABLE_USER,"","u_login_id='".$user_id."'",1);

				if($userD['user_avatar']!="") $uphoto = AVATAR.$userD['user_avatar'];
				else $uphoto = NO_USER_PHOTO; 
				
				 
?>
  <section class="operator_personal top_none">
              <div class="approved_tick"><span class="material-symbols-outlined">new_releases</span></div>
              <div class="upload_imag_result">
			  
                <div class="imageupload" style=" background-image: url(<?=$uphoto;?>); background-repeat: no-repeat;">
				<?php if($user_id == $log_user_id){?>
                  <form name="uploadProfilePhoto" id="uploadProfilePhoto" action="#" method="post" enctype="multipart/form-data">
                    <input type="file" name="profileImageFile" id="profileImageFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}">
                  </form>
				 <?php }?> 
                </div>
				<?php if($user_id == $log_user_id){?>
                <label class="bottom-semi-circle point_cursor" for="profileImageFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>
				 <?php }?> 
              </div>
              <h3><a href="user-profile.php?uId=<?=$userD['u_login_id']?>"><?=$userD['user_full_name'];?></a></h3>
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
			  <?php if($user_id == $log_user_id){?>
              <div class="button_area"> <a href="#edit_pop" rel="modal:open" class="edit_profile add_comanion_btn"> <span class="material-symbols-outlined">edit</span> Edit profile </a>
              </div>
			   <?php }?>
            </section>
            <?php if($obj->get_user_friends($userD['user_id'])){?>
            <section class="companios">
              <h3>Companions <a class="all_link" href="#">All</a></h3>
              <ul>
			   <?php 
			    $sqlF = $obj->selectData(TABLE_FRIENDS,"","fr_status='A' and (fr_to_id = '".$user_id."' or fr_from_id = '".$user_id."')");
			  	while($resF = mysqli_fetch_object($sqlF))
				{ 
					if($resF->fr_to_id!=$user_id) $fr_id = $resF->fr_to_id;
					else $fr_id = $resF->fr_from_id;
					
				$userD   = $obj->selectData(TABLE_USER,"","u_login_id='".$fr_id."'",1);
				if($userD['user_avatar']!="") $uphoto = AVATAR.$userD['user_avatar'];
				else $uphoto = NO_USER_PHOTO; 
			   ?>
                <li><a href="user-profile.php?uId=<?=$userD['u_login_id'];?>" class="add_post_opert"><img src="<?=$uphoto?>" width="72"></a></li>
				<?php
				}
				?>
              </ul>
            </section>
			<?php }?>
            <section class="profile_list_menu">
              <ul>
                <li><a href="user-profile.php?uId=<?=$log_user_id;?>"><span><img src="img/about_me_ico.png" alt=""></span> About me</a></li>
                <li><a href="my-tours.php"><span><img src="img/about_me_ico.png" alt=""></span> My Tours</a></li>
                <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My club</a></li>
                <li><a href="#"><span><img src="img/about_me_ico.png" alt=""></span> My Stores</a></li>
              </ul>
            </section>
			
			
			
			 <!-- .............pop up start............ -->
                <div id="edit_pop" class="modal edit_info">
                  <form name="editPro" id="editPro" action="#" method="post" enctype="multipart/form-data">
                    <div class="motal_top">
                      <h4>Edit Profile</h4>
                      <input id="proSave" type="button" name="proSave" value="Save" class="btn btn-primary" onclick="profile_data_validation()">
                    </div>
                    <div class="form_row_forbanner">
                      <div class="upload_imag_result">
                        <div class="imageupload" id="bFile" style=" background-image: url(<?=$upbanner;?>); background-repeat: no-repeat;">
                          <input type="file" name="profileBannerFile" id="profileBannerFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}">
                        </div>
                        <label class="bottom-semi-circle point_cursor" for="profileBannerFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>
                      </div>
                    </div>
                    <div class="profile_self_img">
                      <div class="upload_imag_result">
                        <div class="imageupload" id="pFile" style=" background-image: url(<?=$upphoto;?>); background-repeat: no-repeat;">
                          <input type="file" name="profileImageFile" id="profileImageFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}" >
                        </div>
                        <label class="bottom-semi-circle point_cursor" for="profileImageFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>
                      </div>
                    </div>
                    <div class="form_row_holder">
                      <label class="labelmsg">Live</label>
					  <input type="hidden" id="us_city_hid" name="us_city_hid" value="<?=$userPD['user_city'];?>" />
                      <input name="us_city" id="us_city" maxlength="" type="text" class="form-control" value="<?=$obj->getFieldValueByFieldName(TABLE_CITIES,"city_title","city_id",$userPD['user_city']);?>" placeholder="Enter where you live" >
					  <div id="suggestion-us-city"><ul id="country-list"></div>
                    </div>
                    <div class="form_row_holder">
                      <label class="labelmsg">From</label>
					  <input type="hidden" id="us_from_city_hid" name="us_from_city_hid" value="<?=$userPD['user_from_city'];?>" />
                      <input maxlength="" id="us_from_city" name="us_from_city" type="text" class="form-control" value="<?=$obj->getFieldValueByFieldName(TABLE_CITIES,"city_title","city_id",$userPD['user_from_city']);?>" placeholder="Enter where you from">
					  <div id="suggestion-us-from-city"></div>
                    </div>
                    
                    <div class="form_row_holder">
                      <label class="labelmsg">Phone number</label>
                      <input maxlength="" type="text" name="us_phone" id="us_phone"  class="form-control" value="<?=$userPD['user_phone'];?>" placeholder="Enter your phone number">
                   <span class="messages" id="us_phone_message"></span>
				    </div>
                    <div class="form_row_holder">
                      <label class="labelmsg">Website</label>
                      <input maxlength="" type="text" name="us_website" id="us_website" class="form-control" value="<?=$userPD['user_website'];?>" placeholder="Enter your website">
                    </div>
                    <div class="form_row_holder">
                      <label class="labelmsg">Gender</label>
					  <select name="us_gender" class="form-control">
						  <option value="m" <?=$userPD['user_gender']=="m"?'selected="selected"':'';?>>Male</option>
						  <option value="f"  <?=$userPD['user_gender']=="f"?'selected="selected"':'';?>>Female</option>
					   </select>	
                    </div>
                    <div class="form_row_holder">
                      <label class="labelmsg">Date of birth</label>
                      <input maxlength="" type="date" name="us_dob" id="us_dob" class="form-control" value="<?=$userPD['user_dob'];?>" placeholder="Enter your date of birth">
                    </div>
                    <div class="form_row_holder">
                      <label class="labelmsg">Language</label>
                      <select class="select form-control" name="us_lang[]" id="us_lang" multiple>
						<?=$obj->languageSelect($userPD['user_lang']);?>
					</select>
                    </div>
					 <div class="form_row_holder">
                      <label class="labelmsg">Hobby</label>
                      <select class="select form-control" name="us_hobby[]" id="us_hobby" multiple>
						<?=$obj->hobbySelect($userPD['user_hobby']);?>
					</select>
                    </div>
                  </form>
                </div>
                <!-- ............popup end........... -->
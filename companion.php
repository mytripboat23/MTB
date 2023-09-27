<?php
include( "includes/connection.php" );
userSecure();
include( "includes/all_form_check.php" );


$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];

if ( isset( $_REQUEST[ 'uId' ] ) && $_REQUEST[ 'uId' ] != '' ) {
  $user_id = $obj->filter_numeric( $_REQUEST[ 'uId' ] );
  $obj->valid_auth_user( $user_id );
} else {
  $user_id = $log_user_id;
}
$obj->unset_notification( $log_user_id, "companion.php" );


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
        <div class="let_body col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-9">
          <div class="lt_first_column"> 
            <!-- ...................btn top section start............ -->
            
            <?=stripslashes($obj->display_message("message"));?>
            <?php include("page_includes/dash_body_nav.php");?>
            <!-- ...................btn top section end............ -->
            
            <h3 class="main_bold_heading"> Companion </h3>
            <!-- ............single block start................ -->
            <div class="companions_list">
              <ul>
                <?php
                if ( $log_user_id == $user_id ) {
                  $comQuery = $obj->selectData( TABLE_FRIENDS, "", "((fr_from_id='" . $log_user_id . "' or fr_to_id='" . $log_user_id . "') and fr_status in ('A')) or (fr_to_id='" . $log_user_id . "' and fr_status in ('S'))", "" );
                } else {
                  $comQuery = $obj->selectData( TABLE_FRIENDS, "", "(fr_from_id='" . $user_id . "' or fr_to_id='" . $user_id . "') and fr_status in ('A')", "" );
                }
				$com_count = 0;
                while ( $comData = mysqli_fetch_array( $comQuery ) ) {
                  $com_user = "";
                  if ( $comData[ 'fr_from_id' ] == $user_id )$com_user = $comData[ 'fr_to_id' ];
                  else $com_user = $comData[ 'fr_from_id' ];

                  $comPD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $com_user . "'", 1 );
                  if ( $comPD[ 'user_avatar' ] != "" || $comPD[ 'user_avatar' ] != NULL )$upphoto = AVATAR . $comPD[ 'user_avatar' ];
                  else $upphoto = NO_USER_PHOTO;
                  ?>
                <li>
                  <figure> <img src="<?=$upphoto;?>" alt=""> </figure>
                  <div class="com_details">
                    <h6><a href="user-profile?uId=<?=$com_user;?>">
                      <?=$comPD['user_full_name'];?>
                      </a></h6>
                    <div class="btn-group rounded-3" role="group" aria-label="Companion buttons">
                      <?php if($log_user_id==$user_id){?>
                      <?php if($comData['fr_status']=='S'){?>
                      <button type="button" class="btn btn-dark" onClick="comp_action('<?=$comData['fr_id'];?>','accept')">Accept</button>
                      <button type="button" class="btn btn-danger" onClick="comp_action('<?=$comData['fr_id'];?>','reject')">Reject</button>
                      <?php }else{?>
                      <?php if($comData['fr_from_id']==$log_user_id){?>
                      <button type="button" class="btn btn-light" onClick="<?php if($comData['fr_follow_1']=='f'){?>comp_action('<?=$comData['fr_id'];?>','unfollow')<?php }else{ ?>comp_action('<?=$comData['fr_id'];?>','follow')<?php }?>">
                      <?php if($comData['fr_follow_1']=='f'){?>
                      Unfollow
                      <?php }else{?>
                      Follow
                      <?php }?>
                      </button>
                      <button type="button" class="btn btn-dark" 
							  onClick=" <?php if($comData['fr_block_1']=='u'){?>comp_action('<?=$comData['fr_id'];?>','block')"
							  <?php }else{ ?>
							  comp_action('<?=$comData['fr_id'];?>','unblock')"
							 <?php }?>
							  >
                      <?php if($comData['fr_block_1']=='u'){?>
                      Block
                      <?php }else{?>
                      Unblock
                      <?php }?>
                      </button>
                      <?php }else{?>
                      <button type="button" class="btn btn-light" onClick="<?php if($comData['fr_follow_2']=='f'){?>comp_action('<?=$comData['fr_id'];?>','unfollow')<?php }else{ ?>comp_action('<?=$comData['fr_id'];?>','follow')<?php }?>">
                      <?php if($comData['fr_follow_2']=='f'){?>
                      Unfollow
                      <?php }else{?>
                      Follow
                      <?php }?>
                      </button>
                      <button type="button" class="btn btn-dark" 
							  onClick="<?php if($comData['fr_block_2']=='u'){?>comp_action('<?=$comData['fr_id'];?>','block')"
							  <?php }else{ ?>comp_action('<?=$comData['fr_id'];?>','unblock')"
							  <?php }?>
							  >
                      <?php if($comData['fr_block_2']=='u'){?>
                      Block
                      <?php }else{?>
                      Unblock
                      <?php }?>
                      </button>
                      <?php }?>
                      <?php }?>
                      <?php }?>
                    </div>
                  </div>
                </li>
                <?php $com_count++; }?>
              </ul>
			  <?php if($log_user_id == $user_id && $com_count==0){?>
			  <div class="alert alert-primary" role="alert">You do not have any companion yet, choose your companion.</div>
			  <?php }elseif($log_user_id != $user_id && $com_count==0){?>
			  <div class="alert alert-primary" role="alert">No companion yet.</div>
			  <?php }?>
			  <?php if($log_user_id == $user_id){
			  $myProfD = $obj->selectData( TABLE_USER, "", "u_login_id='" . $log_user_id . "'", 1 );
			  if(trim($myProf['user_hobby'],",")!=''){
			  $my_hobby = explode(",",trim($myProf['user_hobby'],","));
			  $my_comps = $obj->get_user_friend_ids($log_user_id);
			  
			  $where = "1 and (";
			  for($i=0;isset($my_hobby[$i]),$my_hobby[$i]!="";$i++)
			  {
			  	 $where .= " user_hobby like '%,".$my_hobby[$i].",%' ";
			  }
			  $where = " )";
			  if(trim($my_comps,",")!="")
			  {
			  	 	$where .= " user_id NOT IN (".$my_comps.",".$log_user_id.")";
			  }
			  $comp_sugg_rec = $obj->selectData(TABLE_USER,"",$where,1);
			  if(isset($comp_sugg_rec['user_id']))
			  {
			  ?>
			  <h3 class="main_bold_heading"> Companion suggsion </h3>
			  <div class="companions_list">
                <ul>
				<?php
			     $comp_sugg_rec = $obj->selectData(TABLE_USER,"",$where,1);
			     while($comp_sugg_res = mysqli_fetch_array($comp_sugg_sql))
			     {
				    $recUP = $obj->selectData( TABLE_USER, "", "user_id='" . $comp_sugg_res['user_id'] . "'", 1 );
                    if ( $recUP[ 'user_avatar' ] != "" || $recUP[ 'user_avatar' ] != NULL )$upphoto = AVATAR . $comPD[ 'user_avatar' ];
                    else $upphoto = NO_USER_PHOTO;
					
					 $com_status = $obj->companion_request_status($log_user_id,$recUP['u_login_id']);
				?>
                  <li>
                    <figure> <img src="<?=$upphoto;?>" alt=""> </figure>
                    <div class="com_details">
                      <h6><a href="user-profile?uId=<?=$recUP['u_login_id'];?>"> <?=$recUP['user_display_name'];?> </a></h6>
                      <div class="btn-group rounded-3" role="group" aria-label="Companion buttons">
					  	<?php  if($com_status=='' || $com_status=='R' || $com_status=='C'){?>?>
						<button type="button" class="btn btn-dark" onClick="send_companion_request('<?=$log_user_id;?>','<?=$recUP['u_login_id'];?>')">Add companion</button>
						<?php }else{?>
						<button type="button" class="btn btn-dark" onClick="remove_companion_request('<?=$log_user_id;?>','<?=$recUP['u_login_id'];?>')">Remove Companion</button>
						<?php }?>
					  </div>
                    </div>
                  </li>
				  <?php
				  }
				  ?>
                </ul>
				</div>
			  <?php }}}?>
			  
			  
            </div>
            <!-- .................single block end................. --> 
          </div>
          <div class="lt_second_column">
            <?php include("page_includes/dash_left_sidebar.php");?>
          </div>
          <div class="clear"></div>
        </div>
        <aside class="right_body col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
          <?php include("page_includes/sidebar_right.php");?>
        </aside>
      </div>
    </div>
  </div>
</main>
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>
</link>
<script src="js/companion.js"></script>
<?php include("page_includes/profile_footer_script.php");?>
<?php include("page_includes/dash_comment_script.php");?>
</body>
</html>

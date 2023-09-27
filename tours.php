<?php include("includes/connection.php");
userSecure();
include("includes/all_form_check.php");


$log_user_id  = $_SESSION['user']['u_login_id'];
$user_id      = $obj->filter_numeric($_REQUEST['uId']);
$obj->valid_auth_user($user_id);

$extra_cond = "";
if(isset($_REQUEST['type']) && $_REQUEST['type']!='') 
{
	if($_REQUEST['type']=='completed')  $extra_cond = " and pck_end_dt < '".date("Y-m-d")."'";
	if($_REQUEST['type']=='ongoing')  $extra_cond = "  and pck_end_dt >= '".date("Y-m-d")."'";
	
}


$searchTSql  = $obj->selectData(TABLE_PACKAGE,"","pck_status='Active' and user_id='".$user_id."'".$extra_cond,"","pck_id desc");
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
			while($searchTData = mysqli_fetch_object($searchTSql))
			{
					$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$searchTData->user_id."'",1);
					if($userD['user_avatar']!="") $uphoto = $userD['user_avatar'];
					else $uphoto = 'noimage.jpg';
					
					
					$pstart = strtotime($searchTData->pck_start_dt);
					$pend   = strtotime($searchTData->pck_end_dt);
				
				if($searchTData->pck_start_dt!='0000-00-00')
				{
					$from_to = "";
					$from_to = date("d",$pstart)." ".date("M",$pstart)." - ".date("d",$pend)." ".date("M",$pend);				
					$duration = (((int)(($pend-$pstart)/(24*3600))+1))."D / ".(int)(($pend-$pstart)/(24*3600))."N";
				}
				else
				{
					$from_to = date("F", strtotime($searchTData->pck_month."/12/".date("Y")));
					$duration = "";
				}
			?>
            <!-- ............single block start................ -->
            <div class="single_list_tour">
            <div class="tour_img"><img src="<?=PACKAGE.$searchTData->pck_photo?>" alt="<?php echo $searchTData->pck_title;?>"></div>
              <div class="tour_content ">
                <div class="tour_upper">
                  <div href="#" class="operater profile_listing"> <a href="#"><img src="<?=AVATAR.$uphoto;?>" width="60"></a>
                    <div class="like_area"><a id="pckl_<?=$searchTData->pck_id?>" href="javascript:void(0);" <?php if($obj->get_pck_like_status($searchTData->pck_id,$log_user_id)){?> class="active"<?php }?><?php if($searchTData->user_id!=$log_user_id){ ?>onclick="set_unset_pck_like('<?=$searchTData->pck_id?>','<?=$log_user_id?>')"<?php }?>> <span class="material-symbols-outlined"> favorite </span> </a></div>
                  </div>
                  <div class="tour_info">
                    <div class="tour_about">
                      <h4><a href="tour-details?tId=<?=$searchTData->pck_id?>"><?=$searchTData->pck_title;?></a></h4>
                      <div class="tour_conti_detail">
                          <div class="tour_date"><?=$from_to;?></div>
                          <div class="tour_continuation"><?=$duration;?></div>
                        </div>
                    <!-- <div class="tag_friend_prof">
                        <div class="small_check"> <span>Amlan
                          <input type="checkbox" name="ParticipantSelection[]" value="demo1">
                          </span> <span>Trilokesh
                          <input type="checkbox" name="ParticipantSelection[]" value="demo2">
                          </span> <span>Babli
                          <input type="checkbox" name="ParticipantSelection[]" value="demo3">
                          </span> </div>
                      </div>-->
                    </div>
                    <div class="share_price_area">
                      <div class="tour_price">
                    <!--  <div class="down_arrow_people"> <span class="material-symbols-outlined"> share </span>
                        <div class="see_pop">
                          <h4>Who can see and reply</h4>
                          <p>Choose who can reply to the comment</p>
                          <ul class="who_see_list">
                            <li class="public_show"><a href="#">Public<span class="material-symbols-outlined">public</span></a></li>
                            <li class="friend_show"><a href="#">Friend<span class="material-symbols-outlined">group</span></a></li>
                          </ul>
                        </div>
                      </div>-->
                      <p><strong><?php echo $obj->getCurrency($searchTData->pck_curr);?></strong> <?=$searchTData->pck_price?> /-  Person&nbsp;</p>
					  <?php if($searchTData->pck_foreign_price>0){?>
					   <p><strong><?php echo $obj->getCurrency($searchTData->pck_foreign_curr);?></strong> <?=$searchTData->pck_foreign_price?> /-  Person&nbsp;</p>
					   <?php }?>

                      <?php if($log_user_id==$user_id){?>

                        <a href="create-package?tId=<?=$searchTData->pck_id?>" class="edit_tour"><span class="material-symbols-outlined">edit</span></a>
                    
                    

                    <?php }?>
            <!-- ..................................................... -->
                      
                    </div>
                    <div class="prefference">
                      <?php /*?><p class="pref_by">Preffered by : <strong>110</strong></p><?php */?>
                      <!-- <p class="follow_by">Followed by : <strong>90</strong></p> -->
                      <p class="follow_by">Followed by : <strong id="pck_count_<?=$searchTData->pck_id;?>"><?=$obj->get_pck_like_count($searchTData->pck_id);?></strong></p>
                    </div>
                  </div>
                </div>
                  </div>
                  
                  
                <div class="tour_bottom">
                  <p><?=$obj->short_description_cw($searchTData->pck_desc,120);?></p>
                </div>
              </div>
            </div>
            <?php
			 $i++;
			}
			?>
			<?php if($i==0){?>
			  <div class="alert alert-primary" role="alert">
You do not have any tour. Please <a href="create-package">click here</a> to create your tour .
</div>
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

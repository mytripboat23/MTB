<?php
include "../includes/connection.php";
userSecure();

$last_tour_id = $_POST['last_tour_id'];


$searchTSql  = $obj->selectData(TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." ul",""," p.pck_status='Active' and (p.pck_start_dt > '".date("Y-m-d")."' or ( p.pck_month >= '".date("n")."' and p.pck_year = '".date("Y")."') or ( p.pck_year > '".date("Y")."')) and p.pck_end_dt >= '".date("Y-m-d")."' and ul.u_suspend_status='n' and ul.u_login_status='Active' and p.user_id=ul.u_login_id  and p.pck_id < '".$last_tour_id."'","","p.pck_id desc","","0,5");

	while($searchTData = mysqli_fetch_object($searchTSql))
	{
			$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$searchTData->user_id."'",1);
			if($userD['user_avatar']!="") $uphoto = $userD['user_avatar'];
			else $uphoto = 'noimage.jpg';
			
			
			$pstart = strtotime($searchTData->pck_start_dt);
			$pend   = strtotime($searchTData->pck_end_dt);
		
		$from_to = "";
		//if(date("m",$pstart) == date("m",$pend)) $from_to = date("d",$pstart)." - ".date("d",$pend)." ".date("M",$pend);
		//else $from_to = date("d",$pstart)." ".date("M",$pstart)." - ".date("d",$pend)." ".date("M",$pend);
		if($searchTData->pck_start_dt!='0000-00-00')
		{
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
	
	  <!-- ................................... -->
	  <div class="tour_content ">
	  
		  <div class="tour_upper">
			<div href="#" class="operater profile_listing"> <a href="#"><img src="<?=AVATAR.$uphoto;?>" width="60"></a>
				<div class="like_area"> <a id="pckl_<?=$searchTData->pck_id?>" href="javascript:void(0);" <?php if($obj->get_pck_like_status($searchTData->pck_id,$log_user_id)){?> class="active"<?php }?><?php if($searchTData->user_id!=$log_user_id){ ?>onclick="set_unset_pck_like('<?=$searchTData->pck_id?>','<?=$log_user_id?>')"<?php }?>> <span class="material-symbols-outlined"> favorite </span> </a> 
					<div class="agent_pop">
						<h4><?php if($userD['user_display_name']!=''){ echo $userD['user_display_name']; }else{ echo $userD['user_full_name'];;}?></h4>
						<div class="star_holder">
						<?php /*?><div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div><?php */?>
						</div>
						<ul class="operator_view">
						<li>Tour Completed: <span><?=$obj->completed_tours_by_user($user_id);?></span></li>
						<li>Tour Ongoing: <span><?=$obj->ongoing_tours_by_user($user_id);?></span></li>
						</ul>
					</div>
				</div>
		  	</div>
		  	<div class="tour_info">
			<div class="tour_about">
			  <h4><a href="tour-details?tId=<?=$searchTData->pck_id?>"><?=$searchTData->pck_title;?></a></h4>
			  <div class="tour_conti_detail">
				<div class="tour_date"><?=$from_to;?></div>
				<div class="tour_continuation"><?=$duration;?></div>
			  </div>
			<!-- <div class="tag_friend_prof">
				<div class="small_check"> 
		<span>Amlan </span> <span>Trilokesh </span> <span>Babli </span> 
	  </div>
			  </div>-->
			</div>
			<div class="share_price_area">
			  <div class="tour_price">
				<!--<div class="down_arrow_people"> <span class="material-symbols-outlined"> share </span>
				  <div class="see_pop">
					<h4>Who can see and reply</h4>
					<p>Choose who can reply to the comment</p>
					<ul class="who_see_list">
					  <li class="public_show"><a href="#">Public<span class="material-symbols-outlined">public</span></a></li>
					  <li class="friend_show"><a href="#">Friend<span class="material-symbols-outlined">group</span></a></li>
					</ul>
				  </div>
				</div>-->
				<p><span class="material-symbols-outlined">currency_rupee</span> <?=$searchTData->pck_price?> /-  Person</p>
			  </div>
			  <div class="prefference">
			  <!--  <p class="pref_by">Preffered by : <strong>110</strong></p>-->
				<p class="follow_by">Followed by : <strong id="pck_count_<?=$searchTData->pck_id;?>"><?=$obj->get_pck_like_count($searchTData->pck_id);?></strong></p>
			  </div>
			</div>
		  </div>
		</div>
		<div class="tour_bottom">
		  <p><?=$obj->short_description_cw($searchTData->pck_desc,100);?></p>
		</div>
	  </div>
	</div>
	<?php
	$last_tour_id = $searchTData->pck_id;
	}
	echo "||".$last_tour_id;
	?>



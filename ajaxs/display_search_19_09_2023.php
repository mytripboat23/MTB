<?php
include "../includes/connection.php";

$last_tour_id = $_POST['last_tour_id'];

$search_dest = $obj->filter_mysql($_REQUEST['search_where']);
if(isset($_REQUEST['search_when']) && $_REQUEST['search_when']!="") 
{
	 if(strtotime($_REQUEST['search_when']) >= strtotime(date("Y-m-d")))
	 {
		$search_dt   = date("Y-m-d",strtotime($_REQUEST['search_when']));
		$year = date("Y",strtotime($_REQUEST['search_when']));
		$month = date("n",strtotime($_REQUEST['search_when']));
	 }
	 else
	 {
	 	$search_dt   = date("Y-m-d");
		$year = date("Y");
		$month = date("n");
	 }
}	
else {
 $search_dt   = date("Y-m-d");
 $year = date("Y");
 $month = date("n");
}

$searchTSql = $obj->selectData( TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." as ul", "", "( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%') and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "' and p.pck_status='Active' and ul.u_suspend_status='n' and ul.u_login_id=p.user_id and p.pck_id> '".$last_tour_id ."'", "", "p.pck_id asc", "", "0,5" );

if(isset($_REQUEST['search_filter']) && $_REQUEST['search_filter']!='')
{
	if($_REQUEST['search_filter']=='price-high-low')
	{
		$searchTSql = $obj->selectData(TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." as ul", "", "( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%' ) and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "'  and p.pck_status='Active'  and ul.u_suspend_status='n' and ul.u_login_id=p.user_id and p.pck_id> '".$last_tour_id ."'", "", "pck_price desc", "", "0,5" );
	}
	if($_REQUEST['search_filter']=='price-low-high')
	{
		  $searchTSql = $obj->selectData( TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." as ul", "", "( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%'  or p.pck_tags like '%" . $search_dest . "%') and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "'  and p.pck_status='Active'  and ul.u_suspend_status='n' and ul.u_login_id=p.user_id  and p.pck_id> '".$last_tour_id ."'", "", $order_by, "", "0,5" );
	}
	if($_REQUEST['search_filter']=='top-agent')
	{
		$top_agents = explode(",",trim($obj->get_top_agents_by_pck_likes(),","));
		for($i=0;isset($top_agents[$i]),$top_agents[$i]!="";$i++)
		{
			$order_by .=  " user_id='".$top_agents[$i]."' desc, ";
		}
		$order_by .= "user_id desc";
		$searchTSql = $obj->selectData( TABLE_PACKAGE." as p, ".TABLE_USER_LOGIN." as ul", "", "( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%') and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "'  and p.pck_status='Active'  and ul.u_suspend_status='n' and ul.u_login_id=p.user_id  and p.pck_id> '".$last_tour_id ."", "", $order_by, "", "0,5" );
	}
}

$user_id = $_SESSION['user']['u_login_id'];
if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!="")
{
	$log_id = $_SESSION['user']['u_login_id'];
	
		$dataS = array();
		$dataS['us_where'] 	 = $search_dest;
		$dataS['us_when']    = date("Y-m-d",strtotime($search_dt));
		$dataS['us_updated'] = CURRENT_DATE_TIME;
		
		
	$sqlUS = $obj->selectData(TABLE_USER_SEARCH,"","user_id='".$log_id."'",1);
	if(isset($sqlUS['user_id']))
	{
		$obj->updateData(TABLE_USER_SEARCH,$dataS,"user_id='".$log_id."'");
	}
	else
	{
		$dataS['user_id'] = $log_id;
		$obj->insertData(TABLE_USER_SEARCH,$dataS);
	}
}
	

		while($searchTData = mysqli_fetch_object($searchTSql))
		{
		    	$userD = $obj->selectData(TABLE_USER,"","u_login_id = '".$searchTData->user_id."'",1);
				if($userD['user_avatar']!="") $uphoto = $userD['user_avatar'];
				else $uphoto = 'noimage.jpeg';
				
				
				$pstart = strtotime($searchTData->pck_start_dt);
				$pend   = strtotime($searchTData->pck_end_dt);
			$last_tour_id = 0;
			if($searchTData->pck_start_dt!='0000-00-00')
			{
				$from_to = "";
				if(date("m",$pstart) == date("m",$pend)) $from_to = date("d",$pstart)." - ".date("d",$pend)." ".date("M",$pend);
				else $from_to = date("d",$pstart)." ".date("M",$pstart)." - ".date("d",$pend)." ".date("M",$pend);
			
				$duration = ((int)(($pend-$pstart)/(24*3600))+1)."D / ".(int)(($pend-$pstart)/(24*3600))."N";
			}
			else
			{
				$from_to = date("F", strtotime($searchTData->pck_month."/12/".date("Y")));
				$duration = "";
			}	
		?>
          <div class="single_list_tour full_with_list">
            <div class="tour_img"><img src="<?=PACKAGE.$searchTData->pck_photo;?>" alt="<?php echo $searchTData->pck_title;?>"></div>
            <div class="tour_content">
              
              <div class="tour_upper">

                <div href="#" class="operater profile_listing">
                  <a href="user-profile?uId=<?=$userD['u_login_id']?>"><img src="<?=AVATAR.$uphoto;?>"></a>
                  <div class="like_area"><a href="#"> 
                    <span class="material-symbols-outlined"> favorite </span></a>
                    <div class="agent_pop">
                    <h4><?=$userD['user_full_name'];?></h4>
                    <?php /*?><div class="star_holder">
                      <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div>
                    </div><?php */?>
                    <ul class="operator_view">
                      <!--<li class="yellow_bg">Reffered By 10 K</li>-->
                      <li class="blk_txt">Followed: <span><?=$obj->get_user_follower($searchTData->user_id);?></span></li>
                      <li>Tour Completed: <span><?=$obj->completed_tours_by_user($searchTData->user_id);?></span></li>
                      <li>Tour Ongoing: <span><?=$obj->ongoing_tours_by_user($searchTData->user_id);?></span></li>
                    </ul>
                  </div>
                  </div>
                  
                </div>

                <div class="tour_info">
                  <div class="tour_about">
                    <h4><a href="tour-details.php?tId=<?=$searchTData->pck_id?>"><?=$searchTData->pck_title?></a></h4>
                    <div class="tour_conti_detail">
                      <div class="tour_date"><?=$from_to;?></div>
                      <?php if($duration!=""){?><div class="tour_continuation"><?=$duration;?></div><?php }?>
                    </div>
                  </div>
                  
                    
                  <div class="share_price_area">
                      <div class="tour_price">
                        <p><span class="material-symbols-outlined">currency_rupee</span> <?=$obj->hide_price($searchTData->pck_price);?> /-  Person</p>
                      </div>
                    <?php /*?> <div class="prefference">
                        <p class="pref_by">Preffered by : <strong>110</strong></p>
                        <p class="follow_by">Followed by : <strong>90</strong></p>
                      </div><?php */?>
                  </div>

                </div>
              </div>
              <div class="tour_bottom">
               <p><?=$obj->short_description_cw($searchTData->pck_desc);?></p>
              </div>
            </div>
          </div>
		<?php
			$last_tour_id = $searchTData->pck_id;
		}
		echo "||".$last_tour_id;
		?>  



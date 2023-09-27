 <?php if($_SESSION['user']['user_type']!=''){?><div> <a href="create-package" class="create_btn ceate_pack"> <span class="material-symbols-outlined"> add </span> Create Trip </a></div><?php }?> 
 <!-- .............package start........ -->
       <?php /*?>   <div class="top_package_rt">
            <h3 class="rt_heading"><span>Top Packages</span></h3>
            <ul>
			<?php
			$sqlTP = $obj->selectData(TABLE_PACKAGE,"","pck_top='y' and pck_status='Active'","","pck_start_dt asc","rand()","0,5");
			while($resTP = mysqli_fetch_object($sqlTP))
			{
				if($resTP->pck_start_dt!='0000-00-00')
				{
					$pstart   = strtotime($resTP->pck_start_dt);
					$pend     = strtotime($resTP->pck_end_dt);
					$duration = ((int)(($pend-$pstart)/(24*3600))+1)." Days - ".(int)(($pend-$pstart)/(24*3600))." Nights";
				}
				else
				{
					$duration = "";
				}
			?>
              <li> <a href="tour-details?tId=<?=$resTP->pck_id?>">
                <h4><span><?=$duration;?></span></h4>
                <h3><?=$resTP->pck_title;?></h3>
                </a> </li>
			<?php
			}
			?>
			<li> <a href="single_package.html">
                <h4><span>5 Days</span> - <span>4 Nights</span></h4>
                <h3>Darjeeling  Tour</h3>
                </a> </li>
              <li> <a href="single_package.html">
                <h4><span>5 Days</span> - <span>4 Nights</span></h4>
                <h3>Darjeeling  Tour</h3>
                </a> </li>
            </ul>
          </div><?php */?>
          <!-- .......package end.... -->
          <!-- .......operator start.......... -->
          <div class="top_operator_rt">
            <h3 class="rt_heading"><span>Top Operators</span></h3>
            <ul >
			<?php
			$topAs = $obj->get_top_agents(5);
			$totalPcks = $obj->total_package_posted();
			$userS = $obj->selectData(TABLE_USER,"","u_login_id in (".$topAs.")","","rand()","","0,4");
			while($userD = mysqli_fetch_object($userS))
			{
			if($userD->user_avatar!="") $uphoto = AVATAR.$userD->user_avatar;
			else $uphoto = NO_USER_PHOTO;
			?>
              <li> <a href="user-profile?uId=<?=$userD->u_login_id;?>" class="operator_img"><img src="<?=$uphoto;?>"></a>
                <div class="operator_content">
                  <h3><a href="user-profile?uId=<?=$userD->u_login_id;?>"><?=$userD->user_display_name;?></a></h3>
				  <h4><?=$obj->short_description_cw($userD->user_bio,78);?></h4>
                 <?php /*?> <h4>Organised: <span><?=$obj->total_package_posted_by_user($userD->u_login_id);?></span> Tours</h4><?php */?>
                  <!-- .......Bar start.......... -->
                 <?php /*?> <div class="bar-container">
                    <div class="bar-light-grey bar-round-large">
                      <div class="bar-container bar-red bar-round-large" style="width:<?=round($obj->total_package_posted_by_user($userD->u_login_id)*100/$totalPcks)?>%"><?=round($obj->total_package_posted_by_user($userD->u_login_id)*100/$totalPcks)?>%</div>
                    </div>
                  </div><?php */?>
                  <!-- .......Bar end.......... -->
                </div>
              </li>
			<?php
			}
			?>  
            <?php /*?>  <li> <a href="#" class="operator_img"><img src="img/person_img.png"></a>
                <div class="operator_content">
                  <h3><a href="travel_diary.html"> Cholo Jai</a></h3>
                  <h4>Organised: <span>20</span> Tours</h4>
                  <!-- .......Bar start.......... -->
                  <div class="bar-container">
                    <div class="bar-light-grey bar-round-large">
                      <div class="bar-container bar-red bar-round-large" style="width:55%">55%</div>
                    </div>
                  </div>
                  <!-- .......Bar end.......... -->
                </div>
              </li>
              <li> <a href="#" class="operator_img"><img src="img/person_img.png"></a>
                <div class="operator_content">
                  <h3><a href="travel_diary.html"> Cholo Jai</a></h3>
                  <h4>Organised: <span>20</span> Tours</h4>
                  <!-- .......Bar start.......... -->
                  <div class="bar-container">
                    <div class="bar-light-grey bar-round-large">
                      <div class="bar-container bar-red bar-round-large" style="width:95%">95%</div>
                    </div>
                  </div>
                  <!-- .......Bar end.......... -->
                </div>
              </li><?php */?>
            </ul>
          </div>
          <!-- ....operator end.... -->
          <!-- ....top tour start.... -->
          <div class="top_tour_rt">
            <h3 class="rt_heading"><span>Top Tour </span></h3>
            <ul>
			<?php
			
			$top_rated_packs = explode(",",$obj->get_top_tours(4));
			foreach($top_rated_packs as $pckId)
			{
			$sqlTP = $obj->selectData(TABLE_PACKAGE,"","pck_id = '".$pckId."'");
			while($resTP = mysqli_fetch_object($sqlTP))
			{
			
				if($resTP->pck_start_dt!='0000-00-00' && $resTP->pck_start_dt!='')
				{
					$pstart   = strtotime($resTP->pck_start_dt);
					$pend     = strtotime($resTP->pck_end_dt);
					$duration = ((int)(($pend-$pstart)/(24*3600))+1)." Days - ".(int)(($pend-$pstart)/(24*3600))." Nights";
				}
				else
				{
					$duration = "";
				}
			?>
              <li>
               <div class="top_tour_img"><img src="<?=PACKAGE.$resTP->pck_photo?>"></div>
                <a href="tour-details?tId=<?=$resTP->pck_id?>" class="top_tour_txt">
                <h3><?=$resTP->pck_dest?></h3>
                <strong><?=$resTP->pck_capacity?></strong>
                <p>Seats Remaining</p>
                </a> </li>
			<?php
			}
			}
			?>	
             <?php /*?> <li>
                <div class="top_tour_img"><img src="img/tour_img.jpg"></div>
                <a href="listing.html" class="top_tour_txt">
                <h3>Kashmir</h3>
                <strong>12</strong>
                <p>Seats Remaining</p>
                </a> </li>
              <li>
                <div class="top_tour_img"><img src="img/tour_img.jpg"></div>
                <a href="listing.html" class="top_tour_txt">
                <h3>Kashmir</h3>
                <strong>12</strong>
                <p>Seats Remaining</p>
                </a> </li>
              <li>
                <div class="top_tour_img"><img src="img/tour_img.jpg"></div>
                <a href="listing.html" class="top_tour_txt">
                <h3>Kashmir</h3>
                <strong>12</strong>
                <p>Seats Remaining</p>
                </a> </li><?php */?>
            </ul>
          </div>
          <!-- ....top tour end.... -->
          <!-- ...........add 1 start.......... -->
         <?php /*?> <div class="ad_1">
            <h3>Shimultala Tour</h3>
            <div class="add_txt_holder">
              <div class="add_img_border"><a href="single_package.html"><img src="img/tour_img.jpg" alt=""></a></div>
              <div> <span><strong>1</strong>Days - <strong>2</strong> Nights</span>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
              </div>
            </div>
            <a href="single_package.html" class="add_post_opert"><img src="img/person_img.png"></a> </div><?php */?>
          <!-- ......add 1 end.......... -->
          <!-- .............add 2 start............ -->
		  <?php
		    
			$sqlTP = $obj->selectData(TABLE_PACKAGE,"","pck_promote='y' and pck_status='Active'","","rand()","","0,4");
			while($resTP = mysqli_fetch_object($sqlTP))
			{
				if($resTP->pck_start_dt!='0000-00-00')
				{
					$pstart   = strtotime($resTP->pck_start_dt);
					$pend     = strtotime($resTP->pck_end_dt);
					$duration = ((int)(($pend-$pstart)/(24*3600))+1)." Days - ".(int)(($pend-$pstart)/(24*3600))." Nights";
				}
				else
				{
					$duration = "";
				}
				
				$pRate = floor($obj->get_package_rating($resTP->pck_id));
			?>
		  
          <div class="ad_2">
            <div class="add_img_border"><a href="tour-details?tId=<?=$resTP->pck_id?>"><img src="<?=PACKAGE.$resTP->pck_photo?>"></a></div>
            <div class="add_txt_holder">
              <h3><?=$resTP->pck_title?></h3>
              <div><span><?=$duration;?></span></span>
                <ul class="oparator_rating">
				<?php for($i=1;$i<=5;$i++){?>
                  <li <?php if($pRate<$i){?>class="low_rate"<?php }?>><?=$i;?></li>
				<?php }?>  
                </ul>
              </div>
            </div>
            <img alt="" class="add_post_opert"> </div>
			
			<?php
			}
			?>
          <!-- .............add 2 end............ -->
          <!-- .............suggestion start............ -->
          <div class="top_suggestion_rt">
            <h3 class="rt_heading"><span> suggestion </span></h3>
			<?php
			$where = " 1 ";
			if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!="")
			{
					$log_id = $_SESSION['user']['u_login_id'];
					$sqlUS = $obj->selectData(TABLE_USER_SEARCH,"","user_id='".$log_id."'",1);
					if(isset($sqlUS['us_where']) && $sqlUS['us_where']!="")
					{
						$where .= " and (pck_title like '%".$sqlUS['us_where']."%' or pck_dest like '%".$sqlUS['us_where']."%') ";
						
					}
					if(isset($sqlUS['us_when']) && $sqlUS['us_when']!="")
					{
						$where .= " and pck_start_dt>'".$sqlUS['us_when']."' ";
					}
			}
			else
			{
				$where .= " and pck_start_dt>'".date("Y-m-d")."' ";
			}		
			
			$sqlSU = $obj->selectData(TABLE_PACKAGE,"",$where." and pck_status='Active'","","rand()","","0,3");
			while($resSU = mysqli_fetch_object($sqlSU))
			{
				if($resTP->pck_start_dt!='0000-00-00')
				{
					$pstart   = strtotime($resSU->pck_start_dt);
					$pend     = strtotime($resSU->pck_end_dt);
					$duration = ((int)(($pend-$pstart)/(24*3600))+1)." Days - ".(int)(($pend-$pstart)/(24*3600))." Nights";
				}
				else
				{
					$duration = "";
				}
				
				$pRate = floor($obj->get_package_rating($resTP->pck_id));
			?>
            <ul>
              <li> <a class="top_suggestion_img"><img src="<?=PACKAGE.$resSU->pck_photo?>"></a>
                <div class="top_suggestion_txt">
                  <h3><a href="tour-details?tId=<?=$resSU->pck_id?>"><?=$resSU->pck_dest?></a></h3>
                  <p><span><?=$duration;?></span></p>
                  <ul class="suggestion_rating">
                   <?php for($i=1;$i<=5;$i++){?>
                  <li <?php if($pRate<$i){?>class="low_rate"<?php }?>><?=$i;?></li>
				<?php }?>  
                  </ul>
                </div>
              </li>
            </ul>
		 <?php }?>	
          </div>
          <!-- ................suggestion end............ -->
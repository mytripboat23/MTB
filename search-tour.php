<?php
include( "includes/connection.php" );

$search_dest = $obj->filter_mysql( $_REQUEST[ 'search_where' ] );
if ( isset( $_REQUEST[ 'search_when' ] ) && $_REQUEST[ 'search_when' ] != "" ) {
  if ( strtotime( $_REQUEST[ 'search_when' ] ) >= strtotime( date( "Y-m-d" ) ) ) {
    $search_dt = date( "Y-m-d", strtotime( $_REQUEST[ 'search_when' ] ) );
    $year = date( "Y", strtotime( $_REQUEST[ 'search_when' ] ) );
    $month = date( "n", strtotime( $_REQUEST[ 'search_when' ] ) );
  } else {
    $search_dt = date( "Y-m-d" );
    $year = date( "Y" );
    $month = date( "n" );
  }
} else {
  $search_dt = date( "Y-m-d" );
  $year = date( "Y" );
  $month = date( "n" );
}

$searchTSql = $obj->selectData( TABLE_PACKAGE . " as p, " . TABLE_USER_LOGIN . " as ul", "", " ( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%' ) and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "' and p.pck_status='Active' and ul.u_suspend_status='n' and ul.u_login_status='Active'  and ul.u_login_id=p.user_id", "", "p.pck_id asc", "", "0,16" );

if ( isset( $_REQUEST[ 'search_filter' ] ) && $_REQUEST[ 'search_filter' ] != '' ) {
  if ( $_REQUEST[ 'search_filter' ] == 'price-high-low' ) {
    $searchTSql = $obj->selectData( TABLE_PACKAGE . " as p, " . TABLE_USER_LOGIN . " as ul", "", " ( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%') and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "'  and p.pck_status='Active'  and ul.u_suspend_status='n' and ul.u_login_status='Active'  and ul.u_login_id=p.user_id", "", "pck_price desc", "", "0,16" );
  }
  if ( $_REQUEST[ 'search_filter' ] == 'price-low-high' ) {
    $searchTSql = $obj->selectData( TABLE_PACKAGE . " as p, " . TABLE_USER_LOGIN . " as ul", "", " ( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%' ) and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "'  and p.pck_status='Active'  and ul.u_suspend_status='n' and ul.u_login_status='Active'  and ul.u_login_id=p.user_id", "", "pck_price asc", "", "0,16" );
  }
  if ( $_REQUEST[ 'search_filter' ] == 'top-agent' ) {
    $top_agents = explode( ",", trim( $obj->get_top_agents_by_pck_likes(), "," ) );
    for ( $i = 0; isset( $top_agents[ $i ] ), $top_agents[ $i ] != ""; $i++ ) {
      $order_by .= " user_id='" . $top_agents[ $i ] . "' desc, ";
    }
    $order_by .= "user_id desc";
    $searchTSql = $obj->selectData( TABLE_PACKAGE . " as p, " . TABLE_USER_LOGIN . " as ul", "", " ( p.pck_dest like '%" . $search_dest . "%' or p.pck_title like '%" . $search_dest . "%' or p.pck_tags like '%" . $search_dest . "%' ) and (p.pck_start_dt > '" . $search_dt . "' or ( p.pck_month >= '" . $month . "' and p.pck_year = '" . $year . "') or ( p.pck_year > '" . date( "Y" ) . "')) and p.pck_end_dt>= '" . date( "Y-m-d" ) . "'  and p.pck_status='Active'  and ul.u_suspend_status='n' and ul.u_login_status='Active'  and ul.u_login_id=p.user_id", "", $order_by, "", "0,16" );
  }
}
$user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
if ( isset( $_SESSION[ 'user' ][ 'u_login_id' ] ) && $_SESSION[ 'user' ][ 'u_login_id' ] != "" ) {
  $log_id = $_SESSION[ 'user' ][ 'u_login_id' ];

  $dataS = array();
  $dataS[ 'us_where' ] = $search_dest;
  $dataS[ 'us_when' ] = date( "Y-m-d", strtotime( $search_dt ) );
  $dataS[ 'us_updated' ] = CURRENT_DATE_TIME;


  $sqlUS = $obj->selectData( TABLE_USER_SEARCH, "", "user_id='" . $log_id . "'", 1 );
  if ( isset( $sqlUS[ 'user_id' ] ) ) {
    $obj->updateData( TABLE_USER_SEARCH, $dataS, "user_id='" . $log_id . "'" );
  } else {
    $dataS[ 'user_id' ] = $log_id;
    $obj->insertData( TABLE_USER_SEARCH, $dataS );
  }
}

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
    <!-- ..........filter area start........... -->
    <?php include("page_includes/search_filter.php");?>
    <!-- ..........filter area end........... -->
    <div class="container">
      <div class="row">
        <aside class="right_body col-xs-12 col-sm-4 col-md-3 col-lg-3 col-xl-3">
          <?php include("page_includes/sidebar_right.php");?>
        </aside>
        <div class="let_body col-xs-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
          <div class="search_data">
            <?php
            $count = 0;
            while ( $searchTData = mysqli_fetch_object( $searchTSql ) ) {
              $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $searchTData->user_id . "'", 1 );
              if ( $userD[ 'user_avatar' ] != "" )$uphoto = $userD[ 'user_avatar' ];
              else $uphoto = 'noimage.jpeg';


              $pstart = strtotime( $searchTData->pck_start_dt );
              $pend = strtotime( $searchTData->pck_end_dt );
              $last_tour_id = 0;
              if ( $searchTData->pck_start_dt != '0000-00-00' ) {
                $from_to = "";
                if ( date( "m", $pstart ) == date( "m", $pend ) )$from_to = date( "d", $pstart ) . " - " . date( "d", $pend ) . " " . date( "M", $pend );
                else $from_to = date( "d", $pstart ) . " " . date( "M", $pstart ) . " - " . date( "d", $pend ) . " " . date( "M", $pend );

                $duration = ( ( int )( ( $pend - $pstart ) / ( 24 * 3600 ) ) + 1 ) . "D / " . ( int )( ( $pend - $pstart ) / ( 24 * 3600 ) ) . "N";
              } else {
                $from_to = date( "F", strtotime( $searchTData->pck_month . "/12/" . date( "Y" ) ) );
                $duration = "";
              }
              $count++;
              ?>
            <div class="single_list_tour full_with_list">
              <div class="tour_img"><img src="<?=$obj->getDataURI(PACKAGE.$searchTData->pck_photo);?>" alt="<?php echo $searchTData->pck_title;?>"></div>
              <div class="tour_content">
                <div class="tour_upper">
                  <div href="#" class="operater profile_listing"> <a href="user-profile?uId=<?=$userD['u_login_id']?>"><img src="<?=$obj->getDataURI(AVATAR.$uphoto);?>"></a>
                    <div class="like_area"><a href="#"> <span class="material-symbols-outlined"> favorite </span></a>
                      <div class="agent_pop">
                        <h4>
                          <?=$userD['user_full_name'];?>
                        </h4>
                        <?php /*?>
<div class="star_holder">
  <div class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </div>
</div>
                        <?php */?>
                        <ul class="operator_view">
                          <!--<li class="yellow_bg">Reffered By 10 K</li>-->
                          <li class="blk_txt">Followed: <span>
                            <?=$obj->get_user_follower($searchTData->user_id);?>
                            </span></li>
                          <li>Tour Completed: <span>
                            <?=$obj->completed_tours_by_user($searchTData->user_id);?>
                            </span></li>
                          <li>Tour Ongoing: <span>
                            <?=$obj->ongoing_tours_by_user($searchTData->user_id);?>
                            </span></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="tour_info">
                    <div class="tour_about">
                      <h4><a href="tour-details.php?tId=<?=$searchTData->pck_id?>">
                        <?=$searchTData->pck_title?>
                        </a>
						
						
						 <?php if($duration!=""){?>
                        <span class="badge rounded-pill text-bg-light">
                          <?=$duration;?>
                        </span>
                        <?php }?>
						
						
						
						
						</h4>
                   <?php /*?>   <div class="tour_conti_detail">
                        <div class="tour_date">
                          <?=$from_to;?>
                        </div>
                        <?php if($duration!=""){?>
                        <div class="tour_continuation">
                          <?=$duration;?>
                        </div>
                        <?php }?>
                      </div><?php */?>
                    </div>
                    <div class="share_price_area">
                   	 <div class="tour_price">
                        <p data-bs-custom-class="tooltip_blue" data-bs-toggle="tooltip" data-bs-placement="left" 
						<?php if(!isset($_SESSION['user']['u_login_id']) || $_SESSION['user']['u_login_id']=='' ){?>data-bs-title="Login to see the deal price"<?php }?>> 
							
						
							
						<span class="badge rounded-pill text-bg-warning">
							
							
							<strong><?php echo $obj->getCurrency($searchTData->pck_curr);?></strong>
							<?=$obj->hide_price($searchTData->pck_price);?>
							
							
							
							</span>
                          <?php if($searchTData->pck_foreign_price>0){?>
                         <span class="badge rounded-pill text-bg-primary"> 
							 
							 <strong><?php echo $obj->getCurrency($searchTData->pck_foreign_curr);?></strong>
							<?=$obj->hide_price($searchTData->pck_foreign_price);?>
                          /- <small>For foreign tourist</small></span> </p>
                        <?php }?>
                      </div>
                      <?php /*?>
<div class="prefference">
  <p class="pref_by">Preffered by : <strong>110</strong></p>
  <p class="follow_by">Followed by : <strong>90</strong></p>
</div>
                      <?php */?>
                    </div>
                  </div>
                </div>
                <div class="tour_bottom">
                  <p>
                    <?=$obj->short_description_cw($searchTData->pck_desc);?>
                  </p>
                </div>
              </div>
            </div>
            <?php
            $last_tour_id = $searchTData->pck_id;
            }
            ?>
            <?php if($count==0){?>
            <div class="alert alert-warning" role="alert"> <strong>Oops..... </strong>We can't find Your Boat for " <strong>
              <?=$search_dest;?>
              </strong> ".</div>
            <?php }?>
            <?php /*?>
<div class="single_list_tour">
  <div class="tour_img"><img src="img/tour_img.jpg">
    <div href="#" class="operater"><a href="#"><img src="img/person_img.png"></a>
      <div class="agent_pop">
        <h4>Tapas Das</h4>
        <div class="star_holder">
          <div class="rating"> <span class=""><a href="#"><img src="img/star_img.png"></a></span> <span class=""><a href="#"><img src="img/star_img.png"></a></span> <span class=""><a href="#"><img src="img/star_img.png"></a></span> <span class=""><a href="#"><img src="img/star_img.png"></a></span> <span class=""><a href="#"><img src="img/star_img.png"></a></span> </div>
        </div>
        <ul class="operator_view">
          <li class="yellow_bg">Reffered By 10 K</li>
          <li class="blk_txt">Followed: <span>15k</span></li>
          <li>Tour Completed: <span>20</span></li>
          <li>Tour Ongoing: <span>10</span></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="tour_content">
    <div class="like_area"> <a href="#"> <span class="material-symbols-outlined"> favorite </span> </a> </div>
    <div class="tour_upper">
      <div class="tour_about">
        <h4><a href="single_package.html"> Mooosouri Tour </a></h4>
        <div class="tour_conti_detail">
          <div class="tour_date">6-10 Jul</div>
          <div class="tour_continuation">4D / 3N</div>
        </div>
      </div>
      <div>
        <div class="tour_price">
          <p><span class="material-symbols-outlined">currency_rupee</span> 3000 /- Person</p>
        </div>
        <div class="prefference">
          <p class="pref_by">Preffered by : <strong>110</strong></p>
          <p class="follow_by">Followed by : <strong>90</strong></p>
        </div>
      </div>
    </div>
    <div class="tour_bottom">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation . ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate </p>
    </div>
  </div>
</div>
            <?php */?>
          </div>
          <input type="hidden" id="ldti" value="<?=$last_tour_id;?>">
          <input type="hidden" id="lstatus" value="stop">
        </div>
      </div>
    </div>
  </div>
</main>
<footer  class="footer_bottom">
  <?php include("page_includes/footer.php");?>
</footer>
<?php include("page_includes/faq_footer_script.php");?>
<script>
$(window).scroll(function() {
//alert($(window).scrollTop());
//alert($(window).height());
//alert($(document).height());
  if($(window).scrollTop() + $(window).height() >= $(document).height()-70) {
	  if($('#lstatus').val()=='stop')
	  {
  		 display_more_search();
	  }
  }
});

function display_more_search()
   {
   	  var last_tour_id = $('#ldti').val();
	  var search_where = '<?=$_REQUEST['search_where']?>';
	  $('#lstatus').val('start');
   		$.ajax({
				url: configUrl+'display_search',
				type: 'POST',
				data: {'last_tour_id':last_tour_id,'search_where':search_where},
				success: function(response){
					//alert(response);
				  	var data = response.split("||");
					$('.search_data').append(data[0]);
					$('#ldti').val(data[1]);
					 $('#lstatus').val('stop');
				},
			});
   }
   
   

</script>
</body>
</html>
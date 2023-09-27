<?php
include( "includes/connection.php" );
//userSecure();
include( "includes/all_form_check.php" );
$pck_id = $obj->filter_numeric( $_REQUEST[ 'tId' ] );
$pckD = $obj->selectData( TABLE_PACKAGE, "", "pck_id='" . $pck_id . "'", 1 );

$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];

$user_id = $obj->filter_numeric( $pckD[ 'user_id' ] );

if(($pckD[ 'pck_start_dt' ]!='0000-00-00') && ($pckD[ 'pck_start_dt' ]!='0000-00-00'))
{
	$pstart = strtotime( $pckD[ 'pck_start_dt' ] );
	$pend = strtotime( $pckD[ 'pck_end_dt' ] );
	$duration = ( (int)( ( $pend - $pstart ) / ( 24 * 3600 ) ) + 1 ) . " Days - " . (int)( ( $pend - $pstart ) / ( 24 * 3600 ) ) . " Nights";
}
else
{
	$duration = "";
}
$obj->unset_notification( $log_user_id, "tour-details.php?tId=" . $pck_id );
?>
<!doctype html>
<html lang="en">
<head>
<?php include("page_includes/registration_head.php");?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
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
            <!-- ...................btn top section start............ -->
            <?php include("page_includes/dash_body_nav.php");?>
            <!-- ...................btn top section end............ -->
            <!-- ..........showcase area start............ -->
            <section class="pack_info">
              <div class="">
                <button class="btn pack_jist">Trip highlight<span class="material-symbols-outlined">expand_more</span></button>
              </div>
              <ul class="pack_jist-field">
                <li>
                  <div>
                    <h5>Tour Name</h5>
                    <h4>
                      <?=$pckD['pck_title'];?>
                    </h4>
                  </div>
                </li>
                <li> 
                  <div>
                    <h5>Journey Month</h5>
                    <!--                    <h5>Start Date</h5>-->
                    <h4>
                      <?=date("F", strtotime($pckD['pck_month']."/12/".date("Y")));?>
                    </h4>
                  </div>
                </li>

                <li> 
                  <div>
                    <h5>Seat Available:</h5>
                    <h4>
                      <?=$pckD['pck_capacity'];?>
                    </h4>
                  </div>
                </li>

                <li class="start_point"> 
                  <div>
                    <h5>Start Point:</h5>
                    <h4><?php echo $pckD['pck_start_pt']; ?></h4>
                  </div>
                </li>
                <li class="end_point"> 
                  <div>
                    <h5>End Point:</h5>
                    <h4><?php echo $pckD['pck_end_pt']; ?></h4>
                  </div>
                </li>

				<?php if($duration!=''){?>
                <li> 
                  <div>
                    <h5>Duration of Tours</h5>
                    <h4>
                      <?=$duration;?>
                    </h4>
                  </div>
                </li>
				<?php }?>

        <?php if($pckD['pck_start_dt']!='0000-00-00'){?>                
                <li>
                  <div>
                    <h5>Start Date:</h5>
                    <h4><?php echo date("F d,y",strtotime($pckD['pck_start_dt'])); ?></h4>
                  </div>
                </li>
				<?php }?>
				<?php if($pckD['pck_start_dt']!='0000-00-00'){?>  
				<li> 
                  <div>
                    <h5>End Date:</h5>
                    <h4><?php echo date("F d,y",strtotime($pckD['pck_end_dt'])); ?></h4>
                  </div>
                </li>
			   <?php }?>
                
				<?php if($pckD['pck_start_tm']!=""){?>
                <li> 
                  <div>
                    <h5>Start time:</h5>
                    <h4><?php echo $pckD['pck_start_tm']; ?></h4>
                  </div>
                </li>
				<?php }?>
              </ul>
            </section>
            <!-- ..........showcase area end............ -->
            <picture class="top_banner"> <img src="<?=PACKAGE.$pckD['pck_photo'];?>"> <img src="<?=PACKAGE.$pckD['pck_photo'];?>" class="blur"> </picture>
            

            <div class="modal fade-scale pop_box" id="contact_agent" tabindex="-1" aria-labelledby="contact_agentLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="">Contact us and get details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form name="contactHost" id="contactHost" action="#" method="post" enctype="multipart/form-data">
                      <div class="devide_box mb-3">
                        <div class="col-sm-6 form_row_holder" >
                          <label for="inputEmail3" class="col-sm-3 col-form-label">Adult</label>
                          <div class="plus_minus_block">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">No. of Adult</label>
                            <div class="number"> <span class="minus">-</span>
                              <input type="text" name="cb_adult" id="cb_adult" class="form-control" placeholder="" value="0" >
                              <span class="plus">+</span> <span class="messages" id="pck_capacity_message"></span> </div>
                          </div>
                        </div>
                        <div class="col-sm-6 form_row_holder" >
                          <label for="inputEmail3" class="col-sm-3 col-form-label">Child</label>
                          <div class="plus_minus_block">
                            <label for="inputEmail3" class="col-sm-4 col-form-label">No. of Child</label>
                            <div class="number"> <span class="minus">-</span>
                              <input type="number" name="cb_child" id="cb_child" class="form-control" placeholder="" value="0" >
                              <span class="plus">+</span> <span class="messages" id="pck_capacity_message"></span> </div>
                          </div>
                        </div>
                      </div>
                      <fieldset class="">
                      <div class="form-check  d-flex align-items-center">
                        <input class="form-check-input2" type="checkbox" id="gridCheck1" name="cb_share_phone" value="y">
                        <label class="form-check-label" for="gridCheck1"> Share my contact (Phone) with this host. </label>
                      </div>
                      </fieldset>
                      <div class="col-sm-12 form_row_holder mb-3">
                        <label for="consubt" class="form-label">Share Detail Quarry.</label>
                        <textarea type="text" rows="3" class="form-control" id="cb_query" name="cb_query"></textarea>
                      </div>
					  <div class="log_form_row" id="" >
			
				<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" value="y" id="contact_agree" name="contact_agree">
  <label class="form-check-label" for="agree">
I agree to <a href="<?php echo FURL?>terms" target="_blank">Terms and conditions</a>.
  </label>
</div>
		
				</div>
				
                      <div class="modal-footer">
                        <input type="hidden" name="cb_pck" id="cb_pck" class="" value="<?=$pck_id;?>" >
                        <input id="contact_host" type="submit" name="contact_host" value="Submit" class="btn btn-primary" >
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="single_pack_description">
              <div class="lt_column_sec">
                <div class="mb-3">
                 <?php /*?> <div class="input-group copy_coad_sec mb-0">
                    <input type="text" readonly class="form-control" id="myInput" placeholder="Recipient's username" value="<?=FURL?>tour-details?tId=<?php echo $pckD['pck_id'];?>">
                    <button class="btn btn-outline-secondary" type="button" onClick="myFunction()"> <span class="material-symbols-outlined">content_copy</span> </button>
                  </div>
                  <span class="copy_coad_message">Copy link and share to your other social media site.</span> <?php */?></div>
                <!--			<p><a href="<?=FURL?>tour-details?tId=<?php echo $pckD['pck_id'];?>">Share</a></p>-->
                <h3 class="heading_bold">Tour Details </h3>
                <div class="description_txt">
                  <p>
                    <?=nl2br(html_entity_decode($pckD['pck_desc']));?>
                  </p>
                </div>
				  
				  
				    <?php if($pckD['pck_terms']!=""){?>
                <h3 class="heading_bold" >Terms &amp; Conditions </h3>
                <div class="terms">
				  <?=nl2br(html_entity_decode($pckD['pck_terms']));?>
                </div>
                <?php }?>
				  
				  
				  
				  
              </div>
              <div class="rt_column_sec">
                
                <div class=" p-1 d-flex justify-content-center flex-column">
                  <div class="price_holder"> INR <?=$obj->hide_price($pckD['pck_price']);?> /- <span>Per head</span> </div>
                  <div class="btn-group" role="group" aria-label="Basic example">
                    <?php if($log_user_id!='' && $log_user_id!=$user_id){?>
                    <button type="button" class="btn beta_contact_agent mt-0 border-0" data-bs-toggle="modal" data-bs-target="#contact_agent"> Contact </button>
                    <?php }?>
                    <!-- <button type="button" class="btn beta_contact_agent bg-info mt-0  border-0" data-bs-toggle="modal" data-bs-target="#contact_agent"> Customise </button> -->
                  </div>
                </div>
                <h3 class="heading_bold"> Included </h3>
                <ul class="include">
                  <?php
                  $incL = explode( ",", $obj->package_included_list($pckD[ 'pck_inc' ]) );
				  if(!empty($incL))
				  {
                  foreach ( $incL as $inc ) {
                    ?>
                  <li>
                    <?=$inc;?>
                  </li>
                  <?php }} ?>
                </ul>
                <h3 class="heading_bold"> Excluded </h3>
                <ul class="exclude">
                  <?php
                  $nincL = explode( ",", $obj->package_not_included_list( $pckD[ 'pck_inc' ] ) );
				  if(!empty($nincL))
				  {
                  foreach ( $nincL as $ninc ) {
                    ?>
                  <li><span class="material-symbols-outlined">close</span>
                    <?=$ninc;?>
                  </li>
                  <?php }} ?>
                </ul>
                <?php if($pckD['pck_hotel_veh']!=""){?>
                <h3 class="heading_bold" >Hotels &amp; vehicle details</h3>
                <div class="terms">
				   <?=nl2br(html_entity_decode($pckD['pck_hotel_veh']));?>
                </div>
                <?php }?>
              
                <div class="clearfix"> </div>
              </div>
            </div>
            <?php if($log_user_id!=$user_id){?>
            <button type="button" class="btn beta_contact_agent w-100" data-bs-toggle="modal" data-bs-target="#contact_agent"> Contact Host </button>
            <?php }?>
          </div>
          <div class="lt_second_column">
            <?php include("page_includes/dash_left_sidebar.php");?>
          </div>
          <div class="clear"></div>
          <div class="related_item">
            <?php //include("page_includes/related_tours.php");?>
          </div>
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
<?php include("page_includes/tour_details_footer_script.php");?>
<script>
    
	
	 $(document).ready(function() {
			$('.minus').click(function () {
				var $input = $(this).parent().find('input');
				if($input.val()=='') $input.val(0);
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input');
				if($input.val()=='') $input.val(0);
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
		});


  </script>
<script>
function myFunction() {
  // Get the text field
  var copyText = document.getElementById("myInput");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

  // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);
  
  // Alert the copied text
  alert("Copied the text: " + copyText.value);
}
</script>
</body>
</html>

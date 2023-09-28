<?php
include( "includes/connection.php" );
userSecure();
include( "includes/all_form_check.php" );

$pckD[ 'pck_tags' ] = "";
if ( isset( $_REQUEST[ 'tId' ] ) && $_REQUEST[ 'tId' ] != '' ) {
  $pck_id = $obj->filter_numeric( $_REQUEST[ 'tId' ] );
  $pckD = $obj->selectData( TABLE_PACKAGE, "", "pck_id='" . $pck_id . "'  and pck_status='Active'", 1 );

  $package_inc = explode( ",", trim( $pckD[ 'pck_inc' ], "," ) );

  $_SESSION[ 'pck_photos' ][ 0 ] = $pckD[ 'pck_photo' ];
} else {
  $_SESSION[ 'pck_photos' ][ 0 ] = "";
}

$log_user_id = $_SESSION[ 'user' ][ 'u_login_id' ];
$user_id = $_SESSION[ 'user' ][ 'u_login_id' ];


if ( isset( $_POST[ 'create_new_package' ] ) && $_POST[ 'create_new_package' ] == 'Yes' ) {
  $pckD = $_POST;
  if ( isset( $_POST[ 'faci' ] ) && !empty( $_POST[ 'faci' ] ) ) {
    $data[ 'pck_inc' ] = implode( ",", $_POST[ 'faci' ] );
    $package_inc = explode( ",", trim( $data[ 'pck_inc' ], "," ) );
  } else {
    $package_inc = array();
  }
}

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
            <?=stripslashes($obj->display_message("message"));?>
            <?php include("page_includes/dash_body_nav.php");?>
            <div class="create_single_pack">
              <h3 class="heading_bold top_none">
                <?php if(isset($pckD['pck_id']) && $pckD['pck_id']!=''){?>
                Edit Your Tour Package
                <?php }else{ ?>
                Add Your Tour Details
                <?php }?>
              </h3>
              <section class="create_pach_form_holder">
              <form name="createPackage" id="createPackage" action="" method="post" enctype="multipart/form-data">
              <?php if(!isset($pckD['pck_id'])){?>
              <input type="hidden" name="create_new_package" value="Yes">
              <?php }else{?>
              <input type="hidden" name="edit_package" value="Yes">
              <input type="hidden" name="package_id" value="<?php echo $pckD['pck_id'];?>">
              <?php }?>
              <style>
				  
					  .create_pack_holder .form-control-lg,.create_pack_holder .form-select-lg{
						  height: 4rem;
					  }
				  </style>
              <div class="create_pack_holder row">
                <div class="col-md-12 col-lg-6 mb-3">
                  <label>Package Name <sup>*</sup></label>
                  <input class="text_input form-control form-control-lg" id="pck_title" name="pck_title" type="text" placeholder="Package Name" value="<?php echo $pckD['pck_title'];?>">
                  <span class="messages" id="pck_title_message"></span> </div>
                <div class="col-md-12 col-lg-3 mb-3">
                  <label>Destination <sup>*</sup></label>
                  <input class="text_input form-control form-control-lg" id="pck_dest" name="pck_dest" type="text" placeholder="Destination" value="<?php echo $pckD['pck_dest'];?>">
                  <span class="messages" id="pck_dest_message"></span> </div>
                <div class="col-md-12 col-lg-3  mb-3 col-4">
                  <label>Journey <sup>*</sup></label>
                  <select class="form-select form-select-lg mb-3" name="pck_month" id="pck_month" >
                    <option value="">Month </option>
                    <?php
            $year = date( "Y" );
            for ( $iM = 1; $iM <= 12; $iM++ ) {
              ?>
                    <option value="<?php echo date("n", strtotime("$iM/12/$year"));?>" <?php if($pckD['pck_month'] == date("n", strtotime("$iM/12/$year"))){?>selected<?php }?>><?php echo date("F", strtotime("$iM/12/$year"));?></option>
                    <?php
            }
            ?>
                  </select>
                  <span class="messages" id="pck_month_message"></span> </div>
                <div class="col-md-12 col-lg-3 mb-3 col-4">
                  <label>Start Date</label>
                  <input class="date_input form-control form-control-lg" type="date" name="pck_start_dt" id="pck_start_dt" value="<?php echo $pckD['pck_start_dt'];?>" />
                  <span class="messages" id="pck_start_dt_message"></span> </div>
                <div class="col-md-12 col-lg-3 mb-3 col-4" >
                  <label>End Date</label>
                  <input class="date_input form-control form-control-lg" type="date" name="pck_end_dt" id="pck_end_dt" value="<?php echo $pckD['pck_end_dt'];?>"/>
                  <span class="messages" id="pck_end_dt_message"></span> </div>
                <div class="col-md-12 col-lg-3 mb-3 col-6">
                  <label>Start point <sup>*</sup></label>
                  <input class="text_input form-control form-control-lg"  type="text" placeholder="Start point" name="pck_start_pt" id="pck_start_pt" value="<?php echo $pckD['pck_start_pt'];?>">
                  <span class="messages" id="pck_start_pt_message"></span> </div>
                <div class="col-md-12 col-lg-3 mb-3 col-6">
                  <label>End point <sup>*</sup></label>
                  <input class="text_input form-control form-control-lg"  type="text" placeholder="End point" name="pck_end_pt" id="pck_end_pt" value="<?php echo $pckD['pck_end_pt'];?>">
                  <span class="messages" id="pck_end_pt_message"></span> </div>
                <div class="col-md-12 col-lg-4 mb-3 col-6">
                  <label>Price <sup>*</sup></label>
                  <!--          <input class="text_input  form-control form-control-lg"  type="text" placeholder="Price" name="pck_price" id="pck_price" value="<?php echo $pckD['pck_price'];?>" onKeyUp="filter_price('pck_price',this.value)">-->
                  <div class="input-group mb-3">
                    <select class="form-select form-select-lg"  aria-label="Example select with button addon" style="max-width:6.5rem;" name="pck_curr" id="pck_curr">
                    <?php echo $obj->currencySelect($pckD['pck_curr']);?>
                    </select> 
                    <input class="text_input  form-control form-control-lg"  type="text" placeholder="Price" name="pck_price" id="pck_price" value="<?php echo $pckD['pck_price'];?>" onKeyUp="filter_price('pck_price',this.value)">
                  </div>
                  <span class="messages" id="pck_price_message"></span> </div>
                <div class="col-md-12 col-lg-4 mb-3 col-6">
                  <label>Price for foreign tourist</label>
                  <!--          <input class="text_input  form-control form-control-lg"  type="text" placeholder="Price" name="pck_price" id="pck_price" value="<?php echo $pckD['pck_price'];?>" onKeyUp="filter_price('pck_price',this.value)">-->
                  <div class="input-group mb-3">
                    <select class="form-select form-select-lg"  aria-label="Example select with button addon" style="max-width:6.5rem;"  name="pck_foreign_curr" id="pck_foreign_curr">
                    <?php echo $obj->currencySelect($pckD['pck_foreign_curr']);?> 
                    </select>
                    <input class="text_input  form-control form-control-lg"  type="text" placeholder="Price" name="pck_fo_price" id="pck_fo_price" value="<?php echo $pckD['pck_foreign_price'];?>" onKeyUp="filter_price('pck_fo_price',this.value)">
                  </div>
                </div>
                <div class="col-sm-3 col-md-3  mb-3 col-4">
                  <label>Seat</label>
                  <div class="number input-group"> <span class="minus input-group-text">-</span>
                    <input type="text" class="form-control"  value="<?php if($pckD['pck_capacity']!=""){ echo $pckD['pck_capacity']; }else{ echo "0";}?>" name="pck_capacity" id="pck_capacity"/>
                    <span class="plus input-group-text">+</span> </div>
                  <span class="messages" id="pck_capacity_message"></span> </div>
                <div class="col-md-12 col-lg-4 mb-3 col-4">
                  <label>Start Time </label>
                  <input class="time_input form-control form-control-lg" type="time" name="pck_start_tm" id="pck_start_tm" value="<?php echo $pckD['pck_start_tm'];?>"/>
                  <span class="messages" id="pck_start_tm_message"></span> </div>
                <div class="col-md-12 mb-3">
                  <label>Description </label>
                  <input class="text_input editor"  type="text" placeholder="Description" name="pck_desc" id="pck_desc" value="<?php echo $pckD['pck_desc'];?>">
                </div>
                <div class="col-md-12 mb-3">
                  <label>Hotel and vehicle details</label>
                  <input class="text_input editor"  type="text" placeholder="Hotel and vehicle details" name="pck_hotel_veh" id="pck_hotel_veh" value="<?php echo $pckD['pck_hotel_veh'];?>">
                </div>
                <div class="col-md-12 mb-3">
                  <label>Terms &amp; Conditions </label>
                  <input class="text_input editor"  type="text" placeholder="Package Terms" name="pck_terms" id="pck_terms" value="<?php echo $pckD['pck_terms'];?>">
                </div>
                <div class="col-md-12 mb-3">
                  <label>Include</label>
                  <div class="multiselect_check ">
                    <div class="small_check">
                      <?php
              $dataI = explode( ",", trim( $data[ 'pck_inc' ], "," ) );
              $sqlF = $obj->selectData( TABLE_FACILITY_INC, "", "faci_status='Active'" );
              while ( $resF = mysqli_fetch_array( $sqlF ) ) {
                ?>
                      <span>
                      <input type="checkbox" name="faci[]" id="pck_facil<?=$resF['faci_id'];?>" value="<?=$resF['faci_id'];?>" <?php if(isset($package_inc)){ if(in_array($resF['faci_id'],$package_inc)){?> checked="checked"<?php }} ?>>
                      <label for="pck_facil<?=$resF['faci_id'];?>">
                      <?=$resF['faci_title'];?>
                      </label>
                      </span>
                      <?php }?>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <div class="single_block_row" style="width: 100%">
                    <div class="block_12">
                      <picture>
                        <label class="input-group-text" for="pup_img">Upload Image</label>
                        <input type="file" class="" id="pup_img" name="pup_img" title=".jpg .jpeg .png .gif">
                        <span>Please upload trip image [.jpg .jpeg .png .gif]</span> </picture>
                    </div>
                    <span class="messages" id="up_img_message"></span>
                    <?php /*?><?php if(isset($pckD['pck_photo']) && file_exists(PACKAGE.$pckD['pck_photo'])){?>
<picture class="top_banner"> <img src="<?=PACKAGE.$pckD['pck_photo'];?>"> <img src="<?=PACKAGE.$pckD['pck_photo'];?>" class="blur"> </picture>
            <?php }?><?php */?>
                    <div id="pckupimgs" class="mt-4">
                      <?php if(isset($_SESSION['pck_photos'][0]) && $_SESSION['pck_photos'][0]!=''){?>
                      <div class="row">
                        <?php for($i=0;isset($_SESSION['pck_photos'][$i]),$_SESSION['pck_photos'][$i]!="";$i++){?>
                        <div class="col-4">
                          <div class="1st">
                            <button type="button" class="close" onClick="delPckUpImg('<?=$i;?>')"><span>&times;</span></button>
                            <img class="w-100" src="<?=PACKAGE;?><?=$_SESSION['pck_photos'][$i];?>"></div>
                        </div>
                        <?php }?>
                      </div>
                      <?php }?>
                    </div>
                  </div>
                </div>
                <?php if(!isset($pckD['pck_id']) || $pckD['pck_id']==''){?>
                <div class="col-md-12 col-lg-12 mb-3 " id="tags">
                  <label>Search keyword </label>
                  <select class="select2 form-select lang" name="pck_tags_list[]" id="pck_tag" multiple="multiple" data-tags="true">
                    <?php echo $obj->tagsSelect($pckD['pck_tags']);?>
                  </select>
                  <span class="messages" id="pck_start_tm_message"></span> </div>
                <?php }?>
                <div class="col-md-12 mb-3">
                  <?php if(!isset($pckD['pck_id'])){?>
                  <input class="form-check-input2" type="checkbox" value="y" name="pck_cust" id="pck_cust">
                  <label id=""> Customization available </label>
                  <?php } ?>
                </div>
                <div class="col-md-12 mb-3">
                  <?php if(!isset($pckD['pck_id'])){?>
                  <input class="form-check-input2" type="checkbox" value="Y" name="pck_post_terms" id="pck_post_terms">
                  <label id="check1"> T &amp; C  By Portal <sup>*</sup> </label>
                  <span class="messages" id="pck_post_terms_message"></span>
                  <?php } ?>
                </div>
                <div class="col-md-12 mb-3">
                  <?php if(!isset($pckD['pck_id'])){?>
                  <a type="button"  class="create_btn share_btn" onClick="return create_package_validation();"> <span class="material-symbols-outlined"> add </span> Create Package </a>
                  <?php }else{?>
                  <a type="button" class="create_btn share_btn" onClick="return edit_package_validation();"> <span class="material-symbols-outlined"> add </span> Update Package </a>
                  <?php }?>
                </div>
              </div>
              <!-- Modal -->
              <div class="modal fade-scale pop_share"  id="share_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Share Box</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <div class="pop_check_holder">
              <form>
                <ul>
                  <li> <a href="#" onClick="set_share("1");">
                    <input class="form_share_check" type="radio" name="share" id="share1" value="1" >
                    <div><span class="material-symbols-outlined icon">share</span> <span  class="info_ico"><span class="material-symbols-outlined icon_for_info">error</span><strong class="info_pop">Public</strong></span></div>
                    <h4>Share to </h4>
                    <h3>All</h3>
                    </a> </li>
                  <li> <a href="#"  onClick="set_share("2");">
                    <input class="form_share_check" type="radio" name="share" id="share2" value="2">
                    <div><span class="material-symbols-outlined icon">share</span><span  class="info_ico"> <span class="material-symbols-outlined icon_for_info">error</span><strong class="info_pop">Private</strong></span></div>
                    <h4>Share to </h4>
                    <h3>Friends</h3>
                    </a> </li>
                  <?php /*?>
<li> <a type="button" data-bs-toggle="modal" href="#custom_box">
    <input class="form_share_check" type="radio" name="share" id="share3" value="3">
    <span class="material-symbols-outlined icon">settings_suggest</span>
    <h4>Create</h4>
    <h3>Custom</h3>
  </a> </li>
          <?php */?>
                </ul>
              </form>
            </div>
            <div class="copy_code">
              <p>sitametquam/semper/ fringilla-ex-quis/ornare </p>
              <button href="#"><span class="material-symbols-outlined">content_copy</span> Copy</button>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="create_btn" onClick="create_package()"><span>+</span>Submit</button>
            <button type="button" class="create_btn" ><span>+</span>Need business</button>
          </div>
        </div>
      </div>
    </div>
    <!-- ............model end........... -->
    <!-- Modal 2nd start -->
    <div class="modal fade-scale for_business"  id="custom_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Business Form</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h3>Choose Price for Promotion:</h3>
            <div class="business_drop"> <span>INR</span>
              <select class="pop_form_business" aria-label="">
                <option selected="">1000/-</option>
                <option value="1">2000/-</option>
                <option value="2">3000/-</option>
                <option value="3">4000/-</option>
              </select>
            </div>
            <div class="package_detail">
              <div class="yello_business_box">
                <h6>Duration:</h6>
                <h2><strong>24</strong> Days</h2>
              </div>
              <div class="gray_business_box">
                <h6>Reach Upto:</h6>
                <h2><strong>10,000</strong> People</h2>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="create_btn" ><span>+</span>Create package</button>
          </div>
        </div>
      </div>
    </div>
    <!-- ............model 2nd end........... -->
    </form>
    </section>
  </div>
  </div>
  <div class="lt_second_column">
    <?php include("page_includes/dash_left_sidebar.php");?>
  </div>
  <div class="clear"></div>
  <!-- <div class="related_item">
    <?php include("page_includes/related_tours.php");?>
  </div> -->
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
<script src="js/create_package.js"></script>
<script src="js/common.js"></script>
<?php include("page_includes/profile_footer_script.php");?>
<?php include("page_includes/package_script.php");?>
<script src="https://cdn.tiny.cloud/1/gev1v6mh2xux4097lf3x9pzhj2o0yjo8bvbmedccl5w9z05w/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
      selector: '.editor',
		toolbar:false,
		menubar:false,
      //plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
      //toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
		height:'200px',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ],
    });
	
	 $(document).ready(function() {
			$('.minus').click(function () {
				var $input = $(this).parent().find('input');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
		});

function filter_price(id,value)
{
	var price = value.replace(/[^0-9.]/g,"");
	$('#'+id).val(price);
}
   $(document).ready(function() {



function hideSelected(value) {

  if (value && !value.selected) {
    return $('<span>' + value.text + '</span>');
  }
}

/*$('#jobseeker_skilldata').select2({
  maximumSelectionLength : 4 ,
  allowClear: false,
  templateResult: hideSelected,
});*/

$('#pck_tag').select2().on('select2:open', function() {
    $('.select2-search__field').attr('maxlength', 15);
});


$('#pck_tag').select2({
  dropdownParent: $('#tags'),
  maximumSelectionLength :3,
  language: {
        // You can find all of the options in the language files provided in the
        // build. They all must be functions that return the string that should be
        // displayed.
        maximumSelected: function (e) {
           var t = "Maximum of " + e.maximum + " Tags Allowed";
            //e.maximum != 1 && (t += "s");
            return t;
        }
    },
  allowClear: false,
  templateResult: hideSelected,
});

});
  </script>
</body>
</html>

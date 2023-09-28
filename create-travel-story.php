<?php include("includes/connection.php");
userSecure();
include("includes/all_form_check.php");


$log_user_id = $_SESSION['user']['u_login_id'];
$user_id     = $_SESSION['user']['u_login_id'];

if(isset($_REQUEST[ 'tId' ]) && $_REQUEST[ 'tId' ]!='')
{
	$ts_id = $obj->filter_numeric( $_REQUEST[ 'tId' ] );
	$_SESSION['edit_story_id'] = $ts_id;
	$tsD = $obj->selectData(TABLE_STORY, "", "ts_id='" . $ts_id . "' and ts_status='Active'", 1 );
	$extra = ""; 
	//if(isset($_SESSION['cur_tss_id']) && $_SESSION['cur_tss_id']!='') $extra = " and tss_id = '".$_SESSION['cur_tss_id']."'";
	//$tssD = $obj->selectData(TABLE_STORY_SUB, "", "ts_id='" . $ts_id . "' and tss_status='Active'".$extra, 1 ,"tss_id asc");
	//$_SESSION['cur_tss_id'] = $tssD['tss_id'];
	
	if(isset($tsD['ts_photos']) && $tsD['ts_photos']!='')
	{
		$_SESSION['story_photos'] = explode(",",$tsD['ts_photos']);
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
    <div class="container">
      <div class="row">
        <div class="let_body col-xs-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
          <div class="lt_first_column">
            <!-- ...................btn top section start............ -->
             <?=stripslashes($obj->display_message("message"));?>
		   <?php include("page_includes/dash_body_nav.php");?>
            <!-- ...................btn top section end............ -->
            <form class="traveldiery_create_form" id="storyCreate" name="storyCreate" action="" method="post" enctype="multipart/form-data">
			<?php if(!isset($_SESSION['new_story_id'])){?>
			 <input type="text" placeholder="Story Title" id="st_title" name="st_title" value="<?php echo $tsD['ts_title'];?>">
 			<?php }?>
              
              <section class="travel_story_fold">
              <ul>
                <li>
                  <div class="create_story_block">
                    <div class="single_block_row">
                      <div class="block_6">
					    <label>Start Date</label>
                        <input class="date_input" id="st_start_date" name="st_start_date" type="date" value="<?php echo $tsD['ts_start'];?>">
                      </div>
                      <div class="block_6">
					   <label>End Date</label>
                        <input class="date_input" type="date" name="st_end_date" id="st_end_date" value="<?php echo $tsD['ts_end'];?>"/>
                      </div>
                    </div>
					
                    <div class="single_block_row d-block" >
                    <label> Detail Story </label>
                      <textarea name="st_part_desc" id="st_part_desc" class="editor"><?php echo html_entity_decode($tsD['ts_desc']);?></textarea>
                    </div>
                    
                    <div class="single_block_row">
                
                        <picture class="image_upload_block">
                            <label class="input-group-text" for="up_img">Upload Image</label>
                            <input type="file" class="" id="up_img" name="up_img" title=".jpg .jpeg .png .gif" multiple="multiple">
                            <span>.jpg .jpeg .png .gif</span> 
                        </picture>

						<div id="upimgs" class="mt-4">
						<?php if(isset($_SESSION['story_photos'][0]) && $_SESSION['story_photos'][0]!=''){?>
						<div class="row">
						<?php for($i=0;isset($_SESSION['story_photos'][$i]),$_SESSION['story_photos'][$i]!="";$i++){?>
						<div class="col-4"><div class="1st"><button type="button" class="close" onClick="delUpImg('<?=$i;?>')"><span>&times;</span></button><img class="w-100" src="<?=STORY;?><?=$_SESSION['story_photos'][$i];?>"></div></div>
						<?php }?>
						</div>
						<?php }?>
						</div>
                      <!--<div class="block_6">
                        <div class="story_create_graybox">
                          <h4>You Can Upload Videos</h4>
                          <h5>Click on the button or drag and drop here</h5>
                          <div class="video_up">
                            <label class="input-group-text" for="up_img">Upload Video</label>
                            <input type="file" class="" id="up_img" title="Upload Video" >
                          </div>
                        </div>
                      </div>-->
                    </div>
                    <div class="single_block_row">
					<input type="hidden" name="bcl" id="bcl" value="">
                      <?php /*?><div class="block_2"> <!--<a href="#" class="create_btn" onClick="story_validation('n')"> <span class="material-symbols-outlined"> add </span> Go to Next Part </a>--><input type="submit" class="create_btn" name="sagn" id="sagn" value="Save and Go to Next Part" onClick="setB('n')"> </div><?php */?>
                      <div> <!--<a href="#" class="create_btn" onClick="story_validation('n')"> Save and Exit </a>--> 
					  <!--onClick="setB('e')"-->
					  <input type="submit" name="sae" id="sae" value="Save and Publish" class="create_btn" ></div>
                    </div>
                  </div>
                </li>

              </ul>
            </form>
          </div>
          <div class="lt_second_column">
            <!-- ................Tor operator.............. -->
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
  <?php include("page_includes/profile_footer_script.php");?>
  <?php include("page_includes/dash_comment_script.php");?>
  <?php include("page_includes/story_script.php");?>
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


  </script>
</body>
</html>

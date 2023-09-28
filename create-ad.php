<?php include("includes/connection.php");
userSecure();
include("includes/all_form_check.php");

$log_user_id = $_SESSION['user']['u_login_id'];
$user_id     = $_SESSION['user']['u_login_id'];
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
        <div class="let_body col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-9">
          <div class="lt_first_column">
            <?=stripslashes($obj->display_message("message"));?>
		   <?php include("page_includes/dash_body_nav.php");?>
            <div class="create_single_pack">

<!-- ..................................... amount start................. -->

<!--
<div class="row business_offer_list add_offer">
            
            
            <div class="col-sm-4">
              <div>
                <h4><strong>S1</strong>hours(10)</h4>
                
                <div class="price_list">
                  <p><span>Home page top tour:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>10/-</span></p>
                  <p><span>Home page top user:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>10/-</span></p>
                  <p><span>Home page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                  <p><span>Home page banner add(1920X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>30/-</span></p>
                  <p><span>Home page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>25/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S1</strong>hours(10)</h4>
                
                <div class="price_list">
                  <p><span>Search page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                  <p><span>Search page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>30/-</span></p>
                  <p><span>Search page banner add(200X300):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>25/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S1</strong>hours(10)</h4>
                
                <div class="price_list">
                  <p><span>Inner page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                  <p><span>Inner page banner add(800X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>30/-</span></p>
                  <p><span>Inner page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>25/-</span></p>
                </div>
              </div>
            </div>


            <div class="col-sm-4">
              <div>
                <h4><strong>S2</strong>hours(24)</h4>
                
                <div class="price_list">
                  <p><span>Home page top tour:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>8/-</span></p>
                  <p><span>Home page top user:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>8/-</span></p>
                  <p><span>Home page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>18/-</span></p>
                  <p><span>Home page banner add(1920X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>28/-</span></p>
                  <p><span>Home page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>22/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S2</strong>hours(24)</h4>
                
                <div class="price_list">
                  <p><span>Search page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>18/-</span></p>
                  <p><span>Search page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>28/-</span></p>
                  <p><span>Search page banner add(200X300):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>22/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S2</strong>hours(24)</h4>
                
                <div class="price_list">
                  <p><span>Inner page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>18/-</span></p>
                  <p><span>Inner page banner add(800X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>28/-</span></p>
                  <p><span>Inner page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>22/-</span></p>
                </div>
              </div>
            </div>


            <div class="col-sm-4">
              <div>
                <h4><strong>S3</strong>hours(30)</h4>
                
                <div class="price_list">
                  <p><span>Home page top tour:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>5/-</span></p>
                  <p><span>Home page top user:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>5/-</span></p>
                  <p><span>Home page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>15/-</span></p>
                  <p><span>Home page banner add(1920X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>25/-</span></p>
                  <p><span>Home page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S3</strong>hours(30)</h4>
                
                <div class="price_list">
                  <p><span>Search page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>15/-</span></p>
                  <p><span>Search page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>25/-</span></p>
                  <p><span>Search page banner add(200X300):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S3</strong>hours(30)</h4>
                
                <div class="price_list">
                  <p><span>Inner page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>15/-</span></p>
                  <p><span>Inner page banner add(800X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>25/-</span></p>
                  <p><span>Inner page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                </div>
              </div>
            </div>


            <div class="col-sm-4">
              <div>
                <h4><strong>S4</strong>hours(60)</h4>
                
                <div class="price_list">
                  <p><span>Home page top tour:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>3/-</span></p>
                  <p><span>Home page top user:</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>3/-</span></p>
                  <p><span>Home page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>10/-</span></p>
                  <p><span>Home page banner add(1920X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                  <p><span>Home page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>15/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S4</strong>hours(60)</h4>
                
                <div class="price_list">
                  <p><span>Search page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>10/-</span></p>
                  <p><span>Search page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                  <p><span>Search page banner add(200X300):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>15/-</span></p>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div>
                <h4><strong>S4</strong>hours(60)</h4>
                
                <div class="price_list">
                  <p><span>Inner page banner add(200X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>10/-</span></p>
                  <p><span>Inner page banner add(800X100):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>20/-</span></p>
                  <p><span>Inner page banner add(800X200):</span>   <span class="currency_sym"><span class="material-symbols-outlined">currency_rupee</span>15/-</span></p>
                </div>
              </div>
            </div>
            
            
            
          </div>
-->
<!-- ..................................... amount end................. -->

              <h3 class="heading_bold top_none"> Request for display Ads with us. </h3>
              <section class="create_pach_form_holder">
               
              <form name="createAd" id="createAd" action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="create_new_ad" value="Yes">
              <div class="add_outer">
              <div class="single_block_row">
                <div class="block_6">
                  <label>Package Name</label>
                  <input class="text_input" id="pck_name" name="pck_name" type="text" placeholder="Ex: 15 Day package" value="<?php echo $_POST['pck_name']?>">
                  <span class="messages" id="pck_name_message"></span> </div>
                <div class="block_6">
                <label>Company Name</label>
                  <input class="text_input" id="comp_name" name="comp_name" type="text" placeholder="Ex: Tour travl company" value="<?php echo $_POST['comp_name']?>">
                  <span class="messages" id="comp_name_message"></span> </div>
              </div>
			  <div class="single_block_row">
                <div class="block_6">
                  <label>Contact Name</label>
                  <input class="text_input" id="contact_name" name="contact_name" type="text" placeholder="Ex: Sona Sen" value="<?php echo $_POST['contact_name']?>">
                  <span class="messages" id="contact_name_message"></span> </div>
                <div class="block_6">
                  <label>Contact Phone</label>
                  <input class="text_input" id="contact_num" name="contact_num" type="text" placeholder="XX XXX XXXXX" value="<?php echo $_POST['contact_num']?>">
                  <span class="messages" id="contact_num_message"></span> </div>
              </div>
			  <div class="single_block_row">
                <div class="block_6">
                  <label>Contact Addess</label>
                  <input class="text_input" id="contact_address" name="contact_address" type="text" placeholder="Ex: Salt Lake, Kolkata, W.B." value="<?php echo $_POST['contact_address']?>">
                  <span class="messages" id="contact_address_message"></span> </div>
                <div class="block_6">
                  <label>Contact Email</label>
                  <input class="text_input" id="contact_email" name="contact_email" type="text" placeholder="Ex: sona@gmail.com" value="<?php echo $_POST['contact_email']?>">
                  <span class="messages" id="contact_email_message"></span> </div>
              </div>
			  <div class="single_block_row">
                <div class="block_6">
                  <label>Subject</label>
                  <input class="text_input" id="ad_subject" name="ad_subject" type="text" placeholder="Ex. Hotels in Darjeeling" value="<?php echo $_POST['ad_subject']?>">
                  <span class="messages" id="ad_subject_message"></span> </div>
                 <div class="block_6">
                  <picture>
                    <label class="input-group-text" for="ad_file">Upload Image</label>
                    <input type="file" class="" id="ad_file" name="ad_file" title=".jpg .jpeg .png .gif">
                    <span>.jpg .jpeg .png .gif</span> </picture>
                  <span class="messages" id="ad_file_message"></span> </div>
              </div>

              <div class="single_block_row">
                <div class="w-100">
			    <label>Description</label>
                  <input class="text_input editor"  type="text" placeholder="Description" name="ad_desc" id="ad_desc" value="<?php echo $_POST['ad_desc']?>">
				   <span class="messages" id="ad_desc_message"></span> </div>
                </div>

              </div>
              <div class="single_block_row">  
              <div class="block_4">  
              <button type="button" class="create_btn" onClick="return create_ad_validation();">Request Ads</button>

  </div> 
  </div> 
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
<script src="js/create_ad.js"></script>
<script src="js/common.js"></script>
<?php include("page_includes/profile_footer_script.php");?>
</body>
</html>

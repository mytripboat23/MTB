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
              <h3 class="heading_bold top_none"> Career Application</h3>
				
				
				<p>Welcome to our Career Page!</p>
<p>At <strong>MTB (My Trip Boat) </strong>we believe in the power of talent and innovation. We are constantly looking for passionate individuals who can contribute to our dynamic and diverse team. If you are driven, ambitious, and eager to make a difference, we invite you to explore the exciting career opportunities we have to offer.</p>
<p><br>
  Why Work with Us?</p>
<ol start="1" type="1">
  <li>Meaningful      Impact: Join us in our mission to [describe the company's mission and how      it positively impacts customers, society, or the industry]. You will have      the opportunity to work on challenging projects that make a real      difference.</li>
  <li>Collaboration      and Teamwork: We foster a collaborative and inclusive work environment,      where everyone's ideas are valued. You will have the chance to work with      talented professionals from various backgrounds, sharing knowledge and      expertise to achieve common goals.</li>
  <li>Growth      and Development: We believe in investing in our employees' growth and      development. We provide opportunities for continuous learning, training      programs, and mentorship to help you enhance your skills and reach your      full potential.</li>
  <li>Innovation      and Creativity: We encourage innovation and creativity, and we strive to      stay at the forefront of our industry. You will be part of a team that      embraces new ideas, cutting-edge technologies, and approaches to solving      complex challenges.</li>
  <li>Work-Life      Balance: We understand the importance of work-life balance in maintaining      a happy and healthy workforce. We offer flexible work arrangements,      wellness programs, and a supportive culture that promotes well-being.</li>
</ol>
<p>Our Available Positions:</p>
<ol start="1" type="1">
  <li>UI      Developer</li>
  <li>Full      stack developer</li>
  <li>Online      Marketing </li>
</ol>
<p>If you are interested in joining our team, please submit your application through our online portal. We look forward to reviewing your qualifications and getting to know you better.<br>
  <strong>MTB (My Trip Boat) </strong>is an equal opportunity employer. We celebrate diversity and are committed to creating an inclusive and supportive work environment for all employees.</p>

				
				
				
              <h3 class="heading_bold top_none"> Career Application</h3>
              <section class="create_pach_form_holder">
               
            <form name="careerApply" id="careerApply" action="" method="post" enctype="multipart/form-data">
            <div class="add_outer">
              <input type="hidden" name="career_submission" value="Yes">
              <div class="single_block_row">
                <div class="block_6">
                  <label>Full Name</label>
                  <input class="text_input" id="car_name" name="car_name" type="text" placeholder="Ex: Sona Sen" value="<?php echo $_POST['car_name']?>">
                  <span class="messages" id="car_name_message"></span> </div>
                <div class="block_6">
                  <label>Contact Number</label>
                  <input class="text_input" id="car_con_num" name="car_con_num" type="text" placeholder="XX XXX XXXXX" value="<?php echo $_POST['car_con_num']?>">
                  <span class="messages" id="car_con_num_message"></span> </div>
              </div>
			  <div class="single_block_row">
                <div class="block_6">
                  <label>Contact Email</label>
                  <input class="text_input" id="car_email" name="car_email" type="text" placeholder="Ex: sona@gmail.com" value="<?php echo $_POST['car_email']?>">
                  <span class="messages" id="car_email_message"></span> </div>
                <div class="block_6">
                  <label>Qualification</label>
                  <input class="text_input" id="car_qual" name="car_qual" type="text" placeholder="Ex: Masters, BSC" value="<?php echo $_POST['car_qual']?>">
                  <span class="messages" id="car_qual_message"></span> </div>
              </div>
			  <div class="single_block_row">
                <div class="block_6">
                  <label>Position</label>
                  <input class="text_input" id="car_pos" name="car_pos" type="text" placeholder="Ex: Software developer" value="<?php echo $_POST['car_pos']?>">
                  <span class="messages" id="car_pos_message"></span> </div>
                 <div class="block_6">
                  <picture>
                    <label class="input-group-text" for="car_res">Upload Resume</label>
                    <input type="file" class="" id="car_res" name="car_res" title=".doc, .docx, .pdf">
                    <span>.doc, .docx, .pdf</span> </picture>
                  <span class="messages" id="car_res_message"></span> </div>
              </div>
			  

              
              </div> 
  
              <div class="single_block_row">  
              <div class="block_4">  
              <button type="button" class="create_btn" onClick="return career_validation();"><span>+</span>Apply</button>

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
<script src="js/career.js"></script>
<script src="js/common.js"></script>
<?php include("page_includes/profile_footer_script.php");?>
</body>
</html>

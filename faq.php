<?php include("includes/connection.php");
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
    <?php //include("page_includes/search_filter.php");?>
    <!-- ..........filter area end........... -->
    <div class="container">
      <div class="row">
        <div class="let_body col-xs-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
          <h3 class="heading_bold"> FAQ's </h3>
          <div class="faq_accordian">
            <div class="accordion accordion-flush" id="accordionFlushExample">
			<?php
			$i=1;
			$sqlF = $obj->selectData(TABLE_FAQ,"","faq_status='Active'","","faq_id asc");
			while($resF = mysqli_fetch_array($sqlF))
			{
			?>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-faq_<?=$i?>">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq_<?=$i?>" aria-expanded="false" aria-controls="faq_<?=$i?>"> <?=$resF['faq_quest_title'];?></button>
                </h2>
                <div id="faq_<?=$i?>" class="accordion-collapse collapse" aria-labelledby="flush-faq_<?=$i?>" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body"><?=$resF['faq_quest_ans'];?></div>
                </div>
              </div>
			<?php
			$i++;
			}
			?>  
            <?php /*?>  <div class="accordion-item">
                <h2 class="accordion-header" id="flush-faq_2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq_2" aria-expanded="false" aria-controls="faq_2"> Q2. can I create tour package? </button>
                </h2>
                <div id="faq_2" class="accordion-collapse collapse" aria-labelledby="flush-faq_2" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">Yes, you are.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-faq_3">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq_3" aria-expanded="false" aria-controls="faq_3"> Q3. how can I choose tour ? </button>
                </h2>
                <div id="faq_3" class="accordion-collapse collapse" aria-labelledby="flush-faq_3" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">Just a search and click on any package you like and duscuse with the tour host.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-faq_4">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq_4" aria-expanded="false" aria-controls="faq_4"> Q4. Is my contact ill shared with others ? </button>
                </h2>
                <div id="faq_4" class="accordion-collapse collapse" aria-labelledby="flush-faq_4" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">No, it will be private by site.</div>
                </div>
              </div><?php */?>
            </div>
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
<?php include("page_includes/faq_footer_script.php");?>
</body>
</html>

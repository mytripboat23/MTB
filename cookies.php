<?php include("includes/connection.php");
$sqlC=$obj->selectData(TABLE_CONTENT,"","content_id='6'",1);

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
          <?php if(isset($sqlC['content_banner']) && $sqlC['content_banner']!=""){?><picture class="top_banner"><img src="<?=CBANNER?><?=$sqlC['content_banner'];?>"></picture><?php }?>
          <h3 class="heading_bold"> <?=$sqlC['content_header']?></h3>
          <section class="description_txt cms_page">
           <?php echo html_entity_decode(html_entity_decode($sqlC['content_descr']));?>
          </section>
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
<?php include("page_includes/registration_footer_script.php");?>
</body>
</html>

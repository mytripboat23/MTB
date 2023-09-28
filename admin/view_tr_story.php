<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_tr_story.php";
if($return_url)
{
	$redirect_url=$return_url;
}

$id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['sId']));

$mode="add";
$table_caption="View Travel Story Details";
$dataS=$obj->selectData(TABLE_STORY,"","ts_id='".$id."' and ts_status<>'Deleted'",1);
$sqlSD = $obj->selectData(TABLE_STORY_SUB,"","ts_id='".$id."' and tss_status<>'Deleted'","","tss_id asc");
$mode="View";
$table_caption="View Travel Story Details";


?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("page_includes/common.php");?>
</head>
<body class="no-skin">
<?php include("page_includes/top_navbar.php");?>
<div class="main-container" id="main-container">
  <?php include("page_includes/sidebar.php");?>
  <div class="main-content">
    <div class="breadcrumbs" id="breadcrumbs">
      <script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	  </script>
      <ul class="breadcrumb">
        <li> <i class="ace-icon fa fa-home home-icon"></i> <a href="dashboard.php">Home</a> </li>
        <li> <a href="show_package.php">Story</a></li>
		<li class="active"><?=$data['ts_title'];?></li>
      </ul>
      <!-- /.breadcrumb -->
      <?php /*?><div class="nav-search" id="nav-search">
        <form class="form-search" action="" method="get">
          <span class="input-icon">
          <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
          <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
        </form>
      </div><?php */?>
      <!-- /.nav-search -->
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Package <small> <i class="ace-icon fa fa-angle-double-right"></i><?=$table_caption;?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                <p><?=$dataS['ts_title'];?></p>
				<?php while($dataSD = mysqli_fetch_array($sqlSD)){?>
				<p> <?php echo date("d/m/Y",strtotime($dataSD['tss_date']));?></p>
				<p> <?php echo html_entity_decode(html_entity_decode($dataSD['tss_desc']));?></p>
				<?php 
				  $sImages = array();
				  $sImages = explode(",",trim($dataSD['tss_photos'],","));
				  for($k=0;isset($sImages[$k]),$sImages[$k]!="";$k++){?>
				 	<p><img src="../<?=STORY.$sImages[$k];?>"></p>
				  <?php }?>
				<?php }?>
              </div>
              <!-- /.span -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
            <!-- PAGE CONTENT ENDS -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.page-content-area -->
    </div>
    <!-- /.page-content -->
  </div>
  <!-- /.main-content -->
  <?php include("page_includes/footer.php");?>
</div>
<?php include("page_includes/dashboard_footer_script.php");?>
		<script type="text/javascript" src="js/jquery.validate.js"></script>		
	
		<script language="javascript" type="text/javascript">	
		$().ready(function() {
			$("#packEdit").validate({
					rules: {
						 
						ts_title: "required",						
						ts_validityy: 
							{
								required:true,
								number:true
							}
					},
					messages: {
						 
						ts_title: "Please enter Package title",
						ts_validityy: {
							required:"Please enter Valid Days",
							number:"Please enter a proper Valid Days!"
						}
					}
				});
		});			
</script>	
</script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
 <link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
 <script>
$(function() {
$("#ts_start_dt").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
          $("#ts_end_dt").datepicker("option","minDate", selected)
        }
    });

    $("#ts_end_dt").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selected) {
           $("#ts_start_dt").datepicker("option","maxDate", selected)
        }
    });
	
	$("#ts_start_tm").timepicker({
	 	  timeFormat: 'h:mm tt'
});

});
</script>
</body>
</html>
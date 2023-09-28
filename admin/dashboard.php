<?php include("../includes/connection.php");
adminSecure();
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
        <li class="active">Dashboard</li>
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
          <h1> Dashboard <small>   </small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <div class="alert alert-block alert-success">
              <button type="button" class="close" data-dismiss="alert"> <i class="ace-icon fa fa-times"></i> </button>
              <i class="ace-icon fa fa-check green"></i> Welcome to <?=CAPTION_TEXT;?> </div>
			  
			<?=$obj->display_message("message");?>           
            <div class="hr hr32 hr-dotted"></div>            
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
</body>
</html>

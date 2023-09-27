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
        <li class="active">Login Ad Listing</li>
      </ul>
      <!-- /.breadcrumb -->
      <?php /* ?>
      <div class="nav-search" id="nav-search">
        <form class="form-search" action="" method="get">
          <span class="input-icon">
          <input type="text" name="searchText" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" value="<?=$_REQUEST['searchText'];?>" />
		  
          <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
		  <input type="submit"  autocomplete="off" name="action" value="Search" />
        </form>
      </div>
	  <?php  */ ?>
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Login Ad Management <?php /* <span class="pull-right"><a href="edit_login_ad_listing.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Login Ad Listing</a> </span> */ ?></small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
  <div class="col-xs-12">
  <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr>
			<th>Order ID</th>          
			<th>User</th>  
			<th>Order Price</th> 
			<th>Order Date</th>
			<th>Action</th>
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			$sqlUAO = $obj->selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","o.order_id,o.user_id,o.order_tot_asimi,o.order_created","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id='0' and od.lap_id='0' and od.lasp_id='0' and od.od_status ='Active'","","o.order_id asc");
			if(mysqli_num_rows($sqlUAO)>0)
			{
			while($data=mysqli_fetch_assoc($sqlUAO)){ 
			$class = $i%2==0?'odd':'';
			$i++;
			$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$data['user_id']."' and user_status='Active'",1); 
		
	   ?>
        <tr>         
			<td><?=10000+$data['order_id'];?></td>
			<td><?=$userD['user_first_name']." ".$userD['user_last_name'];?><br>(<?=$userD['user_email']?>)</td> 
			<td><?=$data['order_tot_asimi'];?> Asimi</td>	
			<td><?=$data['order_created'];?></td> 
			<td><a href="manual_assign_login_ad.php?oId=<?=$data['order_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Assign Slot">ASSIGN</a></td>
        </tr>
      <?php
		  }
		  }
		  else
		  {
	  ?>
        <tr>
          <td class="center" colspan="5"><strong>No Record Found!</strong></td>		
        </tr>
	<?php
		  }
	?>	
      </tbody>
    </table>
  </form>	
  </div>
  <!-- /.span --> 
</div><!-- /.row -->
            
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
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
 <link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
<script language="javascript">
function validate_delete(chackboxname)
{
	if( jQuery("[myType]").is(':checked'))
	{
		return confirm("Are you sure you want to delete?");
	}
	else
	{
		alert("Please select at least one record to delete");
	    return false;
	}
	
}

jQuery(document).ready(function(){
jQuery('input[name="master_select"]').bind('click', function(){
var status = jQuery(this).is(':checked');
jQuery('input[type="checkbox"]').attr('checked', status);
});

});
</script>
</body>
</html>
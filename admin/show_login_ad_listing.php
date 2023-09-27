<?php include("../includes/connection.php");
adminSecure();
$userId= $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['mId']));
 

 
$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))); 
$extra .= " and lap_date  <= '".$fromDate."'";
  
$sql=$obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","(lap_pstatus = 'p' or lap_url_1 != '') ".$extra." order by lap_date desc",2);
$pg_obj=new pagingRecords();
$pg_obj->setPagingParam("g",5,20,1,1);
$getarr = $obj->filter_mysql_array($_GET);
unset($getarr['msg']);
$res=$pg_obj->runQueryPaging($sql,$pageno,$getarr);
$qr_str=$pg_obj->makeLnkParam($getarr,0);
$pageno = 1;
if($_REQUEST['pageno']!="")
{
	$pageno = $obj->filter_mysql($obj->filter_numeric($_REQUEST['pageno']));
}
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Login Ad Listing Management <?php /* <span class="pull-right"><a href="edit_login_ad_listing.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Login Ad Listing</a> </span> */ ?></small> </h1>
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
			<th>Date</th>
			<th>User</th>  
			<th>Submitted ads </th> 
			<th>Total View </th>
			<th>Clicks</th> 
			<th>CTR</th>    
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){ 
			$class = $i%2==0?'odd':'';
			$i++;
			$dateLP = date('D,M d,Y', strtotime($data['lap_date']));
			$conver_rate1 = ( $data['lap_url1_tot_click'] / $data['lap_url1_tot_view'] ) * 100;  
			$conver_rate2 = ( $data['lap_url2_tot_click'] / $data['lap_url2_tot_view'] ) * 100;  
			 
	   ?>
        <tr>         
			<td><?=date('D, F d,Y', strtotime($data['lap_date']));?></td>
			<td><?=$obj->getUserName($data['user_id']);?></td>
			<td><?=$data['lap_url_1'];?><br /> <?=$data['lap_url_2'];?></td>
			<td><?=$data['lap_url1_tot_view'];?><br /> <?=$data['lap_url2_tot_view'];?></td>  
			<td><?=$data['lap_url1_tot_click'];?><br /> <?=$data['lap_url2_tot_click'];?></td>  
			<td><?=$conver_rate1;?><br /> <?=$conver_rate2;?></td>   
			 
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="8"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
		 
        </tr>
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
<?php include("../includes/connection.php");
adminSecure();
$userId= $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['mId']));
 
$sql=$obj->selectData(TABLE_LCP_TRACKING,"","lp_status<>'Deleted' and lp_page_name !=''".$extra." group by lp_page_name order by lp_id desc",2);
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
        <li class="active">LCP Tracking Listing</li>
      </ul>
      
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> LCP Tracking Management  </small> </h1>
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
			<th>Page</th>
			<th>URL</th>		 
			<th><center>Total Display</center></th>
			<th><center>Total opt-ins</center></th>
			<th><center>Conversion Rate</center></th>		 
        </tr>
      </thead>
      <tbody>
		<?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){ 
			$class = $i%2==0?'odd':'';
			$i++;
			$sqlLPD = $obj->selectData(TABLE_LCP_TRACKING,"sum(lp_tot_display) as tot_display","lp_page_name='".$data['lp_page_name']."' and lp_status='Active'",1);			
			$tot_display = $sqlLPD['tot_display'];
			
			$sqlLPOPT = $obj->selectData(TABLE_LCP_TRACKING,"sum(lp_tot_opt_ins) as tot_opt_ins","lp_page_name='".$data['lp_page_name']."' and lp_status='Active'",1);
			$tot_opt_ins = $sqlLPOPT['tot_opt_ins'];
			 
		?>
        <tr>         
			<td>
			<?	 
				if($data['lp_page_name'] =='1P') echo "Register Page";
				else if($data['lp_page_name'] =='2P') echo "Sales and Profit";
				else if($data['lp_page_name'] =='3P') echo "LCP 3 ADS";
				else if($data['lp_page_name'] =='3PE') echo "LCP 3 EARN"; 
				else if($data['lp_page_name'] =='4P') echo "LCP 4 ADS";							
				else if($data['lp_page_name'] =='4PE') echo "LCP 4 EARN";					
				else if($data['lp_page_name'] =='5P') echo "LCP 5 ADS";			
				else if($data['lp_page_name'] =='6P') echo "Watching ADS";	 										 
			?>
			 </td>    
			<td>
			<?	 
				if($data['lp_page_name'] =='1P') echo FURL."register.php";
				else if($data['lp_page_name'] =='2P') echo FURL."sales_and_profits.php";
				else if($data['lp_page_name'] =='3P') echo FURL."lcp_3_ads.php";
				else if($data['lp_page_name'] =='3PE') echo FURL."lcp_3_earn.php"; 
				else if($data['lp_page_name'] =='4P') echo FURL."lcp_4_ads.php";							
				else if($data['lp_page_name'] =='4PE') echo FURL."lcp_4_earnings_show_me.php";					
				else if($data['lp_page_name'] =='5P') echo FURL."lcp_5_ads.php";			
				else if($data['lp_page_name'] =='6P') echo FURL."watching_ads.php";	 										 
			?>
			 </td>
			<td><center><?=$tot_display;?></center></td> 		 
			<td><center><?=$tot_opt_ins;?></center></td>	
			<?php  $conver_rate = ( $tot_opt_ins / $tot_display ) * 100; ?>	
			<td><center><?php if($tot_opt_ins) { ?><?=round($conver_rate,8);?>% <?php } else {?> 0% <?php } ?></center></td>
			 
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="5"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
	 
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
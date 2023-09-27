<?php include("../includes/connection.php");
adminSecure();
 
if($_REQUEST['search_ip']!="")
{
	$extra .= "and monl_ip ='".$obj->filter_mysql($_REQUEST['search_ip'])."'"; 
} 
$last_date = date("Y-m-d",mktime(0,0,0,date("m")-4,date("d"),date("Y")));
$sql=$obj->selectData(TABLE_MONITORING_LOGINS,"","monl_date >= '".$last_date."' ".$extra,2,"monl_id desc");

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
        <li class="active">Login History Listing</li>
      </ul>
      
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Login History Management  </small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			 <div class="email_searchBox clearfix">
			<div class="row">
            	 <div class="col-md-12">
					
					<form action="" method="get">
					<div id="custom-search-input">
					<div class=" col-md-3">
							<input type="text" name="search_ip" id="search_ip" class="form-control" placeholder="255.255.255.255" value="<?=$_REQUEST['search_ip']?>" />
					</div>	
					<?php /*?><div class=" col-md-3">
							<input type="text" name="search_date" id="search_date" class="form-control" placeholder="Date" value="<?=$_REQUEST['search_date']?>" />
					</div><?php */?>		
						
						<div class="input-group col-md-4">
							<span class="input-group-btn">
								<button class="btn btn-info" type="submit">Search</button>
							</span>
						</div>
					</div>
					</form>
				</div>
				</div>
            </div>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
  <div class="col-xs-12">
  <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr>
			<th><center>Account ID</center></th>
			<th><center>Referrer ID</center></th>
			<th><center>IP</center></th>
			 
			<th><center>Date Time</center></th>		
			<th><center>Browser </center></th>		 
        </tr>
      </thead>
      <tbody>
		<?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){ 
			$class = $i%2==0?'odd':'';
			$i++;
			$userD = $obj->selectData(TABLE_USER,"","user_status='Active' and u_login_id='".$data['user_id']."'",1);
			// pre($userD);
		?>
        <tr>         
			<td><center> <?=$data['user_id']; //$obj->getUserName($data['user_id']);?></center></td>    
			<td> <center><?=$userD['user_referrer'];?></center> </td>  
			<td> <center><?=$data['monl_ip'];?> </center></td>  
			<td><center> <?=$data['monl_date'];?></center> </td>  
			<td> <center><?=$data['monl_browser'];?></center> </td>  
			 
			 
			 
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
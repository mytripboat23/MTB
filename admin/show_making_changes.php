<?php include("../includes/connection.php");
adminSecure();
 
if($_REQUEST['search_monc_type']!='')
{
	//$extra .= " and monc_type = '".$obj->filter_alphabet($_REQUEST['search_monc_type'])."' ";
} 
 
$sql=$obj->selectData(TABLE_MONITORING_CHANGES,"","monc_type ='t'".$extra." order by monc_id desc",2);
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
        <li class="active">Making changes History Listing</li>
      </ul>
      
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Making changes History Management  </small> </h1>
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
			<th><center>Account ID</center></th>			 
			<th><center>Changes</center></th>			 
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
			<td> <center> <?=$data['user_id']; //$obj->getUserName($data['user_id']);?></center></td> 		 
			<td> <center>  <?=$data['monc_changes'];?> </center></td> 		  
			<td> <center><?=$data['monc_ip'];?> </center></td> 		 
			<td> <center> <?=$data['monc_date'];?></center> </td>  
			<td> <center><?=$data['monc_browser'];?></center> </td>  
			  
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
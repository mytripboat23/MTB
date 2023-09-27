<?php include("../includes/connection.php");
adminSecure();
$seeId= $obj->filter_mysql($obj->filter_numeric($_REQUEST['seeId']));

$sql=$obj->selectData(TABLE_SEND_ENTERNAL_EMAIL,"","see_status<>'D'".$extra." order by see_id desc",2);
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
        <li class="active">Send Email</li>
      </ul>
      <!-- /.breadcrumb -->
      
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Send Email Management <span class="pull-right"><a href="edit_send_email.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Send Email </a> </span> </small> </h1>
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
			  <div class="table-responsive">
				<table id="sample-table-1" class="table table-striped table-bordered table-hover">
				  <thead>
				  
					<tr>
						<th class="hidden-480">Email facility</th>	
						<th class="hidden-480">All/User</th>				 
						<th class="hidden-480">Status</th>
						 
					</tr>
				  </thead>
				  <tbody>
				   <?php
						$i=1;
						while($data=mysqli_fetch_assoc($res)){ //pre($data);
						$class = $i%2==0?'odd':'';
						$i++;
						$dataEF = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$data['ef_id']."'",1);	
						$userD = $obj->selectData(TABLE_USER,"","u_login_id ='".$data['user_id']."'",1);
						//pre($userD);	
				   ?>
					<tr>         
						<td><?php if($data['ef_id'] == 0) { ?> New Email Format <?php } else { ?> <?=$dataEF['ef_title'];?> <?php } ?></td>	
						<td><?php if($data['user_id'] == 0) { ?> All Member <?php } else { ?><?=$userD['user_first_name'].' '.$userD['user_last_name'];?> <?php } ?></td>			 
						<td><?php if($data['see_status'] =='P') { ?>Running <?php } else if($data['see_status'] =='S') { ?> Completed <?php } ?></td>
						 
					</tr>
				  <?php
					  }
				  ?>
					<tr>
					  <td class="center" colspan="3"><div class="pagination"><?=$pg_obj->pageingHTML;?></div></td>
					  
					</tr>
				  </tbody>
				</table>
			   </div>
			  </form>	
			  </div>
			  <!-- /.span --> 
			</div><!-- /.row -->
            <!-- /.row --> 
			
			<?php 
			
			$dataSEE = $obj->selectData(TABLE_SEND_ENTERNAL_EMAIL,"","see_id='".$seeId."'",1);
			$dataEF = $obj->selectData(TABLE_EMAIL_FACILITY,"","ef_id='".$dataSEE['ef_id']."'",1);
			
			
			  ?>
           
			 
		
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
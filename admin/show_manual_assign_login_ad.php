<?php include("../includes/connection.php");
adminSecure();
  
if($_REQUEST['action']=='yes' && $_REQUEST['oId']!='')
{
	$orderID = $obj->filter_numeric($_REQUEST['oId']);
	$packD = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","order_id='".$orderID."'",1);
	$lap_id = $packD['lap_id'];
	$ordData = $obj->selectData(TABLE_ORDER,"","order_id='".$orderID."' and order_status ='Active'",1);	
	//$wallRData = $obj->selectData(TABLE_USER_WALLET,"","order_id='".$orderID."' and wall_pstatus ='p' and wall_type ='r'",1);	
	//$wallLData = $obj->selectData(TABLE_USER_WALLET,"","order_id='".$orderID."' and wall_pstatus ='p' and wall_type ='lap'",1);	
	
	$ordArr['order_pstatus'] ='u';	 
	$obj->updateData(TABLE_ORDER,$ordArr,"order_id='".$orderID."'");
	
	//$rdSetArr['wall_pstatus'] = 'u';	 
	//$obj->updateData(TABLE_USER_WALLET,$rdSetArr,"wall_id='".$wallRData['wall_id']."'");
	
	//$logAdSet['wall_pstatus'] = 'u';	 
	//$obj->updateData(TABLE_USER_WALLET,$logAdSet,"wall_id='".$wallLData['wall_id']."'");
	
	$lapArr['order_id']    		= 0;	
	$lapArr['user_id']    		= 0;
	$lapArr['lap_pstatus'] 		= 'u';
	$lapArr['lap_assign'] 		= 'a';
	$lapArr['lap_u_admin_id'] 	= $_SESSION[ADMIN_SESSION_NAME]['admin_id'];	 
	$lapArr['lap_pstatus_date'] = '0000-00-00 00:00:00';	 
	$obj->updateData(TABLE_LOGIN_AD_PACKAGE,$lapArr,"lap_id='".$lap_id."'"); 
	 
	 
	$_SESSION['messageClass'] = "successClass";
	$obj->add_message("message","Removed successfully!");
	$obj->reDirect(urldecode($_REQUEST['return_url']));	
}
 
  
  
  
//echo $sql = $obj->selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od, ".TABLE_LOGIN_AD_PACKAGE." as lp","o.*,od.*,lp.lap_date","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.lap_id !='0' and lp.lap_id=od.lap_id and lp.lap_pstatus='p' and lp.lap_assign='m' and od.od_status='Active' order by od.lap_id desc",2);

$sql = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_pstatus='p' and lap_assign='r' and lap_status='Active'".$extra." order by lap_id desc",2);
$pg_obj=new pagingRecords();
$pg_obj->setPagingParam("g",5,20,1,1);
$getarr = $obj->filter_mysql_array($_GET);
unset($getarr['msg']);
$res=$pg_obj->runQueryPaging($sql,$pageno,$getarr);
$qr_str=$pg_obj->makeLnkParam($getarr,0);
$pageno = 1;
if($_REQUEST['pageno']!="")
{
	$pageno = $obj->filter_numeric($_REQUEST['pageno']);
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
        <li class="active">Login Ad Manual Assing</li>
      </ul>
    
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Login Ad Manual Assing Management <span class="pull-right"><a href="edit_manual_assign_login_ad.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Login Ad Manual Assing</a> </span> </small> </h1>
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
			<th>Login Ad</th>  
			<th>Order Price</th> 
			<th>Order Date</th>
			<th>Action</th>		 
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res))
			{ 			 
				$orderD = $obj->selectData(TABLE_ORDER,"","order_id='".$data['order_id']."' and order_pstatus='p'",1);			 
				$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$data['user_id']."' and user_status='Active'",1);
	   ?>
        <tr>		 
			<td>#<?=10000+$data['order_id'];?></td>
			<td><?=$userD['user_first_name']." ".$userD['user_last_name'];?><br>(<?=$userD['user_email']?>)</td> 
			<td><?=$data['lap_date'];?> - Login Ad</td>	
			<td><?=$orderD['order_tot_asimi'];?> Asimi</td>	
			<td><?=$orderD['order_created'];?></td> 
			<td>
			<?php 
			$CurDate = date('Y-m-d');
			if($data['lap_date'] > $CurDate) {
			?>
			<a href="javascript:void(0);" onClick="remove_login_ad('<?=$data['order_id'];?>','<?=urlencode($_SERVER['REQUEST_URI'])?>')" class="btn btn-danger btn-xs">Unassigned</a>
			<?php } else { ?>
			Unassigned
			<?php } ?>
			</td> 
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="6"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>	
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
 
<script>
function remove_login_ad(oId,rUrl)
{
	if(confirm("Are you sure you want to Remove Login Ad?"))
	{	
		window.location.href = 'show_manual_assign_login_ad.php?oId='+oId+'&action=yes&return_url='+rUrl;
	}
}
</script>

</body>
</html>
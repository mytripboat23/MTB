<?php include("../includes/connection.php");
adminSecure();
 
 
//$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))); 
//$extra .= " and lap_date  >= '".$fromDate."'";  
//$sql=$obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_pstatus = 'p'".$extra." order by lap_date asc",2);
$sql = $obj->selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od, ".TABLE_LOGIN_AD_PACKAGE." as lp","o.*,od.*,lp.lap_date","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.lap_id !='0' and lp.lap_id=od.lap_id and lp.lap_pstatus='p' and od.od_status='Active' order by od.lap_id desc",2);
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

 

if($_REQUEST['action']=='yes' && $_REQUEST['oId']!='')
{
	$orderID = $obj->filter_numeric($_REQUEST['oId']);
	$ordData = $obj->selectData(TABLE_ORDER,"order_id,user_id,order_burn","order_id='".$orderID."' and order_status ='Active'",1);	
	if($ordData['order_burn'] =='n') $ordArr['order_burn'] = 'y';
	else $ordArr['order_burn'] = 'n';	
	$obj->updateData(TABLE_ORDER,$ordArr,"order_id='".$orderID."'");
	$_SESSION['messageClass'] = "successClass";
	$obj->add_message("message","Updated successfully!");
	$obj->reDirect(urldecode($_REQUEST['return_url']));	
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
        <li class="active">Show Login Token Burn</li>
      </ul>
      
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Login Token Burn Management  <?php /* <span class="pull-right"><a href="edit_login_ad_package.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Login Ad Package</a> </span> */ ?> </small> </h1>
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
			<th>Asimi Price </th>  
			<th>USD Price </th>  
			<th>BTC </th>  
			<th>Affiliate</th>
			<th>Affiliate Commission</th>
			<th>Net Asimi Burn</th>	
			<th>Burned</th>		 
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){  //pre($data);
			$class = $i%2==0?'odd':'';
			$i++;
			//$lapD = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_id='".$data['lap_id']."' and lap_pstatus ='p'",1);
			$userD = $obj->selectData(TABLE_USER,"user_referrer","u_login_id='".$data['user_id']."' and user_status='Active'",1);	
			 
			$discount_persent = $data['order_disc_percent'];
			$tot_dollar_val = ($discount_persent / 100) * $data['order_total'];	
	   ?>
        <tr>		 
			<td><?=date('D, F d,Y', strtotime($data['lap_date']));?></td>
			<td><a href="view_member.php?uId=<?=$data['user_id'];?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View"><?=$obj->getUserName($data['user_id']);?></a></td>
			<td><?=number_format((float)($data['order_subtotal']-$data['order_discount']), 2, '.', ''); ?></td>
			<td><?=number_format((float)($data['order_total']-$tot_dollar_val), 2, '.', ''); ?></td>
			<td><?=$data['order_tot_btc'];?></td>
			
			<td><?php if(!empty($userD['user_referrer'])) { ?><a href="view_member.php?uId=<?=$userD['user_referrer'];?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View"><?=$obj->getUserName($userD['user_referrer']);?></a><?php } else { ?> <?=$obj->getUserName($userD['user_referrer']);?> <?php } ?></td>	
			
			<td><?=number_format((float)($data['od_sales_aff_token']), 2, '.', ''); ?> </td>		 
			<td><?=number_format((float)($data['od_admin_token']), 2, '.', ''); ?> </td>
			<td>
			
			<select name="order_burn" id="order_burn" onChange="asimi_burn('<?=$data['order_id'];?>','<?=urlencode($_SERVER['REQUEST_URI'])?>')">
				<option value="n" <?php if($data['order_burn']=='n'){?> selected="selected"<?php }?>>No</option>
				<option value="y" <?php if($data['order_burn']=='y'){?> selected="selected"<?php }?>>Yes</option>			
			</select>
			 
			</td>
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="9"><div class="pagination"><?=$pg_obj->pageingHTML;?></div></td>
		 
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
function asimi_burn(oId,rUrl)
{
	if(confirm("Are you sure you want to change the status?"))
	{	
		window.location.href = 'show_login_token_burn.php?oId='+oId+'&action=yes&return_url='+rUrl;
	}
}
</script>
  
</body>
</html>
<?php include("../includes/connection.php");
adminSecure();
 checkAdminPermission();
//exit;
 
 
if($_REQUEST['search_email'] !='' && $_REQUEST['search_ip'] !='' )
{
	$userLD = $obj->selectData(TABLE_USER_LOGIN,"u_login_user_email,u_login_id","u_login_user_email='".$obj->filter_mysql($_REQUEST['search_email'])."' and u_login_status='Active'",1);	 
	$extra .= " and user_id = '".$userLD['u_login_id']."' ";  
	$extra .= " and monl_ip = '".$obj->filter_mysql($_REQUEST['search_ip'])."' "; 
}
else if($_REQUEST['search_email'] !='')
{
	$userLD = $obj->selectData(TABLE_USER_LOGIN,"u_login_user_email,u_login_id","u_login_user_email='".$obj->filter_mysql($_REQUEST['search_email'])."' and u_login_status='Active'",1);	 
	$extra .= " and user_id = '".$userLD['u_login_id']."' ";  
}
else if($_REQUEST['search_ip'] !='')
{
	$extra .= " and monl_ip = '".$obj->filter_mysql($_REQUEST['search_ip'])."' "; 
}  
 	
$calDay = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-15,date("y")));

if($extra) $sql=$obj->selectData(TABLE_MONITORING_LOGINS,"monl_ip,user_id","monl_date > '".$calDay.' 00:00:00'."' ".$extra."","","","monl_ip"); 
 
 
 
if(isset($_POST['scReinstatus']) && isset($_POST['user_id']))
{
	$u_login_id= $obj->filter_numeric($_POST['user_id']);	 
	$userD = $obj->selectData(TABLE_USER,"","u_login_id='".$u_login_id."' and user_status='Active'",1);
	$userLD = $obj->selectData(TABLE_USER_LOGIN,"","u_login_id='".$u_login_id."' and u_login_status='Active'",1);
	$affId = $userD['user_referrer'];	 
	$user_suspend_comm_status = $obj->filter_alphabet($_POST['user_suspend_comm_status']);
	
	if($user_suspend_comm_status =='y' && $userLD['user_suspend_comm_status'] =='n')
	{
		if($affId)
		{
			$ded_balance = $obj->total_affiliate_income_generated($u_login_id);	 
			$wallRI['wall_asimi'] 			= $ded_balance; 
			$wallRI['user_id'] 			    = $affId;
			$wallRI['ref_id'] 			    = $u_login_id;		
			$wallRI['wall_pstatus'] 		= 'p';
			$wallRI['wall_type'] 			= 'cd';	 
			$wallRI['wall_created']		    = CURRENT_DATE_TIME;
			$wallRI['wall_modified']		= CURRENT_DATE_TIME;				
			$cur_wall_id = $obj->insertData(TABLE_USER_WALLET,$wallRI);			
		}
		$arrUL['user_suspend_comm_status']     = $user_suspend_comm_status;	
		$userSL = $obj->updateData(TABLE_USER_LOGIN,$arrUL,"u_login_id='".$u_login_id."'");	
		if($arrUL['user_suspend_comm_status']=='y')	$obj->add_message("message","Member has been suspended & commision canceled successfully!");
		else if($arrUL['user_suspend_comm_status']=='n' )$obj->add_message("message","Member suspension canceled & reinstated successfully!");		
	}
	else if($user_suspend_comm_status =='n' && $userLD['user_suspend_comm_status'] =='y')
	{
		if($affId)
		{
			$wdata = $obj->selectData(TABLE_USER_WALLET,"wall_asimi","wall_status='Active' and wall_type='cd' and wall_pstatus='p' and user_id='".$affId."' and ref_id='".$u_login_id."' order by wall_id desc",1);
			$wallRI['wall_asimi'] 			= $wdata['wall_asimi']; 
			$wallRI['user_id'] 			    = $affId;
			$wallRI['ref_id'] 			    = $u_login_id;		
			$wallRI['wall_pstatus'] 		= 'p';
			$wallRI['wall_type'] 			= 'cr';	 
			$wallRI['wall_created']		    = CURRENT_DATE_TIME;
			$wallRI['wall_modified']		= CURRENT_DATE_TIME;				
			$cur_wall_id = $obj->insertData(TABLE_USER_WALLET,$wallRI);	
		}
			$arrUL['user_suspend_comm_status']     = $user_suspend_comm_status;	
			$userSL = $obj->updateData(TABLE_USER_LOGIN,$arrUL,"u_login_id='".$u_login_id."'");	
			if($arrUL['user_suspend_comm_status']=='y')	$obj->add_message("message","Member has been suspended & commision canceled successfully!");
			else if($arrUL['user_suspend_comm_status']=='n' )$obj->add_message("message","Member suspension canceled & reinstated successfully!");
	}
	
	
	$_SESSION['messageClass'] = 'successClass';
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
        <li class="active">Dashboard</li>
      </ul>
      <!-- /.breadcrumb -->
      
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Manual Search  </small> </h1>
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
					<div class=" col-md-4">
							<input type="text" name="search_email" id="search_email" class="form-control" placeholder="example@gmail.com" value="<?=$_REQUEST['search_email']?>" />
					</div>	
					<div class=" col-md-4">
							<input type="text" name="search_ip" id="search_ip" class="form-control" placeholder="IP Address" value="<?=$_REQUEST['search_ip']?>" />
					</div>								
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
 
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>	  
        <tr>           
			<th>IP</th>
			<th>User Name</th>
			<th>Affiliate</th>
			<th>Action</th>
			 
        </tr>
      </thead>
      <tbody>
	    <?php
		 	$flag = 0;
			while($data=mysqli_fetch_assoc($sql)){   // pre($data);
			
			$sql2 = $obj->selectData(TABLE_MONITORING_LOGINS,"monl_ip,user_id","monl_ip = '".$data['monl_ip']."'","","","user_id");
			$user_email_m ='';
			$Ref_user_email_m ='';
			$rowspan = mysqli_num_rows($sql2);
			$i=0;
			while($data2=mysqli_fetch_assoc($sql2))
			{ 
			// $user_data2 = $obj->selectData(TABLE_USER,"user_email,user_referrer","u_login_id='".$data2['user_id']."'",1);
			$user_data2 = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","ul.u_login_id,u.user_email,u.user_referrer,ul.user_suspend_comm_status","ul.u_login_id=u.u_login_id and u.u_login_id='".$data2['user_id']."'",1); 					 
			 
	    ?>
        <tr> 		
			<?php if($i==0){?><td rowspan="<?=$rowspan;?>"> <?=$data['monl_ip'];?> </td><?php }?>
			<td><a href="view_member.php?uId=<?=$user_data2['u_login_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View"><?=$user_data2['user_email'];?></a></td>		
			<td><?php if($user_data2['user_referrer']){?><?=$obj->getUserEmailFromAll($user_data2['user_referrer'])?><?php }else{?>Admin<?php }?></td>	
			
			<td style="width:25%">  
				<?php if($_SESSION[ADMIN_SESSION_NAME]['admin_id']==1 || $_SESSION[ADMIN_SESSION_NAME]['admin_id']==2){?>
				<form action="" name="ususpendion" method="post">
				<input type="hidden" name="user_id" value="<?=$user_data2['u_login_id'];?>">
				<input type="hidden" name="user_suspend_comm_status" value="<?php if($user_data2['user_suspend_comm_status']=='y'){?>n<?php }else{?>y<?php }?>">
				<input type="submit" name="scReinstatus" value="<?php if($user_data2['user_suspend_comm_status']=='y'){?>Cancel suspension & reinstated<?php }else{?>Suspend & Cancel Commision<?php }?>" <?php if($user_data2['user_suspend_comm_status']=='y'){?> class="btn btn-danger"  <?php }else{?> class="btn btn-info" <?php }?>>
				</form>
				<?php }?>
			</td>		
        </tr>
      <?php
	  	$i++;
		$flag++;
	  	  }
			} 
	  ?>
	  <?php if($flag==0){?>
	   <tr> 			
			<td colspan="4" align="center"><strong>Please enter search criteria to get result!</strong></td>		
	
        </tr>
	  <?php }?>
         
		
      </tbody>
    </table>
   
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
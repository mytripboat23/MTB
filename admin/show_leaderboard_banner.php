<?php include("../includes/connection.php");
adminSecure();
  
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		$today = date("Y-m-d H:i:s");
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($obj->filter_numeric($chbxAr));	
			$deleteStatus = $obj->updateData(TABLE_LEADERBOARD_BANNER,array('lb_status'=>'Deleted','lb_sub_deleted'=>$today,'lb_sub_del'=>$_SESSION[ADMIN_SESSION_NAME]['admin_id'],'lb_del_ip'=>$ip),"lb_id='".$chbx."'");
		}
		$affectedRow = count($_POST['chkSelect']);//mysql_affected_rows();
		
		if($affectedRow > 1)
			$rows = 'Records';
		else
			$rows = 'Record';
			
		if($deleteStatus){
			$obj->add_message("message",$affectedRow.' '.$rows.' '.DELETE_SUCCESSFULL);
		}
	}
	$obj->reDirect($_SERVER['REQUEST_URI']);
}

 
if($_REQUEST['search_ban_url']!='')
{
	$extra .= " and lb_url like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_ban_url'])))."%' ";
}
if($_REQUEST['search_dest_url']!='')
{
	$extra .= " and lb_desti_url like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_dest_url'])))."%' ";
} 
if($_REQUEST['search_type']!='')
{
	if($_REQUEST['search_type']=='a') $extra .= " and lb_status = 'Active' ";
	else if($_REQUEST['search_type']=='i') $extra .= " and lb_status = 'Inactive'";	
	else if($_REQUEST['search_type']=='awi') $extra .= " and lb_status = 'Active' and lb_view > lb_view_ach";	
}


$sql=$obj->selectData(TABLE_LEADERBOARD_BANNER,""," lb_status <> 'Deleted' and lb_type='L' ".$extra." order by lb_id desc",2);
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
        <li class="active">Leaderboard Banner</li>
      </ul>
      
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Leaderboard Banner Management <span class="pull-right"><a href="edit_leaderboard_banner.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Leaderboard Banner</a> </span> </small> </h1>
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
							<input type="text" name="search_ban_url" id="search_ban_url" class="form-control" placeholder="Banner URL" value="<?=$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_ban_url'])));?>" />
					</div>	
					<div class=" col-md-3">
							<input type="text" name="search_dest_url" id="search_dest_url" class="form-control" placeholder="Destination URL" value="<?=$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_dest_url'])));?>" />
					</div>
					<div class=" col-md-2">
							<select name="search_type"  class="nav-search-input" id="search_type" >
								<option value="o" <?php if($_REQUEST['search_type']=='o' || $_REQUEST['search_type']==''){?> selected="selected"<?php }?>>All Campaign</option>
								<option value="a" <?php if($_REQUEST['search_type']=='a'){?> selected="selected"<?php }?>>Active Campaign</option>
								<option value="awi" <?php if($_REQUEST['search_type']=='awi'){?> selected="selected"<?php }?>>Active with Impression</option>
								<option value="i" <?php if($_REQUEST['search_type']=='i'){?> selected="selected"<?php }?>>Inactive Campaign</option>							
							</select>
					</div>		
						<div class="input-group col-md-1">							
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
  <div class="table-responsive">
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr>
			<th class="hidden-480">Campaign #</th>			 
			<th>Start Date</th>
			<th>Banner URL</th>
			<th>Destination URL</th>	
			<th>Type</th>	
			<th>End Date</th>	 
			<th>Click</th> 
			<th>Click Served</th>   
			<th>Views Served</th>
			<th class="hidden-480">Status</th>
			<th>Action</th> 
			<th class="center">Delete</th> 
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){ //pre($data);
			$class = $i%2==0?'odd':'';
			$i++;	
			 
			$tot_view_avl = $obj->get_leaderboard_banner_view($data['lb_id']);	 			
	   ?>
        <tr>         
			<td class="center">#L<?=$data['lb_virtual_id'];?></td>	
			<td><?=date('M d Y', strtotime($data['lb_created']));?></td>			 
			<td><?=wordwrap($data['lb_url'],40,'<br>',true);?></td>
			<td><?=wordwrap($data['lb_desti_url'],40,'<br>',true);?></td>		 
			<td class="center">
			<?php 
			if($data['lb_ad_type'] =='c') echo "Clicks";
			else if($data['lb_ad_type'] =='t') echo "Time";			
			?></td>	
			<?php   $totdays = $data['lb_st_days']-1;  ?>	
			<td class="center"><?php if($data['lb_start_date']!='0000-00-00') { ?><?=$data['lb_end_date'];?> <?php } ?></td>		 
			<td class="center"><?=$data['lb_click'];?></td>	
			<td class="center"><?=$data['lb_click_ach'];?></td>	
			<td class="center"><?=$tot_view_avl;?></td>	
			<td class="center"><?=$data['lb_status'];?></td>	
	        <td class="center"><a href="edit_leaderboard_banner.php?bId=<?=$data['lb_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Edit Banner Ads">Edit</a></td>		
			
			<td class="center"><label class="position-relative">
			<input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" myType="demo" value="<?=$data['lb_id'];?>" />
			<span class="lbl"></span> </label></td> 
			
			
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="11"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
		  <td class="center"><?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?></td> 
        </tr>
      </tbody>
    </table>
   </div>
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
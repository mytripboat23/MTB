<?php include("../includes/connection.php");
adminSecure();
   
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_PACKAGE_COMMENT,array('pc_status'=>'Deleted'),"pc_id='".$chbx."'");
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
/*   / delete data   */
if($_REQUEST['ts_id']!="")
{
	$extra .= "and (ts_id = '".$obj->filter_numeric($_REQUEST['ts_id'])."')"; 
}

$sql=$obj->selectData(TABLE_PACKAGE_COMMENT,"","pc_status<>'Deleted'".$extra." order by pc_id asc",2);
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
        <li class="active">Dashboard</li>
		<li class="active">Package</li>
		<li class="active">Package Comments</li>
      </ul>
      <!-- /.breadcrumb -->
      
      <?php /*?><div class="nav-search" id="nav-search">
        <form class="form-search" action="" method="get">
          <span class="input-icon">
          <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
          <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
        </form>
      </div><?php */?>
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Package Comment Listings <?php /*?><span class="pull-right"><a href="edit_tr_story.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Package Comments </a></span><?php */?> </small> </h1>
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
			<th>Package</th>		
			<th>User</th>
			<th>Comments</th>  		
			<th>Added </th> 			 	 	  
			<th> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> Last Update </th>
			<?php /*?><th>Action</th><?php */?>
			<th class="center">Delete</th>
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){ // pre($data);
			$class = $i%2==0?'odd':'';
			$i++;
	   ?>
        <tr> 
			<td><?=$obj->get_package_title($data['pck_id']);?></td>	
			<td><?=$obj->get_user_full_name($data['user_id']);?></td>
			<td><?=$obj->short_description_cw($data['pc_comm'],100);?><a href="javascript:void(0);" onClick="view_full_comments(<?=$data['pc_id'];?>)">View Full</a></td>
			
			<td><?=date(DATE_FORMAT, strtotime($data['pc_added']));?></td>
			<td><?=date(DATE_FORMAT, strtotime($data['pc_updated']));?></td>
			<?php /*?><td><a href="edit_package_comm.php?pcId=<?=$data['pc_id'];?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View">Edit</a></td>	<?php */?>
			<td class="center"><label class="position-relative">             
			<input type="checkbox"  name="chkSelect[]" class="ace" id="chkSelect1" myType="demo" value="<?=$data['pc_id'];?>" />
			<span class="lbl"></span> </label>
			</td>
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="6"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
		  <td class="center"><?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?></td>
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

<script language="javascript">
function validate_delete(chackboxname)
{
	if( $("[myType]").is(':checked'))
	{
		return confirm("Are you sure you want to delete?");
	}
	else
	{
		alert("Please select at least one record to delete");
	    return false;
	}
	
}

$(document).ready(function(){
$('input[name="master_select"]').bind('click', function(){
var status = $(this).is(':checked');
$('input[type="checkbox"]').attr('checked', status);
});

});
</script>
</body>
</html>
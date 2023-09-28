<?php include("../includes/connection.php");
adminSecure();

if($_REQUEST['noti_id']!='')
{
	$sqlN = $obj->selectData(TABLE_NOTIFICATION,"","noti_id='".$_REQUEST['noti_id']."'",1);
	if($sqlN['noti_status']=='Active') { $arrNU['noti_status'] = 'Inactive';}
	if($sqlN['noti_status']=='Inactive') { $arrNU['noti_status'] = 'Active';}
	$obj->updateData(TABLE_NOTIFICATION,$arrNU,"noti_id='".$_REQUEST['noti_id']."'");
	$_SESSION['messageClass'] = "successClass";
	$obj->add_message("message","Status Updated Successfully!");
	$obj->reDirect('show_notification.php');
	exit;
}
 
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_NOTIFICATION,array('noti_status'=>'Deleted'),"noti_id='".$chbx."'"); 
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

 
$sql=$obj->selectData(TABLE_NOTIFICATION,"","noti_status<>'Deleted'".$extra." order by noti_id desc",2);
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
        <li class="active"> Notifications</li>
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Notifications Management <span class="pull-right"><a href="edit_notification.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Notification</a></span></small> </h1>
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
    <table id="sample-table-1" class="table table-striped table-bordered table-hover show-flg">
      <thead>
	  
        <tr>          
          <th>Title</th>
          <th class="hidden-480">Description</th>
          <th> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> Last Updated </th>
          <th class="hidden-480">Enable/Disable</th>
          <th>Action</th>
		 <th class="center">Delete</th>
        </tr>
      </thead>
      <tbody>
		<?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){
			$class = $i%2==0?'odd':'';
			$i++;
		?>
        <tr>         
          <td><?=$data['noti_title'];?></td>
          <td><?=substr(strip_tags(html_entity_decode($data['noti_descr'])),0,100000);?></td>
          <td><?=date("M d, Y",strtotime($data['noti_modified']));?></td>
          <td><?php if($data['noti_status']=='Active'){?><a href="javascript:void(0);" onClick="change_status('<?=$data['noti_id'];?>')" class="btn btn-danger">Disable</a><?php }else{?><a href="javascript:void(0);" onClick="change_status('<?=$data['noti_id'];?>')" class="btn btn-success">Enable</a><?php }?></td>
          <td><a href="edit_notification.php?cId=<?=$data['noti_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Edit">Edit</a></td>			 
		  <td class="center"><label class="position-relative">
			<input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" value="<?=$data['noti_id'];?>" />
			<span class="lbl"></span> </label></td>	
	   </tr>
		<?php
		  }
		?>
        <tr>
          <td class="center" colspan="5"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
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
<script language="javascript" type="text/javascript">
function change_status(v)
{
	if(confirm('Are you sure you want to change the notification status?'))
	{
		window.location.href = 'show_notification.php?noti_id='+v;
	}
}
</script>
</body>
</html>
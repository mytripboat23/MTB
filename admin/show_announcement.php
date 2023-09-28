<?php include("../includes/connection.php");
adminSecure(); 

if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_ANNOUNCEMENT,array('ann_status'=>'Deleted'),"ann_id='".$chbx."'"); 
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


$sql=$obj->selectData(TABLE_ANNOUNCEMENT,"","ann_status<>'Deleted'".$extra." order by ann_id desc",2);
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
        <li class="active">News Announcements</li>
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>News Announcement Management <span class="pull-right"><a href="edit_announcement.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Announcement</a></span></small> </h1>
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
          <th>Title</th>
          <th class="hidden-480">Description</th>
          <th> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> Last Updated </th>
          <th class="hidden-480">Status</th>
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
			<td><?=$data['ann_title'];?></td>
			<td><?=substr(strip_tags(html_entity_decode($data['ann_descr'])),0,100);?>...</td>
			<td><?=date("M d, Y",strtotime($data['ann_modified']));?></td>
			<td><?=$data['ann_status'];?></td>
			<td><a href="edit_announcement.php?cId=<?=$data['ann_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Edit">Edit</a></td>
			<td class="center"><label class="position-relative">
			<input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" value="<?=$data['ann_id'];?>" />
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
</body>
</html>
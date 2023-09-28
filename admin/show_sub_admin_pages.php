<?php include("../includes/connection.php");
 adminSecure();
 
/* 
$page_name = 'show_referred_by.php';
$dataP = $obj->selectData(TABLE_ALL_PAGES,"","page_status='Active' and (page_name like '%".$page_name."%')",1);
$pageId = $dataP['page_id'];
$dataxcP = $obj->selectData(TABLE_SUB_ADMIN_PAGES,"","sap_status='Active' and FIND_IN_SET('".$pageId."', page_id)",1);
pre($dataxcP);
exit;
 
 */
  
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_SUB_ADMIN_PAGES,array('sap_status'=>'Deleted'),"sap_id='".$chbx."'"); 
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
 
 
//$sql=$obj->selectData(TABLE_SUB_ADMIN_PAGES,"","sap_status<>'Deleted' ".$extra,2," sap_id desc");
$sql= $obj->selectData(TABLE_SUB_ADMIN_PAGES." as sa, ".TABLE_ADMIN." as a","","sa.admin_id=a.admin_id and sa.sap_status !='Deleted' and a.admin_status !='Deleted'","2","sa.sap_id desc","a.admin_id"); 
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

//$afsdf = $obj->selectData(TABLE_SUB_ADMIN_PAGES." as sa, ".TABLE_ADMIN." as a","","sa.admin_id=a.admin_id and sa.sap_status='Active'","2","","a.admin_id");



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
        <li class="active">Sub Admin Pages </li>
      </ul>
      <!-- /.breadcrumb -->
       
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Sub Admin Pages Management <span class="pull-right"><a href="edit_sub_admin_pages.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Sub Admin Page</a></span></small> </h1>
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
          <th style="width:70%">Page</th>           
         
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
			<td><?=$obj->getAdminName($data['admin_id']);?></td>	 
			<td><?=$obj->getAllPagesName($data['page_id']);?></td>			 
	 
			<td><?=$data['sap_status'];?></td>
			<td><a href="edit_sub_admin_pages.php?cId=<?=$data['sap_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Edit">Edit</a></td>
			<td class="center"><label class="position-relative">
			<input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" myType="demo" value="<?=$data['sap_id'];?>"/>
			<span class="lbl"></span> </label></td> 
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="4"><div class="pagination"><?=$pg_obj->pageingHTML;?></div></td>
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
<?php include("../includes/connection.php");
adminSecure();
 $dataIpAcc = $obj->selectData(TABLE_IP_ACCESS,"","ipa_ip='".$ip."'",1);
  
if(isset($_POST['btnDelete']))
{
	 
	$obj->deleteData(TABLE_IP_ACCESS,$_POST,"ipa_id='".$dataIpAcc['ipa_id']."'");
	
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_IP_RESTRICT,array('ipr_status'=>'Deleted'),"ipr_id='".$chbx."'");
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

if($_REQUEST['ipr_status']!='' && $_REQUEST['ipr_status'] =='Active' && $_REQUEST['uId'] !='')
{
	$ip_ristrict = $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['uId']));
	$dataIp = $obj->selectData(TABLE_IP_RESTRICT,"","ipr_id='".$ip_ristrict."'",1);

	if($dataIp['ipr_status'] =='Active')
	{
		$obj->deleteData(TABLE_IP_ACCESS,$_POST,"ipa_id='".$dataIpAcc['ipa_id']."'");
		
		$obj->updateData(TABLE_IP_RESTRICT,array('ipr_status'=>'Inactive'),"ipr_id='".$ip_ristrict."'");
	
	} else if($dataIp['ipr_status'] =='Inactive'){
		$obj->updateData(TABLE_IP_RESTRICT,array('ipr_status'=>'Active'),"ipr_id='".$ip_ristrict."'");
	}
}  


$sql=$obj->selectData(TABLE_IP_RESTRICT,"","ipr_status<>'Deleted'",2);
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
        <li class="active">IP Restrict</li>
      </ul>
      <!-- /.breadcrumb -->
      
     <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> IP Restrict Management <span class="pull-right"><a href="edit_ip_restrict.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add IP Restrict</a></span></small> </h1>
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
          <th>IP Address</th>		  
          
          <th>Action</th>
		  <th class="center"> Delete </th>
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
			<td><?=$data['ipr_ip'];?></td>		        
			 
			<td>		
			<button type="button" onclick="ajax_change_status(<?=$data['ipr_id'];?>)" class="tooltip-info btn btn-xs<?php if($data['ipr_status']=='Active'){?> btn-success<? }else{?> btn-default<? }?>"> <?php if($data['ipr_status']=='Active'){?>Deactive<? }else{?>Active<? }?></button>
			</td>		   
			<td class="center"><label class="position-relative">
			<input type="checkbox"  name="chkSelect[]" class="ace" id="chkSelect1" myType="demo" value="<?=$data['ipr_id'];?>" />
			<span class="lbl"></span> </label></td>
        </tr>
      <?
		  }
	  ?>
        <tr>
          <td class="center" colspan="2"><div class="pagination"><? echo $pg_obj->pageingHTML;?></div></td>
		  <td class="center"><? if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><? }?></td>
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
<script type="text/javascript" language="javascript">
	
	function ajax_change_status(ipr_id)
	{  
		 var ipr_status = 'Active';
			window.location.href='<?=FURL;?>admin/show_ip_restrict.php?uId='+ipr_id+'&ipr_status='+ipr_status;
		 
	}
	
 
</script> 

</body>
</html>
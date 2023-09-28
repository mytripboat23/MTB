<?php include("../includes/connection.php");
adminSecure();

if($_REQUEST['action']=='delete' && $_REQUEST['did']!='')
{
	$dcrID  = $obj->filter_numeric($_REQUEST['did']);
	$pagen  = $obj->filter_numeric($_REQUEST['pagen']);
	$sqlD = $obj->selectData(TABLE_DAILY_CSV_REPORT,"","dcr_status='Active' and dcr_id='".$dcrID."'",1);
	if($sqlD['dcr_name']!='')
	{
		$arrDU['dcr_status'] = 'Deleted';
		$obj->updateData(TABLE_DAILY_CSV_REPORT,$arrDU,"dcr_id='".$dcrID."'");
		@unlink("../dreport/".$sqlD['dcr_name']);
		$obj->add_message("message","CSV file deleted successfully!");
		$_SESSION['messageClass'] = 'successClass';
		
		$obj->reDirect("get_daily_reports.php?pageno=".$pagen);
	}	
}

$sql=$obj->selectData(TABLE_DAILY_CSV_REPORT,"","dcr_status='Active' ".$extra." order by dcr_id desc",2);
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
      </ul>
      <!-- /.breadcrumb -->
      
     
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Daily Report Listings </small> </h1>
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
			<th>Date</th>		 
			<th>File</th>			
			<th><i class="fa fa-download" aria-hidden="true"></i>  Download</th>	
			<th><i class="fa fa-download" aria-hidden="true"></i>  Action</th>		
			 
			 	 
		</tr>
      </thead>
       <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)) {
			$class = $i%2==0?'odd':'';
			$i++;		
	   ?>
        <tr> 
			<td><?=date('M d Y',strtotime($data['dcr_date']));?></td>
			<td><?=$data['dcr_name'];?></td>						
			<td><a class="btn btn-success btn-xs" href="../dreport/<?=$data['dcr_name']?>" target="_blank"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
			<td><a class="btn btn-success btn-xs" href="javascript:void(0);" onClick="del_dcr('<?=$data['dcr_id']?>','<?=$pageno;?>')"> <i class="fa fa-trash" aria-hidden="true"></i></a></td>
		   
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="4"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
		  
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

function del_dcr(dId,pagen)
{
	if(confirm("Are you sure you want to delete the file permanantly?"))
	{
		window.location.href =  "get_daily_reports.php?did="+dId+"&action=delete&pagen="+pagen;
	}
}
</script>
</body>
</html>
<?php include("../includes/connection.php");
adminSecure();
   
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_PACKAGE,array('pck_status'=>'Deleted'),"pck_id='".$chbx."'");
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
if($_REQUEST['pck_title']!="")
{
	$extra .= "and (pck_title like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['pck_title'])))."%')"; 
}

$sql=$obj->selectData(TABLE_PACKAGE,"","pck_status<>'Deleted'".$extra." order by pck_id desc",2);
$pg_obj=new pagingRecords();
//$pg_obj->setPagingParam("g",5,20,1,1);
$pg_obj->setPagingParam("g",5,5000,1,1);
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Tour Package Listings <?php /*?> <span class="pull-right"><a href="edit_package.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Tour Package </a></span> <?php */?></small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
<!--            <div class="hr hr32 hr-dotted"></div>-->
            <div class="row">
  <div class="col-xs-12">
	  <div id="example_length" style="margin-bottom: 16px;"></div>
  <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
	  
	  
	  
	 
	  
	  
	  
	  
<!--    <table id="sample-table-1" class="table table-striped table-bordered table-hover">-->
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr> 
			<th>Package Title</th>	
			<th>User</th>	
			<th>Destination</th>	 
			<th>Start From</th>  		
			<th>End At</th> 	  
			<th>Price</th> 
			<th>Capapcity</th> 
			<th>Comments</th> 
			<th>Added </th> 			 	 	  
			<th> Last Update </th>
			<th>Action</th>
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
			<td><?=$data['pck_title'];?></td>	
			<td><?=$obj->get_user_full_name($data['user_id']);?></td>
			<td><?=$data['pck_dest'];?></td>
			<td><?=date(DATE_FORMAT,strtotime($data['pck_start_dt']));?> <?=$data['pck_start_tm'];?></td>
			<td><?=date(DATE_FORMAT,strtotime($data['pck_end_dt']));?></td>
			<td><?=$obj->getCurrency($data['pck_curr']);?>&nbsp;<?=$data['pck_price'];?><br>
			
			<?php if($data['pck_foreign_price']>0){?> <?=$obj->getCurrency($data['pck_foreign_curr']);?> <?=$data['pck_foreign_price'];?><?php }?>
			</td>	
			<td><?=$data['pck_capacity'];?></td>	
			<td><a href="show_package_comm.php?pck_id=<?=$data['pck_id'];?>">View (<?=$obj->get_total_package_comments($data['pck_id']);?>)</a></td>
			<td><?=date(DATE_FORMAT, strtotime($data['pck_added']));?></td>
			<td><?=date(DATE_FORMAT, strtotime($data['pck_updated']));?></td>
			<td><a href="edit_package.php?uId=<?=$data['pck_id'];?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View">Edit</a></td>
			<td class="center"><label class="position-relative">             
			<input type="checkbox"  name="chkSelect[]" class="ace" id="chkSelect1" myType="demo" value="<?=$data['pck_id'];?>" />
			<span class="lbl"></span> </label>
			</td>
        </tr>
      <?php
		  }
	  ?>
        </tbody>
  </table>
	 <?php /*?>   <tables class="table table-striped table-bordered ">
	  <tr>
          <td class="center" ><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
		 <td class="center"><?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?></td>
        </tr>
		
    </table><?php */?>
		
	  <?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?>
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
	
	
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />  
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
	$(document).ready(function() {
    $('.custom_select').select2();
});
    $('#sample-table-1').DataTable({
		
		"order": [[8, 'desc']],
        initComplete: function () {
            this.api()
                //.columns()
			//.columns([1,3,4])
			.columns([1])
                .every(function (d) {
                    var column = this;
				    var theadname = $("#sample-table-1 th").eq([d]).text(); 

                    var select = $('<select class="form-select custom_select"><option value=""> All ' + theadname + "</option></select>")
                        .appendTo($('#sample-table-1_length'))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
 
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
 
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                });
        },
    });	
</script>
</body>
</html>
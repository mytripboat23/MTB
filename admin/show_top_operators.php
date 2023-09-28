<?php include("../includes/connection.php");
adminSecure();
   
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_USER,array('user_top'=>'n'),"user_id='".$chbx."'");
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

if(isset($_POST['add_top_operator']) && $_POST['add_top_operator']!='')
{
	$_SESSION['messageClass'] = "errorClass";
	if(trim($_POST['search_opt_hid'])=="") {$obj->add_message("message","Please select a proper Operator");}
	if($obj->get_message("message")=="")
	{
		$topP = array();
		$topP['user_top'] = 'y';
		$obj->updateData(TABLE_USER,$topP,"user_id='".$_POST['search_opt_hid']."'");	
		
		$_SESSION['messageClass'] = "successClass";
		$obj->add_message("message","Top operator added sucecssfully");
		$obj->reDirect($_SERVER['REQUEST_URI']);
	}				
				
}


if($_REQUEST['pck_title']!="")
{
	$extra .= " and (pck_title like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['pck_title'])))."%')"; 
}

$sql=$obj->selectData(TABLE_USER,"","user_status<>'Deleted' and user_top='y' ".$extra." order by user_id desc",2);
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Top Operator Listings </small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			<div class="email_searchBox clearfix">
              <div class="row">
                <div class="col-md-12">
                  <form action="" method="post" name="topPackage" id="topPackage">
                    <div id="custom-search-input">
                      <div class=" col-md-3">
                        <input type="text" name="search_operator" id="search_operator" class="form-control" placeholder="Operator Name" value="<?=$_REQUEST['search_operator']?>" />
						<div id="suggesstion-box"></div>
                      </div>
                      
                      <div class="input-group col-md-4">
                       <input type="hidden" name="search_opt_hid" id="search_opt_hid" class="form-control"  value="<?=$_REQUEST['search_opt_hid']?>" />
                        <span class="input-group-btn">
                        <input class="btn btn-info" type="submit" name="add_top_operator" value="Add Top Operator">
                        </span> </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
  <div class="col-xs-12">
  <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr> 	
			<th>Full Name</th>	 
			<th>Display Name</th>  		
			<th>Phone</th> 	  
			<th>Email ID</th> 
			<th>Added </th> 			 	 	  
			<th> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> Last Update </th>			
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
			<td><?=$data['user_full_name'];?></td>
			<td><?=$data['user_display_name'];?></td>
			<td><?=$data['user_phone'];?></td>
			<td><?=$data['user_email'];?></td>	
			<td><?=date(DATE_FORMAT, strtotime($data['user_created']));?></td>
			<td><?=date(DATE_FORMAT, strtotime($data['user_modified']));?></td>
			<td class="center"><label class="position-relative">
                          <input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" myType="demo" value="<?=$data['user_id'];?>" />
                          <span class="lbl"></span> </label></td>
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


$(document).ready(function() {
	$("#search_operator").keyup(function() {
		$.ajax({
			type: "POST",
			url: "ajax_script/search_operators.php",
			data: 'keyword=' + $(this).val(),
			beforeSend: function() {
				$("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data) {
				$("#suggesstion-box").show();
				$("#suggesstion-box").html('');
				$("#suggesstion-box").html(data);
				$("#search-box").css("background", "#FFF");
			}
		});
	});
});
//To select a country name
function selectCountry(val,id) {
	$("#search_operator").val(val);
	$("#search_opt_hid").val(id);
	$("#suggesstion-box").hide();
}
</script>
</body>
</html>
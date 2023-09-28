<?php include("../includes/connection.php");
adminSecure();
 checkAdminPermission();
//exit;

$init_q_string = "";
if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!='') $init_q_string = $_SERVER['REQUEST_URI']."&";
else $init_q_string = $_SERVER['REQUEST_URI'].'?';



if(isset($_REQUEST['saction']) && $_REQUEST['saction']!='' && isset($_REQUEST['ulogId']) && $_REQUEST['ulogId']!='')
{
	$u_login_id = $_REQUEST['ulogId'];
	if($_REQUEST['saction']=='suspend') $sus_status = 'y';
	elseif($_REQUEST['saction']=='rsuspend') $sus_status = 'n';
	
	$suspendAction = $obj->updateData(TABLE_USER_LOGIN,array('u_suspend_status'=>$sus_status),"u_login_id='".$u_login_id."'");
	
	if($sus_status=='y') $obj->add_message("message","Selected member suspended successfully.");
	elseif($sus_status=='y') $obj->add_message("message","Selected member's suspension removed successfully.");
	$obj->reDirect($_SESSION['cur_url']);
}

$current_url = FURL.$init_q_string;
$_SESSION['cur_url'] = $current_url;

if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_USER_LOGIN,array('u_login_status'=>'Deleted'),"u_login_id='".$chbx."'");
			$deleteStatus = $obj->updateData(TABLE_USER,array('user_status'=>'Deleted'),"u_login_id='".$chbx."'");
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


$extra = "";
$extraS = "";

 // pre($_REQUEST);
if($_REQUEST['search_start_date'])
{
	$search_start_date 	= date('Y-m-d', strtotime($_REQUEST['search_start_date']));  
	$search_end_date 	= date('Y-m-d', strtotime($_REQUEST['search_end_date']));  
	$extra 				.= " and user_created >= '".$search_start_date."' and user_created <= '".$search_end_date."'"; 	
}

	if($_REQUEST['search_status'])
	{
		$search_status =  $_REQUEST['search_status'];
		$extra 	 .= " and ul.u_login_status = '".$search_status."'"; 	
	} else $extra .= " and ul.u_login_status<>'Deleted'";


	if($_REQUEST['search_email']!='')
	{
		$extra .= " and ul.u_login_user_email Like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_email'])))."%' ";
	}

	if($_REQUEST['search_wallet']!='')
	{
		$extra .= " and u.user_wallet_address = '".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_wallet'])))."' ";
	}

	if($_REQUEST['search_aff_username'])
	{	
		$sqlS=$obj->selectData(TABLE_BECOME_AFFILIATE,"user_id","baff_username='".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_aff_username'])))."' and baff_status<>'Deleted'",1);
		$extraS = " and u_login_id ='".$sqlS['user_id']."'";
	}

	if($_REQUEST['search_profile'])
	{	
		
		$extraS = " and (user_first_name like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_profile'])))."%' or user_last_name like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_profile'])))."%')";
	}

if($extraS)
{
	$userIds = "";
	$sqlU = $obj->selectData(TABLE_USER,"","user_status<>'Deleted' ".$extraS);
	while($resU = mysqli_fetch_array($sqlU))  
	{
		$userIds .= $resU['u_login_id'].",";
	}
	$userIds = rtrim($userIds,",");
	if($userIds=="")  $userIds = 0;
	$extra .= " and ul.u_login_id in (".$userIds.")"; 
}

if($_REQUEST['limit']=='') $limit = 2500;
else $limit = $_REQUEST['limit'];
$sql=$obj->selectData(TABLE_USER_LOGIN." ul, ".TABLE_USER." u","ul.u_login_user_email,ul.u_login_status,ul.u_login_id,ul.u_suspend_status","ul.u_login_id=u.u_login_id ".$extra,2," ul.u_login_id desc");  
$pg_obj=new pagingRecords();
$pg_obj->setPagingParam("g",5,$limit,1,1);
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
      <?php /* 
      <div class="nav-search" id="nav-search">
        <form class="form-search" action="" method="get">
          <span class="input-icon">
          <input type="text" name="searchText" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" value="<?=$_REQUEST['searchText'];?>" />
		  
          <i class="ace-icon fa fa-search nav-search-icon"></i> </span>
		  <input type="submit"  autocomplete="off" name="action" value="Search" />
        </form>
      </div> */ ?>
      <!-- /.nav-search -->
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Members Listings
            <?php /*?><span class="pull-right"><a href="edit_member.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Member</a></span><?php */?>
            </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
          <?php /*?>  <div class="email_searchBox clearfix">
              <div class="row">
                <div class="col-md-12">
                  <form action="" method="get">
                    <div id="custom-search-input">
                      <div class=" col-md-4">
                        <input type="text" name="search_email" id="search_email" class="form-control" placeholder="example@gmail.com" value="<?=$_REQUEST['search_email']?>" />
                      </div>
                      
                      <div class="input-group col-md-4">
                       
                        <span class="input-group-btn">
                        <button class="btn btn-info" type="submit">Search</button>
                        </span> </div>
                    </div>
                  </form>
                </div>
              </div>
            </div><?php */?>
<!--            <div class="hr hr32 hr-dotted"></div>-->
            <div class="row">
              <div class="col-xs-12">
                <div class="row">
                 <?php /*?>   <div class="col-md-6"> 
				  
				  <form action="" method="get">
                    <div id="custom-search-input">
					
                    <div class="col-md-3">
                        <input type="text" name="search_start_date" id="search_start_date" class="form-control" placeholder="From Date" value="<?=$_REQUEST['search_start_date'];?>" /> 
                      </div>
					  
                      <div class="col-md-3">
                        <input type="text" name="search_end_date" id="search_end_date" class="form-control" placeholder="End Date" value="<?=$_REQUEST['search_end_date'];?>" />
                      </div>
					  
                      <div class="col-md-3"> 
						<select name="search_status" id="search_status" class="form-control"> 
							<option value="Registered" <?=$_REQUEST['search_status']=="Registered"?'selected="selected"':'';?>>Registered</option>
							<option value="Active"  <?=$_REQUEST['search_status']=="Active"?'selected="selected"':'';?>>Active</option>
							<option value="Inactive"  <?=$_REQUEST['search_status']=="Inactive"?'selected="selected"':'';?>>Inactive</option> 
						</select>
                      </div>
					  
						<div class="col-md-2"> 
							<span class="input-group-btn"><button class="btn btn-info" type="submit">Search</button> </span> 
						</div>
					
						<div class="col-md-1"> 
						<?php if($_REQUEST['search_start_date'] !='' || $_REQUEST['search_status'] !='') { ?> 
						<a class="btn btn-info" href="export_selected_customer.php?sdate=<?=$_REQUEST['search_start_date'];?>&edate=<?=$_REQUEST['search_end_date'];?>&mstatus=<?=$_REQUEST['search_status'];?>" target="_blank">Export </a>
						<?php } ?>
						</div>
                    </div>
                  </form>
				  
				  
				  </div>
				  <?php */?>
                
				  
				  
                  <?php /*?><div class="col-md-2"> <a class="btn btn-info pull-right" href="show_common_wallet.php" target="_blank">Common Wallet Address</a></div>
                  <div class="col-md-2"> <a class="btn btn-info pull-right" href="export_sales_affiliates.php" target="_blank">Export affiliates as csv</a></div>
                  <div class="col-md-2"> <a class="btn btn-info pull-right" href="export_customer.php" target="_blank">Export customers as csv</a></div><?php */?>
                </div>
				
				
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Package</th>
						<th>Story</th>
                        <th>Reg. Date</th>
                       <?php /*?> <th> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i> Last Update </th><?php */?>
                        <th >Status</th>
                        <th>Action</th>
						<th class="center">Suspend</th>
                        <th class="center"></th>
                      </tr>
                    </thead>
                    <tbody>
				<?php
					$i=1;
					while($data=mysqli_fetch_assoc($res)){ //  pre($data);
					$class = $i%2==0?'odd':'';
					$i++;			
					$user_data = $obj->selectData(TABLE_USER,"","u_login_id='".$data['u_login_id']."' and user_status<>'Deleted'",1);			
				?>
                      <tr>
                        <td><?=$user_data['user_full_name'];?></td>
                        <td><?=$data['u_login_user_email'];?></td>
                        <td><?=$user_data['user_phone'];?></td>
                        <td><?php echo $obj->total_package_posted_by_user($data['u_login_id']);?></td>
						 <td><?php echo $obj->total_story_posted_by_user($data['u_login_id']);?></td>
                        <td><?=date("M d,Y",strtotime($user_data['user_created']))?></td>
                      <?php /*?>  <td><?=date("M d,Y",strtotime($user_data['user_modified']))?></td><?php */?>
						<td>
						<?php 
							if($data['u_suspend_status'] =='y' || $data['user_suspend_comm_status'] =='y') echo "Suspended";
							else echo $data['u_login_status'];	
						?>
						</td>
                        <td><a href="view_member.php?uId=<?=$data['u_login_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View">
							
							<i class="fa fa-eye" aria-hidden="true"></i>

							</a> || <a href="change_member_password.php?uId=<?=$data['u_login_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="View"><i class="fa fa-key" aria-hidden="true"></i>
 </a></td>
						<td class="center">
							<?php if($data['u_suspend_status']=='n'){?>
							<a href="javascript:void(0)" onClick="make_suspend('<?=$data['u_login_id']?>')"> Make Suspend</a>
							<?php }else{?>
							<a href="javascript:void(0)" onClick="remove_suspend('<?=$data['u_login_id']?>')"> Remove Suspension</a>
							<?php }?>
						  </td>
						  
                        <td class="center"><label class="position-relative">
                          <input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" myType="demo" value="<?=$data['u_login_id'];?>" />
                          <span class="lbl"></span> </label></td>
                      </tr>
				<?php
					} 
				?>
						   </tbody>
                  </table>
						
					<?php /*?>	<table>
							<tbody>
                      <tr>
                        <td class="center" colspan="9"><div class="pagination"><?=$pg_obj->pageingHTML;?><?=$pg_obj->limitDropDown("25,50,75,100");?></div></td>
                        <td class="center">
							<?php if($_SESSION['travel_admin']['admin_type'] == 'm') { ?><?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?><?php }?>
						  </td>
                      </tr>
                    </tbody>
                  </table><?php */?>
				  	<?php if($_SESSION['travel_admin']['admin_type'] == 'm') { ?><?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?><?php }?>
                </form>
              </div>
              <!-- /.span -->
            </div>
            <!-- /.row -->
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
<script>  
$(document).ready(function(){
  $("#search_start_date").datepicker({ 
	format:'Y-m-d',
    onSelect: function (selected) {
	 
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 0);
 $("#search_edate").datepicker("option", "minDate", dt);
}                                 
});
  $("#search_end_date").datepicker({
   format:'Y-m-d',
    onSelect: function (selected) {
	 
      var dt1 = new Date(selected);
      dt1.setDate(dt1.getDate() - 1);
      $("#search_start_date").datepicker("option", "maxDate", dt1);
    }
  });
});

</script>

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

function make_suspend(u_login_id)
{
	if(confirm('Are you sure you want to suspend the selected user?'))
	{
		window.location.href = '<?=$current_url?>ulogId='+u_login_id+'&saction=suspend';
	}
}
function remove_suspend(u_login_id)
{
	if(confirm('Are you sure you want to remove suspend the selected user?'))
	{
		window.location.href = '<?=$current_url?>ulogId='+u_login_id+'&saction=rsuspend';
	}
}
</script>

	
	
	
	
	
	
	
	
	
	
			<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
  
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#sample-table-1').DataTable({
		
		"order": [[ 5, "desc" ]] ,
		
        initComplete: function () {
            this.api()
                //.columns()
			//.columns([1,3,4])
			.columns([0,6])
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
	
	
	
$(document).ready(function() {
    $('.custom_select').select2();
});
</script>

	
</body>
</html>

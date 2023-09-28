<?php include("../includes/connection.php");
adminSecure();
 checkAdminPermission();
//exit;

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

if(isset($_REQUEST['action']) && $_REQUEST['action']=='verification')
{
	$dataU = array();
	if(isset($_REQUEST['type']) && $_REQUEST['type']!='' && isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
	{ 
		if($_REQUEST['type']=='v')	$dataU['user_profile_vf'] = 'y';
		if($_REQUEST['type']=='u')	$dataU['user_profile_vf'] = 'n';
		
		$obj->updateData(TABLE_USER,$dataU,"user_id='".$_REQUEST['user_id']."'");
		
		$_SESSION['messageClass'] = "successClass";
		$obj->add_message("message","User verification process updated sucecssfully");
	}
	else
	{
		$_SESSION['messageClass'] = "errorClass";
		$obj->add_message("message","Invalid Request");
	}
	$obj->reDirect(urldecode($_REQUEST['reDirect']));
}


$extra = "";
$extraS = "";


	if($_REQUEST['search_status'])
	{
		$search_status =  $_REQUEST['search_status'];
		$extra 	 .= " and ul.u_login_status = '".$search_status."'"; 	
	} else $extra .= " and ul.u_login_status<>'Deleted'";


	if($_REQUEST['search_email']!='')
	{
		$extra .= " and ul.u_login_user_email Like '%".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_email'])))."%' ";
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

if($_REQUEST['limit']=='') $limit = 25;
else $limit = $_REQUEST['limit'];
$sql=$obj->selectData(TABLE_USER_LOGIN." ul, ".TABLE_USER." u","ul.u_login_user_email,ul.u_login_status,ul.u_login_id,ul.u_suspend_status,u.user_profile_vf,u.user_id","ul.u_login_id=u.u_login_id ".$extra,2," ul.u_login_id desc");  
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
            <div class="email_searchBox clearfix">
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
            </div>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                
				
				
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th class="hidden-480">ID Number</th>
                        <th>ID Photo</th>
						<th>Self Photo</th>
                        <th class="hidden-480">Status</th>
                        <th>Action</th>
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
                        <td><?=$data['user_id_no'];?></td>
                        <td><?php if($data['user_id_photo']!=""){?><img src="../<?=ID?><?=$data['user_id_photo']?>" width="200"><?php }?></td>
						<td><?php if($data['user_self_photo']!=""){?><img src="../<?=AVATAR?><?=$data['user_self_photo']?>" width="200"><?php }?></td>
						<td>
						<?php 
							if($data['u_suspend_status'] =='y' || $data['user_suspend_comm_status'] =='y') echo "Suspended";
							else echo $data['u_login_status'];	
						?>
						</td>
                        <td>
						<?php if($data['user_profile_vf']=='n'){?>
						<a href="javascript:void(0);" onClick="change_verification('v','<?=$data['user_id'];?>');" class="tooltip-info" data-rel="tooltip" title="View">Verify</a>
						<?php }else{?>
						<a href="javascript:void(0);" onClick="change_verification('u','<?=$data['user_id'];?>');" class="tooltip-info" data-rel="tooltip" title="View">Unverify</a>
						<?php }?>
						</td>
                      </tr>
				<?php
					} 
				?>
                      <tr>
                        <td class="center" colspan="7"><div class="pagination"><?=$pg_obj->pageingHTML;?><?=$pg_obj->limitDropDown("25,50,75,100");?></div></td>
                      </tr>
                    </tbody>
                  </table>
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

function change_verification(type,user_id)
{
	 var cur_url = '<?=urlencode($_SERVER['REQUEST_URI'])?>';
	if(type=='v') var action ='verify';
	else var action ='unverify';
	if(confirm('Are you sure you want to '+action+' the selected user?'))
	{
		window.location.href = 'show_member_identity.php?action=verification&type='+type+'&user_id='+user_id+'&reDirect='+cur_url;
	}
}
</script>
</body>
</html>

<?php include("../includes/connection.php");
adminSecure();

if($_REQUEST['search_date']!="")
{ 	 
	$extra .= "and ipa_date='".$obj->filter_mysql($_REQUEST['search_date'])."'"; 
}
if($_REQUEST['search_ip']!="")
{
	$extra .= "and ipa_ip ='".$obj->filter_mysql($_REQUEST['search_ip'])."'"; 
}

$sql=$obj->selectData(TABLE_IP_ACCESS,"","ipa_ip!='' and ipa_user!='' ".$extra,2,"ipa_date desc,char_length(ipa_user) desc");
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
        <li class="active">IP Tracker</li>
      </ul>
      <!-- /.breadcrumb -->
      
     <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> IP Tracker Management <?php /*?><span class="pull-right"><a href="edit_ip_restrict.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add IP Restrict</a></span><?php */?></small> </h1>
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
					<div class=" col-md-3">
							<input type="text" name="search_ip" id="search_ip" class="form-control" placeholder="255.255.255.255" value="<?=$_REQUEST['search_ip']?>" />
					</div>	
					<div class=" col-md-3">
							<input type="text" name="search_date" id="search_date" class="form-control" placeholder="Date" value="<?=$_REQUEST['search_date']?>" />
					</div>		
						
						<div class="input-group col-md-4">
							<span class="input-group-btn">
								<button class="btn btn-info" type="submit">Search</button>
							</span>
						</div>
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
          <th>IP Address</th>		  
          <th>User List</th>	
          <th>Date</th>		 
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
			<td><?=$data['ipa_ip'];?></td>
			<td>
			<?php
				$uids = trim($data['ipa_user'],",");
				if($uids=='') $uids = '0';
				$sqlU = $obj->selectData(TABLE_USER_LOGIN,"u_login_user_email","u_login_id IN (".$uids.")","");
				while($resU = mysqli_fetch_array($sqlU))
				{
					echo $resU['u_login_user_email']." || ";
				}
			?>
			</td>		   
			<td class="center"><?=$data['ipa_date'];?></td>
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="3"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
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
$(function() {
	$( "#search_date" ).datepicker({	 
		inline: true,
		dateFormat: 'yy-mm-dd',	 
		 maxDate: '0',
		 
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
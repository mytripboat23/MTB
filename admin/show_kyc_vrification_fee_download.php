<?php include("../includes/connection.php");
adminSecure();
 
if($_REQUEST['search_email']!='')
{
// $extra = " and mp_email='".$obj->filter_mysql($_REQUEST['search_email'])."' ";
}  
$sql=$obj->selectData(TABLE_KYC_VERIFICATION_REQUEST,"","is_verified='Approve' and req_status='Active'".$extra." order by req_id asc",2);
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
        <li class="active">KYC verification fee download</li>
      </ul>
      <!-- /.breadcrumb -->
      <!-- /.nav-search -->
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> KYC Fee Management </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
             <div class="row">
		<div class="col-md-8"> </div>
		<div class="col-md-4"> <a class="btn btn-info pull-right" href="export_kyc_verification_fee.php" target="_blank">Export customers as csv</a></div>
		</div>
            <div class="row">
              <div class="col-xs-12">
			 
			  
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Member</th>
                        <th>Fee</th>
                        <th class="center">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){
			$class = $i%2==0?'odd':'';
			$i++;
			$wallData = $obj->selectData(TABLE_USER_WALLET,"wall_asimi,wall_created","user_id='".$data['user_id']."' and wall_type='kyc' and wall_pstatus='p' and wall_status='Active'",1);
	   ?>
                      <tr>
                        <td><a href="view_member.php?uId=<?=$data['user_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip">
                          <?=$obj->getUserName($data['user_id']);?>
                          </a></td>
                        <td><?=$wallData['wall_asimi'];?></td>
                        <td class="center"><?=$wallData['wall_created'];?></td>
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
</body>
</html>

<?php include("../includes/connection.php");
adminSecure();
   
if(isset($_POST['LAPSetup'])) 
{	
	 
	$_SESSION['messageClass'] = "errorClass"; 
	if(trim($_POST['pack_id'][0])=="") {$obj->add_message("message","Ad minter package should not be blank!");}  
	if($obj->get_message("message")=="")
	{
		if($_REQUEST['LAPSetup']=='Accept')
		{
			$totrec = count($_POST['pack_id']); 
			for($i=0;$i<$totrec;$i++) 
			{
				$packID 										= $obj->filter_numeric($_POST['pack_id'][$i]); 
				$packArr['pack_desc']	 					 	= $_POST['pack_desc'];	
				$packArr['pack_sales_affliliate_distri']	 	= $obj->filter_numeric($_POST['pack_sales_affliliate_distri']);		
				$packArr['pack_price']	 						= $obj->filter_mysql($_POST['pack_price'][$i]);
				$packArr['pack_ad_minter_adver_view']	 		= $obj->filter_numeric($_POST['pack_ad_minter_adver_view'][$i]);	 
				$packArr['pack_directory_campaign']	 			= $obj->filter_numeric($_POST['pack_directory_campaign'][$i]);	 
				$obj->updateData(TABLE_PACKAGE,$packArr,"pack_id='".$packID."'"); 
			}
			$_SESSION['messageClass'] = "successClass";	
			$obj->add_message("message","Your Ad Minter Package Setup Successfully.");			 
			$obj->reDirect(urldecode($_REQUEST['return_url']));	
		}
		else 
		{ 
			$obj->reDirect(urldecode($_REQUEST['return_url'])); 
		} 
		 
	}
	else 
	{
		$_SESSION['messageClass'] = "errorClass";	
		$obj->add_message("message","Something Wrong.Please try again.");			 
		$obj->reDirect(urldecode($_REQUEST['return_url']));	
		
	}			
}
 
  
 
 
$sql = $obj->selectData(TABLE_PACKAGE,"","pack_status='Active' and pack_id IN(1,6,7,8,9) order by pack_id asc",2);
$pg_obj=new pagingRecords();
$pg_obj->setPagingParam("g",5,20,1,1);
$getarr = $obj->filter_mysql_array($_GET);
unset($getarr['msg']);
$res=$pg_obj->runQueryPaging($sql,$pageno,$getarr);
$qr_str=$pg_obj->makeLnkParam($getarr,0);
$pageno = 1;
if($_REQUEST['pageno']!="")
{
	$pageno = $obj->filter_numeric($_REQUEST['pageno']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("page_includes/common.php");?>
<script type="text/javascript" src="../tiny/tiny/tinymce.min.js"></script>
<script type="text/javascript" src="../tiny/tiny/tinymce-data.js"></script>
<style>
.login_setu-link {
   padding:20px;
}
.login_setu-link img {
    width: 100%;
    max-width: 140px;
    margin: 0 auto;
}
.login_setu-link span {
    display: block;
    text-align: center;
    padding-top: 10px;
    color: 
    #000;
    font-weight: 600;
    font-size: 18px;
}
.login_setu-link h3 {
    font-size: 18px;
    margin-top: 30px;
}
</style>
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
        <li class="active">Ad Minter Advertising Packages </li>
      </ul>
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Ad Minter Advertising Packages </small> </h1>
        </div>
        <!-- /.page-header -->
         <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-lg-12">
               <div class="panel panel-default"> 
				<div class="login_setu-link panel-heading">
                <form name="packEdit" id="packEdit" method="post">
				<div class="row">
				<div class="col-md-7">
				<?php $packDa = $obj->selectData(TABLE_PACKAGE,"","pack_id='1' and pack_status='Active'",1); ?>
				  <div class="form-group">
					<label style="font-size:16px;">Description</label>
					<textarea placeholder="Description"  id="tinymce" name="pack_desc" class="form-control"><?=str_replace(["\r\n", "\n", "\r", "rn", " "], " ", html_entity_decode($packDa['pack_desc']));?> </textarea>
				 </div>
				 </div>
				 </div>
					<table class="table table-striped table-bordered table-hover" style="margin-top:25px;">
						<thead>
							<tr>
								<th>Price USD</th>
								<th>Ad Minter Impressions</th>
								<th>Active Ad Limit</th>
								<th>Economies Bonus</th>
								<th>Cost Per view</th>
								<th>Current Asimi Price</th>
							</tr>
						</thead>
						<tbody>
						<?php while($data = mysqli_fetch_assoc($res)) { 
									$percentage = ( $data['pack_ad_minter_adver_view'] / $data['pack_price'] );
								$cost_per_view  = $data['pack_price']/$data['pack_ad_minter_adver_view'];
						?>
							<input type="hidden" name="pack_id[]" id="pack_id<?=$i;?>" value="<?=$data['pack_id'];?>">
							<tr>
								<td style="width:10%;"><input type="text" name="pack_price[]" value="<?=$data['pack_price'];?>" class="form-control"></td>
								<td style="width:15%;"><input type="text" name="pack_ad_minter_adver_view[]" value="<?=$data['pack_ad_minter_adver_view'];?>" class="form-control"></td>
								<td style="width:10%;"><input type="text" name="pack_directory_campaign[]" value="<?=$data['pack_directory_campaign'];?>" class="form-control"></td>
								
								<td style="width:10%;"><input type="text" value="<?=$percentage;?>%" class="form-control" disabled="disabled"></td>
								<td style="width:10%;"><input type="text" value="<?=round($cost_per_view,8);?>" class="form-control" disabled="disabled"></td>
								<td style="width:10%;"><input type="text" value="<?=$obj->get_current_asimi_token($data['pack_price'])?>" class="form-control" disabled="disabled"></td>							
							</tr>
						<?php } ?>	 
							 
						 
						</tbody>
					</table>
				<div class="row form-group">
					<div class="col-md-3 col-lg-3"><label style="font-size:16px;">Affilate Commision Level (%)</label></div>
					<div class="col-md-3 col-lg-3"><input type="text" name="pack_sales_affliliate_distri" value="<?=$packDa['pack_sales_affliliate_distri'];?>" class="form-control"></div>
				</div>
				<input type="submit" name="LAPSetup" value="Cancel" class="btn btn-danger" style="margin-right:15px;">
				<input type="submit" name="LAPSetup" value="Accept" class="btn btn-success">
				</form>
               </div>
			   </div>
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
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" type="text/javascript">	
	 
	tinymce.init({
  selector: 'textarea#tinymce', //Change this value according to your HTML
  auto_focus: 'element1',

});
 	
	
	
</script>
</body>
</html>

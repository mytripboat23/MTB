<?php include("../includes/connection.php");
adminSecure();
 
if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbxAr)
		{
			$chbx = $obj->filter_mysql($chbxAr);
			$deleteStatus = $obj->updateData(TABLE_GEO_GROUP_COUNTRY,array('ggc_status'=>'Deleted'),"ggc_id='".$chbx."'"); 
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
 
$sql=$obj->selectData(TABLE_GEO_GROUP_COUNTRY,"","ggc_status<>'Deleted'".$extra." order by ggc_id desc",2);
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
        <li class="active">GEO-Targeting</li>
      </ul>
      <!-- /.breadcrumb -->
      <!-- /.nav-search -->
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> GEO-Targeting Management <span class="pull-right"><a href="edit_group_countries.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Group Countries</a></span></small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			 
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">              
			 
              <div class="col-xs-12">
			  	<h2>Country groups</h2>
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Continent</th>
                        <th>Country Group</th>
                        <th>Countries</th>
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
					// echo $vall = trim(",",$data['ggc_countries']); 
					$ggc_countries = rtrim(ltrim($data['ggc_countries'],","),",");  
				?>
                      <tr>
                        <td><?=$data['ggc_continent'];?></td>
                        <td><?=$data['ggc_country_group'];?></td>
                        <td>
						<?php 
						
						$paval = explode(",",$ggc_countries);
						foreach($paval as $varD)
						{
							echo $obj->getCountry($varD).", ";
						}						
						?>	 
						<?php // =$data['ggc_countries'];  $paval = explode(",",$ggcD['ggc_countries']); ?>    
						</td>
						<td><?=$data['ggc_status'];?></td>
						<td><a href="edit_group_countries.php?cId=<?=$data['ggc_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Edit">Edit</a></td>
						<td class="center"><label class="position-relative">
						<input type="checkbox" class="ace" name="chkSelect[]" id="chkSelect1" value="<?=$data['ggc_id'];?>"/>
						<span class="lbl"></span> </label></td>
                      </tr>
				<?php
					}
				?>
                      <tr>
                        <td class="center" colspan="6"><div class="pagination"><?php echo $pg_obj2->pageingHTML;?></div></td>                        
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

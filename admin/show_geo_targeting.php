<?php include("../includes/connection.php");
adminSecure();
$glId= $obj->filter_mysql($obj->filter_numeric($_REQUEST['glId']));
 
 
$sql=$obj->selectData(TABLE_GEO_COUNTRY,"","gcon_status<>'Deleted'".$extra." order by gcon_ucount desc",2);
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

$sql2=$obj->selectData(TABLE_GEO_GROUP_COUNTRY,"","ggc_status<>'Deleted'".$extra." order by ggc_id desc",2);
$pg_obj2=new pagingRecords();
$pg_obj2->setPagingParam("g",5,22,1,1);
$getarr2 = $obj->filter_mysql_array($_GET);
unset($getarr2['msg']);
$res2=$pg_obj2->runQueryPaging($sql2,$pageno2,$getarr2);
$qr_str=$pg_obj2->makeLnkParam($getarr2,0);
$pageno2 = 1;
if($_REQUEST['pageno2']!="")
{
	$pageno2 = $obj->filter_mysql($obj->filter_numeric($_REQUEST['pageno2']));
}

$sql3=$obj->selectData(TABLE_GEO_LANGUAGE,"","glan_status<>'Deleted' and glan_is_active='y'".$extra." order by glan_id desc",2);
$pg_obj3=new pagingRecords();
$pg_obj3->setPagingParam("g",5,22,1,1);
$getarr3 = $obj->filter_mysql_array($_GET);
unset($getarr3['msg']);
$res3=$pg_obj3->runQueryPaging($sql3,$pageno3,$getarr3);
$qr_str=$pg_obj3->makeLnkParam($getarr3,0);
$pageno3 = 1;
if($_REQUEST['pageno3']!="")
{
	$pageno3 = $obj->filter_mysql($obj->filter_numeric($_REQUEST['pageno3']));
}


 
$dataGL = $obj->selectData(TABLE_GEO_LANGUAGE,"","glan_id='".$glId."'",1);

if($_POST)
{	
   
	if(!$err)
	{		
		if($obj->filter_mysql($_POST['glan_id']['0'])=="") {$obj->add_message("message","Please select a valid language.");}		
		if($obj->get_message("message")=="")
		{			 	 
			$obj->updateData(TABLE_GEO_LANGUAGE,array('glan_is_active'=>'n'),"1",2); 			
			foreach($_POST['glan_id'] as $valGlId)
			{	 
				 $obj->updateData(TABLE_GEO_LANGUAGE,array('glan_is_active'=>'y'),"glan_id='".$valGlId."'"); 
			}			 			
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';		 
			$obj->reDirect(urldecode($_REQUEST['return_url']));					
		} 			
	}
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> GEO-Targeting Management <span class="pull-right"> </span></small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			<?php if($dataGL['glan_id']) { ?>
			<div class="email_searchBox clearfix">
			<div class="row">
            	 <div class="col-md-12">
					<h3>Update language button list.</h3>
					<!-----<p>To add a language, please add the language code (xx) with a ";" after it.</p>
					<p><strong>IMPORTANT!</strong>Do not remove the first ";" when removing languages. The field should look like: ";xx;xx;xx;" not "xx;xx;xx;".</p> ---->
					<form action="" name="lanEdit" id="lanEdit" method="post">
					<div id="custom-search-input" class="row">				
					<div class=" col-md-12">
					<?=$obj->select_languages();?>
					</div>		

					<div class="input-group col-md-6">
					<span class="input-group-btn">
					<button class="btn btn-info" type="submit">Save languages</button>
					</span>
					</div>
					</div>
					</form>
				</div>
				</div>
            </div>
			<?php } ?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row"> 
			  <div class="col-xs-12">
			  	<h2>Languages</h2>
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Language</th>                        
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
				
                      <tr>                        
                        <td>
						<?php while($data3=mysqli_fetch_assoc($res3)) {   ?>
						<?=$data3['glan_name'];?>(<?=$data3['glan_code'];?>) <strong>[<?=$data3['glan_ucount'];?>]</strong> ||     
						<?php } ?>
						</td>						
						<td><a href="show_geo_targeting.php?glId=1&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" data-rel="tooltip" title="Edit">Edit</a></td>
                      </tr>                     
                    </tbody>
                  </table>
                </form>
              </div>
			  	
			
              <div class="col-xs-12">
			  	<h2>Country</h2>
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Country</th>
                        <th>Users</th>
                      </tr>
                    </thead>
                    <tbody>
				<?php
					$i=1;
					while($data=mysqli_fetch_assoc($res)) {  //pre($data);
					$class = $i%2==0?'odd':'';
					$i++;					 
				?>
                      <tr>
                        <td><?=$data['gcon_name'];?></td>
                        <td><?=$data['gcon_ucount'];?></td>
                      </tr>
				<?php
					}
				?>
                      <tr>
                        <td class="center" colspan="2"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>      
					 </tr>
                    </tbody>
                  </table>
                </form>
              </div>
			 
              <div class="col-xs-12">
			  	<h2>Country groups</h2>
                <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Country</th>
                        <th>Users</th>
                      </tr>
                    </thead>
                    <tbody>
				<?php
					$i=1;
					while($data2=mysqli_fetch_assoc($res2)) { //pre($data2);
					$class = $i%2==0?'odd':'';
					$i++;
					$ggc_countries = rtrim(ltrim($data2['ggc_countries'],","),",");  
					$conUser  = $obj->selectData(TABLE_GEO_COUNTRY,"sum(gcon_ucount) as tot_member","country_id in (".$ggc_countries.")",1);
				?>
                      <tr>                        
                        <td><?=$data2['ggc_country_group'];?></td>
                        <td><?=$conUser['tot_member'];?></td>
                      </tr>
				<?php
					}
				?>
                      <tr>
                        <td class="center" colspan="2"><div class="pagination"><?php echo $pg_obj2->pageingHTML;?></div></td>                         
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
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" type="text/javascript">	
	$().ready(function() {
		$("#lanEdit").validate({
			rules: {
				glan_id[]: {
					required: true
				} 
			},
			messages: {
				glan_id: {
					required: "Please select at least one language."												
				} 					 
			}
		});
	});			
</script>
</body>
</html>

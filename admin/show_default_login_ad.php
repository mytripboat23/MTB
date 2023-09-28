<?php include("../includes/connection.php");
adminSecure();
   
if(isset($_POST['LAPDSetup'])) 
{	 // pre($_POST); exit;			 
	$_SESSION['messageClass'] = "errorClass";	 
	if(strpos($_POST['default_login_ad_url'],'<') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (<)");}
	if(strpos($_POST['default_login_ad_url'],'>') !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (>)");}
	if(strpos($_POST['default_login_ad_url'],"'") !== false){$obj->add_message("message","Error! The ad campaign contains a not allowed character (')");}
	if(strpos($_POST['default_login_ad_url'],'"') !== false){$obj->add_message("message",'Error! The ad campaign contains a not allowed character (")');}
	 
	$desti_url1 = $_POST['default_login_ad_url'];  
	$parse1 = parse_url($desti_url1);	 
	if($parse1['scheme'] !='https')
	{	
		$obj->add_message("message","Your default URL does not start with https.");	
		$obj->reDirect(urldecode($_REQUEST['return_url']));	
		exit;
	} 
	 
 
	$arrAE['default_login_ad_url'] 	= $obj->filter_mysql(addslashes(stripslashes($_POST['default_login_ad_url'])));	
	$arrAE['set_act_spns_adv'] 		= $obj->filter_mysql($_POST['set_act_spns_adv']);	
	$obj->updateData(TABLE_SETTINGS,$arrAE,"set_id='1'");
	$obj->add_message("message","Default login ad ".UPDATE_SUCCESSFULL);
	$_SESSION['messageClass'] = 'successClass';
	$obj->reDirect(urldecode($_REQUEST['return_url']));	
}
$site_set=$obj->selectData(TABLE_SETTINGS,"","set_id=1",1);
// pre($site_set);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("page_includes/common.php");?>
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
        <li class="active">Default Login Ad </li>
      </ul>
    
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Default Login Ad Management <?php /* ?> <span class="pull-right"><a href="edit_manual_assign_login_ad.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Login Ad Manual Assing</a> </span> <?php */ ?> </small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
			  <div class="col-lg-10 col-lg-offset-1">
				 
				<div class="panel panel-default">
					<div class="login_setu-link panel-heading">
						<form method="post" action="" name="lapInfo" id="lapInfo">
							<div class="row">
								 
								<div class="col-sm-9">					 
									 
									<div class="form-group">
									<div class="row">
										<label class="col-sm-2 col-md-2 text-right">Default URL:</label>
									   <div class="col-sm-10 col-md-10"> <input type="url" required name="default_login_ad_url" id="default_login_ad_url" value="<?=$site_set['default_login_ad_url'];?>"  class="form-control"></div>
									</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Activate Sponsored Advertisement: </label>
										<div class="radio col-sm-2">
											<label> <input type="radio" name="set_act_spns_adv" id="set_act_spns_adv" value="y" <?php if($site_set['set_act_spns_adv'] =='y'){ echo "checked='checked'";}?>>  Yes </label> &nbsp;&nbsp;&nbsp;
											<label> <input type="radio" name="set_act_spns_adv" id="set_act_spns_adv" value="n" <?php if($site_set['set_act_spns_adv'] =='n'){ echo "checked='checked'";}?>>  No </label>
										</div>
									</div>

									 
									<div class="row">
										<div class="col-sm-5 col-md-2">&nbsp; </div>
										<div class="col-sm-7 col-md-10 text-right"> 										 
										<input type="submit" name="LAPDSetup" value="Submit" class="btn btn-success">										 
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
					 
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
 
<script type="text/javascript" src="js/jquery.validate.js"></script> 
<script language="javascript" type="text/javascript">	
	$("#lapInfo").validate({
		rules: {
			lap_url_1: {
					required: true,
					url:true
			   }
			
		},
		messages: {
			lap_url_1: {
						required: "Please enter your URL",
						url:"Please enter valid URL"
				   }		 
		}
	});
	
</script>

</body>
</html>
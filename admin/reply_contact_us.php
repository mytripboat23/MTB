<?php
session_start();
include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_contact_us.php";
if($return_url)
{
	$redirect_url=$return_url;
}

  $id= $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['uId']));
  $table_caption="Contact Us >> Reply";
  $conD=$obj->selectData(TABLE_CONTACTUS,"","contact_id='".$id."'",1);
  
if($_POST)
{

	$_POST['cr_added'] = CURRENT_DATE_TIME;
	$_POST['contact_id'] = $id;
	$con_id=$obj->insertData(TABLE_CON_REPLY,$_POST);
	$obj->add_message("message",ADD_SUCCESSFULL);
	$_SESSION['messageClass'] = 'successClass';
	
			$reply_message  = "<b> Dear ".$conD['contact_name']."</b>, Please find the reply below<br><br>";
			$reply_message .= $_POST['cr_message'];
			$reply_message .="<br><br>Thank You<br>Go Vacation Team";
		
			$body = $obj->mailBody($reply_message);
			
			$from = FROM;
			$to   = $conD['contact_email'];
			
			$subject = "Reply- Contact Us #".$conD['contact_id'];
			
			$obj->sendMailSES($to,$subject,$body,$from,SITE_NAME,$type);	
	
	
			$obj->reDirect($_SERVER['REQUEST_URI']);	
}
  
  

$sql=$obj->selectData(TABLE_CON_REPLY,""," contact_id='".$id."' order by cr_id desc",2);
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
        <li> <a href="show_contact_us.php">Contact Us</a></li>
		<li class="active"><?=$data['contact_name'];?></li>
		
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Contact Us Reply  </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
			<div class="row">
              <div class="col-xs-12">
			
			 <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr>
		<th width="80%">Message</th> 
		<th> <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>Added</th>          
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=1;
			while($data=mysqli_fetch_assoc($res)){
			$class = $i%2==0?'odd':'';
			$i++;
	   ?>
        <tr>
		<td><?=html_entity_decode($data['cr_message']);?></td>
		<td><?=date("F d, Y",strtotime($data['cr_added']));?></td>          
        </tr>
      <?
		  }
	  ?>
	  </tbody>
	  </table>
			</div>
			</div>
			
            <div class="row">
              <div class="col-xs-12">
			 <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>
	  
        <tr>
		<th width="100%">Reply</th> 
		   
        </tr>
      </thead>
	  </table>
                <form class="form-horizontal" role="form" name="conReply" id="conReply" method="post" action="">
                 

				
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Message :</label>
                    <div class="col-sm-5">
					<? 	
					require_once(FCKPATH.'fckeditor.php');
					$oFCKeditor = new FCKeditor('cr_message') ;
					$oFCKeditor->BasePath	=FCKPATH.'' ;
					//$oFCKeditor->Config['SkinPath'] = FURL.'includes/Office2007Real/' ;
					$oFCKeditor->Height	= 300 ;
					$oFCKeditor->Width	= 700 ;	
					//$oFCKeditor->Value	=html_entity_decode($data['cr_messge']);								
					$oFCKeditor->Create(); 
					?>      
                    </div>
                  </div>
				  
				  
				 

				  
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="submit" value="Reply" class="btn btn-info">
                      &nbsp; &nbsp; &nbsp; </div>
                  </div>
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
			$("#packEdit").validate({
					rules: {
						contact_title: "required",						
						contact_valid_month: 
							{
								required:true,
								number:true
							},
						contact_price: 
							{
								required:true,
								number:true
							}
					},
					messages: {
						contact_title: "Please enter Contact Us Name",
						contact_valid_month: {
							required:"Please enter Valid Days",
							number:"Please enter a proper Valid Days!"
						},
						contact_price: 
								{
									required:"Please enter Price Amount",
									number:"Please enter a proper Price Amount!"
								}
					}
				});
		});			
</script>	
</body>
</html>
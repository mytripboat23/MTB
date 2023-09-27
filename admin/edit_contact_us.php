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

  $id= $obj->filter_mysql($obj->filter_numeric($_REQUEST['uId']));

$mode="add";
$table_caption="Add New Contact Us";
$data=$obj->selectData(TABLE_CONTACTUS,"","contact_id='".$id."' and contact_status<>'Deleted'",1);
//print_r($data);

if($data)
{
	$mode="edit";
	$table_caption="Edit Contact Us Details";
}
if($_POST)
{

		
	switch($mode)
	{
		case 'add':

		if(!$err)
		{ 
				  
				 
			$carr['contact_email']    	= $obj->filter_mysql($_POST['contact_email']);
			$carr['contact_name']   	= $obj->filter_mysql($_POST['contact_name']);
			$carr['contact_message']    = $obj->filter_mysql($_POST['contact_message']);
			$carr['contact_created']    = CURRENT_DATE_TIME;
			$carr['contact_modified'] 	= CURRENT_DATE_TIME;
			$com_id=$obj->insertData(TABLE_CONTACTUS,$carr);
			$obj->add_message("message",ADD_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
		case 'edit':
		
		if(!$err){
			
			$carrU['contact_modified'] = CURRENT_DATE_TIME;
			$obj->updateData(TABLE_CONTACTUS,$carrU," contact_id='".$id."'");
			
			$obj->add_message("message",UPDATE_SUCCESSFULL);
			$_SESSION['messageClass'] = 'successClass';
			$obj->reDirect($redirect_url);
		}
		break;
		
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i><?=$table_caption;?> </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-xs-12">
                <form class="form-horizontal" role="form" name="packEdit" id="packEdit" method="post" action="">
                 

				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Title :</label>
                    <div class="col-sm-9">
                     <input type="text" id="contact_name" name="contact_name" placeholder="Contact Us Name" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['contact_name']);?>" />
                    </div>
                  </div>			
				 
				
				 <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email :</label>
                    <div class="col-sm-9">
                     <input type="email" id="contact_email" name="contact_email" placeholder="Email" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['contact_email']);?>" />
                    </div>
                  </div>				  
				  
				<? /*  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Subject :</label>
                    <div class="col-sm-9">
                     <input type="text" id="contact_subject" name="contact_subject" placeholder="Subject" class="col-xs-10 col-sm-5" value="<?=$data['contact_subject'];?>" />
                    </div>
                  </div> 
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Message :</label>
                    <div class="col-sm-9">
                     <input type="text" id="contact_message" name="contact_message" placeholder="Message" class="col-xs-10 col-sm-5" value="<?=$data['contact_message'];?>" />
                    </div>
                  </div>
				  */?>
				   <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Message :</label>
                    <div class="col-sm-5">
					<textarea id="contact_message" name="contact_message" placeholder="Description" class="col-xs-10 col-sm-5" style="width: 466px; height: 277px;"><?=html_entity_decode($data['contact_message']);?></textarea>
                    </div>
                  </div>
				  
				  
				  <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Phone :</label>
                    <div class="col-sm-9">
                     <input type="text" id="contact_number" name="contact_number" placeholder="Phone" class="col-xs-10 col-sm-5" value="<?=htmlentities($data['contact_number']);?>" />
                    </div>
                  </div>
				
				

				  
                  <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                      <input type="submit" name="submit" value="Save" class="btn btn-info">
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
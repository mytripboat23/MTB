<?php
require_once("../includes/connection.php");
adminSecure();
$u_login_id= $obj->filter_numeric($_REQUEST['uId']);
if(empty($u_login_id)) $obj->reDirect($_REQUEST['return_url']);
if(isset($_REQUEST['return_url']))
{
	$return_url = urldecode($_REQUEST['return_url']);
}
else
{
	$return_url='show_member.php';
}

$mode="add";
$table_caption="Add New Member";
$jqueryReq = 'true';

	if($u_login_id !="")
	{
		$mode="edit";
		$table_caption="View Member Details";		 
		$data = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","","ul.u_login_id=u.u_login_id and u.u_login_id='".$u_login_id."' and ul.u_login_status<>'Deleted' and u.user_status<>'Deleted'",1);  
	}
	else
	{
		$data = $_POST;		 
	}

//pre($data);
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
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>
            <?=$table_caption;?>
            </small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <h2>
              <?=$data['user_first_name'];?>
            </h2>
            <div class="hr hr32 hr-dotted"></div>
            <?php //include("page_includes/member_tabs.php");?>
            <div class="row">
              <div class="col-md-offset-1 col-md-10">
                <form class="form-horizontal" role="form" name="userEdit" id="userEdit" method="post" action="" enctype="multipart/form-data">
                <div class="panel panel-default"> 
                <div class="panel-heading">
					<label> <strong>Account Details:</strong></label>					
				</div>
              <div class="panel-body">
                <table class="table table-bordered view_valu">
                  <tbody>
                    <tr>
                      <td width="50%"><label class="col-sm-4 no-padding-right"> Name </label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$obj->filter_mysql($data['user_full_name']);?>
                          </h5>
                        </div>
						</td>
						 <td width="50%"><label class="col-sm-4 no-padding-right"> Display Name </label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$data['user_display_name'];?>
                          </h5>
                        </div>
						</td>
						</tr>
                    <tr>
					 <td><label class="col-sm-4 no-padding-right"> Email </label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$obj->filter_mysql($data['user_email']);?>
                          </h5>
                        </div></td>
                      <td><label class="col-sm-4 no-padding-right"> DOB </label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=date("d/m/Y",strtotime($data['user_dob']));?>
                          </h5>
                        </div></td>
                      
                    </tr>
                    <tr>
                      <td><label class="col-sm-4 no-padding-right">Agency</label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$data['user_agency'];?>
                          </h5>
                        </div></td>
                      <td><label class="col-sm-4 no-padding-right">Gender</label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?php if($data['user_gender']=='m') { echo "Male"; } else { echo "Female"; } ?>
                          </h5>
                        </div></td>
                    </tr>
					
					 <tr>
                      <td><label class="col-sm-4 no-padding-right">City</label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$data['user_city'];?>
                          </h5>
                        </div></td>
                      <td><label class="col-sm-4 no-padding-right">From</label>
                       <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$data['user_from_city'];?>
                          </h5>
                        </div></td>
                    </tr>
					 <tr>
                      <td><label class="col-sm-4 no-padding-right">Phone</label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$data['user_phone'];?>
                          </h5>
                        </div></td>
                      <td><label class="col-sm-4 no-padding-right">Website</label>
                       <div class="col-sm-7">
                          <h5><span>:</span>
                            <?=$data['user_website'];?>
                          </h5>
                        </div></td>
                    </tr>
    				<tr>
                      <td><label class="col-sm-4 no-padding-right">Language</label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                  <?php if($data['user_lang']!="" && $data['user_lang']!=NULL){?>
   				<?php
			  	$lang_ids = trim($data['user_lang'],",");
			    $sqlL = $obj->selectData(TABLE_LANGUAGE,"","lang_id in (".$lang_ids.")");
				while($resL = mysqli_fetch_object($sqlL))
				{
			  ?>
      		  <?=$resL->lang_title;?>, 
    		 <?php }?>
 			 <?php }?>
                          </h5>
                        </div></td>
                      <td><label class="col-sm-4 no-padding-right">Hobby</label>
                       <div class="col-sm-7">
                          <h5><span>:</span>
                <?php if($data['user_hobby']!="" && $data['user_hobby']!=NULL){?>
   				<?php
			  	$hobby_ids = trim($data['user_hobby'],",");
			    $sqlH = $obj->selectData(TABLE_HOBBIES,"","hobby_id in (".$hobby_ids.")");
				while($resH = mysqli_fetch_object($sqlH))
				{
			  ?>
      		  <?=$resH->hobby_title;?>, 
    		 <?php }?>
 			 <?php }?>
                          </h5>
                        </div></td>
                    </tr>
					
					<tr>
                      <td><label class="col-sm-4 no-padding-right">Photo</label>
                        <div class="col-sm-7">
                          <h5><span>:</span>
                           <?php if($data['user_avatar']!=""){?><img src="../<?=AVATAR.$data['user_avatar']?>" width="75"><?php }?>
                          </h5>
                        </div></td>
                      <td><label class="col-sm-4 no-padding-right">Banner</label>
                       <div class="col-sm-7">
                          <h5><span>:</span>
                            <?php if($data['user_banner']!=""){?><img src="../<?=BANNER.$data['user_banner']?>" width="75"><?php }?>
                          </h5>
                        </div></td>
                    </tr>
					<tr>
                      <td colspan="2"><label class="col-sm-4 no-padding-right">Bio</label>
                        <div class="col-sm-12">
						<br>
                          <h5>
                          <?=$data['user_bio'];?>
                          </h5>
                        </div></td>
                     
                    </tr>
                  </tbody>
                </table>
              </div>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
<script language="javascript" type="text/javascript">
function set_emergency_reset(uId)
{
	if(confirm('Do you really want to apply an emergency reset to this account?'))
	{
		window.location.href = 'view_member.php?uId='+uId+'&action=EReset';
	}
} 

function stop_2fa(uId)
{
	if(confirm('Do you really want to stop 2fa authentication to this account?'))
	{
		window.location.href = 'view_member.php?uId='+uId+'&action=2faauth';
	}
}

function remove_wallet(uId)
{
	if(confirm('Do you really want to remove to delete the wallet address from the user profile?'))
	{
		window.location.href = 'view_member.php?uId='+uId+'&action=delWall';
	}
}
</script>
<script>
 
	$(function() {
		$( "#user_dob" ).datepicker({
			changeYear: true ,
			yearRange : 'c-75:c0',
			changeMonth: true ,	 
			dateFormat: 'yy-mm-dd',	 
			todayBtn: 'linked'
		});
	});
</script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" type="text/javascript">	
$().ready(function() {
	$("#userEdit").validate({
			rules: {
				user_first_name: "required",
				user_last_name: "required",	
				u_login_user_email: "required",
				u_login_username: "required",				
				//u_login_password: "required"
			},
			messages: {
				user_first_name: "Please enter your firstname",
				user_last_name: "Please enter your lastname",
				u_login_user_email: "Please enter your email",
				u_login_username: "Please enter your username",		
				//u_login_password: "Please enter password"
			}
		});
});			
</script>
</body>
</html>

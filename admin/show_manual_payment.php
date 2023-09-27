<?php include("../includes/connection.php");
adminSecure();
 
if($_REQUEST['search_email']!='')
{
 $extra = " and mp_email='".$obj->filter_mysql($_REQUEST['search_email'])."' ";
}  
$sql=$obj->selectData(TABLE_MANUAL_PAYMENT,"","1".$extra." order by mp_id desc",2);
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
        <li class="active">Manual purchase</li>
      </ul>
      <!-- /.breadcrumb -->
      
     <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Manual purchase Management <span class="pull-right"><a href="edit_manual_payment.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Manual purchase</a></span></small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			<div class="email_searchBox clearfix">
				<div class="row">
            	 <div class="col-md-4">
					
					<form action="" method="get">
					<div id="custom-search-input">
						<div class="input-group col-md-10">	
							<input type="text" name="search_email" id="search_email" class="form-control" placeholder="example@gmail.com" value="<?=$_REQUEST['search_email']?>" />		 
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
		  <th>Order No.</th>
		  <th class="center">Date</th> 
          <th>Email</th>
          <th>Name</th>
          <th>Order Description</th>
		  <th>Amount</th>
		  <th>Status</th>		  
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
			<td>#<?=$data['mp_id'];?></td>
			<td><?=$data['mp_created'];?></td>
			<td><?=$data['mp_email'];?></td>	
			<td><?=$data['mp_name'];?></td>		
			<td><?=$data['mp_desc'];?></td>	
			<td><?=$data['mp_amount'];?></td>
  
			<td>
				<span id="msg_status_<?=$data['mp_id'];?>" style="margin-left:5px; color:#FF0000; display:none;">Status Changed Successfully</span>
				<input type="hidden" name="<?=$data['mp_id'];?>" value="<?=$data['mp_id'];?>" id="apply_order_id">
				<select name="mp_status" id="mp_status" onchange='ajax_change_status(this.value,"<?=$data['mp_id'];?>")' class="form-control">
					<option value="Initialized" <?php if($data['mp_status']=='Initialized'){?> selected="selected"<?php }?>>Initialized </option>
					<option value="Finished" <?php if($data['mp_status']=='Finished'){?> selected="selected"<?php }?>> Finished</option>			 
				</select>		
			</td>			 
		<?php /* ?>	<td><a href="edit_manual_payment.php?mpId=<?=$data['mp_id']?>&return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="tooltip-info" title="View">Edit</a></td> <?php */ ?>			
		 		 
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="7"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
		  
		  
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
 
  

<script type="text/javascript" language="javascript">
function ajax_change_status(order_status,apply_order_id)
{
	var r=confirm("Are you sure to change this ?");
	if(r==true){
		jQuery.ajax({
		type: "POST",				 
		 url: "ajax_script/ajax_change_status.php",
		data:{order_status:order_status,apply_order_id:apply_order_id},
		cache: false,					
			success: function(msg)	{			 
					//jQuery('#sm'+userId).html('<a class="btn btn-success" href="javascript:void(0);" style="cursor:text">Suspended <i class="fa fa-check"></i></a>');
					jQuery('#msg_status_'+apply_order_id).fadeIn().delay(1000).fadeOut();				 		
				
			}
		});
	}
}

</script>







</body>
</html>
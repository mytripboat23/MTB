<?php include("../includes/connection.php");
adminSecure();
$return_url=urldecode($_REQUEST['return_url']);

$redirect_url="show_internal_image.php";
if($return_url)
{
	$redirect_url=$return_url;
}

if(isset($_POST['btnDelete']))
{
	if(is_array($_POST['chkSelect']))
	{
		foreach($_POST['chkSelect'] as $chbx)
		{
			$chbxId = $obj->filter_numeric($chbx);
			$data = $obj->selectData(TABLE_INTERNAL_IMG,"","img_id='".$chbxId."'",1);
			unlink('../'.UPLOADIMG.$data['img_image']);			
			$deleteStatus = $obj->deleteData(TABLE_INTERNAL_IMG,"img_id='".$chbxId."'");
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
  

if($_POST['submit'] =='Save')
{	 
	 
	if(isset($_FILES['pimage1']))
	{	   
		$file_name = $_FILES['pimage1']['name'];		 
		$file_size =$_FILES['pimage1']['size'];
		$file_tmp =$_FILES['pimage1']['tmp_name'];
		$file_type=$_FILES['pimage1']['type'];
		$file_ext=strtolower(end(explode('.',$_FILES['pimage1']['name'])));	
	
		$extensions= array("gif","jpeg","jpg","png");	  
		if(in_array($file_ext,$extensions)=== false){
			$errors ="Extension not allowed, please choose a JPEG , PNG or gif file.";		 
		}
		
		$file_info = pathinfo($file_name);
		$filename = $file_info['filename'];
		$dataFile = $obj->selectData(TABLE_INTERNAL_IMG,"count(img_id) as tot_image","img_image LIKE '%".$filename."%'",1);		
		if($dataFile['tot_image'])
		{
			$uniqer = '-'.$dataFile['tot_image']+1;
		} 		 
		
		$get_file_name = $filename.$uniqer.'.'.$file_ext;
		 
		if(empty($errors)==true)
		{
			move_uploaded_file($file_tmp,"../".UPLOADIMG.$get_file_name);
			$arrU['img_image']=$get_file_name;
		}
		else
		{		 
			$obj->add_message("message",$errors);		 
			$err=1;
		}
	}	 
	  
	if($obj->get_message("message")=="") 
	{ 	
		$arrU['img_created'] = CURRENT_DATE_TIME;
		$obj->insertData(TABLE_INTERNAL_IMG,$arrU); 				
		$obj->add_message("message",UPDATE_SUCCESSFULL);
		$_SESSION['messageClass'] = 'successClass';
	}
	$obj->reDirect($redirect_url);	
	 
}
 

$sql=$obj->selectData(TABLE_INTERNAL_IMG,"","img_id!='' order by img_id desc",2);
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
        <li class="active">Internal Image</li>
      </ul>
      <!-- /.breadcrumb -->
       
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">
      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Internal Image Management <span class="pull-right"> </span></small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			
			<div class="email_searchBox clearfix">
				<div class="row">
					<div class="col-md-12">				
						<form class="form-horizontal" role="form" name="internalEdit" id="internalEdit" method="post" action="" enctype="multipart/form-data"> 
							<div id="custom-search-input">					
							<div class="col-md-3">
									 <input type="file" id="pimage1" class="col-sm-12" value="<?=$data['set_image'];?>" name="pimage1"/>
							</div>			 
							  
							<div class="input-group col-md-1">							
								<span class="input-group-btn">
									 <input type="submit" name="submit" value="Save" class="btn btn-info">
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
						<th>Name</th> 
						<th>Image URL</th>   
						<th class="center">Delete</th> 
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
						<td><?=$data['img_image'];?></td> 
						<td><?=FURL;?><?=UPLOADIMG.$data['img_image'];?></td> 
						<td class="center"><label class="position-relative">             
						<input type="checkbox"  name="chkSelect[]" class="ace" id="chkSelect1" myType="demo" value="<?=$data['img_id'];?>" />
						<span class="lbl"></span> </label></td>
					</tr>
				  <?php
					  }
				  ?>
					<tr>
					  <td class="center" colspan="2"><div class="pagination"><?php echo $pg_obj->pageingHTML;?></div></td>
					 <td class="center"><?php if($pg_obj->totrecord){?><input type="submit" name="btnDelete" value="Delete"><?php }?></td>
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

<script language="javascript">
function validate_delete(chackboxname)
{
	if( $("[myType]").is(':checked'))
	{
		return confirm("Are you sure you want to delete?");
	}
	else
	{
		alert("Please select at least one record to delete");
	    return false;
	}
	
}

$(document).ready(function(){
$('input[name="master_select"]').bind('click', function(){
var status = $(this).is(':checked');
$('input[type="checkbox"]').attr('checked', status);
});

});
</script>

<script>
function del_internal_img()
{
	if(confirm('Are you sure you want to delete internal image.?'))
	{
	 	window.location.href = 'show_internal_image.php?action=yes';
	}	 
}
</script> 
</body>
</html>
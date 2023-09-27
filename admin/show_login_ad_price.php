<?php include("../includes/connection.php");
adminSecure();
 
$fromDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+6,date("Y"))); 
$extra .= " and lap_date  >= '".$fromDate."'";
 
$count = 0;
$scount = 0;
if($_POST['lock_price']=='Lock Price')
{
	$sqlP = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_pstatus='u' and lap_status='Active'".$extra,"","lap_date asc");
	while($resP = mysqli_fetch_array($sqlP))
	{
		if($_POST['price_'.$resP['lap_id']]!='')
		{
			//$scount++;
			//if(is_int($_POST['price_'.$resP['lap_id']]))
			//{
				$dataU['lap_price'] = $obj->filter_mysql($_POST['price_'.$resP['lap_id']]);
				$obj->updateData(TABLE_LOGIN_AD_PACKAGE,$dataU,"lap_id='".$resP['lap_id']."'");
				$count++;
			//}
		}
	}
	if($count)
	{
		$_SESSION['messageClass'] = "successClass";
		$obj->add_message("message","Total ".$count." records updated successfully!");
	}
	else
	{
		$_SESSION['messageClass'] = "errorClass";
		$obj->add_message("message","No records updated!".$scount);
	}
}
 
if($_POST['setLSrate'] =='BaseRate')
{
		$arrAE['logad_base_rate'] = $obj->filter_mysql($_POST['logad_base_rate']);		 
		$obj->updateData(TABLE_SETTINGS,$arrAE,"set_id='1'");
		$obj->add_message("message","Login Ad Base Rate ".UPDATE_SUCCESSFULL);
		$_SESSION['messageClass'] = 'successClass';
		$obj->reDirect("show_login_ad_price.php");
}
$site_set=$obj->selectData(TABLE_SETTINGS,"logad_base_rate","set_id=1",1);

$sql=$obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_pstatus='u' and lap_status='Active'".$extra,2,"lap_date asc");
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
        <li class="active">Login Ad Package</li>
      </ul>
      
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Login Ad Package Management  <span class="pull-right"><a href="edit_login_ad_package.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Login Ad Package</a> </span>  </small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
			<div class="email_searchBox clearfix">
			<div class="row">
            	 <div class="col-md-4"> 
					<form action="" method="post">
					<div id="custom-search-input">
						<div class="col-md-12">
							<div class="form-inline">
								<div class="form-group" style="padding-right:15px;">
									<label style="font-weight:500; padding-right:15px;">Base Rate (USD)</label>
									<input type="text" name="logad_base_rate" id="logad_base_rate" class="form-control" required placeholder="50" value="<?=$site_set['logad_base_rate']?>" />
								</div>
								<button class="btn btn-info" name="setLSrate" value="BaseRate" type="submit">Update</button>								 
							</div>
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
  
  <div class="row">
  	<div class="col-md-12">
	<div class="row form-group text-left">
		<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-primary" id="abutt" onClick="set_button('a');">Automatic Price</a></div>
		<div class="col-md-2"><a href="javascript:void(0);" class="btn btn-primary" id="mbutt" onClick="set_button('m');">Manual Price</a></div>
	   <div class="col-md-2" id="lbuttp"> <a href="javascript:void(0);" class="btn" id="lbutt" onClick="set_lock_unlock();">Unlock Price</a></div>
	</div>
	<div class="form-inline">
	<div class="form-group" style="padding-right:15px;">
	<label style="font-weight:500; padding-right:15px;">General price change (USD)</label>
	 <input type="text" name="cprice" id="cprice" value="" size="6" disabled="disabled">
	 </div>
	  <input type="button" name="applyC" id="applyC" value="Apply" disabled="disabled" onClick="set_apply()">
	  <input type="hidden" name="btype" id="btype" value="a">
	  <input type="hidden" name="btype" id="ltype" value="l">	  
	
	</div>
	<br/>
	</div>
  </div>


    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>	  
        <tr>
			<th width="10%">Slot</th>    
			<th width="20%">Login Ad Day</th>  
			<th width="70%">Price ($)</th> 
        </tr>
      </thead>
      <tbody>
	   <?php
			$i=0;
			while($data=mysqli_fetch_assoc($res)){ 
			$class = $i%2==0?'odd':'';
			$i++;
			 
	   ?>
        <tr>         
			<td><?=$data['lap_id'];?></td>
			<td><?=$data['lap_date'];?></td>
			<td><input type="text" name="price_<?=$data['lap_id']?>" id="price_<?=$i?>" value="<?=$data['lap_price']?>" disabled="disabled"></td>
		 
		 
        </tr>
      <?php
		  }
	  ?>
        <tr>
          <td class="center" colspan="8"><div class="pagination"><?=$pg_obj->pageingHTML;?></div></td>
		 
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
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
 <link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
<script language="javascript">
function set_button(val)
{
	if(val=='m')
	{
		$('#btype').val('m');
		$('#lbutt').addClass('btn-primary');
		$('#applyC').removeAttr('disabled');
		$('#applyC').addClass('btn-primary')
		$('#cprice').removeAttr('disabled');
		
		for(var k=1;k<=14;k++)
		{
			if($('#price_'+k)) $('#price_'+k).removeAttr('disabled');
		}
	}
	else if(val=='a')
	{
		$('#btype').val('a');
		$('#lbutt').removeClass('btn-primary');
		$('#applyC').attr('disabled', 'disabled');
		$('#applyC').removeClass('btn-primary')
		$('#cprice').attr('disabled', 'disabled');
		for(var k=1;k<=14;k++)
		{
			if($('#price_'+k)) $('#price_'+k).attr('disabled', 'disabled');
		}
	}
}

function set_lock_unlock()
{
	if($('#ltype').val()=='l') 
	{
		$('#ltype').val('u'); 
		 $('#lbuttp').html( '<input type="submit" class="btn btn-primary" id="lbutt" name="lock_price" value="Lock Price">');
	}
	else
	{
		 $('#ltype').val('l'); 
		 $('#lbuttp').html( '<a href="javascript:void(0);" class="btn" id="lbutt" onClick="set_lock_unlock();">Unlock Price</a>');
		
	}
}

function set_apply()
{
	if($('#cprice').val()=='')
	{
		alert("Please enter a value to apply");
	}
	else if($('#ltype').val()=='l')
	{
		alert("Please unlock price before applying any change");
	}
	else
	{
		var pval = $('#cprice').val();
		if(isNaN(pval))
		{
			var sign  = pval.substring(0,1);
			var value = parseInt(pval.substring(1,pval.length));
		}
		else
		{
			var sign  = '+';
			var value = parseInt(pval);
		}
		
		for(var k=1;k<=14;k++)
		{
			if($('#price_'+k))
			{
				if(sign=='+') var nPrice =  parseInt($('#price_'+k).val()) + value ;
				else  var nPrice =  parseInt($('#price_'+k).val()) - value ;
				$('#price_'+k).val(nPrice);
				
			}
		}
	}
}
</script>
</body>
</html>
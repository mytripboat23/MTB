<?php include("../includes/connection.php");
adminSecure();
 
if($_REQUEST['search_date']!="")
{ 
	//$sqlME=$obj->selectData(TABLE_MEMBER_EARNING,"count(me_id) as totalE","me_created='".$_REQUEST['search_date']."'",1);	
	 // $totalEC = $sqlME['totalE'];
	 
	$extra .= "and wall_created ='".$obj->filter_mysql(addslashes(stripslashes($_REQUEST['search_date'])))."'";   
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
        <li class="active">Transaction Reports</li>
      </ul>
      <!-- /.breadcrumb -->
     
      <!-- /.nav-search --> 
    </div>
    <div class="page-content">

      
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i> Transaction Reports <?php /*?><span class="pull-right"><a href="edit_member.php?return_url=<?=urlencode($_SERVER['REQUEST_URI'])?>"><i class="fa fa-plus-square"></i> Add Member</a></span><?php */?></small> </h1>
        </div>
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
        
		<?php /*?>   <div class="email_searchBox clearfix">
			<div class="row">
            	 <div class="col-md-4">
					
					<form action="" method="get">
					<div id="custom-search-input">
						<div class="input-group col-md-10">
							<input type="text" name="search_date" id="search_date" class="form-control" placeholder="Select date" value="<?=$_REQUEST['search_date']?>" />
							<span class="input-group-btn">
								<button class="btn btn-info" type="submit">Search</button>
							</span>
						</div>
					</div>
					</form>
				</div>
				</div>
            </div>
			<?php */?>
			
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
  <div class="col-xs-12">
  <form name="form1" method="post" action="<?=$_SERVER['REQUEST_URI']?>" onSubmit="return validate_delete('chkSelect1')">
    <table id="sample-table-1" class="table table-striped table-bordered table-hover">
      <thead>	  
        <tr>           
          <th>Month, Year</th>
          <th>Ads Delivers</th>
		  <th>Staking figures.</th>
		  <th>Data For Xero</th>
        </tr>
      </thead>
      <tbody>
	   <?php
	   		$i=0;
			$curr_date = date("Y-m-d");
			$last_date =  date("Y-m-d",mktime(0,0,0,11,1,2018));
			while(strtotime($last_date)<=strtotime($curr_date))
			{
			$mon = date("m");
	   ?>
        <tr>          
		<td><?=date("F, Y",date(strtotime($curr_date)));?></td>	 
		<td><a onClick="javascript:void(0);" href="export_finance_details.php?dt=<?=date("m_Y",date(strtotime($curr_date)));?>" target="_blank">Export</a></td>
		<td><a onClick="javascript:void(0);" href="export_staking_figures.php?dt=<?=date("m_Y",date(strtotime($curr_date)));?>" target="_blank">Export</a></td>
		<td><a onClick="javascript:void(0);" href="export_xero_data.php?dt=<?=date("m_Y",date(strtotime($curr_date)));?>" target="_blank">Export</a></td>
       </tr>
      <?php
	  		  $i++;
	  		  $curr_date = date("Y-m-d",mktime(0,0,0,$mon-$i,12,date("Y")));
	      } 
	  ?>
        <tr>
          <td class="center" colspan="11"><div class="pagination"><? echo $pg_obj->pageingHTML;?></div></td>
		  
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
  
 
<script>
$(function() {
	$( "#search_date" ).datepicker({	 
		inline: true,
		dateFormat: 'yy-mm-dd',	 
		 maxDate: '-1',
		 
	}); 

});


function refresh_page()
{
	setTimeout("location.reload(true);",2000);
	 
}
 
</script>

 
</body>
</html>
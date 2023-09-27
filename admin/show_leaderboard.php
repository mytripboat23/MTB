<?php include("../includes/connection.php");
adminSecure();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("page_includes/common.php");?>
<style>
.leader_table .table>tbody>tr>td, .leader_table .table>tbody>tr>th, .leader_table .table>tfoot>tr>td, .leader_table .table>tfoot>tr>th, .leader_table .table>thead>tr>td, .leader_table .table>thead>tr>th{ border:none;font-size: 18px;
font-weight: 600; width: 50%;}
	

.leader_table_new .table>tbody>tr>td, .leader_table_new .table>tbody>tr>th, .leader_table_new .table>tfoot>tr>td, .leader_table_new .table>tfoot>tr>th, .leader_table_new .table>thead>tr>td{ border:none;font-size: 18px;
font-weight: 400; border-top: 1px solid #ddd; }
.leader_table_new .table>thead>tr>th{border:none; font-size: 18px;}
.leader_table_new .table>thead>tr{background:none; border:none;}
.leader_table_new .table>tbody>tr>td small{ display:block;color: #5c5c5c; font-size: 14px;}
.leader_table_new .table>tbody>tr>td.right_broder{ border-right:1px dotted #b9b8b8;}
.leader_table_new .table>tbody>tr>td.right_broder2{ border-right:1px dotted #b9b8b8; margin:20px;}
table.table.prizes_table {margin: 0; background: none; border: none;}
table.table.prizes_table tr td{ border:none; font-size:14px;}
.leader_table_new .table>tbody>tr>td.broder_right{border-right: 1px solid #ddd;}
.promotional_terms ul{ margin-left: 22px;}
.promotional_terms ul li{list-style-type:square; font-weight:400; color:#000; font-size:18px; padding:6px 0; line-height:26px;}
.tableU>tbody>tr td{font-size:18px;}
table.table.tableU {
    background: no-repeat;
    border: none; margin:0;
}
table.table.tableU tr td{border:none;}
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
        <li class="active">Dashboard</li>
      </ul>
      <!-- /.breadcrumb -->
      <!-- /.nav-search -->
    </div>
    <div class="page-content">
      <div class="page-content-area">
        <div class="page-header">
          <h1> Dashboard <small> <i class="ace-icon fa fa-angle-double-right"></i>Leaderboard</small> </h1>
        </div>
        <!-- /.page-header -->
        <div class="row">
          <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <?=$obj->display_message("message");?>
            <div class="hr hr32 hr-dotted"></div>
            <div class="row">
              <div class="col-md-12">
                <h1 class="page-header wallet_balen">Leaderboard</h1>
              </div>
			  
              <div class="col-lg-12">
                <div class="row">				
                  <div class="col-md-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3>Top Minters</h3>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive leader_table_new">
                          <table class="table table-bordered  table-striped">
                            <thead>
                              <tr>
                                <th colspan="2">Leaderboard </th>
                                <th>Prizes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
						$last= 0;
						$low = 0;
						$smallD = '';
						$sqlTM = $obj->selectData(TABLE_ORDER." as o, ".TABLE_ORDER_DETAILS." as od","o.user_id,count(od.pack_quan) as totalp","o.order_id=od.order_id and o.order_pstatus='p' and o.order_status='Active' and od.pack_id ='5' and od.od_status='Active'","","totalp DESC","o.user_id","0,15");
						$i = 1;		
						$j = 1;			
						while($dataTM = mysqli_fetch_assoc($sqlTM)) 
						{ 
						
						if($i==1) 
						{ 
							$j = $i;
							$smallD = " &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;( Congratulations! You are leading!)";
							$last = $dataTM['totalp'];
						
						}
						else if($i>1 && $i< 16) 
						{
							
							$low = $last - $dataTM['totalp'];
							if($i==2 && $low == 0)
							{
								$smallD = " &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;( Congratulations! You are leading!)";
								$j = $i-1;
							}
							else
							{
								$smallD = " &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;( ".$low." stakes required to advance to ".getOrdinal($i-1).")";	
								$j = $i;
							}
							$last = $dataTM['totalp'];
						}
						?>
                              <tr>
					  
                        <td width="45%"><?php if($i<11){?><img src="../img/Asimi_token.png" alt="asimi" width="40px"><?php }else{?><img src="../img/blank.png" alt="asimi" width="40px"><?php }?> &nbsp;&nbsp;<?=getOrdinal($j);?> place: <?=$obj->getUserName($dataTM['user_id']);?> <small>
						<?php						
						echo $smallD;						
						?>
						
						</small></td>
                        <td class="right_broder" width="5%"><?=$dataTM['totalp'];?></td>
						<td style="font-size:14px"><?=$obj->top_minters_all_prizes($j);?>  </td>
                      </tr>
                      <?php $i++;} ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
				  
                  <div class="col-md-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3>Top Affiliates</h3>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive leader_table_new">
                          <table class="table table-bordered  table-striped">
                            <thead>
                              <tr>
                                <th colspan="1">Leaderboard  </th>
                                <th colspan="1">Prizes</th>
                              </tr>
                            </thead>
                            <?php  
					$sqlU  = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","","ul.u_login_id=u.u_login_id and u.user_status='Active' and ul.u_login_status='Active' and u.user_tot_asv!=0","","user_tot_asv DESC","","0,15"); 
					?>
                            <tbody>
                              <?php 
					$a = 1;					
					while($dataU = mysqli_fetch_assoc($sqlU)) 
					{ 
					?>
                              <tr>
                                <td class="right_broder" width="50%"><table class="table tableU">
                                    <tbody>
                                      <tr>
                                        <td width="35%"><?=getOrdinal($a);?>
                                          place:</td>
                                        <td width="65%"><?=$obj->getUserName($dataU['u_login_id']);?>
                                          </td>
                                        <?php /*?><td width="20%"><?=$dataU['user_tot_asv'];?></td><?php */?>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="1"><small>
                                          <?php
									if($a==1) 
									{ 
										echo "( Congratulations! You are leading!)";
										$last = $dataU['user_tot_asv'];
									
									}
									else if($a>1 && $a< 16) 
									{
				
										$low = $last - $dataU['user_tot_asv'];
										echo "( $".$low." volume required to advance to ".getOrdinal($a-1).")";
										$last = $dataU['user_tot_asv'];
									}
									?>
                                          </small></td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                                <td class="broder_right"><table class="table prizes_table">
                                    <tbody>
                                      <tr>
                                        <?=$obj->top_affiliates_prizes($a);?>
                                        
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                              <?php $a++;} ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
				  <div class="col-md-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3>Affiliate Sales Contest - November 16th to December 15th 2019</h3>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive leader_table_new">
                          <table class="table table-bordered  table-striped">
                            <thead>
                              <tr>
                                <th></th>
                                <th colspan="2">Leaderboard  </th>
                                <th colspan="3">Prizes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
					     $i=1;
						 $last = 0;
						 $low = 0;
						 $sqlU  = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","u.u_login_id,u.user_totm_asv","ul.u_login_id=u.u_login_id and u.user_status='Active' and ul.u_login_status='Active'","","u.user_totm_asv DESC","","0,15"); 
						 while($resU = mysqli_fetch_array($sqlU))
						 {
					?>
                              <tr>
                                <td><?php if($i <= 3) { ?>
                                  <img src="../img/Asimi_token.png" alt="asimi" width="40px">
                                  <?php } ?>
                                </td>
                                <td style="width:13%"><?=getOrdinal($i);?>
                                  place:</td>
                                <td class="right_broder" style="width:30%"><?=$obj->getUserName($resU['u_login_id']);?>
                                  <small>
                                  <?php
						
						if($i==1) 
						{ 
							echo "( Congratulations! You are leading!)";
							$last = $resU['user_totm_asv'];
						
						}
						else if($i>1 && $i< 16) 
						{
	
							$low = $last - $resU['user_totm_asv'];
							echo "( $".$low." volume required to advance to ".getOrdinal($i-1).")";
							$last = $resU['user_totm_asv'];
						}
						
						?>
                                  </small> </td>
                                <td class="broder_right"><table class="table prizes_table">
                                    <tbody>
                                      <tr>
                                        <?=$obj->affiliate_sales_content_prizes($i);?>
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                              <?php
					  	$i++;}
					  ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
				  <div class="col-md-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h3>Minter Stakes Contest - November 16th to December 15th 2019</h3>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive leader_table_new">
                          <table class="table table-bordered  table-striped">
                            <thead>
                              <tr>
                                <th colspan="1">Leaderboard </th>
                                <th colspan="1">Prizes</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
					     $i=1;
						 $last = 0;
						 $low = 0;
						 $sqlU  = $obj->selectData(TABLE_USER_LOGIN." as ul, ".TABLE_USER." as u","u.u_login_id,u.user_totm_stake","ul.u_login_id=u.u_login_id and u.user_status='Active' and ul.u_login_status='Active' and u.user_totm_stake!='0'","","u.user_totm_stake DESC","","0,15"); 
						 while($resU = mysqli_fetch_array($sqlU))
						 {
					?>
                              <tr>
                                <td class="right_broder" width="35%"><table class="table tableU">
                                    <tbody>
                                      <tr>
                                        <td width="30%"> <?=getOrdinal($i);?>
                                          place:</td>
                                        <td><?=$obj->getUserName($resU['u_login_id']);?></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3"><small>
                                          <?php
									if($i==1) 
									{ 
										echo "( Congratulations! You are leading!)";
										$last = $resU['user_totm_stake'];
									
									}
									else if($i>1 && $i< 16) 
									{
				
										$low = $last - $resU['user_totm_stake'];
										echo "( ".$low." stakes required to advance to ".getOrdinal($i-1).")";
										$last = $resU['user_totm_stake'];
									}
									?>
                                          </small></td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                                <td class="broder_right"><table class="table prizes_table">
                                    <tbody>
                                      <tr>
                                        <?=$obj->ad_minter_stakes_contest_prizes($i);?>
                                        
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                              <?php
					  	$i++;}
					  ?>
                              <?php
					  for($j=$i;$j<=15;$j++)
					  {
					  ?>
                              <tr>
                                <td class="right_broder" width="35%"><table class="table tableU">
                                    <tbody>
                                      <tr>
                                        <td width="30%"><?=getOrdinal($j);?>
                                          place:</td>
                                        <td></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3"><small> </small></td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                                <td class="broder_right"><table class="table prizes_table">
                                    <tbody>
                                      <tr>
                                        <?=$obj->ad_minter_stakes_contest_prizes($j);?>
                                        
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                              <?php
					  }				  
					  ?>
                              <?php if(mysqli_num_rows($sqlU) =='') { ?>
                              <tr>
                                <td class="text-center">Record not found. </td>
                                <td></td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
				  <div class="col-md-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                       <h3>Purchase Multiplier Prizes</h3>
                      </div>
                      <div class="panel-body">
                        <div class="table-responsive leader_table_new">
                          <table class="table table-bordered  table-striped">
                            <thead>
                            </thead>
                            <tbody>
                              <tr>
                                <td width="15%">1st Place: </td>
                                <td>5 x Personal ad purchases / Minter Stakes</td>
                              </tr>
                              <tr>
                                <td>2nd place: </td>
                                <td>4 x Personal ad purchases / Minter Stakes</td>
                              </tr>
                              <tr>
                                <td>3rd Place: </td>
                                <td>3 x Personal ad purchases / Minter Stakes</td>
                              </tr>
                              <tr>
                                <td>4th place: </td>
                                <td>2 x Personal ad purchases / Minter Stakes</td>
                              </tr>
                              <tr>
                                <td>5th Place: </td>
                                <td>1 x Personal ad purchases / Minter Stakes</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  
				  <div class="col-md-12">
                    <div class="promotional_terms">
                      <ul>
						<li>Advertising percentage bonuses are redeemable in the winner's choosing (acombination of Ad Minter advertising, Banner Ad impressions and Login Ad Days is feasible)</li>
						<li>"Purchase Multiplier Prizes" cannot exceed 100,000 Asimi. In the event of multiplied winnings above 100,000, the maximum of 100,000 Asimi worth of prizes will be awarded.</li>
                      </ul>
                    </div>
                  </div>
				  
                </div>
              </div>
			  
            </div>
          </div>
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

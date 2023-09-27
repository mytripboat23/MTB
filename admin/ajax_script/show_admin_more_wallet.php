<?php
include("../../includes/connection.php"); 

$val   		= $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['value']));
$userId   	= $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['user_id']));
$start 		= ($val-1)*100;
$end   		= 1000;
// pre($_REQUEST); exit;
$sqlW=$obj->selectData(TABLE_USER_WALLET,"wall_id,order_id,ref_id,od_id,iaf_id,wref_id,wall_created,wall_type,wall_pstatus,wall_btc,wall_asimi,wall_wd_fee_asimi,wall_wd_fee_asimi","wall_status='Active' and wall_pstatus!='u' and user_id='".$userId."'","","wall_created desc, wall_id desc","",$start.",".$end);
 
			$i=1;
			while($data2=mysqli_fetch_assoc($sqlW)){
			$class = $i%2==0?'odd':'';
			$i++;
			$oD = array();
			if($data2['wall_type'] =='p')
			{
				$oD = $obj->selectData(TABLE_ORDER_DETAILS,"","order_id='".$data2['order_id']."'",1);
			}
			else if($data2['wall_type'] =='lap'){
				$oD = $obj->selectData(TABLE_ORDER_DETAILS,"","order_id='".$data2['order_id']."'",1);
				$lapD = $obj->selectData(TABLE_LOGIN_AD_PACKAGE,"","lap_id='".$oD['lap_id']."'",1);
				$aType = '';
				if($lapD['lap_assign'] =='m' ) $aType = ' - M';							 
			}
			else if($data2['wall_type'] =='lasp')
			{
				$oD = $obj->selectData(TABLE_ORDER_DETAILS,"","order_id='".$data2['order_id']."'",1);
				$laspD = $obj->selectData(TABLE_LOGIN_AD_STAKER_PACKAGE,"","lasp_id='".$oD['lasp_id']."'",1);
				$aType = '';
				if($laspD['lasp_assign'] =='m' ) $aType = ' - M';							 
			}
			else if($data2['wall_type'] =='a')
			{
				$oDA = $obj->selectData(TABLE_ORDER_DETAILS,"","order_id='".$data2['order_id']."'",1);
				$oDR = $obj->selectData(TABLE_USER_LOGIN,"u_login_user_email","u_login_id='".$oDA['user_id']."'",1);
			}
			$user_refD = $obj->selectData(TABLE_USER,"user_email","u_login_id='".$data2['ref_id']."' and user_status='Active'",1);
			$iafD 	= $obj->selectData(TABLE_INACTIVE_ACC_FEE,"","iaf_id='".$data2['iaf_id']."' and iaf_status='Active'",1);
			
			if($data2['wref_id']!='')
			{
				$surveyAD = $obj->selectData(TABLE_USER_WALLET,"user_id","wall_status='Active' and wall_pstatus='p' and wall_type in ('sb','psb') and wref_id='".$resW['wref_id']."'",1);
				$userD = $obj->selectData(TABLE_USER,"user_email","u_login_id='".$surveyAD['user_id']."' and user_status='Active'",1);
			}
					
			if($data2['wall_type'] =='ref') $oDetail = $obj->selectData(TABLE_ORDER_DETAILS,"","od_id='".$data2['od_id']."'",1);	 
?>
                  <tr> 
                   <td><?=date('M d, Y',strtotime($data2['wall_created']));?></td>
					<td><?php 						 
						if($data2['wall_type'] =='d') echo "Deposit";
						else if($data2['wall_type'] =='w' && $data2['wall_wd_fee'] =='0.00') echo "Withdraw";
						else if($data2['wall_type'] =='w' && $data2['wall_wd_fee'] !='0.00') echo "Withdrawal";						
						else if($data2['wall_type'] =='mw') echo "Manual withdrawal";
						else if($data2['wall_type'] =='p'){ if($oD['pack_id']==5){ echo "Staking"; }else { echo "Purchase";}}
						else if($data2['wall_type'] =='vep') echo "V2E Advertsing Purchase";							
						else if($data2['wall_type'] =='e') echo "Earning";
						else if($data2['wall_type'] =='me') echo "Minting Earning";
						else if($data2['wall_type'] =='nmae') echo "New Minting Affiliate Earning - <b>".$user_refD['user_email']."</b>";
						else if($data2['wall_type'] =='rs') echo "Stake Returned";
						else if($data2['wall_type'] =='r') echo "Admin Reward";
						else if($data2['wall_type'] =='j') echo "Join Reward";	
						else if($data2['wall_type'] =='a') { echo "Affiliate Earning - ".$oDA['pack_title']." - ".$oDR['u_login_user_email'];}
						else if($data2['wall_type'] =='rw') echo "Raffle Winner";
						else if($data2['wall_type'] =='rwa') echo "Raffle Winner (Affiliate)";
						else if($data2['wall_type'] =='ve') echo "V2E Earning";	
						else if($data2['wall_type'] =='vea') { if($data2['order_id']!=0) { echo "V2E Affiliate Commission - Ad Purchase"; }else { echo "V2E Affiliate Commission - Ad Viewing";}}  
						else if($data2['wall_type'] =='rsw') echo "Raffle Stake Winner";
						else if($data2['wall_type'] =='lsw') echo "Login Stake Winner";
						else if($data2['wall_type'] =='lrw') echo "Leaderboard Raffle Winner ";
						else if($data2['wall_type'] =='rbw') echo "Raffle Banner Winner";						
						else if($data2['wall_type'] =='btcp') echo "BTC payment conversion (".$data2['wall_btc']." BTC)";
						else if($data2['wall_type'] =='ethp') echo "ETH payment conversion (".$data2['wall_btc']." ETH)";
						else if($data2['wall_type'] =='wavesp') echo "WAVES payment conversion (".$data2['wall_btc']." WAVES)";
						else if($data2['wall_type'] =='ltcp') echo "LTC payment conversion (".$data2['wall_btc']." LTC)";
						else if($data2['wall_type'] =='lap') echo "Login Ad ".$lapD['lap_date'].$aType;
						else if($data2['wall_type'] =='laa') echo "Login Ad Affiliate Commission -".$lapD['lap_date']."";
						else if($data2['wall_type'] =='lasp') echo "Login Ad - All ".$laspD['lasp_date'].$aType;
						else if($data2['wall_type'] =='lasa') echo "Login Ad - Stakers Affiliate Commission -".$laspD['lasp_date']."";					
						else if($data2['wall_type'] =='cd')  echo "Commission denial - ".$user_refD['user_email']."";	
						else if($data2['wall_type'] =='cr')  echo "Commission reinstated - ".$user_refD['user_email']."";
						else if($data2['wall_type'] =='pbt') echo "Pre Launch Balance Transferred to <b>".$user_refD['user_email']."</b>";
					    else if($data2['wall_type'] =='pbr') echo "Pre Launch Balance Received from <b>".$user_refD['user_email']."</b>";
						else if($data2['wall_type'] =='btz') echo "Transfer for Zendesk Ticket #".$data2['wref_id'];
					    else if($data2['wall_type'] =='brz') echo "Received for Zendesk Ticket #".$data2['wref_id'];
						else if($data2['wall_type'] =='ref' && $data2['od_id'] !='0') echo "Refund Order #".(10000+$data2['order_id'])." - <b>".$oDetail['pack_title']."</b>";
						else if($data2['wall_type'] =='ref' && $data2['od_id'] =='0') echo "Refund Order #".(10000+$data2['order_id']);						
						else if($data2['wall_type'] =='ls')  echo "Login Stake setup ";	
						else if($data2['wall_type'] =='lus') echo "Login Stake return";
						else if($data2['wall_type'] =='spr') echo "Unpaid Return Of Stake";	
						else if($data2['wall_type'] =='lsr') echo "Login Stake earning";
						
						else if($data2['wall_type'] =='bsp') echo "Bonus Asimi Tokens";	
						else if($data2['wall_type'] =='spbc') echo "Starter Package Basic Commission";	
						else if($data2['wall_type'] =='sppc') echo "Starter Package Pro Commission";	
						else if($data2['wall_type'] =='spb') echo "Purchase Starter Package Basic";	
						else if($data2['wall_type'] =='spp') echo "Purchase Starter Package Pro";	
						
						else if($data2['wall_type'] =='ref' && $data2['od_id'] !='0') echo "Refund Order #".(10000+$data2['order_id'])." - <b>".$oDetail['pack_title']."</b>";
						else if($data2['wall_type'] =='ref' && $data2['od_id'] =='0') echo "Refund Order #".(10000+$data2['order_id']);
						else if($data2['wall_type'] =='spr')  echo "Unpaid Return Of Stake";	
						
						else if($data2['wall_type'] =='kyc') echo "KYC Verification Fee";	
						else if($data2['wall_type'] =='kvfa') echo "KYC Verification fee adjustment";
						else if($data2['wall_type'] =='iaf')	 echo "Inactive Account Fee - ".date("F Y", strtotime($iafD['iaf_amt_deduct_date']))." - ".$iafD['iaf_fee']." Asimi - Last login: ".date("d F Y", strtotime($iafD['iaf_last_login_date']));					 
						else if($data2['wall_type'] =='sb') 	echo "Survey Earning  # ".$data2['wref_id'];
						else if($data2['wall_type'] =='sba') 	echo "Affiliate Survey Earning  # ".$data2['wref_id'].' - '.$userD['user_email'];
						else if($data2['wall_type'] =='psb') 	echo "Pollfish Survey Earning  # ".$data2['wref_id'];
						else if($data2['wall_type'] =='psba') 	echo "Affiliate Pollfish Survey Earning  # ".$data2['wref_id'].' - '.$userD['user_email'];
						else if($data2['wall_type'] =='dmp') 	echo "Minting Raffle Prize - <b>".date('M d, Y',strtotime($data2['wall_created']))."</b>";
						if($data2['wall_pstatus'] =='d') echo "  ( Denied )";	
  
					?>
					 
					</td>
                    <td align="right">
					<?php 
						$k = 1;
					if($data2['wall_type'] =='d') 			$k = 1;					
					else if($data2['wall_type'] =='laa') 	$k = 1;
					else if($data2['wall_type'] =='e') 		$k = 1;
					else if($data2['wall_type'] =='me') 	$k = 1;
					else if($data2['wall_type'] =='rs') 	$k = 1;
					else if($data2['wall_type'] =='r') 		$k = 1;
					else if($data2['wall_type'] =='j') 		$k = 1;
					else if($data2['wall_type'] =='a') 		$k = 1;
					else if($data2['wall_type'] =='rw') 	$k = 1;
					else if($data2['wall_type'] =='rwa') 	$k = 1;
					else if($data2['wall_type'] =='ve') 	$k = 1;
					else if($data2['wall_type'] =='vea') 	$k = 1;
					else if($data2['wall_type'] =='rsw') 	$k = 1;
					else if($data2['wall_type'] =='rbw') 	$k = 1;
					else if($data2['wall_type'] =='btcp') 	$k = 1;
					else if($data2['wall_type'] =='ethp') 	$k = 1;	
					else if($data2['wall_type'] =='wavesp') $k = 1;	
					else if($data2['wall_type'] =='ltcp') 	$k = 1;				
					else if($data2['wall_type'] =='lus') 	$k = 1;
					else if($data2['wall_type'] =='lsr') 	$k = 1;
					else if($data2['wall_type'] =='ref') 	$k = 1;
					else if($data2['wall_type'] =='spr') 	$k = 1;
					else if($data2['wall_type'] =='cr') 	$k = 1;	
					else if($data2['wall_type'] =='pbr') 	$k = 1;					
					else if($data2['wall_type'] =='brz') 	$k = 1;										
					else if($data2['wall_type'] =='lsw') 	$k = 1;									
					else if($data2['wall_type'] =='kvfa') 	$k = 1;
					else if($data2['wall_type'] =='lrw') 	$k = 1;	
					else if($data2['wall_type'] =='lasa') 	$k = 1;	
					else if($data2['wall_type'] =='sb') 	$k = 1;	
					else if($data2['wall_type'] =='sba') 	$k = 1;	
					else if($data2['wall_type'] =='psb') 	$k = 1;	
					else if($data2['wall_type'] =='psba') 	$k = 1;							
					else if($data2['wall_type'] =='nmae')   $k = 1;						
					else if($data2['wall_type'] =='dmp')   	$k = 1;
					
					
					else if($data2['wall_type'] =='bsp')   	$k = 1;			
					else if($data2['wall_type'] =='spbc')   $k = 1;			
					else if($data2['wall_type'] =='sppc')   $k = 1;
					else if($data2['wall_type'] =='w') 		$k = -1;
					else if($data2['wall_type'] =='p') 		$k = -1;
					else if($data2['wall_type'] =='vep') 	$k = -1;
					else if($data2['wall_type'] =='lap') 	$k = -1;
					else if($data2['wall_type'] =='cd') 	$k = -1;
					else if($data2['wall_type'] =='pbt') 	$k = -1;
					else if($data2['wall_type'] =='btz') 	$k = -1;
					else if($data2['wall_type'] =='ls') 	$k = -1;
					else if($data2['wall_type'] =='kyc') 	$k = -1;
					else if($data2['wall_type'] =='iaf') 	$k = -1; 
					else if($data2['wall_type'] =='lasp') 	$k = -1;
					
					if($data2['wall_type'] =='w') echo number_format(($data2['wall_asimi']-$data2['wall_wd_fee_asimi'])*$k, 8);
					else echo number_format($data2['wall_asimi']*$k, 8);				
					?>
					</td>
                    
                  </tr>
				   <?php if($data2['wall_type'] =='w' && $data2['wall_wd_fee'] !='0.00') { $k=-1;?>
					<tr> 
                     <td><?=date('M d, Y',strtotime($data2['wall_created']));?></td>
					<td><?php if($data2['wall_type'] =='w' && $data2['wall_wd_fee'] !='0.00') echo "Withdrawal fee"; ?> </td>
                    <td align="right"><?=number_format($data2['wall_wd_fee_asimi']*$k, 8);?></td>
                   
                  </tr>
				  <?php } ?>				  
				  
                  <?php } ?>
				  <?php if($i>1){?>
				  <tr id="tr_<?=$val+1;?>">				  	
					<td colspan="3" align="right"><a href="javascript:void(0);" onClick="show_more('<?=$val+1;?>','<?=$userId?>')">Next 100 transactions >> </a></td>
				  </tr>
				  <?php }else{?>
				  <tr id="tr_<?=$val+1;?>">				  	
					<td colspan="3" align="center"><strong>All records are displayed above!</strong></td>
				  </tr>
				  <?php }?>
<?php				  
$obj->close_mysql();
?>
<?php
include("../../includes/connection.php"); 

$val   = $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['value']));
$userId   = $obj->filter_mysql(preg_replace('/[^0-9]/', '', $_REQUEST['user_id']));
$start = ($val-1)*100;
$end   = 100;

$sqlW=$obj->selectData(TABLE_USER_MWALLET,"","mw_status='Active' and mw_pstatus='p' and user_id='".$userId."'","","mw_added desc, mw_id desc","",$start.",".$end);

			$i=1;
			while($data2=mysqli_fetch_assoc($sqlW)){
			$class = $i%2==0?'odd':'';
			$i++;
	 
?>
                  <tr> 
                   <td><?=date('M d, Y',strtotime($data2['mw_added']));?></td>
					<td><?php if($data2['mw_type'] =='d'){?>Deposit<?php }else{?>Minting Payment Fees - <?=date('d-m-Y',strtotime($data2['mw_added']));?><?php }?></td>
                    <td align="right">
					<?php
					$k = 1;
					if($resW['mw_type'] =='d') 			    $k = 1;					
					else if($resW['mw_type'] =='p') 		$k = -1;
					echo number_format($resW['mw_wave']*$k,8);	
					?>
					</td>
                    
                  </tr>
                  <?php } ?>
				  <?php if($i>1){?>
				  <tr id="tr_<?=$val+1;?>">				  	
					<td colspan="3" align="right"><a href="javascript:void(0);" onClick="show_more_waves('<?=$val+1;?>','<?=$userId?>')">Next 100 transactions >> </a></td>
				  </tr>
				  <? }else{?>
				  <tr id="tr_<?=$val+1;?>">				  	
					<td colspan="3" align="center"><strong>All records are displayed above!</strong></td>
				  </tr>
				  <? }?>
<?php				  
$obj->close_mysql();
?>
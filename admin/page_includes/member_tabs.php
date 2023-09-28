<div class="member_button">
<ul class="nav nav-tabs nav-justified">
	<li <?php if($curPage=='view_member.php'){?>class="active"<?php }?>><a href="view_member.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>" class="btn btn-info">View Details</a></li>
	<li <?php if($curPage=='edit_member.php'){?>class="active"<?php }?>><a href="edit_member.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>" class="btn btn-info">Edit Details</a></li>
	<li <?php if($curPage=='member_purchase.php'){?>class="active"<?php }?>><a href="member_purchase.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>" class="btn btn-info">Purchase Details</a></li>
	
	<li <?php if($curPage=='member_wallet_transaction.php'){?>class="active"<?php }?>><a href="member_wallet_transaction.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Wallet Transactions</a></li>
	
	<?php /* ?>
	<li <?php if($curPage=='member_deposit.php'){?>class="active"<?php }?>><a href="member_deposit.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Deposit</a></li>
	<li <?php if($curPage=='member_withdrawal.php'){?>class="active"<?php }?>><a href="member_withdrawal.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Withdrwal</a></li>
	<li <?php if($curPage=='member_wallet_address.php'){?>class="active"<?php }?>><a href="member_wallet_address.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Wallets</a></li>
	<?php */ ?>
	
	<li <?php if($curPage=='member_campaigns.php'){?>class="active"<?php }?>><a href="member_campaigns.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Campaigns</a></li>
	<li <?php if($curPage=='member_earning.php'){?>class="active"<?php }?>><a href="member_earning.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Earning</a></li>
	<li <?php if($curPage=='member_login_stake.php'){?>class="active"<?php }?>><a href="member_login_stake.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Login Stake</a></li>
	<li <?php if($curPage=='member_minting_package.php'){?>class="active"<?php }?>><a href="member_minting_package.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Minting Package</a></li>
	 
	<?php $checkbecomaff = $obj->selectData(TABLE_BECOME_AFFILIATE,"user_id","user_id='".$obj->filter_numeric($_REQUEST['uId'])."' and baff_status='Active'",1); ?>
	<?php if($checkbecomaff['user_id'] !='') { ?>
	<li <?php if($curPage=='member_referrals.php'){?>class="active"<?php }?>><a href="member_referrals.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Referrals</a></li>
	<?php } ?>
	<li <?php if($curPage=='member_transaction_history.php'){?>class="active"<?php }?>><a href="member_transaction_history.php?uId=<?=$obj->filter_numeric($_REQUEST['uId']);?>"class="btn btn-info">Transaction History</a></li>
</ul>
</div>
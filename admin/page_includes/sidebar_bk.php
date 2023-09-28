<script type="text/javascript">
try{ace.settings.check('main-container' , 'fixed')}catch(e){}
</script>

<div id="sidebar" class="sidebar responsive">
  <script type="text/javascript">
try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
</script>
  <!-- /.sidebar-shortcuts -->
  <ul class="nav nav-list">
    <li <?php if($curPage=='dashboard.php'){?>class="active"<?php }?>> <a href="dashboard.php"><i class="menu-icon fa fa-tachometer"></i> <span class="menu-text"> Dashboard </span> </a> <b class="arrow"></b> </li>
    <?php   if($_SESSION['admin']['admin_type'] == 'm') { ?>
    <li <?php if($curPage=='show_sub_admin.php' || $curPage=='edit_sub_admin.php'){?>class="active"<?php }?>> <a href="show_sub_admin.php"> <i class="menu-icon fa fa-user-plus"></i> <span class="menu-text"> Sub Admin </span> </a> <b class="arrow"></b> </li>
    <li <?php if($curPage=='show_sub_admin_pages.php' || $curPage=='edit_sub_admin_pages.php'){?>class="active"<?php }?>> <a href="show_sub_admin_pages.php"> <i class="menu-icon fa fa-file-text"></i> <span class="menu-text"> Page Permission </span> </a> <b class="arrow"></b> </li>
    <li <?php if($curPage=='show_referred_by.php' || $curPage=='edit_referred_by.php'){?>class="active"<?php }?>> <a href="show_referred_by.php"> <i class="menu-icon fa fa-exchange"></i> <span class="menu-text"> Referred by </span> </a> <b class="arrow"></b> </li>
    <?php }  ?>
	
	<?php 
		$risk_disclaimerA 	=  $obj->checkPagePermission('show_risk_disclaimer.php'); 
		$risk_disclaimerB 	=  $obj->checkPagePermission('edit_risk_disclaimer.php');	
		$privacyPolicyA 	=  $obj->checkPagePermission('show_privacy_policy_contents.php'); 
		$privacyPolicyB 	=  $obj->checkPagePermission('edit_privacy_policy_content.php'); 
		$showtermncond 		=  $obj->checkPagePermission('show_term_condition.php');
		$showafftermncond 	=  $obj->checkPagePermission('show_affiliate_term_condition.php');	
	?>
	
	 
	
	 <li <?php if($curPage=='show_staking_agrmt.php' || $curPage=='edit_staking_agrmt.php'|| $curPage=='show_adp_service_agrmt.php'|| $curPage=='edit_adp_service_agrmt.php'|| $curPage=='show_risk_disclaimer.php' || $curPage=='edit_risk_disclaimer.php' || $curPage=='show_privacy_policy_contents.php' || $curPage=='edit_privacy_policy_content.php' || $curPage=='show_term_condition.php' || $curPage=='show_affiliate_term_condition.php' || $curPage=='edit_term_condition.php' || $curPage=='edit_affiliate_term_condition.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Static Text Changes </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_staking_agrmt.php' || $curPage=='edit_staking_agrmt.php'){?>class="active"<?php }?>> <a href="show_staking_agrmt.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Staking Agreement </span> </a> <b class="arrow"></b> </li> 
		
        <li <?php if($curPage=='show_adp_service_agrmt.php' || $curPage=='edit_adp_service_agrmt.php'){?>class="active"<?php }?>> <a href="show_adp_service_agrmt.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Ad Purchase </span> </a> <b class="arrow"></b> </li>  
		
		<?php  if($risk_disclaimerA == 1 || $risk_disclaimerB ==1) { ?>
			<li <?php if($curPage=='show_risk_disclaimer.php' || $curPage=='edit_risk_disclaimer.php'){?>class="active"<?php }?>> <a href="show_risk_disclaimer.php"> <i class="menu-icon fa fa-user-secret"></i> <span class="menu-text"> Risk Disclaimer </span> </a> <b class="arrow"></b> </li>
		<?php } ?>	
		
		<?php if($privacyPolicyA == 1 || $privacyPolicyB ==1) { ?>
			<li <?php if($curPage=='show_privacy_policy_contents.php' || $curPage=='edit_privacy_policy_content.php'){?>class="active"<?php }?>> <a href="show_privacy_policy_contents.php"> <i class="menu-icon fa fa-unlock-alt"></i> <span class="menu-text"> Privacy Policy </span> </a> <b class="arrow"></b> </li>
		<?php } ?>
		<?php if($showtermncond == 1) {  ?>
		<li <?php if($curPage=='show_term_condition.php' || $curPage=='edit_term_condition.php'){?>class="active"<?php }?>> <a href="show_term_condition.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Term & Condition </span> </a> <b class="arrow"></b> </li>
		<?php } ?>
		<?php if($showafftermncond == 1) {  ?>
        <li <?php if($curPage=='show_affiliate_term_condition.php' || $curPage=='edit_affiliate_term_condition.php'){?>class="active"<?php }?>> <a href="show_affiliate_term_condition.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Affiliate T&C </span> </a> <b class="arrow"></b> </li>
		<?php } ?>
		  
       
      </ul>
    </li>
	 
	 
    <?php  $show_content =  $obj->checkPagePermission('show_contents.php'); ?>
    <?php if($show_content == 1) { ?>
    <li <?php if($curPage=='show_contents.php' || $curPage=='edit_content.php' || $curPage=='show_customer_contents.php' || $curPage=='edit_customer_content.php' || $curPage=='show_mining_contents.php' || $curPage=='edit_mining_content.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Home </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_contents.php' || $curPage=='edit_content.php'){?>class="active"<?php }?>> <a href="show_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Home Advertise </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_customer_contents.php' || $curPage=='edit_customer_content.php'){?>class="active"<?php }?>> <a href="show_customer_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Home Customer</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_mining_contents.php' || $curPage=='edit_mining_content.php'){?>class="active"<?php }?>> <a href="show_mining_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Home Mining </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
	
    <?php 
	$about_usA =  $obj->checkPagePermission('show_about_us.php'); 
	$about_usB =  $obj->checkPagePermission('show_about_us_contents.php'); 	
	if($about_usA == 1 || $about_usB ==1) { 
	?>
    <li <?php if($curPage=='show_about_us_contents.php' || $curPage=='edit_about_us_content.php' || $curPage=='show_about_us.php' || $curPage=='edit_about_us.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> About Us </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_about_us_contents.php' || $curPage=='edit_about_us_content.php'){?>class="active"<?php }?>> <a href="show_about_us_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> About Us CMS</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_about_us.php' || $curPage=='edit_about_us.php'){?>class="active"<?php }?>> <a href="show_about_us.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> About Us </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
	
	
	
    <?php   if($_SESSION['admin']['admin_type'] == 'm') { ?>
    <li <?php if($curPage=='show_email_facility.php' || $curPage=='edit_email_facility.php' || $curPage=='show_send_email.php' || $curPage=='edit_send_email.php'){?>class="active"<?php }?>> 
		<a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Internal Email </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_email_facility.php' || $curPage=='edit_email_facility.php'){?>class="active"<?php }?>> <a href="show_email_facility.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Email Format </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_send_email.php' || $curPage=='edit_send_email.php'){?>class="active"<?php }?>> <a href="show_send_email.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Send Email </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
	
	
	
<?php  
$ServiceA =  $obj->checkPagePermission('show_services_contents.php'); 
$ServiceB =  $obj->checkPagePermission('edit_services_content.php'); 

if($ServiceA == 1 || $ServiceB ==1) { 
?>
    <li <?php if($curPage=='show_services_contents.php' || $curPage=='edit_services_content.php' || $curPage=='show_services_head_contents.php' || $curPage=='edit_services_head_content.php'|| $curPage=='show_services_adminer_contents.php'|| $curPage=='edit_services_adminer_content.php'|| $curPage=='show_services_loginad_contents.php'|| $curPage=='edit_services_loginad_content.php'|| $curPage=='show_services_banners_contents.php'|| $curPage=='edit_services_bannerd_content.php'|| $curPage=='show_services_tracking_contents.php'|| $curPage=='edit_services_tracking_content.php' || $curPage=='show_services_expert_contents.php' || $curPage=='edit_services_expert_content.php' || $curPage=='show_services_earncrypto_contents.php'|| $curPage=='edit_services_earncrypto_content.php' || $curPage=='show_services_anymine_contents.php'|| $curPage=='edit_services_anymine_content.php'|| $curPage=='show_services_nolimit_contents.php'|| $curPage=='edit_services_nolimit_content.php'|| $curPage=='show_services_finance_contents.php'|| $curPage=='edit_services_finance_content.php'|| $curPage=='show_services_strtfree_contents.php'|| $curPage=='edit_services_strtfree_content.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-server"></i> <span class="menu-text"> Services </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_services_head_contents.php' || $curPage=='edit_services_head_content.php'){?>class="active"<?php }?>> <a href="show_services_head_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Service Head </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_contents.php' || $curPage=='edit_services_content.php'){?>class="active"<?php }?>> <a href="show_services_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Fast and Simple </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_adminer_contents.php' || $curPage=='edit_services_adminer_content.php'){?>class="active"<?php }?>> <a href="show_services_adminer_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Ad Minter </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_loginad_contents.php' || $curPage=='edit_services_loginad_content.php'){?>class="active"<?php }?>> <a href="show_services_loginad_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Login Ads </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_banners_contents.php' || $curPage=='edit_services_bannerd_content.php'){?>class="active"<?php }?>> <a href="show_services_banners_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Banners </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_tracking_contents.php' || $curPage=='edit_services_tracking_content.php'){?>class="active"<?php }?>> <a href="show_services_tracking_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Tracking </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_expert_contents.php' || $curPage=='edit_services_expert_content.php'){?>class="active"<?php }?>> <a href="show_services_expert_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Be an expert </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_earncrypto_contents.php' || $curPage=='edit_services_earncrypto_content.php'){?>class="active"<?php }?>> <a href="show_services_earncrypto_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Earn Crypto </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_anymine_contents.php' || $curPage=='edit_services_anymine_content.php'){?>class="active"<?php }?>> <a href="show_services_anymine_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Any One Mint </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_nolimit_contents.php' || $curPage=='edit_services_nolimit_content.php'){?>class="active"<?php }?>> <a href="show_services_nolimit_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">No Limits </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_finance_contents.php' || $curPage=='edit_services_finance_content.php'){?>class="active"<?php }?>> <a href="show_services_finance_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Control your finances</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_services_strtfree_contents.php' || $curPage=='edit_services_strtfree_content.php'){?>class="active"<?php }?>> <a href="show_services_strtfree_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Get Start Free</span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
<?php } ?>
	
    <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
    <li <?php if($curPage=='show_members.php'){?>class="active"<?php }?>> <a href="show_members.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Members </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
	
	
	<li <?php if($curPage=='kyc_approve_deny.php' || $curPage=='kyc_verified.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">KYC Verification  </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
		<ul class="submenu">		
			<li <?php if($curPage=='kyc_approve_deny.php'){?>class="active"<?php }?>> <a href="kyc_approve_deny.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Approve/Deny </span> </a> <b class="arrow"></b> </li>
			<li <?php if($curPage=='kyc_verified.php'){?>class="active"<?php }?>> <a href="kyc_verified.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Verified </span> </a> <b class="arrow"></b> </li>
			<li <?php if($curPage=='show_kyc_vrification_fee_download.php'){?>class="active"<?php }?>> <a href="show_kyc_vrification_fee_download.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> KYC Fees </span> </a> <b class="arrow"></b> </li>
		</ul>
	</li> 
	
	
	
	
	
    <?php  
	$show_manual_payment =  $obj->checkPagePermission('show_manual_payment.php');  
	if($show_manual_payment == 1) { 
	?>
    <li <?php if($curPage=='show_manual_payment.php' || $curPage=='edit_manual_payment.php'){?>class="active"<?php }?>> <a href="show_manual_payment.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Manual Payment </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$aff_reg =  $obj->checkPagePermission('show_affiliate_registration.php');  
	if($aff_reg == 1) { 
	?>
    <li <?php if($curPage=='show_affiliate_registration.php'){?>class="active"<?php }?>> <a href="show_affiliate_registration.php"> <i class="menu-icon fa fa-book"></i> <span class="menu-text"> Affiliate Reg. (Paid) </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$packageA =  $obj->checkPagePermission('show_package.php');  
	if($packageA == 1) { 
	?>
    <li <?php if($curPage=='show_package.php' || $curPage=='edit_package.php'){?>class="active"<?php }?>> <a href="show_package.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Package Subs... </span> </a> <b class="arrow"></b> </li>
	<?php /* ?>
	<li <?php if($curPage=='show_package.php' || $curPage=='edit_package.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Package Subs... </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
		<ul class="submenu">		
			<li <?php if($curPage=='show_package.php' || $curPage=='edit_package.php'){?>class="active"<?php }?>> <a href="show_package.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Package Subs... </span> </a> <b class="arrow"></b> </li>
			
				---- <li <?php if($curPage=='set_ads_settings.php'){?>class="active"<?php }?>> <a href="set_ads_settings.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Ads Settings </span> </a> <b class="arrow"></b> </li> -----------
		</ul>
	</li> 
	<?php */ ?>
    <?php } ?>
    <?php  
	$orderA =  $obj->checkPagePermission('show_order.php');  
	if($orderA == 1) { 
	?>
    <li <?php if($curPage=='show_order.php'){?>class="active"<?php }?>> <a href="show_order.php"> <i class="menu-icon fa fa-shopping-cart"></i> <span class="menu-text"> Package Subscribed </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$orderAdvPro =  $obj->checkPagePermission('show_adv_product_order.php');  
	if($orderAdvPro == 1) { 
	?>
    <li <?php if($curPage=='show_adv_product_order.php'){?>class="active"<?php }?>> <a href="show_adv_product_order.php"> <i class="menu-icon fa fa-shopping-cart"></i> <span class="menu-text"> Advertsing Product </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$bannerAds =  $obj->checkPagePermission('show_banner_ads.php');  
	if($bannerAds == 1) { 
	?>
    <li <?php if($curPage=='show_banner_ads.php' || $curPage=='edit_banner_ads.php'){?>class="active"<?php }?>> <a href="show_banner_ads.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Banner Ads </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    
	<?php  
	$show_geo_targeting =  $obj->checkPagePermission('show_geo_targeting.php');  
	if($show_geo_targeting == 1) { 
	?>
	<li <?php if($curPage=='show_geo_targeting.php' || $curPage=='edit_group_countries.php'){?>class="active"<?php }?>> <a href="show_geo_targeting.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> GEO-Targeting </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
	 
    <?php  
	$AdsdirectoryA =  $obj->checkPagePermission('show_ad_directory_listing.php');  
	if($AdsdirectoryA == 1) { 
	?>
    <li <?php if($curPage=='show_ad_directory_listing.php' || $curPage=='edit_ad_directory_listing.php'){?>class="active"<?php }?>> <a href="show_ad_directory_listing.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Minting Ads</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$AdsdirectoryP =  $obj->checkPagePermission('show_pending_ad_directory_listing.php');  
	if($AdsdirectoryP == 1) { 
	?>
    <li <?php if($curPage=='show_pending_ad_directory_listing.php'){?>class="active"<?php }?>> <a href="show_pending_ad_directory_listing.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Pending Ads</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$bannerAds =  $obj->checkPagePermission('show_pending_banner_ad_listing.php');  
	if($bannerAds == 1) { 
	?>
    <li <?php if($curPage=='show_pending_banner_ad_listing.php'){?>class="active"<?php }?>> <a href="show_pending_banner_ad_listing.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Pending Banner Ads</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$show_login_ad_package =  $obj->checkPagePermission('show_login_ad_package.php'); 
	$show_login_ad_listing =  $obj->checkPagePermission('show_login_ad_listing.php');  
	$show_login_ad_price =  $obj->checkPagePermission('show_login_ad_price.php'); 
	$show_manual_login_ad =  $obj->checkPagePermission('show_manual_login_ad.php'); 
	$show_manual_ass_login_ad =  $obj->checkPagePermission('show_manual_assign_login_ad.php'); 
	$show_login_token_burn =  $obj->checkPagePermission('show_login_token_burn.php');  
	 
	if($show_login_ad_package == 1 || $show_login_ad_listing ==1 || $show_login_ad_price ==1 || $show_manual_login_ad ==1 || $show_manual_ass_login_ad ==1 || $show_login_token_burn ==1) { 
	?>
    <li <?php if($curPage=='show_login_ad_package.php' || $curPage=='edit_login_ad_package.php' || $curPage=='show_login_ad_listing.php' || $curPage=='edit_login_ad_listing.php' || $curPage=='show_login_ad_price.php' || $curPage=='show_manual_login_ad.php' || $curPage=='login_token_burn.php' || $curPage=='show_manual_assign_login_ad.php' || $curPage=='show_login_token_burn.php' || $curPage=='edit_manual_assign_login_ad.php' || $curPage=='show_default_login_ad.php'|| $curPage=='show_today_login_ad.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Login Ads </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_login_ad_package.php' || $curPage=='edit_login_ad_package.php'){?>class="active"<?php }?>> <a href="show_login_ad_package.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Login Ad Package</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_login_ad_listing.php' || $curPage=='edit_login_ad_listing.php'){?>class="active"<?php }?>> <a href="show_login_ad_listing.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Login Ad Listing </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_login_ad_price.php'){?>class="active"<?php }?>> <a href="show_login_ad_price.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Login Ad Price </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_manual_login_ad.php' || $curPage=='manual_assign_login_ad.php'){?>class="active"<?php }?>> <a href="show_manual_login_ad.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Manual Login Ad </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_manual_assign_login_ad.php' || $curPage=='edit_manual_assign_login_ad.php'){?>class="active"<?php }?>> <a href="show_manual_assign_login_ad.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Assign Manual Login Ad </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_login_token_burn.php'){?>class="active"<?php }?>> <a href="show_login_token_burn.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Login Token Burn </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_default_login_ad.php'){?>class="active"<?php }?>> <a href="show_default_login_ad.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Default Login Ad </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_today_login_ad.php'){?>class="active"<?php }?>> <a href="show_today_login_ad.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Today Login Ad </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php /*?><li <?php if($curPage=='show_login_ad_package.php' || $curPage=='edit_login_ad_package.php'){?>class="active"<?php }?>> <a href="show_login_ad_package.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Login Ad Package </span> </a> <b class="arrow"></b> </li>
	
	<?php  
	$loginAdDirect =  $obj->checkPagePermission('show_login_ad_listing.php');  
	if($loginAdDirect == 1) { 
	?>	
	<li <?php if($curPage=='show_login_ad_listing.php' || $curPage=='edit_login_ad_listing.php'){?>class="active"<?php }?>> <a href="show_login_ad_listing.php"> <i class="menu-icon fa fa-picture-o"></i> <span class="menu-text"> Login Ad Listing </span> </a> <b class="arrow"></b> </li>
	<?php } ?><?php */?>
    <?php  
	$videoCatA =  $obj->checkPagePermission('show_video_categories.php'); 
	$videoB =  $obj->checkPagePermission('show_videos.php'); 

	if($videoCatA == 1 || $videoB ==1) { 
	?>
    <li <?php if($curPage=='edit_video.php' || $curPage=='edit_video_category.php' || $curPage=='show_video_categories.php'|| $curPage=='show_videos.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-video-camera"></i> <span class="menu-text"> Video Training </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_video_categories.php' || $curPage=='edit_video_category.php'){?>class="active"<?php }?>> <a href="show_video_categories.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Category </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_videos.php' || $curPage=='edit_video.php'){?>class="active"<?php }?>> <a href="show_videos.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Videos </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php 
/*	
	$bannerAdViewer =  $obj->checkPagePermission('show_banner_ads_viewer.php');  
	if($bannerAdViewer == 1) { 
	?>	
	<li <?php if($curPage=='show_banner_ads_viewer.php' || $curPage=='edit_banner_ads_viewer.php'){?>class="active"<?php }?>> <a href="show_banner_ads_viewer.php"> <i class="menu-icon fa fa-eye"></i> <span class="menu-text"> Banner Ads View </span> </a> <b class="arrow"></b> </li>
	<?php } ?>
	
	<?php  
	$AdsdirectoryViewer =  $obj->checkPagePermission('show_ads_directory_viewer.php');  
	if($AdsdirectoryViewer == 1) { 
	?>	
	<li <?php if($curPage=='show_ads_directory_viewer.php' || $curPage=='edit_ads_directory_viewer.php'){?>class="active"<?php }?>> <a href="show_ads_directory_viewer.php"> <i class="menu-icon fa fa-eye"></i> <span class="menu-text"> Ad Directory View </span> </a> <b class="arrow"></b> </li>
	<?php } ?>
	<?php  
	$loginAdsviewA =  $obj->checkPagePermission('show_login_ads_viewer.php');  
	if($loginAdsviewA == 1) { 
	?>	
	<li <?php if($curPage=='show_login_ads_viewer.php' || $curPage=='edit_login_ads_viewer.php'){?>class="active"<?php }?>> <a href="show_login_ads_viewer.php"> <i class="menu-icon fa fa-eye"></i> <span class="menu-text"> Login Ad View </span> </a> <b class="arrow"></b> </li>	
	<?php } */?>
    <?php  
	$flagsds =  $obj->checkPagePermission('show_flagged_ads.php');  
	if($flagsds == 1) { 
	?>
    <li <?php if($curPage=='show_flagged_ads.php' || $curPage=='edit_flagged_ads.php' || $curPage=='view_flagged_ads.php'){?>class="active"<?php }?>> <a href="show_flagged_ads.php"> <i class="menu-icon fa fa-eye"></i> <span class="menu-text"> Flagged ads </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$showmemberEarning =  $obj->checkPagePermission('show_members_earning.php'); 
 
	if($showmemberEarning == 1) { 
	?>
    <li <?php if($curPage=='show_members_earning.php' || $curPage=='show_members_earning.php'){?>class="active"<?php }?>> <a href="show_members_earning.php"> <i class="menu-icon fa fa-circle "></i> <span class="menu-text"> Asimi Earning </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$withdrawalReq =  $obj->checkPagePermission('exp_members_withdrawal.php');  
	$withdrawalReq2 =  $obj->checkPagePermission('show_denied_withdrawal.php');  

	if($withdrawalReq == 1 || $withdrawalReq2 ==1) { 
	?>
    <li <?php if($curPage=='exp_members_withdrawal.php' || $curPage=='show_denied_withdrawal.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-circle"></i> <span class="menu-text"> Withdrawal </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='exp_members_withdrawal.php'){?>class="active"<?php }?>> <a href="exp_members_withdrawal.php"> <i class="menu-icon fa fa-circle "></i> <span class="menu-text"> Withdrawal Request </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_denied_withdrawal.php'){?>class="active"<?php }?>> <a href="show_denied_withdrawal.php"> <i class="menu-icon fa fa-circle "></i> <span class="menu-text"> Denied Withdrawal </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php  
	$memDepositReq =  $obj->checkPagePermission('exp_members_deposit.php');  
	if($memDepositReq == 1) { 
	?>
    <li <?php if($curPage=='exp_members_deposit.php'){?>class="active"<?php }?>> <a href="exp_members_deposit.php"> <i class="menu-icon fa fa-circle "></i> <span class="menu-text"> Deposit Request </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
   
    <?php  
	$showmintercreditreward =  $obj->checkPagePermission('show_minter_credit_reward.php');  
	if($showmintercreditreward == 1) { 
	?>
    <li <?php if($curPage=='show_minter_credit_reward.php' || $curPage=='edit_minter_credit_reward.php' || $curPage=='show_banner_ad_credit_reward.php' || $curPage=='edit_banner_ad_credit_reward.php' || $curPage=='show_admin_reward.php' || $curPage=='edit_admin_reward.php' || $curPage=='set_joining_reward.php' || $curPage=='show_vacation_time.php' || $curPage=='edit_vacation_time.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Reward </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
			<?php  
				$show_minter_credit_reward =  $obj->checkPagePermission('show_minter_credit_reward.php'); 
				$edit_minter_credit_reward =  $obj->checkPagePermission('edit_minter_credit_reward.php'); 				
				if($show_minter_credit_reward == 1 || $edit_minter_credit_reward ==1) { 
			?>
        <li <?php if($curPage=='show_minter_credit_reward.php' || $curPage=='edit_minter_credit_reward.php'){?>class="active"<?php }?>> <a href="show_minter_credit_reward.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Minter view credits </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
			<?php  
				$show_banner_ad_credit_reward =  $obj->checkPagePermission('show_banner_ad_credit_reward.php'); 
				$edit_banner_ad_credit_reward =  $obj->checkPagePermission('edit_banner_ad_credit_reward.php');  
				if($show_banner_ad_credit_reward == 1 || $edit_banner_ad_credit_reward ==1) { 
			?>
        <li <?php if($curPage=='show_banner_ad_credit_reward.php' || $curPage=='edit_banner_ad_credit_reward.php'){?>class="active"<?php }?>> <a href="show_banner_ad_credit_reward.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Banner ad credits </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
		<?php  
			$show_vacation_time =  $obj->checkPagePermission('show_vacation_time.php'); 
			$edit_vacation_time =  $obj->checkPagePermission('edit_vacation_time.php'); 				 
			if($show_join_reward == 1 || $edit_vacation_time ==1) { 
		?>
        <li <?php if($curPage=='show_vacation_time.php' || $curPage=='edit_vacation_time.php'){?>class="active"<?php }?>> <a href="show_vacation_time.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Vacation Time </span> </a> <b class="arrow"></b> </li>
		<?php } ?>
		<?php  
			$fourx_imp_reward =  $obj->checkPagePermission('show_fourx_imp_reward.php'); 				 
			if($fourx_imp_reward == 1) { 
		?>
        <li <?php if($curPage=='show_fourx_imp_reward.php'){?>class="active"<?php }?>> <a href="show_fourx_imp_reward.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> 4X Impression Reward </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php  
				$show_admin_reward =  $obj->checkPagePermission('show_admin_reward.php'); 
				$edit_admin_reward =  $obj->checkPagePermission('edit_admin_reward.php');  
				if($show_admin_reward == 1 || $edit_admin_reward ==1) { 
		?>
        <li <?php if($curPage=='show_admin_reward.php' || $curPage=='edit_admin_reward.php'){?>class="active"<?php }?>> <a href="show_admin_reward.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Admin Reward </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php  
				$show_join_reward =  $obj->checkPagePermission('set_joining_reward.php'); 				 
				if($show_join_reward == 1) { 
		?>
        <li <?php if($curPage=='set_joining_reward.php'){?>class="active"<?php }?>> <a href="set_joining_reward.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Joining Reward </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>
	
    <?php  
	$showtestimonial =  $obj->checkPagePermission('show_testimonial.php');  
	if($showtestimonial == 1) { 
	?>
    <li <?php if($curPage=='show_testimonial.php' || $curPage=='edit_testimonial.php'){?>class="active"<?php }?>> <a href="show_testimonial.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Testimonial </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$showfaq =  $obj->checkPagePermission('show_faq.php');  
	if($showfaq == 1) { 
	?>
    <li <?php if($curPage=='show_faq.php' || $curPage=='edit_faq.php'){?>class="active"<?php }?>> <a href="show_faq.php"> <i class="menu-icon fa fa-question"></i> <span class="menu-text">FAQ's </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	 $showexplanationVideo =  $obj->checkPagePermission('set_explanation_video.php');  
	if($showexplanationVideo == 1) { 
	?>
    <li <?php if($curPage=='set_explanation_video.php'){?>class="active"<?php }?>> <a href="set_explanation_video.php"> <i class="menu-icon fa fa-youtube-play"></i> <span class="menu-text">Explanation Video</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$setprelaunch =  $obj->checkPagePermission('set_pre_launch.php');  
	if($setprelaunch == 1) { 
	?>
    <li <?php if($curPage=='set_pre_launch.php'){?>class="active"<?php }?>> <a href="set_pre_launch.php"> <i class="menu-icon fa fa-play"></i> <span class="menu-text">Prelaunch Login Ad</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$setprelaunch =  $obj->checkPagePermission('set_pre_sale_minting.php');  
	if($setprelaunch == 1) { 
	?>
    <li <?php if($curPage=='set_pre_sale_minting.php'){?>class="active"<?php }?>> <a href="set_pre_sale_minting.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Pre-sale Minting</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$contactUs =  $obj->checkPagePermission('show_contact_us.php');  
	if($contactUs == 1) { 
	?>
    <li <?php if($curPage=='show_contact_us.php'){?>class="active"<?php }?>> <a href="show_contact_us.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Contact Information </span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$notificationArr =  $obj->checkPagePermission('show_notification.php');  
	if($notificationArr == 1) { 
	?>
    <li <?php if($curPage=='show_notification.php' || $curPage=='edit_notification.php'){?>class="active"<?php }?>> <a href="show_notification.php"> <i class="menu-icon fa fa-bell-o"></i> <span class="menu-text">Notifications</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$Announcement =  $obj->checkPagePermission('show_announcement.php');  
	if($Announcement == 1) { 
	?>
    <li <?php if($curPage=='show_announcement.php' || $curPage=='edit_announcement.php' || $curPage=='show_scroll_announcement.php' || $curPage=='edit_acroll_announcement.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Announcement </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_announcement.php' || $curPage=='edit_announcement.php'){?>class="active"<?php }?>> <a href="show_announcement.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Announcement</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_scroll_announcement.php' || $curPage=='edit_acroll_announcement.php'){?>class="active"<?php }?>> <a href="show_scroll_announcement.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Scroll Announcement</span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php  
	$metatags =  $obj->checkPagePermission('show_meta_tags.php');  
	if($metatags == 1) { 
	?>
    <li <?php if($curPage=='show_meta_tags.php' || $curPage=='edit_meta_tags.php'){?>class="active"<?php }?>> <a href="show_meta_tags.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Meta Tags</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$asimiprice =  $obj->checkPagePermission('show_asimi_price.php');  
	if($asimiprice == 1) { 
	?>
    <li <?php if($curPage=='show_asimi_price.php'){?>class="active"<?php }?>> <a href="show_asimi_price.php"> <i class="menu-icon fa fa-anchor"></i> Asimi Price</a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$walletAdd =  $obj->checkPagePermission('set_wallet_address.php');  
	if($walletAdd == 1) { 
	?>
    <li <?php if($curPage=='set_wallet_address.php'){?>class="active"<?php }?>> <a href="set_wallet_address.php"> <i class="menu-icon fa fa-money"></i> Wallet Address</a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$domainBlack =  $obj->checkPagePermission('show_domain_blacklist.php');  
	if($domainBlack == 1) { 
	?>
    <li <?php if($curPage=='show_domain_blacklist.php' || $curPage=='edit_domain_blacklist.php'){?>class="active"<?php }?>> <a href="show_domain_blacklist.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Domain blacklist</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$emaildomainBlack =  $obj->checkPagePermission('show_email_domain_blacklist.php');  
	if($emaildomainBlack == 1) { 
	?>
    <li <?php if($curPage=='show_email_domain_blacklist.php' || $curPage=='edit_email_domain_blacklist.php'){?>class="active"<?php }?>> <a href="show_email_domain_blacklist.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">SignUp Domain blacklist</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$domainWht =  $obj->checkPagePermission('show_domain_whitelist.php');  
	if($domainWht == 1) { 
	?>
    <li <?php if($curPage=='show_domain_whitelist.php' || $curPage=='edit_domain_whitelist.php'){?>class="active"<?php }?>> <a href="show_domain_whitelist.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Domain Whitelist</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$ipblock =  $obj->checkPagePermission('show_ip_tracker.php');  
	if($ipblock == 1) { 
	?>
    <li <?php if($curPage=='show_ip_tracker.php'){?>class="active"<?php }?>> <a href="show_ip_tracker.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">IP Tracker</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$dpreport =  $obj->checkPagePermission('get_daily_reports.php');  
	if($dpreport == 1) { 
	?>
    <li <?php if($curPage=='get_daily_reports.php'){?>class="active"<?php }?>> <a href="get_daily_reports.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Daily Report</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$show_minting_rotator =  $obj->checkPagePermission('show_minting_rotator.php');  
	$show_affiliate_volume_bonus =  $obj->checkPagePermission('show_affiliate_volume_bonus.php');  

	if($show_minting_rotator == 1 || $show_affiliate_volume_bonus ==1) 
	{  
	?>
    <li <?php if($curPage=='show_minting_rotator.php' || $curPage=='show_affiliate_volume_bonus.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Lead Rotator </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_minting_rotator.php'){?>class="active"<?php }?>> <a href="show_minting_rotator.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Minting Rotator </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_affiliate_volume_bonus.php' ){?>class="active"<?php }?>> <a href="show_affiliate_volume_bonus.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> Affiliate Volume Bonus </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php  
	$pending_ads =  $obj->checkPagePermission('show_pending_view_to_earn_ads.php');  
	$show_earn_ads =  $obj->checkPagePermission('show_view_to_earn_ads.php');  
	if($pending_ads == 1 || $show_earn_ads ==1) 
	{  
	?>
    <li <?php if($curPage=='show_view_to_earn_ads.php' || $curPage=='show_raffle_banner_entry.php' || $curPage=='show_raffle_stake_winner.php' || $curPage=='show_pending_view_to_earn_ads.php' || $curPage=='show_raffle_banner_winner.php' || $curPage=='show_raffle_winner.php' || $curPage=='show_raffle_entry.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">V2E Ads </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_view_to_earn_ads.php'){?>class="active"<?php }?>> <a href="show_view_to_earn_ads.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text"> View to Earn Ads </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_pending_view_to_earn_ads.php' ){?>class="active"<?php }?>> <a href="show_pending_view_to_earn_ads.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Pending View to Earn Ads </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_raffle_winner.php'){?>class="active"<?php }?>> <a href="show_raffle_winner.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Raffle Winner </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_raffle_entry.php'){?>class="active"<?php }?>> <a href="show_raffle_entry.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Raffle Entry </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_raffle_stake_winner.php'){?>class="active"<?php }?>> <a href="show_raffle_stake_winner.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Raffle Stake Winner </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_raffle_stake_entry.php'){?>class="active"<?php }?>> <a href="show_raffle_stake_entry.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Raffle Stake Entry </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_raffle_banner_winner.php'){?>class="active"<?php }?>> <a href="show_raffle_banner_winner.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Raffle Banner Winner </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_raffle_banner_entry.php'){?>class="active"<?php }?>> <a href="show_raffle_banner_entry.php"> <i class="menu-icon fa fa-chain"></i> <span class="menu-text">Raffle Banner Entry </span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
	
    <?php  
	$lcp_track =  $obj->checkPagePermission('show_lcp_tracking.php');  
	if($lcp_track == 1) { 
	?>
    <li <?php if($curPage=='show_lcp_tracking.php'){?>class="active"<?php }?>> <a href="show_lcp_tracking.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Affiliate tracking</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
	
    <?php 
	$show_affiliates =  $obj->checkPagePermission('show_affiliates.php');  
	if($show_affiliates == 1) { 
	?>
    <li <?php if($curPage=='show_affiliates.php'){?>class="active"<?php }?>> <a href="show_affiliates.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Affiliates</span> </a> <b class="arrow"></b> </li>
    <?php }   ?>
	
	
	
	
    <?php  
	$show_finance =  $obj->checkPagePermission('show_finance.php');  
	if($show_finance == 1) { 
	?>
    <li <?php if($curPage=='show_finance.php'){?>class="active"<?php }?>> <a href="show_finance.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Transaction Reports</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$login_history 		=  $obj->checkPagePermission('show_login_history.php');  
	$ad_click_history 	=  $obj->checkPagePermission('show_ad_click_history.php');  
	$making_changes 	=  $obj->checkPagePermission('show_making_changes.php'); 
	$false_log_change 	=  $obj->checkPagePermission('show_false_login_and_changes.php');  
	if($login_history == 1 || $ad_click_history ==1 || $making_changes ==1 || $false_log_change ==1) 
	{  
	?>
    <li <?php if($curPage=='show_login_history.php' || $curPage=='show_ad_click_history.php' || $curPage=='show_making_changes.php' || $curPage=='show_false_login_and_changes.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Monitoring</span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_login_history.php'){?>class="active"<?php }?>> <a href="show_login_history.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Login History</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_ad_click_history.php'){?>class="active"<?php }?>> <a href="show_ad_click_history.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Ad Clicks</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_making_changes.php'){?>class="active"<?php }?>> <a href="show_making_changes.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Making changes</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_false_login_and_changes.php'){?>class="active"<?php }?>> <a href="show_false_login_and_changes.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> False Logins & Changes</span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php  
	$unsolved_alert =  $obj->checkPagePermission('show_unresolved_alerts.php'); 
	$solved_alert =  $obj->checkPagePermission('show_solved_alerts.php'); 	 
	if($unsolved_alert == 1 || $solved_alert == 1) 
	{  
	?>
    <li <?php if($curPage=='show_unresolved_alerts.php' || $curPage=='show_solved_alerts.php' || $curPage=='show_manual_search.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Security Center</span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_unresolved_alerts.php'){?>class="active"<?php }?>> <a href="show_unresolved_alerts.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Unresolved Alerts</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_solved_alerts.php'){?>class="active"<?php }?>> <a href="show_solved_alerts.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Solved Alerts</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_manual_search.php'){?>class="active"<?php }?>> <a href="show_manual_search.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Manual Search</span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php  
	$bonus_payments =  $obj->checkPagePermission('show_contractor_bonus_payments.php'); 
	$wages_pay =  $obj->checkPagePermission('show_wages_payments.php'); 	 
	if($bonus_payments == 1 || $wages_pay == 1) 
	{  
	?>
    <li <?php if($curPage=='show_contractor_bonus_payments.php' || $curPage=='show_wages_payments.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Other Payment</span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_contractor_bonus_payments.php'){?>class="active"<?php }?>> <a href="show_contractor_bonus_payments.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Contractor B. Payment</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_wages_payments.php'){?>class="active"<?php }?>> <a href="show_wages_payments.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Wages Payment</span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php  
	$show_article =  $obj->checkPagePermission('show_article.php');
	$edit_article =  $obj->checkPagePermission('edit_article.php'); 	 
	if($show_article == 1 || $edit_article == 1) 
	{  
	?>
    <li <?php if($curPage=='show_article.php' || $curPage=='edit_article.php'){?>class="active"<?php }?>> <a href="show_article.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Article</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  
	$show_internal_image =  $obj->checkPagePermission('show_internal_image.php');
	 
	if($show_internal_image == 1) 
	{  
	?>
    <li <?php if($curPage=='show_internal_image.php'){?>class="active"<?php }?>> <a href="show_internal_image.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Image URL</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <?php  /* 
	$show_leaderboard =  $obj->checkPagePermission('show_leaderboard_banner.php');	 
	if($show_leaderboard == 1) 
	{  
	?>	  	
	<li <?php if($curPage=='show_leaderboard_banner.php'){?>class="active"<?php }?>> <a href="show_leaderboard_banner.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Leaderboard Banner</span> </a> <b class="arrow"></b> </li>	
	<?php } */ ?>
    <?php  
	$show_leaderboard =  $obj->checkPagePermission('show_leaderboard_banner.php'); 
	$show_pbanner_a =  $obj->checkPagePermission('show_pbanner_a.php'); 	 
	if($show_leaderboard == 1 || $show_pbanner_a == 1) 
	{  
	?>
    <li <?php if($curPage=='show_leaderboard_banner.php' || $curPage=='edit_leaderboard_banner.php' || $curPage=='edit_pbanner_a.php' || $curPage=='edit_pbanner_b.php' || $curPage=='show_pbanner_a.php' || $curPage=='show_pbanner_b.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Premium Banners</span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <li <?php if($curPage=='show_leaderboard_banner.php' || $curPage=='edit_leaderboard_banner.php'){?>class="active"<?php }?>> <a href="show_leaderboard_banner.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Leaderboard Banner</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_pbanner_a.php' || $curPage=='edit_pbanner_a.php'){?>class="active"<?php }?>> <a href="show_pbanner_a.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Banner A</span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_pbanner_b.php' || $curPage=='edit_pbanner_b.php'){?>class="active"<?php }?>> <a href="show_pbanner_b.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Banner B</span> </a> <b class="arrow"></b> </li>
      </ul>
    </li>
    <?php } ?>
    <?php   
	$showLeaArr =  $obj->checkPagePermission('show_leaderboard.php');	 
	if($showLeaArr == 1) 
	{  
	?>
    <li <?php if($curPage=='show_leaderboard.php'){?>class="active"<?php }?>> <a href="show_leaderboard.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Leaderboard </span> </a> <b class="arrow"></b> </li>
    <?php }   ?>
    <?php  
	$show_demograph =  $obj->checkPagePermission('show_demographic_reports.php');	 
	if($show_demograph == 1) 
	{  
	?>
    <li <?php if($curPage=='show_demographic_reports.php'){?>class="active"<?php }?>> <a href="show_demographic_reports.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Demographic Reports</span> </a> <b class="arrow"></b> </li>
    <?php } ?>
    <li <?php if($curPage=='set_google_auth.php' || $curPage=='change_from_email.php' || $curPage=='change_phone.php' || $curPage=='set_default_tax.php' || $curPage=='set_address.php' || $curPage=='set_commission_rate.php' || $curPage=='set_social_links.php' || $curPage=='change_email.php' || $curPage=='change_password.php' ){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-cog fa-spin"></i> <span class="menu-text"> Settings </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <?php  if($_SESSION['admin']['admin_type'] == 'm') { ?>
        <li <?php if($curPage=='set_google_auth.php'){?>class="active"<?php }?>> <a href="set_google_auth.php"> <i class="menu-icon fa fa-chain "></i> Google Auth </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='set_announcement.php'){?>class="active"<?php }?>> <a href="set_announcement.php"> <i class="menu-icon fa fa-chain "></i> Announcement </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='set_social_links.php'){?>class="active"<?php }?>> <a href="set_social_links.php"> <i class="menu-icon fa fa-chain "></i> Social Links </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='change_phone.php'){?>class="active"<?php }?>> <a href="change_phone.php"> <i class="menu-icon fa fa-phone"></i> Change Phone </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='change_from_email.php'){?>class="active"<?php }?>> <a href="change_from_email.php"> <i class="menu-icon fa fa-envelope "></i> Change From Email </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='change_email.php'){?>class="active"<?php }?>> <a href="change_email.php"> <i class="menu-icon fa fa-envelope "></i> Change Admin Email </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='set_address.php'){?>class="active"<?php }?>> <a href="set_address.php"> <i class="menu-icon fa fa-envelope "></i> Change Address </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='change_pin.php'){?>class="active"<?php }?>> <a href="change_pin.php"> <i class="menu-icon fa fa-envelope "></i> Change Pin </a> <b class="arrow"></b> </li>
        <?php } ?>
        <li <?php if($curPage=='change_password.php'){?>class="active"<?php }?>> <a href="change_password.php"> <i class="menu-icon fa fa-edit "></i> Change Password </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='logout.php'){?>class="active"<?php }?>> <a href="logout.php"> <i class="menu-icon fa fa-power-off"></i> Logout </a> <b class="arrow"></b> </li>
      </ul>
    </li>
  </ul>
  <!-- /.nav-list -->
  <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse"> <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i> </div>
  <script type="text/javascript">
		try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
	</script>
</div>

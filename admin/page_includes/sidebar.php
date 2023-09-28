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
    <li <?php if($curPage=='show_contents.php' || $curPage=='edit_content.php' || $curPage=='show_testimonial.php' || $curPage=='edit_testimonial.php' || $curPage=='show_faq.php' || $curPage=='edit_faq.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> CMS </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <?php  $show_content =  $obj->checkPagePermission('show_contents.php'); ?>
        <?php if($show_content == 1) { ?>
        <li <?php if($curPage=='show_contents.php' || $curPage=='edit_content.php'){?>class="active"<?php }?>> <a href="show_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Content & Meta</span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php /*?>	<?php 
			$about_usA =  $obj->checkPagePermission('show_about_us.php'); 
			$about_usB =  $obj->checkPagePermission('show_about_us_contents.php'); 	
			if($about_usA == 1 || $about_usB ==1) { 
			?>
			<li <?php if($curPage=='show_about_us.php' || $curPage=='edit_about_us.php'){?>class="active"<?php }?>> <a href="show_about_us.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> About Us </span> </a> <b class="arrow"></b> </li>
			<li <?php if($curPage=='show_about_us_contents.php' || $curPage=='edit_about_us_content.php'){?>class="active"<?php }?>> <a href="show_about_us_contents.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> About Us CMS</span> </a> <b class="arrow"></b> </li>
			<?php } ?><?php */?>
        <?php /*?><?php  
			$show_article =  $obj->checkPagePermission('show_article.php');
			$edit_article =  $obj->checkPagePermission('edit_article.php'); 	 
			if($show_article == 1 || $edit_article == 1) 
			{  
			?>
			<li <?php if($curPage=='show_article.php' || $curPage=='edit_article.php'){?>class="active"<?php }?>> <a href="show_article.php"> <i class="menu-icon fa fa-bullhorn"></i> <span class="menu-text">Article</span> </a> <b class="arrow"></b> </li>
			<?php } ?><?php */?>
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
      </ul>
    </li>
    <li <?php if($curPage=='show_members.php' || $curPage=='view_member.php' || $curPage=='show_member_identity.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Memebers </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_members.php'){?>class="active"<?php }?>> <a href="show_members.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Member List </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
		
		<?php  
	$memberA =  $obj->checkPagePermission('show_top_operators.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_top_operators.php'){?>class="active"<?php }?>> <a href="show_top_operators.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Top Operator List </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_member_identity.php'){?>class="active"<?php }?>> <a href="show_member_identity.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Member Identity List</span> </a> <b class="arrow"></b> </li>
        <?php } ?>
      </ul>
    </li>
	
    <li <?php if($curPage=='show_package.php' || $curPage=='edit_package.php' || $curPage=='show_package_comm.php' || $curPage=='edit_package_comm.php' || $curPage=='show_facility.php' || $curPage=='edit_facility.php' || $curPage=='show_non_facility.php' || $curPage=='edit_non_facility.php' || $curPage=='show_top_package.php' || $curPage=='show_promote_package.php' || $curPage=='show_tags.php' || $curPage=='edit_tag.php'){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Tour Package </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_facility.php'){?>class="active"<?php }?>> <a href="show_facility.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Facility </span> </a> <b class="arrow"></b> </li>
		<li <?php if($curPage=='show_tags.php'){?>class="active"<?php }?>> <a href="show_tags.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Tags </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php /*?><?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
    <li <?php if($curPage=='show_non_facility.php'){?>class="active"<?php }?>> <a href="show_non_facility.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Non-Facility </span> </a> <b class="arrow"></b> </li>
    <?php } ?><?php */?>
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_package.php'){?>class="active"<?php }?>> <a href="show_package.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Package </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
		
       
		  
		  
		  
		  
		<?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_top_tour.php'){?>class="active"<?php }?>> <a href="show_top_tour.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">
			Tranding tour
			</span> </a> <b class="arrow"></b> </li>
        <?php } ?>
		  
		  
		   <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_top_package.php'){?>class="active"<?php }?>> <a href="show_top_package.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">
			Hot Tour / Left top Pack </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
		  
		  
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_promote_package.php'){?>class="active"<?php }?>> <a href="show_promote_package.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Promoted Pack Left profile</span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_package_comm.php'){?>class="active"<?php }?>> <a href="show_package_comm.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Package Comments</span> </a> <b class="arrow"></b> </li>
        <?php } ?>
      </ul>
    </li>
    <li <?php if($curPage=='show_tr_story.php' || $curPage=='edit_tr_story.php' || $curPage=='show_tr_story_comm.php' || $curPage=='edit_tr_story_comm.php' ){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-home"></i> <span class="menu-text"> Travel Story </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_tr_story.php'){?>class="active"<?php }?>> <a href="show_tr_story.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text"> Story </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
        <?php  
	$memberA =  $obj->checkPagePermission('show_members.php');  
	if($memberA == 1) { 
	?>
        <li <?php if($curPage=='show_tr_story_comm.php'){?>class="active"<?php }?>> <a href="show_tr_story_comm.php"> <i class="menu-icon fa fa-users"></i> <span class="menu-text">Story Comment </span> </a> <b class="arrow"></b> </li>
        <?php } ?>
      </ul>
    </li>
    <li <?php if($curPage=='show_sub_admin.php' || $curPage=='show_sub_admin_pages.php' || $curPage=='set_google_auth.php' || $curPage=='change_from_email.php' || $curPage=='change_admin_phone.php' || $curPage=='set_default_tax.php' || $curPage=='set_address.php' || $curPage=='set_commission_rate.php' || $curPage=='set_social_links.php' || $curPage=='change_email.php' || $curPage=='change_password.php' ){?>class="active"<?php }?>> <a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-cog fa-spin"></i> <span class="menu-text"> Settings </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
      <ul class="submenu">
        <?php   if($_SESSION[ADMIN_SESSION_NAME]['admin_type'] == 'm') { ?>
        <li <?php if($curPage=='show_sub_admin.php' || $curPage=='edit_sub_admin.php'){?>class="active"<?php }?>> <a href="show_sub_admin.php"> <i class="menu-icon fa fa-user-plus"></i> <span class="menu-text"> Sub Admin </span> </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='show_sub_admin_pages.php' || $curPage=='edit_sub_admin_pages.php'){?>class="active"<?php }?>> <a href="show_sub_admin_pages.php"> <i class="menu-icon fa fa-file-text"></i> <span class="menu-text"> Page Permission </span> </a> <b class="arrow"></b> </li>
        <?php }  ?>
        <?php /*?> <?php   if($_SESSION[ADMIN_SESSION_NAME]['admin_type'] == 'm') { ?>
		<li <?php if($curPage=='show_email_facility.php' || $curPage=='edit_email_facility.php' || $curPage=='show_send_email.php' || $curPage=='edit_send_email.php'){?>class="active"<?php }?>> 
			<a href="#" class="dropdown-toggle"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Internal Email </span> <b class="arrow fa fa-angle-down"></b> </a> <b class="arrow"></b>
		  <ul class="submenu">
			<li <?php if($curPage=='show_email_facility.php' || $curPage=='edit_email_facility.php'){?>class="active"<?php }?>> <a href="show_email_facility.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text"> Email Format </span> </a> <b class="arrow"></b> </li>
			<li <?php if($curPage=='show_send_email.php' || $curPage=='edit_send_email.php'){?>class="active"<?php }?>> <a href="show_send_email.php"> <i class="menu-icon fa fa-cubes"></i> <span class="menu-text">Send Email </span> </a> <b class="arrow"></b> </li>
		  </ul>
		</li>
		<?php } ?><?php */?>
        <?php  if($_SESSION[ADMIN_SESSION_NAME]['admin_type'] == 'm') { ?>
        <?php /*?><li <?php if($curPage=='set_google_auth.php'){?>class="active"<?php }?>> <a href="set_google_auth.php"> <i class="menu-icon fa fa-chain "></i> Google Auth </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='set_announcement.php'){?>class="active"<?php }?>> <a href="set_announcement.php"> <i class="menu-icon fa fa-chain "></i> Announcement </a> <b class="arrow"></b> </li><?php */?>
        <li <?php if($curPage=='set_social_links.php'){?>class="active"<?php }?>> <a href="set_social_links.php"> <i class="menu-icon fa fa-chain "></i> Social Links </a> <b class="arrow"></b> </li>
        <?php /*?><li <?php if($curPage=='change_phone.php'){?>class="active"<?php }?>> <a href="change_phone.php"> <i class="menu-icon fa fa-phone"></i> Change Display Phone </a> <b class="arrow"></b> </li><?php */?>
        <li <?php if($curPage=='change_from_email.php'){?>class="active"<?php }?>> <a href="change_from_email.php"> <i class="menu-icon fa fa-envelope "></i> Change From Email </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='change_email.php'){?>class="active"<?php }?>> <a href="change_email.php"> <i class="menu-icon fa fa-envelope "></i> Change Admin Email </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='change_admin_phone.php'){?>class="active"<?php }?>> <a href="change_admin_phone.php"> <i class="menu-icon fa fa-envelope "></i> Change Admin phone </a> <b class="arrow"></b> </li>
        <li <?php if($curPage=='set_address.php'){?>class="active"<?php }?>> <a href="set_address.php"> <i class="menu-icon fa fa-envelope "></i> Change Address </a> <b class="arrow"></b> </li>
        <?php /*?> <li <?php if($curPage=='change_pin.php'){?>class="active"<?php }?>> <a href="change_pin.php"> <i class="menu-icon fa fa-envelope "></i> Change Pin </a> <b class="arrow"></b> </li><?php */?>
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

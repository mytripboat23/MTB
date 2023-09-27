<div id="navbar" class="navbar navbar-default"> 
  <script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
  <div class="navbar-container" id="navbar-container">
    <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler"> <span class="sr-only">Toggle sidebar</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <div class="navbar-header pull-left"> <a href="#" class="navbar-brand"> <small> <i class="fa fa-industry"></i> <?=SITE_TITLE;?> Admin Consol </small> </a> </div>
    <div class="navbar-buttons navbar-header pull-right" role="navigation">
      <ul class="nav ace-nav">
     <?php
	 if($_SESSION['admin']['admin_type'] == 'm') { 
	   $LogAdmin = 'Super Admin';	
	 }  else {
		 $LogAdmin = $_SESSION['admin']['admin_name'];	
	 }
	
	 ?>
        <li class="light-blue"> <a data-toggle="dropdown" href="#" class="dropdown-toggle"> <img class="nav-user-photo" src="assets/avatars/admin.jpg" alt="Administrator Photo" width="36" /> <span class="user-info"> <small>Welcome,</small> <?=$LogAdmin;?> </span> <i class="ace-icon fa fa-caret-down"></i> </a>
          <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
            <li> <a href="change_password.php"> <i class="ace-icon fa fa-cog"></i> Change Password </a> </li>
			<?php if($_SESSION['admin']['admin_type'] == 'm') { ?>
            <li> <a href="change_email.php"> <i class="ace-icon fa fa-user"></i> Change Email </a> </li>
			<?php } ?>
            <li class="divider"></li>
            <li> <a href="logout.php"> <i class="ace-icon fa fa-power-off"></i> Logout </a> </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /.navbar-container --> 
</div>
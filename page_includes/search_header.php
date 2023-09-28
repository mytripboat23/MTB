<div class="mobile_header_logo d-block d-sm-none d-flex justify-content-center py-4 "> 
<div>
	<a href="<?=FURL;?>"><img src="img/logo.png" alt=""></a> 
	</div>	
	<div>
		
		
		
		   <ul class="nav-menu nav-menu_inner align-to-right">
        
            <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
            <li class="mob_login"><a href="logout">Logout</a></li>
            <?php }else{?>
            <li class="mob_login"><a href="login">Login</a></li>
     
            <?php }?>
			   
			     
          </ul>
		
		
		
		
		
		
	
	</div>
</div>
<style>
	.mob_login{
		width: 50px;
    height: 50px;
    border-radius: 74px;
    background: #f26051;
    display: flex;
    justify-content: center;
    align-items: center;
	}
	.mob_login a{color: #fff;}
</style>
<!--<div class="header_bg search_page_header">-->
<div class="header_bg ">
  <div class="container">
    <div class="holder_area j_b">
      <div class="logo_area d-none d-md-block"> <a href="<?=FURL;?>"><img src="img/logo.png" alt=""></a> </div>
      <!-- ...search start... -->
      <div class="search_area">
        <div class="search_box">
          <form name="searchTour" id="searchTour" method="get" action="search-tour">
            <div class="form_group">
              <label>Wish your destination</label>
              <input class="text_input place" id="search_where" name="search_where" type="text" placeholder="Search your destination" value="<?=htmlentities($_REQUEST['search_where'])?>">
            </div>
            <div class="form_group">
              <label>Date</label>
              <input type="text" class="date" id="datepicker" name="search_when" placeholder="When" value="<?=htmlentities($_REQUEST['search_when'])?>">
            </div>
            <button type="submit">Find</button>
          </form>
        </div>
      </div>
      <!-- ...search end... -->
      
      <?php /*?>
<nav class="menu_area">
  <div class="nav-menus-wrapper">
    <ul class="nav-menu nav-menu_inner align-to-right">
      <li><a href="index">Home</a></li>
      <li><a href="faq">FAQ</a></li>
      <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
      <li><a href="logout">Logout</a></li>
      <?php }else{?>
      <li><a href="login">Login</a></li>
      <?php }?>
      <?php
            if ( isset( $_SESSION[ 'user' ][ 'u_login_id' ] ) && $_SESSION[ 'user' ][ 'u_login_id' ] != "" ) {
              $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $_SESSION[ 'user' ][ 'u_login_id' ] . "'", 1 );
              if ( $userD[ 'user_avatar' ] != "" )$uphoto = $userD[ 'user_avatar' ];
              else $uphoto = 'noimage.jpg';
              ?>
      <li class="drop_class">
        <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
        <a href="logout">Logout <span class="material-symbols-outlined">logout</span></a>
        <?php }else{?>
        <a href="login">Login <span class="material-symbols-outlined">login</span></a>
        <?php }?>
      </li>
      <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
      <li class="drop_class"> <a href="dashboard" class="prof_drop_sec">
          <p>
            <?=$userD['user_full_name']?>
          </p>
          <img class="prof_img" src="<?=AVATAR.$uphoto?>">
          <!--<p class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </p>-->
        </a> </li>
      <?php }?>
    </ul>
    <?php }?>
    <div class="dropdown">
      <button onClick="myFunction()" class="dropbtn"></button>
      <div id="myDropdown" class="dropdown-content">
        <?php
              if ( isset( $_SESSION[ 'user' ][ 'u_login_id' ] ) && $_SESSION[ 'user' ][ 'u_login_id' ] != "" ) {
                $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $_SESSION[ 'user' ][ 'u_login_id' ] . "'", 1 );
                if ( $userD[ 'user_avatar' ] != "" )$uphoto = $userD[ 'user_avatar' ];
                else $uphoto = 'noimage.jpg';

                ?>
        <a href="logout">Logout <span class="material-symbols-outlined">logout</span></a>
        <?php }else{?>
        <a href="login">Login <span class="material-symbols-outlined">login</span></a>
        <?php }?>
        <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
        <a href="dashboard" class="prof_drop_sec">
          <p>
            <?=$userD['user_full_name']?>
          </p>
          <img class="prof_img" src="<?=AVATAR.$uphoto?>">
          <!--<strong class="rating"> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> <span class="material-symbols-outlined">star</span> </strong>-->
        </a>
        <?php }?>
      </div>
    </div>
  </div>
</nav>
      <?php */?>
      <nav class="menu_area d-none d-md-block">
        <div class="nav-menus-wrapper">
          <ul class="nav-menu nav-menu_inner align-to-right">
            <li><a href="index">Home</a></li>
            <li><a href="faq">FAQ</a></li>
            <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
            <li><a href="logout">Logout</a></li>
            <?php }else{?>
            <li><a href="login">Login / Register</a></li>
            <?php }?>
            <?php
            if ( isset( $_SESSION[ 'user' ][ 'u_login_id' ] ) && $_SESSION[ 'user' ][ 'u_login_id' ] != "" ) {
              $userD = $obj->selectData( TABLE_USER, "", "u_login_id = '" . $_SESSION[ 'user' ][ 'u_login_id' ] . "'", 1 );
              if ( $userD[ 'user_avatar' ] != "" )$uphoto = $userD[ 'user_avatar' ];
              else $uphoto = 'noimage.jpg';
              ?>
            <?php if(isset($_SESSION['user']['u_login_id']) && $_SESSION['user']['u_login_id']!=""){?>
            <li> <a href="dashboard" data-bs-toggle="tooltip" data-bs-title=" <?=$userD['user_display_name']?>" data-bs-placement="left">
              <figure style="width: 48px; height:48px; overflow: hidden; border-radius: 50%; margin: 0;"> <img class="prof_img" src="<?=AVATAR.$uphoto?>" style="width: 100%"> </figure>
              </a> </li>
            <?php }?>
          </ul>
          <?php }?>
        </div>
      </nav>
    </div>
  </div>
</div>

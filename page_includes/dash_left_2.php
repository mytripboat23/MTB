<?php
$user_id = $_SESSION['user']['u_login_id'];
$userD   = $obj->selectData(TABLE_USER,"","u_login_id='".$user_id."'",1);

				if($userD['user_avatar']!="") $uphoto = AVATAR.$userD['user_avatar'];
				else $uphoto = NO_USER_PHOTO; 
				
				 
?>

<!-- ................Tor operator.............. -->
    <section class="operator_personal top_none">
      <div class="operator_personal_img"><img src="<?=$uphoto;?>" alt=""></div>
      <h3><a href="#"><?=$userD['user_full_name']?></a></h3>
      <h4>Tour Operator</h4>
      <a class="mail" href="mailto:<?=$userD['user_email'];?>">Mail</a> </section>
    <!-- ................list.............. -->
    <section class="personal_list">
      <ul>
        <li> <img src="img/star_ico.png"> Rating: <span><?=$obj->user_package_rating($user_id);?></span> </li>
        <li> <img src="img/complete_ico.png"> Complete: <span> <?=$obj->completed_tours_by_user($user_id);?> Tours</span> </li>
        <li> <img src="img/recomend_ico.png"> Recomended: <span> 50?</span> </li>
        <li> <img src="img/follow_ico.png"> Follow: <span> 1000?</span> </li>
        <li> <img src="img/running_ico.png"> Ongoing: <span> <?=$obj->ongoing_tours_by_user($user_id);?></span> </li>
      </ul>
    </section>
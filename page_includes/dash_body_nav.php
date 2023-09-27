<ul class="top_tab_btn_area">
  <li> <a href="dashboard">Latest Tour</a> </li>
  <li> <a href="stories">Story</a> </li>
  <?php if($_SESSION['user']['user_type']!=''){?>
  <li> <a href="create-package">Create Tour</a> </li>
  <?php }?>
  <li> <a href="create-travel-story">Create Story</a> </li>
</ul>
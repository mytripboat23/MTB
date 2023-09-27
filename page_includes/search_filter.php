<section class="filter_area">
      <div class="container">
        <div class="inner_search">
          <div class="lt_search">
            <form name="filterForm" id="filterForm" action="" method="get">
			  <input type="hidden" name="search_where" id="search_where" value="<?=$_REQUEST['search_where']?>" />
			  <input type="hidden" name="search_when" id="search_when" value="<?=$_REQUEST['search_when']?>" />
				<select class="select search_filter" name="search_filter">
				<!--<option>Ratting</option>-->
				<option value="price-high-low" <?php if(isset($_REQUEST['search_filter']) && $_REQUEST['search_filter']=='price-high-low'){?>selected<?php }?>>Price high to low</option>
				<option value="price-low-high" <?php if(isset($_REQUEST['search_filter']) && $_REQUEST['search_filter']=='price-low-high'){?>selected<?php }?>>Price low to high</option>
				<option value="top-agent" <?php if(isset($_REQUEST['search_filter']) && $_REQUEST['search_filter']=='top-agent'){?>selected<?php }?>>Top Agent</option>
				</select>
              <button type="submit"><span class="material-symbols-outlined"> filter_alt </span>Filter</button>
            </form>
          </div>
          <?php /*?><div class="mid_search"> <a href="#"> <span class="material-symbols-outlined"> grid_view </span> </a> </div><?php */?>
          
        </div>
      </div>
    </section>
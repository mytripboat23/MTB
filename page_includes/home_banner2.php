  <!--..................banner start....................-->
  <?php /*?><section class="home_banner_section">
    <div id="home_banner" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators"> </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <video class="object-fit-cover" loop="true" autoplay="autoplay" muted="" style=" max-width: 100%">
            <source src="img/long_video_only.mp4" type="video/mp4" style="">
            <source src="img/long_video_only.ogg" type="video/ogg" style="">
<!--            <source src="https://youtu.be/edBlesKUyQg" type="video/mp4" style="">-->
          </video>
          <div class="carousel-caption d-none d-md-block"> <img  src="img/web_text.png" class="d-block w-100" alt="...">
<!--
            <h4 class="text-center text-dark">Follow us:</h4>
            <div class="social_box">
              <ul class="nav justify-content-center">
                <li class="nav-item"> <a class="nav-link" href="https://www.facebook.com/mytripboat" target="_blank"> <img src="img/app_facebook_logo_media_popular_icon.png" alt="" style="width:36px"> </a> </li>
                <li class="nav-item"> <a class="nav-link" href="https://twitter.com/mytripboat" target="_blank"> <img src="img/app_logo_media_popular_social_icon.png" alt="" style="width: 36px"> </a> </li>
                <li class="nav-item"> <a class="nav-link" href="https://www.instagram.com/mytripboat" target="_blank"> <img src="img/app_instagram_logo_media_popular_icon.png" alt="insta" style="width: 36px"> </a> </li>
                <li class="nav-item"> <a class="nav-link" href="https://youtu.be/glat6iUBtFQ" target="_blank"> <img src="img/youtube_ico.png" alt="" style="width: 36px"> </a> </li>
              </ul>
            </div>
-->
          </div>
        </div>
      </div>
    </div>
  </section><?php */?>
  <!--..................banner end....................-->
  <!-- ...search start... -->
<?php /*?>  <div class="home_search search_area home_search_main">
    <div class="search_box">
      <form name="searchTour" id="searchTour" method="get" action="search-tour">
        <div class="form_group">
          <label>Search for your dream destination</label>
          <input class="text_input place" id="search_where" name="search_where" type="text" placeholder="Search">
        </div>
<!--
        <div class="form_group">
          <label>Date</label>
          <input type="text" class="date" id="datepicker" name="search_when" placeholder="When">
        </div>
-->
        <button type="submit">Find</button>
      </form>
    </div>
  </div><?php */?>
  <!-- ...search end... -->
  <!-- ...body start... -->





<section id="home_new_banner">
	
        <video  autoplay muted loop class="vidbacking d-none d-md-block" >
			
            <source src="img/long_video_only.ogg" type="video/ogg" style="">
            <source src="img/long_video_only.mp4" type="video/mp4">
        </video>
   

	
<!--	<iframe  src="https://www.youtube.be/embed/vQJ-etvtf34" frameborder="0" allow="autoplay"   allowfullscreen  class="vidbacking"></iframe>-->
	
	

	<div class="brand_holder d-none d-md-block">
	 <img  src="img/web_text.png" class="d-block w-100" alt="...">
	</div>

  <!-- ...search start... -->
  <div class="home_search_new search_area home_search_main">
    <div class="search_box_n">
      <form name="searchTour" id="searchTour" method="get" action="search-tour">
		  
		  
		  
		  <div class="input-group">
  <input type="text" class="form-control border-0" id="search_where" name="search_where" placeholder="Search for your dream destination" aria-label="Search for your dream destination" aria-describedby="button-addon2">
  <button class="btn btn-outline-secondary border-0" type="submit" id="button-addon2">
			  <img src="img/search_tour.svg" alt="" style="width: 36px">
			  
			  </button>
</div>
		  

      </form>
    </div>
  </div>
  <!-- ...search end... -->



</section>
<style>
	#home_new_banner{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		position: relative;
/*
		background-image: url(img/mytripboat_vbg.webp) ;
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
*/
	}
	
/*
	#home_new_banner video{
		position: absolute;
		top: 0;
		width: 100%;
		height: 100%;
		z-index: -1
	}
*/
	
.search_box_n {
    background: #f26051;
    border: 0.1rem #f3f3f4 solid;
    padding: 1rem ;
    max-width: 55rem;
    margin: 0 auto;
    box-shadow: 0 4px 15px 0 rgb(0 0 0 / 10%);
}
	
	
	.search_box_n input{min-height: 36px; font-size: 1.8rem; background: rgba(0,0,0,0); color: #fff;}
	
	
	.search_box_n input::-webkit-input-placeholder { /* Edge */
  color: #fff;
}

.search_box_n input:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: #fff;
}

.search_box_n input::placeholder {
  color: #fff;
}
	
	
	.search_box_n button{min-height: 36px; background: #fff;border-radius: 12px!important; color: #fff;font-size: 1.8rem;}
	
	
	@media(min-width:591px){
		#home_new_banner{
		display: flex;
		justify-content: center;
		align-items: center;
			height: 100vh;
/*			background: #ccc;*/
	}
	
		.search_box_n {
			border-radius: 1rem;
		max-width: 55rem;
		width: 1000px;
		}
		
		.search_box_n {
    background: #ffffff;
    border: 0.1rem #f3f3f4 solid;
    padding: 1rem ;
    max-width: 55rem;
    margin: 0 auto;
    box-shadow: 0 4px 15px 0 rgb(0 0 0 / 10%);
}
	.search_box_n input{min-height:60px; font-size: 2.2rem; color: #333;}
			.search_box_n input::-webkit-input-placeholder { /* Edge */
  color: #333;
}

.search_box_n input:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: #333;
}

.search_box_n input::placeholder {
  color: #333;
}
	.search_box_n button{min-height: 60px; background: #f26051;border-radius: 12px!important; color: #fff;font-size: 2.2rem;}
		.search_box_n button img{filter: invert();}
		
		
		
	}
	
	
	
	
/*
	.video-back{display:block;padding:200px 0px;text-align:center;}
.video-back h1{display:block;text-align:center;color:#fff;text-shadow:2px 2px 4px #000;font-size:50px;}
.video-back p{display:block;color:#fff;text-shadow:2px 2px 1px #000;margin:0px;padding:0px;margin-bottom:20px;}
.video-back img{width:150px;}
*/

</style>






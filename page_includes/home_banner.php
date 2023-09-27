  <!--..................banner start....................-->
  <section class="home_banner_section">
    <div id="home_banner" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators"> </div>
      <div class="carousel-inner">
        <div class="carousel-item active"> <img src="img/home_banner.jpg" class="d-block w-100" alt="...">
          <div class="carousel-caption d-none d-md-block">
            <h1>Travel and Create your travel story share to the world</h1>
            <h4>We are exploring the world of travel and 
              let you chose your destinations.</h4>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#home_banner" data-bs-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="visually-hidden">Previous</span> </button>
        <button class="carousel-control-next" type="button" data-bs-target="#home_banner" data-bs-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="visually-hidden">Next</span> </button>
      </div>
    </div>
  </section>
  <!--..................banner end....................-->
  <!-- ...search start... -->
  <div class="home_search search_area">
    <div class="search_box">
      <form name="searchTour" id="searchTour" method="get" action="search-tour">
        <div class="form_group">
          <label>Wish your destination</label>
          <input class="text_input place" id="search_where" name="search_where" type="text" placeholder="Where">
        </div>
        <div class="form_group">
          <label>Date</label>
          <input type="text" class="date" id="datepicker" name="search_when" placeholder="When">
        </div>
        <button type="submit">Find</button>
      </form>
    </div>
  </div>
  <!-- ...search end... -->
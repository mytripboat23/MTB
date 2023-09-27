<!-- Filtered js-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Filtered js end-->
<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="dist/js/webslidemenu.js.download"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="h//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
-->
<script>
  /* When the user clicks on the button, 
  toggle between hiding and showing the dropdown content */
  function myFunction() 
  {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  
  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }

  var validateForm = function() {
  var checks = $('input[type="checkbox"]:checked').map(function() {
    return $(this).val();
  }).get()
  console.log(checks);
  return false;
}




  $( function() {
    $( "#datepicker" ).datepicker({
	 minDate: 1,
	 dateFormat: 'dd-mm-yy',
	});
	
	$( "#datepicker_m" ).datepicker({
	 minDate: 1,
	 dateFormat: 'dd-mm-yy',
	});
  } );



  $(document).ready(function(){
	
	$(".add_section").click(function() {
		$(".right_body").toggleClass('show_add');	
	});


  

  $(document).ready(function(){
    
    $(".pack_jist").click(function() {
      $(".pack_jist-field").toggleClass('show-in-mobile');	
    });
  });

});


function set_unset_pck_like(pck_id,user_id)
   {
   		$.ajax({
				url: configUrl+'package_like',
				type: 'POST',
				data: {'pck_id':pck_id,'user_id':user_id},
				success: function(response){
					//alert(response);
				  var data = response.split("||");
					if(data[0] != 0){
						//alert(data[1]);
						if(data[0]==1) $('#pckl_'+pck_id).removeClass("active");
						else $('#pckl_'+pck_id).addClass("active");
						
						$('#pck_count_'+pck_id).html(data[2]);
						//window.location.href = window.location.href;
					}else{
						//alert(data[1]);
					}
				},
			});
   }
   
   
   function set_unset_story_like(ts_id,user_id)
   {
   		$.ajax({
				url: configUrl+'story_like',
				type: 'POST',
				data: {'ts_id':ts_id,'user_id':user_id},
				success: function(response){
					//alert(response);
				  var data = response.split("||");
					if(data[0] != 0){
						if(data[0]==1) $('#tsl_'+ts_id).removeClass("active");
						else $('#tsl_'+ts_id).addClass("active");
						
						$('#story_count_'+ts_id).html(data[2]);
						//window.location.href = window.location.href;
					}else{
						//alert(data[1]);
					}
				},
			});
   }


  $(document).ready(function() {
			$('.minus').click(function () {
				var $input = $(this).parent().find('input');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
		});
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
  </script>
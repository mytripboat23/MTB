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
-->
<script src="js/jquery.validate.js"></script>
<script>
  /* When the user clicks on the button, 
  toggle between hiding and showing the dropdown content */
  function myFunction() {
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
    $( "#datepicker" ).datepicker();
	$( "#datepicker_m" ).datepicker();
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

$().ready(function() {
	$("#contactHost").validate({
					rules: {
						cb_adult: "required",
						cb_query: "required",
						cb_agree: "required",	
					},
					messages: {
						cb_adult: "Please enter adults count",
						cb_query: "Please enter your query",
						cb_agree: "Please agree to our terms",			
					},
					 submitHandler: function(form) {
					 //alert($("#editPro").serialize());
            			$.ajax({
							url: configUrl+'contact_host',
							type: 'POST',
							data: $("#contactHost").serialize(),
							success: function(response){
								//alert(response);
							  var data = response.split("||");
								if(data[0] != 0){
									alert(data[1]);
									window.location.href = window.location.href;
								}else{
									alert(data[1]);
								}
							},
						});
						return false;
						
      				}
				});
});	

 

  </script>
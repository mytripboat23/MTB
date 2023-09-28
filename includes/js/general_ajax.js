function loadTestsForClinic(clinic_id){
	
	$.ajax({
			type: "POST",
			url: "tests_ajax.php?mode=generateTests&clinic_id="+clinic_id+"&jsid="+Math.random(),
			data: "",
			success: function(msg){
				$('#PutTestsHere').html(msg);
			}
	});

}



function loadOptionField(no_of_fields){	
	jQuery.ajax({
			type: "POST",
			url: "general_ajax.php?mode=addOptionFields&no_of_fields="+no_of_fields+"&jsid="+Math.random(),
			data: "",
			success: function(msg){
				jQuery('#load_option_fields').html(msg);
			}
	});

}


red_ball = new Image(); 
red_ball.src = "images/red_ball.png"; 
green_ball = new Image(); 
green_ball.src = "images/green_ball.png"; 



function update_show_front(logo_id){
	jQuery.ajax({
			type: "POST",
			url: "general_ajax.php?mode=UpdateShowOnFront&logo_id="+logo_id,
			data: "",
			success: function(msg){
				if(msg == 1){
					document.getElementById("ball_"+logo_id).src = green_ball.src;
				} else if( msg == 0){
					document.getElementById("ball_"+logo_id).src = red_ball.src;
				} else {
					alert('You can select at most 6 logos to show on front end.')
				}
			}
	});
}

var $afo = jQuery.noConflict();

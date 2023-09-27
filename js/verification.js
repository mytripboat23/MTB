 
function otp_validation()
{
		var otp = jQuery('#otp_val').val();
		var otp_len = jQuery('#otp_val').val().length;
		var valid = 1;
		var otp_pattern=/^\d+$/;
		if(otp_pattern.test(otp)==false)
		{
				jQuery('#otp_val_message').css('border-color', 'red');
				$("#otp_val_message").html("OTP required");
				$("#otp_val_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
		}
		else
		{
			$('#otp_val_message').css("border", "1px solid green");
			$('#otp_val').css("border", "1px solid green");
			$("#otp_val_message").hide();
			
		}
		
		if(valid)
		{
			document.getElementById("otpVerification").submit(); 		
		}
		else
		{
		    return false;
		}
}

function otp_run_validation(otp,id,msgId)
{

		var otp_pattern=/^\d+$/;
		if(otp_pattern.test(otp)==false)
		{
				$("#"+msgId).show();
				$("#"+msgId).html("OTP required");
				$("#"+msgId).addClass("notification");
				//$(".labelmsg").addClass("note_label");
		}
		else
		{
			$("#"+msgId).css("border", "1px solid green");
			$('#'+id).css("border", "1px solid green");
			$("#"+msgId).hide();
			
		}
}


var el = document.getElementById('reset_time');
var seconds = 120;

function incrementSeconds() {
    seconds -= 1;
	if(seconds>=0) el.innerText = seconds + " seconds.";
	else el.innerHTML  = '<a class="reset_otp" href="#" onClick="resend_activation();">Reset</a>';
}

var cancel = setInterval(incrementSeconds, 1000);


function resend_activation()
{
		   //alert(configUrl);
			 $.ajax({

				url: configUrl+'resend_otp_code.php?resend=Yes',
				type: 'POST',
				//dataType: 'json',
				success: function(data){
				//alert(data);
				   $("#reset_time").html('120 Seconds');
				   seconds = 120;
					Swal.fire({
					  title: 'Success!',
					  text: data,
					  icon: 'success',
					  confirmButtonText: 'OK'
					})
				},
				error: function(data){
					alert("We are facing some technical issues. Please try after sometimes.");

				} 

			});
}
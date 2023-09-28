function profile_data_validation()
{
			var us_phone = jQuery('#us_phone').val();
			var valid = 1;
		    if(us_phone!="")
			{
				if(isNaN(us_phone))
				{
					$("#us_phone_message").html("Phone number should be numeric");
					valid = 0;
				}
				else if(us_phone.length!=10)
				{
					$("#us_phone_message").html("Phone number should be 10 digits");
					valid = 0;
				}
			}
			
			
		if(valid)
		{
				$.ajax({
					url: configUrl+'update_profile.php',
					type: 'POST',
					data: $("#editPro").serialize(),
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
		}
		else
		{
		    return false;
		}
}


function recaptchaCallback()
{
   $("#g-recaptcha_message").html("");
}


function SignIn_validation()
{
			var login_email = $('#login_email').val();
			var login_pass  = $('#login_pw').val();
			var valid = 1;

			if (login_email == "") 
			{
				$('#login_email').css('border-color', 'red');
				$("#login_email_message").html("Valid email address required");
				$("#login_email_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				$('#login_email').css('border-color', 'black');
				$("#login_email_message").html("");
			}
			
			var mail_pattern=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			if(mail_pattern.test(login_email)==false)
			{
				$('#login_email').css("border-color", "red");
				
				$("#login_email_message").html("Valid email address required");
			    $("#login_email_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				$('#login_email').css('border-color', 'black');
				$("#login_email_message").html("");
			}
			

			if(login_pass=="")
			{
				
				$('#login_pw').css("border-color", "red");
				$("#login_pw_message").html("Password required");
				$("#login_pw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
				return false;
			}
			else
			{
				$('#login_passw').css('border-color', 'black');
				$("#login_pw_message").html("");
			}
			
			
			if((parseInt(login_pass.length)<8) || (parseInt(login_pass.length)>20))
			{
				$('#login_pw').css("border-color", "red");
				//$('#user_pass_message').val('');
				//$("#user_pass_message").show();
				$("#login_pw_message").html("Password Should be Minimum 8 and Maximum 20 characters");
				$("#login_pw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				$('#login_pw').css('border-color', 'black');
				$("#login_pw_message").html("");
			}
			
			/*var captchResponse = $('#g-recaptcha-response').val();
			if(captchResponse.length == 0 )
			{
				$("#g-recaptcha_message").html("Please verify you are a human");
				valid = 0;
			}
			else 
			{
				$("#g-recaptcha_message").html("");
			}*/
			
			 
			
			
		if(valid)
		{
			document.getElementById("loginTrip").submit(); 		
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

function signUp_validation()
{
			var user_full_name = jQuery('#user_full_name').val();
			var user_email_id = jQuery('#user_email_id').val();
			var user_dob = jQuery('#user_dob').val();
			var user_passw = jQuery('#user_passw').val();
			var user_cpassw = jQuery('#user_cpassw').val();

			var valid = 1;
			if (user_full_name == '') 
			{
				jQuery('#user_full_name').css('border-color', 'red');
				$("#user_full_name_message").html("Full name required");
				$("#user_full_name_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			} 
			else
			{
				jQuery('#user_full_name').css('border-color', 'black');
				$("#user_full_name_message").html("");
			}
			if (user_email_id == "") 
			{
				jQuery('#user_email_id').css('border-color', 'red');
				$("#user_email_message").html("Valid email address required");
				$("#user_email_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_email_id').css('border-color', 'black');
				$("#user_email_message").html("");
			}
			
			var mail_pattern=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			if(mail_pattern.test(user_email_id)==false)
			{
				jQuery('#user_email_id').css("border-color", "red");
				jQuery('#user_email_id').val('');
				$("#user_email_message").html("Valid email address required");
			    $("#user_email_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_email_id').css('border-color', 'black');
				$("#user_email_message").html("");
			}
			if (user_dob == '') 
			{
				jQuery('#user_dob').css('border-color', 'red');
				$("#user_dob_message").html("Date of birth required");
				$("#user_dob_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_dob').css('border-color', 'black');
				$("#user_dob_message").html("");
			}

			if(user_passw=="")
			{
				jQuery('#user_passw').css("border-color", "red");
				$("#user_pw_message").html("Password required");
				$("#user_pw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_passw').css('border-color', 'black');
				$("#user_pw_message").html("");
			}
			
			
			if((parseInt(user_passw.length)<8)&&(parseInt(user_passw.length)>20))
			{
				$('#user_passw').css("border-color", "red");
				//$('#user_pass_message').val('');
				//$("#user_pass_message").show();
				$("#user_passw_message").html("Password Should be Minimum 8 and Maximum 20 characters");
				$("#user_passw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_passw').css('border-color', 'black');
				$("#user_passw_message").html("");
			}
			
			if(user_cpassw=="")
			{
				jQuery('#user_cpassw').css("border-color", "red");
				//$("#user_cpassw_message").show();
				$("#user_cpw_message").html("Confirm password required");
				$("#user_cpw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_cpassw').css('border-color', 'black');
				$("#user_cpw_message").html("");
			}
			if(user_cpassw!=user_passw)
			{
				jQuery('#user_cpassw').css("border-color", "red");
				//$("#user_cpass_message").show();
				$("#user_cpassw_message").html("Password & Confirm password should be same");
				$("#user_cpassw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}			
			else
			{
				jQuery('#user_cpassw').css('border-color', 'black');
				$("#user_cpassw_message").html("");
			}
			if((parseInt(user_cpassw.length)<8)&&(parseInt(user_cpassw.length)>20))
			{
				$('#user_cpassw').css("border-color", "red");
				//$('#user_pass_message').val('');
				//$("#user_pass_message").show();
				$("#user_cpassw_message").html("Confirm password Should be Minimum 8 and Maximum 20 characters");
				$("#user_cpassw_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#user_cpassw').css('border-color', 'black');
				$("#user_cpassw_message").html("");
			}
			if($('#user_agree').is(":checked")==false)
			{
				$('#user_agree').css("border-color", "red");
				$("#user_agree_message").html("Please agree to our terms");
				$("#user_agree_message").addClass("notification");
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
					document.getElementById("userRegistration").submit(); 		
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


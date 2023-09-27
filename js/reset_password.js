function ResetPass_validation_1()
{
			var login_email = $('#reg_email_id').val();
			var valid = 1;

			if (login_email == "") 
			{
				$('#reg_email_id').css('border-color', 'red');
				$("#reg_email_id_message").html("Valid email address required");
				$("#reg_email_id_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				$('#reg_email_id').css('border-color', 'black');
				$("#reg_email_id_message").html("");
			}
			
			var mail_pattern=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			if(mail_pattern.test(login_email)==false)
			{
				$('#reg_email_id').css("border-color", "red");
				
				$("#reg_email_id_message").html("Valid email address required");
			    $("#reg_email_id_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				$('#reg_email_id').css('border-color', 'black');
				$("#reg_email_id_message").html("");
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
			document.getElementById("resetPassword").submit(); 		
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


function ResetPass_validation_2()
{
		var user_passw = jQuery('#set_pass').val();
		var user_cpassw = jQuery('#set_re_pass').val();
		var otp = jQuery('#otp_val').val();
		var otp_len = jQuery('#otp_val').val().length;
		var valid = 1;
		var otp_pattern=/^\d+$/;
		
		
		    if(user_passw=="")
			{
				jQuery('#set_pass').css("border-color", "red");
				$("#set_pass_message").html("Password required");
				$("#set_pass_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#set_pass').css('border-color', 'black');
				//$("#set_pass_message").html("");
			}
			
			
			if((parseInt(user_passw.length)<8)&&(parseInt(user_passw.length)>20))
			{
				$('#set_pass').css("border-color", "red");
				//$('#user_pass_message').val('');
				//$("#user_pass_message").show();
				$("#set_pass_message").html("Password Should be Minimum 8 and Maximum 20 characters");
				$("#set_pass_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#set_pass').css('border-color', 'black');
				//$("#set_pass_message").html("");
			}
			
			if(user_cpassw=="")
			{
				jQuery('#set_re_pass').css("border-color", "red");
				//$("#user_cpassw_message").show();
				$("#set_re_pass_message").html("Confirm password required");
				$("#set_re_pass_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#set_re_pass').css('border-color', 'black');
				//$("#set_re_pass_message").html("");
			}
			if(user_cpassw!=user_passw)
			{
				jQuery('#set_re_pass').css("border-color", "red");
				//$("#user_cpass_message").show();
				$("#set_re_pass_message").html("Password & Confirm password should be same");
				$("#set_re_pass_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}			
			else
			{
				jQuery('#set_re_pass').css('border-color', 'black');
				//$("#set_re_pass_message").html("");
			}
			if((parseInt(user_cpassw.length)<8)&&(parseInt(user_cpassw.length)>20))
			{
				$('#set_re_pass').css("border-color", "red");
				//$('#user_pass_message').val('');
				//$("#user_pass_message").show();
				$("#set_re_pass_message").html("Confirm password Should be Minimum 8 and Maximum 20 characters");
				$("#set_re_pass_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			}
			else
			{
				jQuery('#set_re_pass').css('border-color', 'black');
				//$("#set_re_pass_message").html("");
			}
			
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
			document.getElementById("resetPasswordFinal").submit(); 		
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

var url = 'http://localhost/travelc/';
function full_name_validation(fullname,id,msgId)
{
	     var name_pattern= /^[a-zA-Z\s]+$/;
	     if(fullname=="")
			{
				$('#'+id).css("border-color", "red");
				$("#"+msgId).html("This field is required");
				$("#"+msgId).addClass("notification");
			}
			if((fullname!="")&&(name_pattern.test(fullname)==false)&&((parseInt(fullname.length)>=1)&&(parseInt(fullname.length)<=40)))
			{
			
				$('#'+id).css("border-color", "red");
				$("#"+msgId).html("Numbers are not permitted");
				$("#"+msgId).addClass("notification");
			  
			}
			if((fullname!="")&&(name_pattern.test(fullname)==true)&&(parseInt(fullname.length)<=1)||(parseInt(fullname.length)>40))
			{
				$('#'+id).css("border-color", "red");
				$("#"+msgId).html("Enter your full name - minimum 2 and maximum of 40 characters");
				$("#"+msgId).addClass("notification");
			    
			}
			 if((fullname!="")&&(name_pattern.test(fullname)==true)&&(parseInt(fullname.length)>=2)&&(parseInt(fullname.length)<=40))
			{
			    $('#'+id).css("border-color", "green");
				$("#"+msgId).html("");
				$("#"+msgId).removeClass("notification");
			   return false;
			   
			}
			return false;
}

function email_exist_validation(email,id,msgId)
{
		$("#"+msgId).html("");
		if(email=="")
		{
			$('#'+id).css("border-color", "red");
			$("#"+msgId).html("This field is required");
			$("#"+msgId).addClass("notification");
			$("#"+msgId).addClass("note_label");
		}
		//Check minimum valid length of an Email.
        if (email.length < 2) {
            	$('#'+id).css("border-color", "red");
            	$("#"+msgId).html("Minimum of 2 characters required");
				$("#"+msgId).addClass("notification");
            return false;
        }
        //If whether email has @ character.
        if (email.indexOf("@") == -1) {
            	$('#'+id).css("border-color", "red");
            	$("#"+msgId).html("Email must contain @ character");
				$("#"+msgId).addClass("notification");
				$("#"+msgId).addClass("note_label");
            return false;
        }
 
        var parts = email.split("@");
        var dot = parts[1].indexOf(".");
        var len = parts[1].length;
        var dotSplits = parts[1].split(".");
        var dotCount = dotSplits.length - 1;
 
 
        //Check whether Dot is present, and that too minimum 1 character after @.
       /* if (dot == -1 || dot < 2 || dotCount > 2) {
            jQuery('#email').css("border-color", "red");
            $("#emailMessage").html("Minimum of 2 characters required after @ followed by . ");
			$("#emailMessage").addClass("notification");
			 $("#emaillabelmsg").addClass("note_label");
            return false;
        }*/
 
        //Check whether Dot is not the last character and dots are not repeated.
        /*for (var i = 0; i < dotSplits.length; i++) {
            if (dotSplits[i].length == 1) {
                jQuery('#email').css("border-color", "red");
                 $("#emailMessage").html("The . must be proceeded with min 2 characters");
				 $("#emailMessage").addClass("notification");
				  $("#emaillabelmsg").addClass("note_label");
				 
                return false;
            }
        }*/
        	
			
			if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
		    {
			    $('#'+id).css("border-color", "green");
				$('#'+msgId).html("");
				$('#'+msgId).removeClass("notification");
				$('#'+msgId).removeClass("note_label");
				return true
			
			}
			else
			{
				$('#'+id).css("border-color", "red");
            	$("#"+msgId).html("Please enter valid email id");
				$("#"+msgId).addClass("notification");
				$("#"+msgId).addClass("note_label");
				return false;
			}
				
				
				
				/*jQuery.ajax({
					type: "POST",
					url: configUrl+'common/configuration?no_html=1&existjobSeekerEmail='+jobSeekerEmail,
					success: function(data){
					if(data>='1')
					{
							jQuery('#email').css('border-color', 'red');
							$("#emailMessage").html(" ("+jobSeekerEmail+") Email Already Exist");
							 $("#emailMessage").addClass("notification");
							 $("#emaillabelmsg").addClass("note_label");
							jQuery('#email').val('');
							return false;

					}
					else
					{
						jQuery('#email').css('border-color', 'green');
						$("#emailMessage").html("");
						 $("#emailMessage").removeClass("notification");
				 		$("#emaillabelmsg").removeClass("note_label");
					}
					}
					});	*/
	}
	
	
function required_validation(value,fldName,id,msgId)
{
	$("#"+msgId).html("");
	if(value=="")
	{
		$('#'+id).css("border-color", "red");
		$("#"+msgId).html("Please enter "+fldName);
		$("#"+msgId).addClass("notification");
		$("#"+msgId).addClass("note_label");
	}	
}

function password_validation(password,id,msgId)
{
		if(password=="")
			{
				jQuery('#'+id).css("border-color", "red");
				jQuery("#"+msgId).html("Enter Password");
				jQuery("#"+msgId).addClass("notification");
			}
			var password_pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,16}$/;
			 if((password!="")&&(password_pattern.test(password)==false))
			{
				jQuery('#'+id).css("border-color", "red");
				jQuery("#"+msgId).html("Enter new password - minimum 8, maximum 16 digits, with at least one uppercase, one lowercase letter, one number & one special character");
				jQuery("#"+msgId).addClass("notification");
			}
			if((password!="")&&(password_pattern.test(password)==true))
			{
				jQuery('#'+id).css("border-color", "green");
				jQuery("#"+msgId).html("");
				jQuery("#"+msgId).removeClass("notification");
				 
		        
			}
			return false;
}

function cpassword_validation(password,cpassword,fld1,fld2,id,msgId)
{
		if(password=="")
			{
				jQuery('#'+id).css("border-color", "red");
				jQuery("#"+msgId).html("Enter Password");
				jQuery("#"+msgId).addClass("notification");
			}
			var password_pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,16}$/;
			 if((password!="")&&(password_pattern.test(password)==false))
			{
				jQuery('#'+id).css("border-color", "red");
				jQuery("#"+msgId).html("Enter new password - minimum 8, maximum 16 digits, with at least one uppercase, one lowercase letter, one number & one special character");
				jQuery("#"+msgId).addClass("notification");
			}
			if((password!="")&&(password_pattern.test(password)==true))
			{
				jQuery('#'+id).css("border-color", "green");
				jQuery("#"+msgId).html("");
				jQuery("#"+msgId).removeClass("notification");
			}
			if(password!=cpassword)
			{
				jQuery('#'+id).css("border-color", "red");
				jQuery("#"+msgId).html(fld1+ " and "+fld2+" should be same");
				jQuery("#"+msgId).addClass("notification");
			}
			return false;
}
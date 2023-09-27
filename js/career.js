function career_validation()
{
			var car_name 		 = jQuery('#car_name').val();
			var car_con_num 	 = jQuery('#car_con_num').val();
			var car_email  	 	 = jQuery('#car_email').val();
			var car_qual      	 = jQuery('#car_qual').val();
			var car_pos  	 	 = jQuery('#car_pos').val();


			
			var valid = 1;
			if(car_name == '') 
			{
				$("#car_name_message").html("Name required");
				$("#car_name_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			} 
			else
			{
				$("#car_name_message").html("");
			}
			if(car_con_num == "") 
			{
				$("#car_con_num_message").html("Contact Number required");
				$("car_con_num_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#_message").html("");
			}
			
			if(car_email == "") 
			{
				$("#car_email_message").html("Contact email required");
				$("#car_email").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#car_email").html("");
			}
			
	
			if(car_qual == "") 
			{
				$("#car_qual_message").html("Contact email required");
				$("#car_qual_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#car_qual_message").html("");
			}
			
			if(car_pos == "") 
			{
				$("#car_pos_message").html("Position apply for required");
				$("#car_pos_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#car_pos_message").html("");
			}
			
			
			if($("#car_res").val()=="")
			{
				$("#car_res_message").html("resume is required");
				valid = 0;
			}
			else
			{
				var $input = $("#car_res"); 
				/* collect list of files choosen */
				var files = $input[0].files; 
			
				var filename = files[0].name;    
								
				/* getting file extenstion eg- .jpg,.png, etc */
				var extension = filename.substr(filename.lastIndexOf("."));
				var allowedExtensionsRegx = /(\.doc|\.docx|\.pdf)$/i; 
				/* testing extension with regular expression */
				var isAllowed = allowedExtensionsRegx.test(extension);
					
				if(isAllowed){
					$("#car_res_message").html("");
				}else{
					$("#car_res_message").html("Invalid file type");
					valid = 0;
				}
			}
			

			
		if(valid)
		{
			document.getElementById("careerApply").submit(); 	
			//$("#share_box").modal('show');
		}
		else
		{
		    return false;
		}
}


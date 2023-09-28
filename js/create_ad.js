function create_ad_validation()
{
			var pck_name 		 = jQuery('#pck_name').val();
			var comp_name 		 = jQuery('#comp_name').val();
			var contact_name  	 = jQuery('#contact_name').val();
			var contact_num      = jQuery('#contact_num').val();
			
			var contact_email  	 = jQuery('#contact_email').val();
			var ad_subject     	 = jQuery('#ad_subject').val();
			var ad_desc     	 = jQuery('#ad_desc').val();
			var files = ad_file.files;
			
			var valid = 1;
			if(pck_name == '') 
			{
				$("#pck_name_message").html("Package name required");
				$("#pck_name_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			} 
			else
			{
				$("#pck_name_message").html("");
			}
			if(comp_name == "") 
			{
				$("#comp_name_message").html("Company name required");
				$("#comp_name_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#comp_name_message").html("");
			}
			
			if(contact_name == "") 
			{
				$("#contact_name_message").html("Contact name required");
				$("#contact_name_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#contact_name_message").html("");
			}
			
			if(contact_num == "") 
			{
				$("#contact_num_message").html("Contact number required");
				$("#contact_num_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#contact_num_message").html("");
			}
			
			if(contact_email == "") 
			{
				$("#contact_email_message").html("Contact email required");
				$("#contact_email_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#contact_email_message").html("");
			}
			
			if(ad_subject == "") 
			{
				$("#ad_subject_message").html("Subject required");
				$("#ad_subject_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#ad_subject_message").html("");
			}
			
			if(ad_desc == "") 
			{
				$("#ad_desc_message").html("Description required");
				$("#ad_desc_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#ad_desc_message").html("");
			}
			
			
			
			if($("#ad_file").val()=="")
			{
				$("#ad_file_message").html("Image file is required");
				valid = 0;
			}
			else
			{
				var $input = $("#ad_file"); 
				/* collect list of files choosen */
				var files = $input[0].files; 
			
				var filename = files[0].name;    
								
				/* getting file extenstion eg- .jpg,.png, etc */
				var extension = filename.substr(filename.lastIndexOf("."));
				var allowedExtensionsRegx = /(\.jpg|\.jpeg|\.png|\.gif)$/i; 
				/* testing extension with regular expression */
				var isAllowed = allowedExtensionsRegx.test(extension);
					
				if(isAllowed){
					$("#ad_file_message").html("");
				}else{
					$("#ad_file_message").html("Invalid file type");
					valid = 0;
				}
			}
			

			
		if(valid)
		{
			document.getElementById("createAd").submit(); 	
			//$("#share_box").modal('show');
		}
		else
		{
		    return false;
		}
}


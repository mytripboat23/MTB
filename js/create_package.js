function create_package_validation()
{
			var pck_title 		= jQuery('#pck_title').val();
			var pck_dest		= jQuery('#pck_dest').val();
			var pck_month 		= jQuery('#pck_month').val();
			var pck_start_date  = jQuery('#pck_start_date').val();
			var pck_start_tm	= jQuery('#pck_start_tm').val();
			
			var pck_start_pt    = jQuery('#pck_start_pt').val();
			var pck_end_pt      = jQuery('#pck_end_pt').val();
			var pck_end_date     = jQuery('#pck_end_date').val();
			var pck_capacity     = jQuery('#pck_capacity').val();
			var pck_price        = jQuery('#pck_price').val();
			//var pck_discount_price  = jQuery('#pck_discount_price').val();
			
			//var pck_description  = jQuery('#pck_description').val();

			//var pck_my_terms  	 = jQuery('#pck_my_terms').val();
			var pck_post_terms   = jQuery('#pck_post_terms').val();
			
			
			
			
			var valid = 1;
			
			if(pck_dest == "") 
			{
				$("#pck_dest_message").html("Destination required");
				$("#pck_dest_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_dest_message").html("");
			}
			if(pck_month== '') 
			{
				$("#pck_month_message").html("Month required");
				$("#pck_month_message").addClass("notification");
				//$(".labelmsg").addClass("note_label");
				valid = 0;
			} 
			else
			{
				$("#pck_month_message").html("");
			}
			
			/*if(pck_start_date == "") 
			{
				$("#pck_start_date_message").html("Package start date required");
				$("#pck_start_date_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_start_date_message").html("");
			}
			
			if(pck_start_time == "") 
			{
				$("#pck_start_time_message").html("Package start time required");
				$("#pck_start_time_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_start_time_message").html("");
			}*/
			
			if(pck_start_pt == "") 
			{
				$("#pck_start_pt_message").html("Package start point required");
				$("#pck_start_pt_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_start_pt_message").html("");
			}
			
			if(pck_end_pt == "") 
			{
				$("#pck_end_pt_message").html("Package end point required");
				$("#pck_end_pt_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_end_pt_message").html("");
			}
			
			/*if(pck_end_date == "") 
			{
				$("#pck_end_date_message").html("Package end date required");
				$("#pck_end_date_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_end_date_message").html("");
			}*/
			
			if(pck_capacity == "") 
			{
				$("#pck_capacity_message").html("Seat Available required");
				$("#pck_capacity_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_capacity_message").html("");
			}
			
			if(pck_price == "") 
			{
				$("#pck_price_message").html("Package price required");
				$("#pck_price_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_price_message").html("");
			}
			
			/*if(pck_discount_price == "") 
			{
				$("#pck_discount_price_message").html("Package end date required");
				$("#pck_discount_price_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_discount_price_message").html("");
			}*/
			
				
			
			if(!$("#pck_post_terms").is(':checked'))
			{
				$("#pck_post_terms_message").html("Please accept our terms");
				$("#pck_post_terms_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_post_terms_message").html("");
			}
			
			
		if(valid)
		{
			//document.getElementById("createPackage").submit(); 	
			//$("#share_box").modal('show');
			create_package();
		}
		else
		{
		    return false;
		}
}

function edit_package_validation()
{
			var pck_name 		= jQuery('#pck_name').val();
			var pck_destination = jQuery('#pck_destination').val();
			var pck_start_date  = jQuery('#pck_start_date').val();
			var pck_start_time  = jQuery('#pck_start_time').val();
			
			var pck_start_point  = jQuery('#pck_start_point').val();
			var pck_end_date     = jQuery('#pck_end_date').val();
			var pck_capacity     = jQuery('#pck_capacity').val();
			var pck_price        = jQuery('#pck_price').val();
			var pck_discount_price  = jQuery('#pck_discount_price').val();
			
			//var pck_description  = jQuery('#pck_description').val();

			//var pck_my_terms  	 = jQuery('#pck_my_terms').val();
			var pck_post_terms   = jQuery('#pck_post_terms').val();
			
			
			
			
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
			if(pck_destination == "") 
			{
				$("#pck_destination_message").html("Package destination required");
				$("#pck_destination_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_destination_message").html("");
			}
			
			if(pck_start_date == "") 
			{
				$("#pck_start_date_message").html("Package start date required");
				$("#pck_start_date_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_start_date_message").html("");
			}
			
			if(pck_start_time == "") 
			{
				$("#pck_start_time_message").html("Package start time required");
				$("#pck_start_time_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_start_time_message").html("");
			}
			
			if(pck_start_point == "") 
			{
				$("#pck_start_point_message").html("Package start point required");
				$("#pck_start_point_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_start_point_message").html("");
			}
			
			if(pck_end_date == "") 
			{
				$("#pck_end_date_message").html("Package end date required");
				$("#pck_end_date_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_end_date_message").html("");
			}
			
			if(pck_capacity == "") 
			{
				$("#pck_capacity_message").html("Seat Available required");
				$("#pck_capacity_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_capacity_message").html("");
			}
			
			if(pck_price == "") 
			{
				$("#pck_price_message").html("Package end date required");
				$("#pck_price_message").addClass("notification");
				valid = 0;
			}
			else
			{
				$("#pck_price_message").html("");
			}
			
			
			
			if(valid)
			{
				//document.getElementById("createPackage").submit(); 	
				//$("#share_box").modal('show');
				create_package();
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


function create_package()
{
		document.getElementById("createPackage").submit(); 	
}

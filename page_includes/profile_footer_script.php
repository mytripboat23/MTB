<!-- Filtered js-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Filtered js end-->
<!--
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="dist/js/webslidemenu.js.download"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
-->
<script src="js/jquery.validate.js"></script>
<script>
  /* When the user clicks on the button, 
  toggle between hiding and showing the dropdown content */
  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  
  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }




  var validateForm = function() {
  var checks = $('input[type="checkbox"]:checked').map(function() {
    return $(this).val();
  }).get()
  console.log(checks);
  return false;
}




  $( function() {
    $( "#datepicker" ).datepicker();
	$( "#datepicker_m" ).datepicker();
  } );



  $(document).ready(function(){
	
	$(".add_section").click(function() {
		$(".right_body").toggleClass('show_add');	
	});


  

  $(document).ready(function(){
    
    $(".pack_jist").click(function() {
      $(".pack_jist-field").toggleClass('show-in-mobile');	
    });
  });

});

$(document).ready(function(){
    $("#profileImageFile").on('change',function(){
        upload_profile_photo();
    });
});
$(document).ready(function(){
    $("#profileBannerFile").on('change',function(){
        upload_banner_photo();
    });
});

    function upload_profile_photo()
	{
        var fd = new FormData();
        var files = $('#profileImageFile')[0].files[0];
        fd.append('file',files);
        $.ajax({
            url: configUrl+'upload_profile_pic',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
			var data = response.split("||");
                if(data[0] != 0){
                    $("#pFile").css("background-image", "url(" + data[1] + ")");
                    //$(".preview img").show(); // Display image element
                }else{
                    alert(data[2]);
                }
            },
        });
	}
		function upload_banner_photo()
		{
        var fd = new FormData();
        var files = $('#profileBannerFile')[0].files[0];
        fd.append('file',files);
        $.ajax({
            url: configUrl+'upload_banner_pic',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
              var data = response.split("||");
                if(data[0] != 0){
                    $("#bFile").css("background-image", "url(" + data[1] + ")");
                    //$(".preview img").show(); // Display image element
                }else{
                    alert(data[2]);
                }
            },
        });
}


/*$(document).ready(function() {
	$("#us_city").keyup(function() {
	if($(this).val().length>=3)
	{
		$.ajax({
			type: "POST",
			url: configUrl+"search_city",
			data: 'keyword=' + $(this).val()+'&type=live_city',
			beforeSend: function() {
				$("#suggestion-us-city").css("background", "#CCC url(LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data) {
				$("#suggestion-us-city").show();
				$("#suggestion-us-city").html('');
				$("#suggestion-us-city").html(data);
				$("#suggestion-us-city").css("background", "#CCC");
			}
		});
		}
	});
});
$(document).ready(function() {
	$("#us_from_city").keyup(function() {
	if($(this).val().length>=3)
	{
		$.ajax({
			type: "POST",
			url: configUrl+"search_city",
			data: 'keyword=' + $(this).val()+'&type=city',
			beforeSend: function() {
				$("#suggestion-us-from-city").css("background", "#CCC url(LoaderIcon.gif) no-repeat 165px");
			},
			success: function(data) {
				$("#suggestion-us-from-city").show();
				$("#suggestion-us-from-city").html('');
				$("#suggestion-us-from-city").html(data);
				$("#suggestion-us-from-city").css("background", "#CCC");
			}
		});
		}
	});
});
//To select a country name
function selectCity(val,id) {
	$("#us_city").val(val);
	$("#us_city_hid").val(id);
	$("#suggestion-us-city").hide();
}
function selectFromCity(val,id) {
	$("#us_from_city").val(val);
	$("#us_from_city_hid").val(id);
	$("#suggestion-us-from-city").hide();
}*/
	
		$().ready(function() {
			$("#editPro").validate({
					rules: {
						 
						us_city: "required",
						us_from_city: "required",						
						us_phone: 
							{
								required:true,
								digits:true,
								maxlength:10,
								minlength:10
							},
						us_dob: {date:true}		
							
					},
					messages: {
						 
						us_city: "Please enter your current city",
						us_from_city: "Please enter your origin city",	
						us_phone: {
							required:"Please enter phone number",
							digits:"Please enter a valid phone number",
							maxlength:"Phone number should be 10 digits",
							minlength:"Phone number should be 10 digits"
						},
						us_dob: {date:"Please select a proper date"}		
					},
					 submitHandler: function(form) {
					 //alert($("#editPro").serialize());
            			$.ajax({
							url: configUrl+'update_profile',
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
						return false;
						
      				}
				});
				
			
			$("#contactHost").validate({
					rules: {
						cb_adult: "required",
						cb_query: "required",	
					},
					messages: {
						cb_adult: "required",
						cb_query: "required",			
					},
					 submitHandler: function(form) {
					 //alert($("#editPro").serialize());
            			$.ajax({
							url: configUrl+'contact_host',
							type: 'POST',
							data: $("#contactHost").serialize(),
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
						return false;
						
      				}
				});
				
				
				$("#updateId").validate({
					rules: {
						 
						id_num: "required",
						id_photo: "required",						
						id_self_photo: "required"							
					},
					messages: {
						 
						id_num: "Please enter ID number",
						id_photo: "Please upload ID photo",						
						id_self_photo: "Please upload self photo"			
					}
					
				});
		});			

	
	function send_companion_request(from_id,to_id)
	{
		$.ajax({
				url: configUrl+'companion_request',
				type: 'POST',
				data: {from:from_id,to:to_id,type:'send'},
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
	
	function remove_companion_request(from_id,to_id)
	{
		$.ajax({
				url: configUrl+'companion_request',
				type: 'POST',
				data: {'from':from_id,'to':to_id,'type':'remove'},
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

   function set_unset_pck_like(pck_id,user_id)
   {
   		$.ajax({
				url: configUrl+'package_like',
				type: 'POST',
				data: {'pck_id':pck_id,'user_id':user_id},
				success: function(response){
					//alert(response);
				  var data = response.split("||");
					if(data[0] != 0){
						//alert(data[0]);
						if(data[0]==1) $('#pckl_'+pck_id).removeClass("active");
						else $('#pckl_'+pck_id).addClass("active");
						
						$('#pck_count_'+pck_id).html(data[2]);
						//window.location.href = window.location.href;
					}else{
						//alert(data[1]);
					}
				},
			});
   }
   
   function set_unset_story_like(ts_id,user_id)
   {
   		$.ajax({
				url: configUrl+'story_like',
				type: 'POST',
				data: {'ts_id':ts_id,'user_id':user_id},
				success: function(response){
					//alert(response);
				  var data = response.split("||");
					if(data[0] != 0){
						if(data[0]==1) $('#tsl_'+ts_id).removeClass("active");
						else $('#tsl_'+ts_id).addClass("active");
						
						$('#story_count_'+ts_id).html(data[2]);
						//window.location.href = window.location.href;
					}else{
						//alert(data[1]);
					}
				},
			});
   }
   
   function display_more_tours()
   {
   	  var last_tour_id = $('#ldti').val();
	  $('#lstatus').val('start');
   		$.ajax({
				url: configUrl+'display_tours',
				type: 'POST',
				data: {'last_tour_id':last_tour_id},
				success: function(response){
					//alert(response);
				  	var data = response.split("||");
					$('.lt_first_column').append(data[0]);
					$('#ldti').val(data[1]);
					 $('#lstatus').val('stop');
				},
			});
   }
   
   function display_more_stories()
   {
   	  var last_story_id = $('#ldsi').val();
	  $('#lstatus').val('start');
   		$.ajax({
				url: configUrl+'display_stories',
				type: 'POST',
				data: {'last_story_id':last_story_id},
				success: function(response){
					//alert(response);
				  	var data = response.split("||");
					$('.lt_first_column').append(data[0]);
					$('#ldti').val(data[1]);
					 $('#lstatus').val('stop');
				},
			});
    }
  
  

  </script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.lang').select2();
    $('.hobb').select2();
	 //$('.single_list_tour').lazy();
});
</script>
<?php if($curPage=='dashboard.php'){?>
<script>
$(window).scroll(function() {
//alert($(window).scrollTop());
//alert($(window).height());
//alert($(document).height());
  if($(window).scrollTop() + $(window).height() >= $(document).height()-70) {
	  if($('#lstatus').val()=='stop')
	  {
  		 display_more_tours();
	  }
  }
});
</script>
<?php }?>
<?php if($curPage=='stories.php'){?>
<script>
$(window).scroll(function() {
//alert($(window).scrollTop());
//alert($(window).height());
//alert($(document).height());
  if($(window).scrollTop() + $(window).height() >= $(document).height()-70) {
	  if($('#lstatus').val()=='stop')
	  {
  		 display_more_stories();
	  }
  }
});
</script>
<?php }?>
<?php if($log_user_id == $user_id ){?>
<!-- .............edit profile modal  start............ -->
<div class="modal fade" id="reg_modal"  tabindex="-1" aria-labelledby="reg_modalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable " style="max-width: 600px">
    <div class="modal-content">
      <div id="" class="modal-body  edit_info">
        <form name="editPro" id="editPro" action="#" method="post" enctype="multipart/form-data">
          <div class="motal_top">
            <h4>Edit Profile</h4>
            <span>
            <input id="proSave" type="submit" name="proSave" value="Save" class="btn btn-primary" >
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </span> </div>
          <div class="form_row_forbanner">
            <div class="upload_imag_result">
              <div class="imageupload" id="bFile" style=" background-image: url(<?=$upbanner;?>); background-repeat: no-repeat;">
                <input type="file" name="profileBannerFile" id="profileBannerFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}">
              </div>
              <label class="bottom-semi-circle point_cursor" for="profileBannerFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>
            </div>
          </div>
          <div class="profile_self_img">
            <div class="upload_imag_result">
              <div class="imageupload" id="pFile" style=" background-image: url(<?=$upphoto;?>); background-repeat: no-repeat;">
                <input type="file" name="profileImageFile" id="profileImageFile" class="img-upload-input-bs" passurl="" pshape="circle" w="200" h="200" size="{200,200}" >
              </div>
              <label class="bottom-semi-circle point_cursor" for="profileImageFile"> <span class="material-symbols-outlined"> add_a_photo </span> </label>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label class="labelmsg">Display Name</label>
              <input type="text" name="us_display_name" id="us_display_name"  class="form-control" value="<?=$userPD['user_display_name'];?>" placeholder="Enter your display name">
              <span class="messages" id="us_display_name"></span> </div>
            <div class="col-sm-3">
              <label class="labelmsg">Phone number</label>
              <input type="text" name="us_phone" id="us_phone" rows="3" class="form-control" value="<?=$userPD['user_phone'];?>" placeholder="Enter your phone number">
              <span class="messages" id="us_phone_message"></span> </div>
            <div class="col-sm-3">
              <label class="labelmsg">Date of birth</label>
              <input maxlength="" type="date" name="us_dob" id="us_dob" class="form-control" value="<?=$userPD['user_dob'];?>" placeholder="Enter your date of birth">
            </div>
            <div class="col-sm-5 ">
              <div class="form-check form-switch mt-3">
                <label class="form-check-label" for="tour_operator"> I'm tour Operator. </label>
                <input class="form-check-input" type="checkbox" role="switch" id="tour_operator" name="tour_operator" value="y" onClick="set_opt()" <?php if($userPD['user_type']!=""){?> checked="checked"<?php }?>>
              </div>
            </div>
            <div class="col-sm-7">
              <div  class="mt-3" id="opt_biz_type" <?php if($userPD['user_type']==""){?> style="display:none"<?php }?>>
                <label>Type:</label>
                
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" value="b2b" id="opt_btb" name="opt_type[]"  <?php if(strpos($userPD['user_type'],'b2b')!== false){?> checked="checked"<?php }?> />
                  <label class="form-check-label" for="opt_btb"> B2B </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" value="b2c" id="opt_btc" name="opt_type[]"  <?php if(strpos($userPD['user_type'],'b2c')!== false){?> checked="checked"<?php }?> />
                  <label class="form-check-label" for="opt_btc"> B2C </label>
                </div>
              </div>
            </div>
            <div class="col-sm-12">
              <label class="labelmsg">Bio</label>
              <textarea  name="us_bio" id="us_bio"  class="form-control"  placeholder="Enter your Bio"><?=$userPD['user_bio'];?>
</textarea>
              <span class="messages" id="user_display_name"></span> </div>
            <div class="col-sm-6">
              <label class="labelmsg">Language</label>
              <select class="lang" name="us_lang[]" id="us_lang" multiple>
                <?=$obj->languageSelect($userPD['user_lang']);?>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="labelmsg">Hobby</label>
              <select class="hobb" name="us_hobby[]" id="us_hobby" multiple>
                <?=$obj->hobbySelect($userPD['user_hobby']);?>
              </select>
            </div>
            <div class="col-sm-6">
              <label class="labelmsg">Live</label>
              <input name="us_city" id="us_city"  type="text" class="form-control" value="<?=$userPD['user_city'];?>" placeholder="Enter where you live" >
            </div>
            <div class="col-sm-6">
              <label class="labelmsg">From</label>
              <input id="us_from_city" name="us_from_city" type="text" class="form-control" value="<?=$userPD['user_from_city'];?>" placeholder="Enter where you from">
            </div>
            <div class="col-sm-6">
              <label class="labelmsg">Website</label>
              <input type="text" name="us_website" id="us_website" class="form-control" value="<?=$userPD['user_website'];?>" placeholder="Enter your website">
            </div>
            <div class="col-sm-6">
              <label class="labelmsg">Gender</label>
              <select name="us_gender" class="form-control">
                <option value="m" <?=$userPD['user_gender']=="m"?'selected="selected"':'';?>>Male</option>
                <option value="f"  <?=$userPD['user_gender']=="f"?'selected="selected"':'';?>>Female</option>
                <option value="o"  <?=$userPD['user_gender']=="o"?'selected="selected"':'';?>>Other</option>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script language="javascript">
function set_opt()
{
	var isChecked = $("#tour_operator").is(":checked");
	if(isChecked)
	{
		$('#opt_biz_type').show();
	}
	else
	{
		 $("#opt_ind").prop("checked", false);
		  $("#opt_btb").prop("checked", false);
		   $("#opt_btc").prop("checked", false);
		$('#opt_biz_type').hide();
	}
}
</script>
<!-- ............edit profile  modal end........... -->
<?php }?>

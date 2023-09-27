<script>

function setB(type)
{
	$("#bcl").val(type);
}
$().ready(function() {
	$("#storyCreate").validate({
				rules: {
						st_title: "required",
						st_start_date: {required:true,date:true},					
						st_end_date: {required:true,date:true},
						st_part_desc: "required",
						//up_img: "required"		
					},
					messages: {						 
						st_title: "Please enter story title",
						st_start_date: {required:"Please select a date",date:"Please select a date"},					
						st_end_date: {required:"Please select a date",date:"Please select a date"},
						st_part_desc: "Please enter story description",
						//up_img: "Please select a file"			
					},
					 submitHandler: function(form) {
					 //alert($("#storyCreate").serialize());
            			$.ajax({
							url: configUrl+'update_story',
							type: 'POST',
							data: $("#storyCreate").serialize(),
							success: function(response){
								//alert(response);
							  var data = response.split("||");
								if(data[0] != 0){
									alert(data[1]);
									window.location.href = '<?php echo FURL;?>user-stories?uId=<?php echo $_SESSION['user']['u_login_id'];?>';
								}else{
									alert(data[1]);
								}
							},
						});
						return false;
						
      				}
				});
});
$(document).ready(function(){
    $("#up_img").on('change',function(){
        upload_story_photo();
    });
});

function upload_story_photo()
{
	var fd = new FormData();
	//alert($('#up_img')[0].files.length);
	var size = 0;
	for(var i = 0; i < $('#up_img')[0].files.length; i++){
           var file = $('#up_img')[0].files[i];
		   fd.append('file_'+i,file);
		   size += parseFloat(file.size/(1024*1024));
     }
	 //alert(parseFloat(size).toFixed(2)+' MB');
	 if(parseFloat(size).toFixed(2)>5)
	 {
	 	alert('Max Image upload size is 5 MB');
	 	return false;
	 }
	 fd.append('count',$('#up_img')[0].files.length);
	
	
	$.ajax({
		url: configUrl+'upload_story_pic',
		type: 'post',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response){
		//alert(response);
		var data = response.split("||");
			if(data[0]){
			var imgs = data[1].split(",");
			////alert(data[1]);
			//alert(imgs.length);
			var htmlString = '<div class="row">';
			    for(var k=0; k< imgs.length; k++)
				{
					htmlString += '<div class="col-4"><div class="1st"><button type="button" class="close" onClick="delUpImg('+k+')"><span>&times;</span></button><img class="w-100" src="<?=STORY;?>'+imgs[k]+'"></div></div>';
				}
				htmlString += '</div>';
				$('#upimgs').html(htmlString);
				//$("#pFile").css("background-image", "url(" + data[1] + ")");
				//$(".preview img").show(); // Display image element
				
				
			}else{
				alert(data[2]);
			}
		},
	});
}

function delUpImg(id)
{
	//alert(id);
	$.ajax({
		url: configUrl+'delete_story_pic',
		type: 'post',
		data: { pos: id },
		success: function(response){
		//alert(response);
		var data = response.split("||");
			if(data[0]!=0){
			var imgs = data[1].split(",");
			////alert(data[1]);
			//alert(imgs.length);
			var htmlString = '<div class="row">';
			    for(var k=0; k< imgs.length; k++)
				{
				if(imgs[k]!=""){
					htmlString += '<div class="col-4"><div class="1st"><button type="button" class="close" onClick="delUpImg('+k+')"><span>&times;</span></button><img class="w-100" src="<?=STORY;?>'+imgs[k]+'"></div></div>';
					}
				}
				htmlString += '</div>';
				$('#upimgs').html('');
				$('#upimgs').html(htmlString);
				//$("#pFile").css("background-image", "url(" + data[1] + ")");
				//$(".preview img").show(); // Display image element
				
				
			}else{
				alert(data[2]);
			}
		},
	});
}
</script>
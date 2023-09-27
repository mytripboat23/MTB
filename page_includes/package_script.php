<script>

$(document).ready(function(){
    $("#pup_img").on('change',function(){
        upload_pck_photo();
    });
});

function upload_pck_photo()
{
	var fd = new FormData();
	var size = 0;
	//alert($('#pup_img')[0].files.length);
	//alert($("#pup_img").val());
	var image = $("#pup_img").val();
	var checkimg = image.toLowerCase();
	  if (!checkimg.match(/(\.jpg|\.png|\.JPG|\.PNG|\.jpeg|\.JPEG\|.webp|\.WEBP)$/)){ // validation of file extension using regular expression before file upload
		  alert("Image extension should be jpg or jpeg or png or webp");
		  return false;
	   }
		   
	for(var i = 0; i < $('#pup_img')[0].files.length; i++){

           var file = $('#pup_img')[0].files[i];
		   fd.append('file_'+i,file);
		   size += parseFloat(file.size/(1024*1024));
     }
	 if(parseFloat(size).toFixed(2)>5)
	 {
	 	alert('Max Image upload size is 5 MB');
	 	return false;
	 }
	 fd.append('count',$('#pup_img')[0].files.length);
	
	
	$.ajax({
		url: configUrl+'upload_pck_pic',
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
					htmlString += '<div class="col-4"><div class="1st"><button type="button" class="close" onClick="delPckUpImg('+k+')"><span>&times;</span></button><img class="w-100" src="<?=PACKAGE;?>'+imgs[k]+'"></div></div>';
				}
				htmlString += '</div>';
				$('#pckupimgs').html(htmlString);
				//$("#pFile").css("background-image", "url(" + data[1] + ")");
				//$(".preview img").show(); // Display image element
				
				
			}else{
				alert(data[2]);
			}
		},
	});
}

function delPckUpImg(id)
{
	//alert(id);
	$.ajax({
		url: configUrl+'delete_pck_pic',
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
					htmlString += '<div class="col-4"><div class="1st"><button type="button" class="close" onClick="delPckUpImg('+k+')"><span>&times;</span></button><img class="w-100" src="<?=PACKAGE;?>'+imgs[k]+'"></div></div>';
					}
				}
				htmlString += '</div>';
				$('#pckupimgs').html('');
				$('#pckupimgs').html(htmlString);
				//$("#pFile").css("background-image", "url(" + data[1] + ")");
				//$(".preview img").show(); // Display image element
				
				
			}else{
				alert(data[2]);
			}
		},
	});
}
</script>
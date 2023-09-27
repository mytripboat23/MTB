<?php
$scan = scandir('uploads/package/');
foreach($scan as $file) {
   if (!is_dir("uploads/package/".$file)) {
      $fileNameParts = explode('.', $file);
	  $ext = end($fileNameParts);
	  
 if (!file_exists('uploads/package/300X300_opt/'.$file)) 
 {
	  if($ext=='jpg' || $ext=='JPG' || $ext=='jpeg' || $ext=='JPEG')
	  {
	  		$source_properties = getimagesize('uploads/package/'.$file);
	  		$image_resource_id = imagecreatefromjpeg('uploads/package/'.$file);
            $target_layer = fn_resize($image_resource_id, $source_properties[0], $source_properties[1]);
            imagejpeg($target_layer,'uploads/package/300X300_opt/'.$file);	  	
	  }
	  elseif($ext=='png' || $ext=='PNG')
	  {
	  		$source_properties = getimagesize('uploads/package/'.$file);
	  		$image_resource_id = imagecreatefrompng('uploads/package/'.$file);
            $target_layer = fn_resize($image_resource_id, $source_properties[0], $source_properties[1]);
            imagepng($target_layer, 'uploads/package/300X300_opt/'.$file);	  	
	  }	  
	  elseif($ext=='webp' || $ext=='WEBP')
	  {
	  		$source_properties = getimagesize('uploads/package/'.$file);
	  		$image_resource_id = imagecreatefromwebp('uploads/package/'.$file);
            $target_layer = fn_resize($image_resource_id, $source_properties[0], $source_properties[1]);
            imagewebp($target_layer, 'uploads/package/300X300_opt/'.$file);	  	
	  }	  
  }

}
}

function fn_resize($image_resource_id, $width, $height)
{
    $target_width = 300;
    $target_height = 300;
    $target_layer = imagecreatetruecolor($target_width, $target_height);
    imagecopyresampled($target_layer, $image_resource_id, 0, 0, 0, 0, $target_width, $target_height, $width, $height);
    return $target_layer;
}

?>
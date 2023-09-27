<?php
function createThumbs( $pathToImages, $pathToThumbs, $thumbHeight, $thumbWidth, $fname ) 
{
  // open the directory
  //$dir = opendir( $pathToImages );

  // loop through it, looking for any/all JPG files:
  //while (false !== ($fname = readdir( $dir ))) {
    // parse path for the extension

   $info = pathinfo($pathToImages . $fname);
    // continue only if this is a JPEG image
   // if ( strtolower($info['extension']) == 'jpg' ) 
   // {
     // echo "Creating thumbnail for {$fname} <br />";

      // load image and get image size
      //$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
	  if ( strtolower($info['extension']) == 'jpg' ) 
	  $img = imagecreatefromjpeg( "{$pathToImages}" );
	  if ( strtolower($info['extension']) == 'gif' ) 
	  $img = imagecreatefromgif( "{$pathToImages}" );
	  if ( strtolower($info['extension']) == 'png' ) 
	  $img = imagecreatefrompng( "{$pathToImages}" );
	  
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
	  if($height>$width)
	  {
      	$new_height = $thumbHeight;
      	$new_width = floor( $width * ( $new_height / $height ) );
	  }
	  else
	  {
	  	$new_width = $thumbWidth;
      	$new_height = floor( $height * ( $new_width / $width ) );
	  }
	  //$new_width = "75";

      // create a new temporary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image 
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
	  if ( strtolower($info['extension']) == 'jpg' ) 
      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
	  if ( strtolower($info['extension']) == 'gif' ) 
	  imagegif( $tmp_img, "{$pathToThumbs}{$fname}" );
	  if ( strtolower($info['extension']) == 'png' ) 
	  imagepng( $tmp_img, "{$pathToThumbs}{$fname}" );
	  
   // }
  //}
  // close the directory
  //closedir( $dir );
}


?>
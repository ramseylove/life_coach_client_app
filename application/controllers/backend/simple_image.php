<?php
 
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class Simple_image 
{
 
   var $image;
   var $image_type;
 
   function load($filename) 
   {
 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
 
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   
   function output($image_type=IMAGETYPE_JPEG) 
   {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   
   function getWidth() 
   {
 
      return imagesx($this->image);
   }
   
   function getHeight() 
   {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }     

   function createThumb($pathToImage, $pathToThumb, $thumbWidth, $imageName, $thumbName,$transparency=true,$degree=0)   
   {
		$fname = $imageName;
		$info = pathinfo($pathToImage . $fname);
		if((strtolower($info['extension']) == 'jpg') || (strtolower($info['extension']) == 'gif') || (strtolower($info['extension']) == 'jpeg') || (strtolower($info['extension']) == 'png')){
		// load image and get image size depending upon image type				
			if((strtolower($info['extension']) == 'jpg') || (strtolower($info['extension']) == 'jpeg')){
				$img = imagecreatefromjpeg( "{$pathToImage}/{$fname}" );
			}

			if(strtolower($info['extension']) == 'png')
			{
				$im = @imagecreatefrompng("{$pathToImage}/{$fname}");
                if(!$im)
                {
					$img = imagecreatefromjpeg( "{$pathToImage}/{$fname}" );    
                }
                else
				{
					$img=imagecreatefrompng("{$pathToImage}/{$fname}");
				}
			}

			if(strtolower($info['extension']) == 'gif'){
				$img = imagecreatefromgif( "{$pathToImage}/{$fname}" );
			}
            $rotated_img = imagerotate($img, $degree,0);
			$width = imagesx( $rotated_img );
			$height = imagesy( $rotated_img );

			if ($width >= $height) {
				$imageType = "landscape";
				$new_width = $thumbWidth;
				$new_height = floor( $height * ( $thumbWidth / $width ) );
			}
			if ($width < $height) {
				$imageType = "portrait";
				$new_height = $thumbWidth;
				$new_width = floor( $width * ( $thumbWidth / $height ) );
			}

			// create a new tempopary image
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );
				
			if($transparency)
			{
				if(strtolower($info['extension']) == 'png')
				{
					$im = @imagecreatefrompng("{$pathToImage}/{$fname}");
					if($im)
					{
						 imagealphablending($tmp_img, false);
						 $colorTransparent = imagecolorallocatealpha($tmp_img, 0, 0, 0, 127);
						 imagefill($tmp_img, 0, 0, $colorTransparent);
						 imagesavealpha($tmp_img, true);
					}
				}
			} 
		
			// copy and resize old image into new image 
			imagecopyresampled( $tmp_img, $rotated_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height ); 
		
			// save thumbnail into a file
			if((strtolower($info['extension']) == 'jpg') || (strtolower($info['extension']) == 'jpeg')){
				imagejpeg($tmp_img, "{$pathToThumb}/{$thumbName}" );
			}

			if(strtolower($info['extension']) == 'png'){
				imagepng($tmp_img, "{$pathToThumb}/{$thumbName}" );
			}

			if(strtolower($info['extension']) == 'gif'){
				imagegif($tmp_img, "{$pathToThumb}/{$thumbName}" );
			}
		}
		
		return true;
	}
   
 
}
?>

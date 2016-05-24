<?php
/*
* File: Piczy.php
* Author: Arpit Aggarwal
* Copyright: 2015 Arpit Aggarwal
* Date: 26/05/2015
* Link: http://picwiz.net/
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
 
class Piczy {
   
   public $IMAGE;
   public $IMAGE_TYPE;
 
   public function load($filename) {
      $this->IMAGE_INFO = getimagesize($filename);
      $this->IMAGE_TYPE = $this->IMAGE_INFO[2];
      if( $this->IMAGE_TYPE == IMAGETYPE_JPEG ) {
         $this->IMAGE = imagecreatefromjpeg($filename);
      } elseif( $this->IMAGE_TYPE == IMAGETYPE_GIF ) {
         $this->IMAGE = imagecreatefromgif($filename);
      } elseif( $this->IMAGE_TYPE == IMAGETYPE_PNG ) {
         $this->IMAGE = imagecreatefrompng($filename);
      }
   }
   public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $this->IMAGE_TYPE == IMAGETYPE_JPEG ) {
         imagejpeg($this->IMAGE,$filename,$compression);
      } elseif( $this->IMAGE_TYPE == IMAGETYPE_GIF ) {
         imagegif($this->IMAGE,$filename);         
      } elseif( $this->IMAGE_TYPE == IMAGETYPE_PNG ) {
         imagepng($this->IMAGE,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   public function output($image_type=IMAGETYPE_JPEG) {
      if( $this->IMAGE_TYPE == IMAGETYPE_JPEG ) {
         imagejpeg($this->IMAGE);
      } elseif( $this->IMAGE_TYPE == IMAGETYPE_GIF ) {
         imagegif($this->IMAGE);         
      } elseif( $this->IMAGE_TYPE == IMAGETYPE_PNG ) {
         imagepng($this->IMAGE);
      }   
   }
   public function getWidth() {
      return imagesx($this->IMAGE);
   }
   public function getHeight() {
      return imagesy($this->IMAGE);
   }
   public function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   public function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   public function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   public function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->IMAGE, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->IMAGE = $new_image;   
   }
   public function watermark($text,$font,$size=0,$alpha=63,$position="lower-center",$color=array(255,255,255),$left=0,$top=0) {

      $color=imagecolorallocatealpha($this->IMAGE, $color[0], $color[1], $color[2], $alpha);

      $x=$this->getWidth();

      $y=$this->getHeight();

      $font_size=$x/20;

      if ($size==0) $size=$font_size;

      else $size=$font_size*$size;

      if ($left==0) $left=$x/70;

      if ($top==0) $top=$y-($y/25);

      if ($position=="lower-center") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_width = abs($box[4] - $box[0]);

         $left=round($x/2)-round($w_width/2);

      }

      if ($position=="lower-left") {

         $left=10;

      }

      if ($position=="lower-right") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_width = abs($box[4] - $box[0]);

         $left=$x-($w_width+10);

      }

      if ($position=="upper-center") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_width = abs($box[4] - $box[0]);

         $left=round($x/2)-round($w_width/2);

         $w_height=abs($box[5]) + abs($box[1]);

         $top=3+$w_height;

      }

      if ($position=="upper-left") {

         $left=10;

         $box=imagettfbbox($size, 0, $font, $text);

         $w_height=abs($box[5]) + abs($box[1]);

         $top=3+$w_height;

      }

      if ($position=="upper-right") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_width = abs($box[4] - $box[0]);

         $left=$x-($w_width+10);

         $w_height=abs($box[5]) + abs($box[1]);

         $top=3+$w_height;

      }

      if ($position=="center") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_width = abs($box[4] - $box[0]);

         $w_height=abs($box[5]) + abs($box[1]);

         $left=round($x/2)-round($w_width/2);

         $top=round($y/2)-round($w_height/2);

      }

      if ($position=="left") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_height=abs($box[5]) + abs($box[1]);

         $top=round($y/2)-round($w_height/2);

         $left=10;

      }

      if ($position=="right") {

         $box=imagettfbbox($size, 0, $font, $text);

         $w_width = abs($box[4] - $box[0]);

         $left=$x-($w_width+10);

         $w_height=abs($box[5]) + abs($box[1]);

         $top=round($y/2)-round($w_height/2);

      }

      imagettftext($this->IMAGE, $size, 0 , $left-4, $top, imagecolorallocatealpha($this->IMAGE, 0, 0, 0, 60), $font, $text);

      imagettftext($this->IMAGE, $size, 0 , $left, $top, $color, $font, $text);

   }
   
   public function contrast($range) 
   {
   	   imagefilter($this->IMAGE,IMG_FILTER_CONTRAST,0-$range);
   }
   public function brightness($range) 
   {
         imagefilter($this->IMAGE,IMG_FILTER_BRIGHTNESS,$range);
   }
   public function crop($width,$height,$position="center")
   {
      $orig_width=$this->getWidth();
      $orig_height=$this->getHeight();
      if ($orig_height>=$orig_width) $this->resizeToWidth($height);
      else if ($orig_width>=$orig_height) $this->resizeToHeight($width);
      $orig_width=$this->getWidth();
      $orig_height=$this->getHeight();
      if ($position=="center") {
         $centreX = round($orig_width / 2);
         $centreY = round($orig_height / 2);
         $cropWidthHalf  = round($width / 2);
         $cropHeightHalf = round($height / 2);
      }
      if ($position=="right") {
         $centreX = $orig_width;
         $centreY = round($orig_height / 2);
         $cropWidthHalf  = $width;
         $cropHeightHalf = round($height / 2);
      }
      if ($position=="left") {
         $centreX = 0;
         $centreY = round($orig_height / 2);
         $cropWidthHalf  = $width;
         $cropHeightHalf = round($height / 2);
      }
      if ($position=="top-center") {
         $centreX = round($orig_width / 2);
         $centreY = 0;
         $cropWidthHalf  = round($width / 2);
         $cropHeightHalf = $height;
      }
      if ($position=="top-left") {
         $centreX = 0;
         $centreY = 0;
         $cropWidthHalf  = $width;
         $cropHeightHalf = $height;
      }
      if ($position=="top-right") {
         $centreX = $orig_width;
         $centreY = 0;
         $cropWidthHalf  = $width;
         $cropHeightHalf = $height;
      }
      if ($position=="bottom-center") {
         $centreX = round($orig_width / 2);
         $centreY = $orig_height;
         $cropWidthHalf  = round($width / 2);
         $cropHeightHalf = $height;
      }
      if ($position=="bottom-left") {
         $centreX = 0;
         $centreY = $orig_height;
         $cropWidthHalf  = $width;
         $cropHeightHalf = $height;
      }
      if ($position=="bottom-right") {
         $centreX = $orig_width;
         $centreY = $orig_height;
         $cropWidthHalf  = $width;
         $cropHeightHalf = $height;
      }
      $x1 = max(0, $centreX - $cropWidthHalf);
      $y1 = max(0, $centreY - $cropHeightHalf);
      $x2 = min($width, $centreX + $cropWidthHalf);
      $y2 = min($height, $centreY + $cropHeightHalf);
      $temp=imagecreatetruecolor($x2, $y2);
      imagecopyresampled($temp, $this->IMAGE, 0, 0, $x1, $y1, $x2, $y2, $x2, $y2);
      $this->IMAGE=$temp;
   }
   public function close() {
      imagedestroy($this->IMAGE);
   }      
}
?>

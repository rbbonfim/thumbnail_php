<?php
function Thumb($src, $width, $height, $crop=0, $target='files/'){
	//check if the image can be resized
	if(!list($w, $h) = getimagesize($src)) {
		return array("status"=>"error","msg"=>"Unsupported image type!");
	}

	//image type
	$type = strtolower(substr(strrchr($src,"."),1));

	switch($type){
    	case 'bmp': $img = imagecreatefromwbmp($src); break;
		case 'gif': $img = imagecreatefromgif($src); break;
		case 'jpg': $img = imagecreatefromjpeg($src); break;			
		case 'jpeg': $img = imagecreatefromjpeg($src); break;			
		case 'png': $img = imagecreatefrompng($src); break;
		default : return array("status"=>"error","msg"=>"Unsupported image type!");
  	}
	
	//crop centered image
	if($crop){
			if((!is_numeric($width) || $width  == 0) && (!is_numeric($height) || $height == 0)){
			return array("status"=>"error","msg"=>"Inform height or width!");
		}
		
		if (!is_numeric($width) || $width  == 0){
			$width = ($height * $w ) / $h;
		}
		
		if (!is_numeric($height) || $height  == 0){
			$height = ($width * $h) / $w;
		}
		
		#dimensions informed are too small
	    if($w < $width or $h < $height) {
	    	return array("status"=>"success","src"=>$src,'w'=>$w,'h'=>$h);
		}
		
	    //calculating a part of the image to use for thumbnail
		if ($w > $h) {
			$y = 0;
			$x = ($w - $h) / 2;
			$w = $h;
			$h = $h;
		} else {
			$x = 0;
			$y = ($h - $w) / 2;
			$w = $w;
			$h = $w;
		}
	}
	//resize without crop
	else{
		if((!is_numeric($width) || $width  == 0) && (!is_numeric($height) || $height == 0)){
			return array("status"=>"error","msg"=>"Inform height or width!");
		}
		
		if (!is_numeric($width) || $width  == 0){
			$width = ($height * $w ) / $h;
		}
		
		if (!is_numeric($height) || $height  == 0){
			$height = ($width * $h) / $w;
		}
		
		#dimensions informed are too small
		if($w < $width and $h < $height) {
			return array("status"=>"success","src"=>$src,'w'=>$w,'h'=>$h);
		}
		
		$ratio = min($width/$w, $height/$h);
		$width = $w * $ratio;
		$height = $h * $ratio;
		$x = 0;
		$y = 0;
		
		$width = ceil($width);
		$height = ceil($height);
	}
	
	$new = imagecreatetruecolor($width, $height);
	
	//preserves transparency
	if($type == "gif" or $type == "png"){
		imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
		imagealphablending($new, false);
		imagesavealpha($new, true);
	}
	
	imagecopyresampled($new, $img, 0, 0, $x, $y, $width, $height, $w, $h);
	
	//sizes
	$wn = ceil($width);
	$hn = ceil($height);
	
	//image name
	$dst = str_replace('.'.$type, '_'.$wn.'x'.$hn.'.'.$type, $src);
	$dst = $target.$dst;		
	
	switch($type){
		case 'jpg': imagejpeg($new, $dst, 100);  break;
		case 'jpeg': imagejpeg($new, $dst, 100);  break;
		case 'png': imagepng($new, $dst); break;
	}

	return array("status"=>"success","src"=>$dst,'w'=>$wn,'h'=>$hn);
}

?>
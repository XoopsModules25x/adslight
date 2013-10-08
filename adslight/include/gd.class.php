<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops                           

        Redesigned and ameliorate By Luc Bizet user at www.frxoops.org
		Started with the Classifieds module and made MANY changes 
        Website : http://www.luc-bizet.fr
        Contact : adslight.translate@gmail.com
-------------------------------------------------------------------------
             Original credits below Version History                       
##########################################################################
#                    Classified Module for Xoops                         #
#  By John Mordo user jlm69 at www.xoops.org and www.jlmzone.com         #
#      Started with the MyAds module and made MANY changes               #
##########################################################################
 Original Author: Pascal Le Boustouller                                   
 Author Website : pascal.e-xoops@perso-search.com                         
 Licence Type   : GPL                                                     
------------------------------------------------------------------------- 
*/

class GD
{
	var $image;
	var $width;
	var $height;
	
	function GD($location)
	{
		$imageinfo = @getimagesize($location) or exit('Unknown picture');
		
		$this -> width = $imageinfo['0'];
		$this -> height = $imageinfo['1'];
		
		switch($imageinfo['2'])
		{
			case '1':
			$this -> image = imagecreatefromgif($location);
			break;
			
			case '2':
			$this -> image = imagecreatefromjpeg($location);
			break;
			
			case '3':
			$this -> image = imagecreatefrompng($location);
			break;
			
			default:
			exit('Unknown file format');
		}
	}
	
	function resize($sizex, $sizey)
	{
		$org = round($this -> width / $this -> height, 2);
		$new = round($sizex / $sizey, 2);
		
		if($new > $org)
		{
			$sizex = round($this -> width / ($this -> height / $sizey), 0);
			$sizey = $sizey;
		}
		else
		{
			$sizex = $sizex;
			$sizey = round($this -> height / ($this -> width / $sizex), 0);
		}
		
		$resized = imagecreatetruecolor($sizex, $sizey);
		imagecopyresampled($resized, $this -> image, 0, 0, 0, 0, $sizex, $sizey, $this -> width, $this -> height);
		
		$this -> image = $resized;
		$this -> width = $sizex;
		$this -> height = $sizey;
	}
	
	function make_color($color)
	{
		$rgb = array();
		
		if(is_array($color) AND count($color) == '3')
		{
			$rgb['r'] = $color['0'];
			$rgb['g'] = $color['1'];
			$rgb['b'] = $color['2'];
		}
		elseif(preg_match('/^#?([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', $color, $results))
		{
			$rgb['r'] = hexdec($results['1']);
			$rgb['g'] = hexdec($results['2']);
			$rgb['b'] = hexdec($results['3']);
		}
		else
		{
			exit('Unknown color');
		}
		
		foreach(array('r', 'g', 'b') as $value)
		{
			if(!array_key_exists($value, $rgb) OR $rgb[$value] < 0 OR $rgb[$value] > 255 OR !is_numeric($rgb[$value]))
			{
				exit('Wrong color');
			}
		}
		
		return $rgb;
	}
	
	function add_border($width, $color)
	{
		$rgb = $this -> make_color($color);
		$allocate = imagecolorallocate($this -> image, $rgb['r'], $rgb['g'], $rgb['b']);
		
		if($width < 1)
		{
			exit('Wrong frame width');
		}
		
		$sizex = $this -> width + (2 * $width);
		$sizey = $this -> height + (2 * $width);
		$new_image = imagecreatetruecolor($sizex, $sizey);
		
		imagefill($new_image, 0, 0, $allocate);
		imagecopyresampled($new_image, $this -> image, $width, $width, 0, 0, $this -> width, 
		$this -> height, $this -> width, $this -> height);
		
		$this -> image = $new_image;
		$this -> width = $sizex;
		$this -> height = $sizey;
	}
	
	function add_text($text, $font, $color, $x, $y)
	{
		if($font < 1 OR $font > 5)
		{
			exit('Wrong font');
		}
		
		$rgb = $this -> make_color($color);
		$allocate = imagecolorallocate($this -> image, $rgb['r'], $rgb['g'], $rgb['b']);
		$text_width = imagefontwidth($font) * strlen($text);
		$text_height = imagefontheight($font);
		
		//Dokoncaj
	}
	
	function add_ttf_text($text, $size, $color, $x, $y, $font = './tahoma.ttf')
	{
		if(!is_file($font))
		{
			exit('Unknown font');
		}
		
		$rgb = $this -> make_color($color);
		$allocate = imagecolorallocate($this -> image, $rgb['r'], $rgb['g'], $rgb['b']);
		imagettftext($this -> image, $size, 0, $x, $y, $allocate, $font, $text);
	}
	
	function save($location, $quality = '80')
	{
		imagejpeg($this -> image, $location, $quality);
		imagedestroy($this -> image);
	}
}
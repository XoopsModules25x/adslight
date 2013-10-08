<?php
/*
-------------------------------------------------------------------------
                     ADSLIGHT 2 : Module for Xoops                           

        Redesigned and ameliorate By iluc user at www.frxoops.org
		Started with the Classifieds module and made MANY changes 
        Website : http://www.limonads.com
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

$img_number = imagecreate(275,25);
$backcolor = imagecolorallocate($img_number,102,102,153);
$textcolor = imagecolorallocate($img_number,255,255,255);

imagefill($img_number,0,0,$backcolor);
$number = "$_SERVER[REMOTE_ADDR]";

Imagestring($img_number,10,5,5,$number,$textcolor);

header("Content-type: image/jpeg");
imagejpeg($img_number);
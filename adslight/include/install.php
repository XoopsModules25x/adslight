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

function xoops_module_install_adslight() {

    
 global $xoopsModule, $xoopsConfig, $xoopsDB;
    
    
	//Creation du fichier AdsLight/
	$dir = XOOPS_ROOT_PATH."/uploads/AdsLight";
	if(!is_dir($dir))
		mkdir($dir);
		chmod($dir, 0777);
	
	//Creation du fichier AdsLight/images/
	$dir = XOOPS_ROOT_PATH."/uploads/AdsLight/midsize";
	if(!is_dir($dir))
		mkdir($dir);
		chmod($dir, 0777);
	
	//Creation du fichier AdsLight/images/cat
	$dir = XOOPS_ROOT_PATH."/uploads/AdsLight/thumbs";
	if(!is_dir($dir))
		mkdir($dir);
		chmod($dir, 0777);
	
//Copie des index.html
	$indexFile = XOOPS_ROOT_PATH."/modules/adslight/include/index.html";
	copy($indexFile, XOOPS_ROOT_PATH."/uploads/AdsLight/index.html");
	copy($indexFile, XOOPS_ROOT_PATH."/uploads/AdsLight/thumbs/index.html");
	copy($indexFile, XOOPS_ROOT_PATH."/uploads/AdsLight/midsize/index.html");
     
   return true;
	
}
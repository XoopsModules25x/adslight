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

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$main_lang =  '_' . strtoupper( $mydirname ) ;


/**
 * Xoops header ...
 */
include_once("../../mainfile.php");
$xoopsOption['template_main'] = "adslight_index.html";
include_once("../../header.php");

/**
 * Modules class includes  
 */
include("class/pictures.php");

/**
 * Factory of pictures created  
 */
$album_factory = new Xoopsjlm_picturesHandler($xoopsDB);

/**
 * Getting the title 
 */
$title = $_POST['caption'];
$lid = $_POST['lid'];
/**
 * Getting parameters defined in admin side  
 */

$path_upload    = $xoopsModuleConfig["adslight_path_upload"];
$pictwidth      = $xoopsModuleConfig["adslight_resized_width"];
$pictheight     = $xoopsModuleConfig["adslight_resized_height"];
$thumbwidth     = $xoopsModuleConfig["adslight_thumb_width"];
$thumbheight    = $xoopsModuleConfig["adslight_thumb_height"];
$maxfilebytes   = $xoopsModuleConfig["adslight_maxfilesize"];
$maxfileheight  = $xoopsModuleConfig["adslight_max_original_height"];
$maxfilewidth   = $xoopsModuleConfig["adslight_max_original_width"];

/**
 * If we are receiving a file  
 */
if ($_POST['xoops_upload_file'][0]=='sel_photo'){

              /**
 * Check if using Xoops or XoopsCube (by jlm69)
 * Right now Xoops does not have a directory called preload, Xoops Cube does.
 * If this finds a diectory called preload in the Xoops Root folder $xCube=true.
 * This could change if Xoops adds a Directory called preload
 */

$xCube=false;
if(preg_match("/^XOOPS Cube/",XOOPS_VERSION)) // XOOPS Cube 2.1x
{
$xCube=true;
}
if ($xCube) {
		if ( ! $xoopsGTicket->check( true , 'token' ) ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}
} else {
	if (!($GLOBALS['xoopsSecurity']->check())){
                     redirect_header($_SERVER['HTTP_REFERER'], 3, constant("_ADSLIGHT_TOKENEXPIRED"));
              }
}
              /**
              * Try to upload picture resize it insert in database and then redirect to index
              */
              if ($album_factory->receivePicture($title,$path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes,$maxfilewidth,$maxfileheight)){
                     header("Location: ".XOOPS_URL."/modules/adslight/view_photos.php?lid=$lid&uid=".$xoopsUser->getVar('uid'));

		$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("adslight_listing")." SET photo=photo+1 WHERE lid = ".mysql_real_escape_string($lid)."");

              } else {
                     redirect_header(XOOPS_URL."/modules/adslight/view_photos.php?uid=".$xoopsUser->getVar('uid'),15,constant("_ADSLIGHT_NOCACHACA"));
              }
}

/**
 * Close page  
 */
include("../../footer.php");
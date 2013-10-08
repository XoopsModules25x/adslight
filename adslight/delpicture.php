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
 * Xoops Header
 */
include_once("../../mainfile.php");
include_once("../../header.php");
include_once("../../class/criteria.php");

/**
 * Module classes  
 */

include("class/pictures.php");


/**
 * Check if using XoopsCube (by jlm69)
 * Needed because of a difference in the way Xoops and XoopsCube handle tokens 
 */

$xCube=false;
if(preg_match("/^XOOPS Cube/",XOOPS_VERSION)) // XOOPS Cube 2.1x
{
$xCube=true;
}
/**
 * Verify Ticket for Xoops Cube (by jlm69)
 * If your site is XoopsCube it uses $xoopsGTicket for the token.

 */

if ($xCube) {

if ( ! $xoopsGTicket->check( true , 'token' ) ) {
		redirect_header($_SERVER['HTTP_REFERER'],3,$xoopsGTicket->getErrors());
	}
} else {
/**
 * Verify TOKEN for Xoops
 * If your site is Xoops it uses xoopsSecurity for the token.
 */
if (!($GLOBALS['xoopsSecurity']->check())){
            redirect_header($_SERVER['HTTP_REFERER'], 3, constant("_ADSLIGHT_TOKENEXPIRED"));
}

}


/**
 * Receiving info from get parameters  
 */ 
$cod_img = $_POST['cod_img'];

/**
 * Creating the factory  and the criteria to delete the picture
 * The user must be the owner
 */  
$album_factory = new Xoopsjlm_picturesHandler($xoopsDB);
$criteria_img = new Criteria ('cod_img',$cod_img);
$uid = $xoopsUser->getVar('uid');
$criteria_uid = new Criteria ('uid_owner',$uid);
//$criteria_lid = new Criteria ('lid',$lid);
$criteria = new CriteriaCompo ($criteria_img);
$criteria->add($criteria_uid);
  
$objects_array = $album_factory->getObjects($criteria);
$image_name = $objects_array[0]->getVar('url');
/**
 * Try to delete  
 */
if ($album_factory->deleteAll($criteria)){

$path_upload = $xoopsModuleConfig["adslight_path_upload"];


unlink("$path_upload/$image_name");

unlink("$path_upload/thumbs/thumb_$image_name");

unlink("$path_upload/midsize/resized_$image_name");



$lid = $_POST['lid'];
$xoopsDB->queryF("UPDATE ".$xoopsDB->prefix("adslight_listing")." SET photo=photo-1 WHERE lid='$lid'");



         redirect_header("view_photos.php?lid=".$lid."&uid=".$uid."", 13, constant("_ADSLIGHT_DELETED"));
} else {
         redirect_header("view_photos.php?lid=".$lid."&uid=".$uid."", 13, constant("_ADSLIGHT_NOCACHACA"));
}

/**
 * Close page  
 */
include("../../footer.php");
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

include("header.php");
require_once( XOOPS_ROOT_PATH."/modules/adslight/include/gtickets.php" ) ;

$myts =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}
$gperm_handler =& xoops_gethandler('groupperm');
if (isset($_POST['item_id'])) {
    $perm_itemid = intval($_POST['item_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight("adslight_view", $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL."/index.php", 3, _NOPERM);
    exit();
}
if (!$gperm_handler->checkRight("adslight_premium", $perm_itemid, $groups, $module_id)) {
    $prem_perm = "0";
} else {
    $prem_perm = "1";
}


#  function tips_writing
#####################################################
function tips_writing()
{
	global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $xoopsUser, $xoopsTpl, $myts, $mytree, $meta, $mid, $mydirname, $main_lang, $prem_perm;
	
	$GLOBALS['xoopsOption']['template_main'] = "adslight_tips_writing_ad.html";
	include XOOPS_ROOT_PATH."/header.php";
	
	$xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
	$xoopsTpl->assign('permit', $prem_perm);
	$xoopsTpl->assign('add_from', _ADSLIGHT_ADDFROM." ".$xoopsConfig['sitename']);
	$xoopsTpl->assign('add_from_title', _ADSLIGHT_ADDFROM);
	$xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
	$xoopsTpl->assign('only_pix', _ADSLIGHT_ONLYPIX);
	$xoopsTpl->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);
	$xoopsTpl->assign('bullinfotext', _ADSLIGHT_TIPSWRITE);
	$xoopsTpl->assign('adslight_writetexte', $xoopsModuleConfig['adslight_tips_writetxt']);
    $xoopsTpl->assign('adslight_writetitle', $xoopsModuleConfig['adslight_tips_writetitle']);
    $xoopsTpl->assign('ads_use_tipswrite', $xoopsModuleConfig['adslight_use_tipswrite']);
    
    $xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" href="'.XOOPS_URL.'/modules/adslight/style/adslight.css" type="text/css" media="all" />');
	$xoTheme -> addMeta ( 'meta', 'robots', 'noindex, nofollow');
	
		// adslight 2
	$xoopsTpl->assign('adslight_active_menu', $xoopsModuleConfig['adslight_active_menu']);
	$xoopsTpl->assign('adslight_active_rss', $xoopsModuleConfig['adslight_active_rss']);
    
    if ($xoopsUser) {
	$member_usid = $xoopsUser->getVar('uid');
	if ($usid = $member_usid) {
		$xoopsTpl->assign('istheirs', true);
		
	list($show_user) = $xoopsDB->fetchRow($xoopsDB->query("select COUNT(*) FROM ".$xoopsDB->prefix("adslight_listing")." WHERE usid=$member_usid"));

	$xoopsTpl->assign('show_user', $show_user);
	$xoopsTpl->assign('show_user_link', "members.php?usid=$member_usid");
		}
	}   
    
}


######################################################

$pa = !isset($_GET['pa'])? NULL : $_GET['pa'];


switch($pa)
{
   default:
		$xoopsOption['template_main'] = "adslight_tips_writing_ad.html";
		tips_writing();
		break;
}
include(XOOPS_ROOT_PATH."/footer.php");
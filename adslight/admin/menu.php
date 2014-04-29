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
// defined("XOOPS_ROOT_PATH") || die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';

//global $xoopsModule;
++$i;
$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU0;
$adminmenu[$i]['link'] = "admin/main.php";
$adminmenu[$i]['icon'] =  $pathIcon32 . '/dashboard.png';
++$i;
$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU1;
$adminmenu[$i]['link'] = "admin/map.php";
$adminmenu[$i]['icon'] =  $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU5;
$adminmenu[$i]['link'] = "admin/options.php";
$adminmenu[$i]['icon'] = "assets/images/icons/preferences.png";
++$i;
$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU2;
$adminmenu[$i]['link'] = "admin/groupperms.php";
$adminmenu[$i]['icon'] =  $pathIcon32 . '/permissions.png';
//++$i;
//if (isset($xoopsModule) && $xoopsModule->getVar('dirname') == basename(dirname(dirname(__FILE__)))) {
//$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU3;
//$adminmenu[$i]['link'] = '../../modules/system/admin.php?fct=blocksadmin&amp;selvis=-1&amp;selmod=-2&amp;selgrp=-1&amp;selgen=' . $xoopsModule->getVar('mid');
//}
//$adminmenu[$i]['icon'] = "assets/images/icons/window.png";
//++$i;
/*$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU9;
$adminmenu[$i]['link'] = "index.php";
$adminmenu[$i]['icon'] = "assets/images/icons/up_alt.png"; */
//++$i;
//$adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU10;
//$adminmenu[$i]['link'] = "../system/admin.php?fct=modulesadmin&op=update&module=adslight";
//$adminmenu[$i]['icon'] = "assets/images/icons/refresh.png";
//++$i;
/* $adminmenu[$i]['title'] = _MI_ADSLIGHT_ADMENU11;
$adminmenu[$i]['link'] = "admin/support_forum.php";
$adminmenu[$i]['icon'] = "assets/images/icons/discussion.png"; */
++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
//++$i;
//$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
//$adminmenu[$i]["link"]  = "admin/about0.php";
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';

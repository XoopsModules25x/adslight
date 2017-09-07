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
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}


$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

//$moduleHelper->loadLanguage('modinfo');
//xoops_loadLanguage('modinfo', $moduleDirName);

$adminmenu[] = [
    'title' => _AM_MODULEADMIN_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png'
];

//global $xoopsModule;

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU0,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/dashboard.png'
];

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU1,
    'link'  => 'admin/map.php',
    'icon'  => $pathIcon32 . '/category.png'
];

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU5,
    'link'  => 'admin/options.php',
    'icon'  => $pathModIcon32 . '/preferences.png'
];

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU2,
    'link'  => 'admin/groupperms.php',
    'icon'  => $pathIcon32 . '/permissions.png'
];

//$adminmenu[] = array(
//    'title' => _MI_ADSLIGHT_ADMENU3,
//    'link'  => '../../modules/system/admin.php?fct=blocksadmin&amp;selvis=-1&amp;selmod=-2&amp;selgrp=-1&amp;selgen=' . $xoopsModule->getVar('mid');
//    'icon'  => $pathModIcon32 . '/window.png'
//);

//$adminmenu[] = array(
//    'title' => _MI_ADSLIGHT_ADMENU9,
//    'link'  => 'admin/index.php',
//    'icon'  => $pathModIcon32 . '/up_alt.png'
//);

//$adminmenu[] = array(
//    'title' => _MI_ADSLIGHT_ADMENU11,
//    'link'  => 'admin/support_forum.php',
//    'icon'  => $pathModIcon32 . '/discussion.png'
//);

$adminmenu[] = [
    'title' => _AM_MODULEADMIN_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];

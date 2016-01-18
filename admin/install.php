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

include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include_once __DIR__ . '/header.php';
xoops_cp_header();

//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
//    adslight_adminmenu(6, "");
//} else {
//    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//    loadModuleAdminMenu (6, "");
//}

$action='';
if (isset($_POST['action'])) {
  $action = $_POST['action'];
  $file=$_POST['file'];
}

$sql = 'SELECT conf_id FROM ' . $xoopsDB->prefix('config') . ' WHERE conf_name = "theme_set"';
$res = $xoopsDB->query( $sql );
list( $conf_id ) = $xoopsDB->fetchRow( $res );

$module         =& $module_handler->getByDirname('system');
$config_handler =& xoops_gethandler('config');
$config_theme   = $config_handler->getConfig($conf_id, true);

switch ($action) {
  case 'new':
    copy(XOOPS_ROOT_PATH.'/modules/adslight/Root/'.$file,XOOPS_ROOT_PATH.'/'.$file);
    break;
  case 'remove':
    unlink(XOOPS_ROOT_PATH.'/'.$file);
    break;
  case 'copy':
    rename(XOOPS_ROOT_PATH.'/'.$file,XOOPS_ROOT_PATH.'/'.$file.'.svg');
    copy(XOOPS_ROOT_PATH.'/modules/adslight/Root/'.$file,XOOPS_ROOT_PATH.'/'.$file);
    break;
  case 'restore':
    unlink(XOOPS_ROOT_PATH.'/'.$file);
    rename(XOOPS_ROOT_PATH.'/'.$file.'.svg',XOOPS_ROOT_PATH.'/'.$file);
    break;
  case 'install_template':
    if (file_exists(XOOPS_ROOT_PATH.'/themes/'.$config_theme->getConfValueForOutput().'/modules/'.$file)) {
      unlink(XOOPS_ROOT_PATH.'/themes/'.$config_theme->getConfValueForOutput().'/modules/'.$file);
    }
    FS_Storage::dircopy(XOOPS_ROOT_PATH.'/modules/adslight/Root/themes/',XOOPS_ROOT_PATH.'/themes/'.$config_theme->getConfValueForOutput().'/',$success,$error);
       include_once XOOPS_ROOT_PATH.'/class/template.php';
$xoopsTpl = new XoopsTpl();
$xoopsTpl->clear_cache('db:system_block_user.html');
$xoopsTpl->clear_cache('db:system_userinfo.html');
$xoopsTpl->clear_cache('db:profile_userinfo.tpl');
    break;
  case 'remove_template':
    unlink(XOOPS_ROOT_PATH.'/themes/'.$config_theme->getConfValueForOutput().'/modules/'.$file);
    break;
}

xoops_cp_footer();

<?php

declare(strict_types=1);

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 * @author       Pascal Le Boustouller: original author (pascal.e-xoops@perso-search.com)
 * @author       Luc Bizet (www.frxoops.org)
 * @author       jlm69 (www.jlmzone.com)
 * @author       mamba (www.xoops.org)
 */

use Xmf\Request;
use XoopsModules\Adslight\{
    Utility
};

require_once __DIR__ . '/admin_header.php';
xoops_cp_header();

//if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php")) {
//    adslight_adminmenu(6, "");
//} else {
//    require_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
//    loadModuleAdminMenu (6, "");
//}

$action = Request::getString('action', '', 'POST');
if (!empty($action)) {
    $file = Request::getString('file', '', 'POST');
}
/*
$action = '';
if (\Xmf\Request::hasVar('action', 'POST')) {
    $action = $_POST['action'];
    $file   = $_POST['file'];
}
*/
$sql = 'SELECT conf_id FROM ' . $xoopsDB->prefix('config') . ' WHERE conf_name = "theme_set"';
$res = $xoopsDB->query($sql);
[$conf_id] = $xoopsDB->fetchRow($res);
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname('system');
/** @var \XoopsConfigHandler $configHandler */
$configHandler = xoops_getHandler('config');
$config_theme  = $configHandler->getConfig($conf_id, true);

switch ($action) {
    case 'new':
        copy(XOOPS_ROOT_PATH . "/modules/adslight/Root/{$file}", XOOPS_ROOT_PATH . "/{$file}");
        break;
    case 'remove':
        unlink(XOOPS_ROOT_PATH . "/{$file}");
        break;
    case 'copy':
        rename(XOOPS_ROOT_PATH . "/{$file}", XOOPS_ROOT_PATH . "/{$file}.svg");
        copy(XOOPS_ROOT_PATH . "/modules/adslight/Root/{$file}", XOOPS_ROOT_PATH . "/{$file}");
        break;
    case 'restore':
        unlink(XOOPS_ROOT_PATH . "/{$file}");
        rename(XOOPS_ROOT_PATH . "/{$file}.svg", XOOPS_ROOT_PATH . "/{$file}");
        break;
    case 'install_template':
        if (file_exists(XOOPS_ROOT_PATH . '/themes/' . $config_theme->getConfValueForOutput() . "/modules/{$file}")) {
            unlink(XOOPS_ROOT_PATH . '/themes/' . $config_theme->getConfValueForOutput() . "/modules/{$file}");
        }
        //        FS_Storage::dircopy(XOOPS_ROOT_PATH . '/modules/adslight/Root/themes/', XOOPS_ROOT_PATH . '/themes/' . $config_theme->getConfValueForOutput() . '/', $success, $error);
        Utility::rcopy(XOOPS_ROOT_PATH . '/modules/adslight/Root/themes/', XOOPS_ROOT_PATH . '/themes/' . $config_theme->getConfValueForOutput() . '/');
        require_once XOOPS_ROOT_PATH . '/class/template.php';
        $xoopsTpl = new \XoopsTpl();
        $GLOBALS['xoopsTpl']->clear_cache('db:system_block_user.tpl');
        $GLOBALS['xoopsTpl']->clear_cache('db:system_userinfo.tpl');
        $GLOBALS['xoopsTpl']->clear_cache('db:profile_userinfo.tpl');
        break;
    case 'remove_template':
        unlink(XOOPS_ROOT_PATH . '/themes/' . $config_theme->getConfValueForOutput() . "/modules/{$file}");
        break;
}

xoops_cp_footer();

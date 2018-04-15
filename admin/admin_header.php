<?php
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
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once  dirname(dirname(dirname(__DIR__))) . '/class/xoopsformloader.php';
// require_once  dirname(__DIR__) . '/class/Utility.php';

$moduleDirName = basename(dirname(__DIR__));

//require_once  dirname(__DIR__) . '/include/gtickets.php';
// require_once  dirname(__DIR__) . '/class/Utility.php';
// require_once  dirname(__DIR__) . '/class/classifiedstree.php';
// require_once  dirname(__DIR__) . '/class/grouppermform.php';
require_once  dirname(dirname(dirname(__DIR__))) . '/class/xoopsform/grouppermform.php';
// require_once  dirname(__DIR__) . '/class/classifiedstree.php';

// require_once  dirname(__DIR__) . '/class/Utility.php';
//require_once  dirname(__DIR__) . '/include/common.php';

$helper = \XoopsModules\AboutHelper::getInstance();

$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

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

use Xmf\Module\Admin;
use XoopsModules\Adslight;
use XoopsModules\Adslight\Helper;

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

//global $xoopsModule;

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU0,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/dashboard.png',
];

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU1,
    'link'  => 'admin/map.php',
    'icon'  => $pathIcon32 . '/category.png',
];
if (isset($pathModIcon32) && null !== $pathModIcon32) {
    $adminmenu[] = [
        'title' => _MI_ADSLIGHT_ADMENU5,
        'link'  => 'admin/options.php',
        'icon'  => $pathModIcon32 . '/preferences.png',
    ];
}
$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ADMENU2,
    'link'  => 'admin/groupperms.php',
    'icon'  => $pathIcon32 . '/permissions.png',
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

//Feedback
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_FEEDBACK'),
    'link'  => 'admin/feedback.php',
    'icon'  => $pathIcon32 . '/mail_foward.png',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];


if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_ADSLIGHT_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];

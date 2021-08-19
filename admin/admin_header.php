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
 */

use Xmf\Module\Admin;
use XoopsModules\Adslight\Helper;

/** @var Admin $adminObject */
/** @var Helper $helper */

require \dirname(__DIR__) . '/preloads/autoloader.php';
require \dirname(__DIR__, 3) . '/include/cp_header.php';
require \dirname(__DIR__, 3) . '/class/xoopsformloader.php';
require \dirname(__DIR__) . '/include/common.php';
require_once \dirname(__DIR__, 3) . '/class/xoopsform/grouppermform.php';
$db            = XoopsDatabaseFactory::getDatabaseConnection();
$moduleDirName = \basename(\dirname(__DIR__));

$helper = Helper::getInstance();

$adminObject         = Admin::getInstance();
$listingHandler      = $helper->getHandler('Listing');
$typeHandler         = $helper->getHandler('Type');
$itemvotedataHandler = $helper->getHandler('Itemvotedata');
$uservotedataHandler = $helper->getHandler('Uservotedata');
$pathIcon16          = Admin::iconUrl('', 16);
$pathIcon32          = Admin::iconUrl('', 32);
$pathModIcon32       = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
$helper->loadLanguage('common');
$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

/** @var \XoopsPersistableObjectHandler $listingHandler */
$listingHandler = $helper->getHandler('Listing');
/** @var \XoopsPersistableObjectHandler $categoriesHandler */
$categoriesHandler = $helper->getHandler('Categories');
/** @var \XoopsPersistableObjectHandler $conditionHandler */
$conditionHandler = $helper->getHandler('Condition');
/** @var \XoopsPersistableObjectHandler $typeHandler */
$typeHandler = $helper->getHandler('Type');
/** @var \XoopsPersistableObjectHandler $priceHandler */
$priceHandler = $helper->getHandler('Price');
/** @var \XoopsPersistableObjectHandler $iplogHandler */
$iplogHandler = $helper->getHandler('Iplog');
/** @var \XoopsPersistableObjectHandler $itemvotesHandler */
$itemvotesHandler = $helper->getHandler('Itemvotes');
/** @var \XoopsPersistableObjectHandler $uservotesHandler */
$uservotesHandler = $helper->getHandler('Uservotes');
/** @var \XoopsPersistableObjectHandler $picturesHandler */
$picturesHandler = $helper->getHandler('Pictures');
/** @var \XoopsPersistableObjectHandler $repliesHandler */
$repliesHandler = $helper->getHandler('Replies');

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
    PicturesHandler,
    Utility
};

/** @var Helper $helper */

require_once __DIR__ . '/header.php';
/**
 * Xoops header
 */
require_once \dirname(__DIR__, 2) . '/mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'adslight_view_photos.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/**
 * Module classes
 */

$lid = Request::getInt('lid', 0, 'GET');
if (empty($lid)) {
    $helper->redirect('index.php');
}

// Is a member looking ?
if ($GLOBALS['xoopsUser'] instanceof \XoopsUser) {
    // If no $_GET['uid'] then redirect to own
    if (Request::hasVar('uid', 'GET')) {
        $uid = Request::getInt('uid', 0, 'GET');
    } else {
        $helper->redirect('index.php');
    }

    /**
     * Is the user the owner of the album ?
     */

    $isOwner = $GLOBALS['xoopsUser']->getVar('uid') === $uid;

    $module_id = $xoopsModule->getVar('mid');

    $groups = &$GLOBALS['xoopsUser']->getGroups();

    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');

    $perm_itemid = Request::getInt('item_id', 0, 'POST');

    //If no access
    if ($grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
        $permit = '1';
    } else {
        $permit = '0';
    }
    /**
     * If it is an anonym
     */
} elseif (Request::hasVar('uid', 'GET')) {     // user is anon
    $uid = Request::getInt('uid', 0, 'GET');
} else {
    $helper->redirect('index.php');
    $isOwner = false;
}

/**
 * Filter for search pictures in database
 */
$criteria_lid = new \Criteria('lid', $lid);
$criteria_uid = new \Criteria('uid', $uid);

// Creating a factory of pictures
$album_factory = new PicturesHandler($xoopsDB);
/**
 * Fetch pictures from the factory
 */
$pictures_object_array = $album_factory->getObjects($criteria_lid, $criteria_uid);

// How many pictures are on the user album
$pictures_number = $album_factory->getCount($criteria_lid, $criteria_uid);

// Are there pictures in the album?
if (0 === $pictures_number) {
    $GLOBALS['xoopsTpl']->assign('lang_nopicyet', _ADSLIGHT_NOTHINGYET);
} else {
    // no pictures in the album
    /**
     * Lets populate an array with the data from the pictures
     */
    $i = 0;
    foreach ($pictures_object_array as $picture) {
        $pictures_array[$i]['url']     = $picture->getVar('url', 's');
        $pictures_array[$i]['desc']    = $picture->getVar('title', 's');
        $pictures_array[$i]['cod_img'] = $picture->getVar('cod_img', 's');
        $pictures_array[$i]['lid']     = $picture->getVar('lid', 's');
        $GLOBALS['xoopsTpl']->assign('pics_array', $pictures_array);

        ++$i;
    }
}

/**
 * Show the form if it is the owner and he can still upload pictures
 */
if (!empty($GLOBALS['xoopsUser'])) {
    if ($isOwner
        && $GLOBALS['xoopsModuleConfig']['adslight_nb_pict'] > $pictures_number) {
        $maxfilebytes = $GLOBALS['xoopsModuleConfig']['adslight_maxfilesize'];
        $album_factory->renderFormSubmit($uid, $lid, $maxfilebytes, $xoopsTpl);
    }
}

/**
 * Let's get the user name of the owner of the album
 */
$owner      = new \XoopsUser();
$identifier = $owner::getUnameFromId($uid);

/**
 * Adding to the module js and css of the lightbox and new ones
 */
Utility::load_lib_js(); // JJDai
// if (1 == $GLOBALS['xoopsModuleConfig']['adslight_lightbox']) {
//     $header_lightbox = '<script type="text/javascript" src="lightbox/js/prototype.js"></script>
// <script type="text/javascript" src="lightbox/js/scriptaculous.js?load=effects"></script>
// <script type="text/javascript" src="lightbox/js/lightbox.js"></script>
// <link rel="stylesheet" href="include/adslight.css" type="text/css" media="screen" >
// <link rel="stylesheet" href="lightbox/css/lightbox.css" type="text/css" media="screen" >';
// } else {
//     $header_lightbox = '<link rel="stylesheet" href="assets/css/galery.css" type="text/css" media="screen" >';
//}

/**
 * Assigning smarty variables
 */
$sql    = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $lid . " AND valid='Yes'";
$result = $xoopsDB->query($sql);
while ([$title] = $xoopsDB->fetchRow($result)) {
    $GLOBALS['xoopsTpl']->assign('lang_gtitle', "<a href='viewads.php?lid=" . $lid . "'>" . $title . '</a>');
    $GLOBALS['xoopsTpl']->assign('lang_showcase', _ADSLIGHT_SHOWCASE);
}

$GLOBALS['xoopsTpl']->assign('lang_not_premium', sprintf(_ADSLIGHT_BMCANHAVE, $GLOBALS['xoopsModuleConfig']['adslight_not_premium']));
$GLOBALS['xoopsTpl']->assign('lang_no_prem_nb', sprintf(_ADSLIGHT_PREMYOUHAVE, $pictures_number));

$upgrade = '<a href="premium.php"><strong> ' . _ADSLIGHT_UPGRADE_NOW . '</strong></a>';
$GLOBALS['xoopsTpl']->assign('lang_upgrade_now', $upgrade);

$GLOBALS['xoopsTpl']->assign('lang_max_nb_pict', sprintf(_ADSLIGHT_YOUCANHAVE, $GLOBALS['xoopsModuleConfig']['adslight_nb_pict']));
$GLOBALS['xoopsTpl']->assign('lang_nb_pict', sprintf(_ADSLIGHT_YOUHAVE, $pictures_number));

$GLOBALS['xoopsTpl']->assign('lang_albumtitle', sprintf(_ADSLIGHT_ALBUMTITLE, '<a href=' . XOOPS_URL . '/userinfo.php?uid=' . addslashes((string)$uid) . '>' . $identifier . '</a>'));

$GLOBALS['xoopsTpl']->assign('path_uploads', $GLOBALS['xoopsModuleConfig']['adslight_link_upload']);

$GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $identifier . "'s album");

$GLOBALS['xoopsTpl']->assign('nome_modulo', $xoopsModule->getVar('name'));

$GLOBALS['xoopsTpl']->assign('lang_delete', _ADSLIGHT_DELETE);
$GLOBALS['xoopsTpl']->assign('lang_editdesc', _ADSLIGHT_EDITDESC);
$GLOBALS['xoopsTpl']->assign('isOwner', $isOwner);
$GLOBALS['xoopsTpl']->assign('permit', $permit);
//$GLOBALS['xoopsTpl']->assign('xoops_module_header', $header_lightbox);

$GLOBALS['xoopsTpl']->assign('xoopsSecurity', $GLOBALS['xoopsSecurity']->getTokenHTML());

/**
 * Adding the comment system
 */
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';

/**
 * Closing the page
 */
require_once XOOPS_ROOT_PATH . '/footer.php';

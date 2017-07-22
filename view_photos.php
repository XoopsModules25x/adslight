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

use Xmf\Request;

require_once __DIR__ . '/header.php';

/**
 * Xoops header
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'adslight_view_photos.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/**
 * Module classes
 */
require_once __DIR__ . '/class/pictures.php';
$lid = Request::getInt('lid', 0, 'GET');
if (empty($lid)) {
    header('Location: ' . XOOPS_URL . '/modules/adslight/index.php');
}

// Is a member looking ?
if ($GLOBALS['xoopsUser'] instanceof XoopsUser) {
    // If no $_GET['uid'] then redirect to own
    if (Request::hasVar('uid', 'GET')) {
        $uid = Request::getInt('uid', 0, 'GET');
    } else {
        header('Location: ' . XOOPS_URL . '/modules/adslight/index.php');
    }

    /**
     * Is the user the owner of the album ?
     */

    $isOwner = ($GLOBALS['xoopsUser']->getVar('uid') == $uid) ? true : false;

    $module_id = $xoopsModule->getVar('mid');

    $groups =& $GLOBALS['xoopsUser']->getGroups();

    /** @var XoopsGroupPermHandler $gpermHandler */
    $gpermHandler = xoops_getHandler('groupperm');

    $perm_itemid = Request::getInt('item_id', 0, 'POST');

    //If no access
    if (!$gpermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
        $permit = '0';
    } else {
        $permit = '1';
    }

    /**
     * If it is an anonym
     */
} else {
    // user is anon
    if (Request::hasVar('uid', 'GET')) {
        $uid = Request::getInt('uid', 0, 'GET');
    } else {
        header('Location: ' . XOOPS_URL . '/modules/adslight/index.php');
        $isOwner = false;
    }
}

/**
 * Filter for search pictures in database
 */
$criteria_lid = new criteria('lid', $lid);
$criteria_uid = new criteria('uid', $uid);

// Creating a factory of pictures

$album_factory = new AdslightPicturesHandler($xoopsDB);

/**
 * Fetch pictures from the factory
 */
$pictures_object_array = $album_factory->getObjects($criteria_lid, $criteria_uid);

// How many pictures are on the user album
$pictures_number = $album_factory->getCount($criteria_lid, $criteria_uid);

// Are there pictures in the album?
if (0 == $pictures_number) {
    $xoopsTpl->assign('lang_nopicyet', _ADSLIGHT_NOTHINGYET);
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
        $xoopsTpl->assign('pics_array', $pictures_array);

        ++$i;
    }
}

/**
 * Show the form if it is the owner and he can still upload pictures
 */
if (!empty($GLOBALS['xoopsUser'])) {
    if ($isOwner
        && $GLOBALS['xoopsModuleConfig']['adslight_nb_pict'] > $pictures_number
    ) {
        $maxfilebytes = $GLOBALS['xoopsModuleConfig']['adslight_maxfilesize'];
        $album_factory->renderFormSubmit($uid, $lid, $maxfilebytes, $xoopsTpl);
    }
}

/**
 * Let's get the user name of the owner of the album
 */
$owner      = new XoopsUser();
$identifier = $owner->getUnameFromId($uid);

/**
 * Adding to the module js and css of the lightbox and new ones
 */

if (1 == $GLOBALS['xoopsModuleConfig']['adslight_lightbox']) {
    $header_lightbox = '<script type="text/javascript" src="lightbox/js/prototype.js"></script>
<script type="text/javascript" src="lightbox/js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="lightbox/js/lightbox.js"></script>
<link rel="stylesheet" href="include/adslight.css" type="text/css" media="screen" >
<link rel="stylesheet" href="lightbox/css/lightbox.css" type="text/css" media="screen" >';
} else {
    $header_lightbox = '<link rel="stylesheet" href="assets/css/galery.css" type="text/css" media="screen" >';
}

/**
 * Assigning smarty variables
 */

$sql    = 'SELECT title FROM ' . $xoopsDB->prefix('adslight_listing') . ' WHERE lid=' . $lid . " AND valid='Yes'";
$result = $xoopsDB->query($sql);
while (list($title) = $xoopsDB->fetchRow($result)) {
    $xoopsTpl->assign('lang_gtitle', "<a href='viewads.php?lid=" . $lid . "'>" . $title . '</a>');
    $xoopsTpl->assign('lang_showcase', _ADSLIGHT_SHOWCASE);
}

$xoopsTpl->assign('lang_not_premium', sprintf(_ADSLIGHT_BMCANHAVE, $GLOBALS['xoopsModuleConfig']['adslight_not_premium']));

$xoopsTpl->assign('lang_no_prem_nb', sprintf(_ADSLIGHT_PREMYOUHAVE, $pictures_number));

$upgrade = '<a href="premium.php"><strong> ' . _ADSLIGHT_UPGRADE_NOW . '</strong></a>';
$xoopsTpl->assign('lang_upgrade_now', $upgrade);

$xoopsTpl->assign('lang_max_nb_pict', sprintf(_ADSLIGHT_YOUCANHAVE, $GLOBALS['xoopsModuleConfig']['adslight_nb_pict']));
$xoopsTpl->assign('lang_nb_pict', sprintf(_ADSLIGHT_YOUHAVE, $pictures_number));

$xoopsTpl->assign('lang_albumtitle', sprintf(_ADSLIGHT_ALBUMTITLE, '<a href=' . XOOPS_URL . '/userinfo.php?uid=' . addslashes($uid) . '>' . $identifier . '</a>'));

$xoopsTpl->assign('path_uploads', $GLOBALS['xoopsModuleConfig']['adslight_link_upload']);

$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $identifier . "'s album");

$xoopsTpl->assign('nome_modulo', $xoopsModule->getVar('name'));

$xoopsTpl->assign('lang_delete', _ADSLIGHT_DELETE);
$xoopsTpl->assign('lang_editdesc', _ADSLIGHT_EDITDESC);
$xoopsTpl->assign('isOwner', $isOwner);
$xoopsTpl->assign('permit', $permit);
$xoopsTpl->assign('xoops_module_header', $header_lightbox);

/**
 * Check if using Xoops or XoopsCube (by jlm69)
 */
$xCube = false;
if (preg_match('/^XOOPS Cube/', XOOPS_VERSION)) { // XOOPS Cube 2.1x
    $xCube = true;
}

/**
 * Verify Ticket (by jlm69)
 * If your site is XoopsCube it uses $xoopsGTicket for the token.
 * If your site is Xoops it uses xoopsSecurity for the token.
 */

if ($xCube) {
    $xoopsTpl->assign('token', $GLOBALS['xoopsGTicket']->getTicketHtml(__LINE__));
    $xoopsTpl->assign('xcube', '1');
} else {
    $xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());
    $xoopsTpl->assign('xcube', '');
}

/**
 * Adding the comment system
 */
include XOOPS_ROOT_PATH . '/include/comment_view.php';

/**
 * Closing the page
 */
include XOOPS_ROOT_PATH . '/footer.php';

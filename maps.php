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

require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$perm_itemid      = Request::getInt('item_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}
$prem_perm = $grouppermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id) ? '1' : '0';

#  function adslightMaps
#####################################################
function adslightMaps()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts, $prem_perm;

    $GLOBALS['xoopsOption']['template_main'] = 'adslight_maps.tpl';

    require_once XOOPS_ROOT_PATH . '/header.php';

    $GLOBALS['xoopsTpl']->assign('xmid', $xoopsModule->getVar('mid'));
    $GLOBALS['xoopsTpl']->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('add_from_title', _ADSLIGHT_ADDFROM);
    $GLOBALS['xoopsTpl']->assign('add_from_sitename', $xoopsConfig['sitename']);
    $GLOBALS['xoopsTpl']->assign('search_listings', _ADSLIGHT_SEARCH_LISTINGS);
    $GLOBALS['xoopsTpl']->assign('all_words', _ADSLIGHT_ALL_WORDS);
    $GLOBALS['xoopsTpl']->assign('any_words', _ADSLIGHT_ANY_WORDS);
    $GLOBALS['xoopsTpl']->assign('exact_match', _ADSLIGHT_EXACT_MATCH);
    $GLOBALS['xoopsTpl']->assign('only_pix', _ADSLIGHT_ONLYPIX);
    $GLOBALS['xoopsTpl']->assign('search', _ADSLIGHT_SEARCH);
    $GLOBALS['xoopsTpl']->assign('permit', $prem_perm);
    $GLOBALS['xoopsTpl']->assign('imgscss', XOOPS_URL . '/modules/adslight/assets/css/adslight.css');
    $GLOBALS['xoopsTpl']->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);

    $GLOBALS['xoTheme']->addMeta('meta', 'robots', 'noindex, nofollow');

    $header_cssadslight = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" >';

    $GLOBALS['xoopsTpl']->assign('xoops_module_header', $header_cssadslight);

    $maps_name   = $GLOBALS['xoopsModuleConfig']['adslight_maps_set'];
    $maps_width  = $GLOBALS['xoopsModuleConfig']['adslight_maps_width'];
    $maps_height = $GLOBALS['xoopsModuleConfig']['adslight_maps_height'];

    $GLOBALS['xoopsTpl']->assign('maps_name', $maps_name);
    $GLOBALS['xoopsTpl']->assign('maps_width', $maps_width);
    $GLOBALS['xoopsTpl']->assign('maps_height', $maps_height);

    $GLOBALS['xoopsTpl']->assign('adlight_maps_title', _ADSLIGHT_MAPS_TITLE);
    $GLOBALS['xoopsTpl']->assign('bullinfotext', _ADSLIGHT_MAPS_TEXT);

    // adslight 2
    $GLOBALS['xoopsTpl']->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
    $GLOBALS['xoopsTpl']->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);

    if ($GLOBALS['xoopsUser']) {
        $member_usid = $GLOBALS['xoopsUser']->getVar('uid');
        if ($usid = $member_usid) {
            $GLOBALS['xoopsTpl']->assign('istheirs', true);

            [$show_user] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE usid=$member_usid"));

            $GLOBALS['xoopsTpl']->assign('show_user', $show_user);
            $GLOBALS['xoopsTpl']->assign('show_user_link', "members.php?usid=$member_usid");
        }
    }
}

######################################################

$pa                                      = Request::getInt('pa', null, 'GET');
$GLOBALS['xoopsOption']['template_main'] = 'adslight_maps.tpl';
adslightMaps();
break;
require_once XOOPS_ROOT_PATH . '/footer.php';

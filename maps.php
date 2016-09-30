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

include_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/adslight/include/gtickets.php';

$myts      = MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');

if (is_object($GLOBALS['xoopsUser'])) {
    $groups = $GLOBALS['xoopsUser']->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
$gpermHandler = xoops_getHandler('groupperm');
if (isset($_POST['item_id'])) {
    $perm_itemid = (int)$_POST['item_id'];
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gpermHandler->checkRight('adslight_view', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/index.php', 3, _NOPERM);
}
if (!$gpermHandler->checkRight('adslight_premium', $perm_itemid, $groups, $module_id)) {
    $prem_perm = '0';
} else {
    $prem_perm = '1';
}

#  function adslightMaps
#####################################################
function adslightMaps()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsTpl, $myts, $mytree, $meta, $mid, $moduleDirName, $main_lang, $prem_perm;

    $GLOBALS['xoopsOption']['template_main'] = 'adslight_maps.tpl';

    include XOOPS_ROOT_PATH . '/header.php';

    $xoopsTpl->assign('xmid', $xoopsModule->getVar('mid'));
    $xoopsTpl->assign('add_from', _ADSLIGHT_ADDFROM . ' ' . $xoopsConfig['sitename']);
    $xoopsTpl->assign('add_from_title', _ADSLIGHT_ADDFROM);
    $xoopsTpl->assign('add_from_sitename', $xoopsConfig['sitename']);
    $xoopsTpl->assign('search_listings', _ADSLIGHT_SEARCH_LISTINGS);
    $xoopsTpl->assign('all_words', _ADSLIGHT_ALL_WORDS);
    $xoopsTpl->assign('any_words', _ADSLIGHT_ANY_WORDS);
    $xoopsTpl->assign('exact_match', _ADSLIGHT_EXACT_MATCH);
    $xoopsTpl->assign('only_pix', _ADSLIGHT_ONLYPIX);
    $xoopsTpl->assign('search', _ADSLIGHT_SEARCH);
    $xoopsTpl->assign('permit', $prem_perm);
    $xoopsTpl->assign('imgscss', XOOPS_URL . '/modules/adslight/assets/css/adslight.css');
    $xoopsTpl->assign('adslight_logolink', _ADSLIGHT_LOGOLINK);

    $xoTheme->addMeta('meta', 'robots', 'noindex, nofollow');

    $header_cssadslight = '<link rel="stylesheet" href="' . XOOPS_URL . '/modules/adslight/assets/css/adslight.css" type="text/css" media="all" />';

    $xoopsTpl->assign('xoops_module_header', $header_cssadslight);

    $maps_name   = $GLOBALS['xoopsModuleConfig']['adslight_maps_set'];
    $maps_width  = $GLOBALS['xoopsModuleConfig']['adslight_maps_width'];
    $maps_height = $GLOBALS['xoopsModuleConfig']['adslight_maps_height'];

    $xoopsTpl->assign('maps_name', $maps_name);
    $xoopsTpl->assign('maps_width', $maps_width);
    $xoopsTpl->assign('maps_height', $maps_height);

    $xoopsTpl->assign('adlight_maps_title', _ADSLIGHT_MAPS_TITLE);
    $xoopsTpl->assign('bullinfotext', _ADSLIGHT_MAPS_TEXT);

    // adslight 2
    $xoopsTpl->assign('adslight_active_menu', $GLOBALS['xoopsModuleConfig']['adslight_active_menu']);
    $xoopsTpl->assign('adslight_active_rss', $GLOBALS['xoopsModuleConfig']['adslight_active_rss']);

    if ($GLOBALS['xoopsUser']) {
        $member_usid = $GLOBALS['xoopsUser']->getVar('uid');
        if ($usid = $member_usid) {
            $xoopsTpl->assign('istheirs', true);

            list($show_user) = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('adslight_listing') . " WHERE usid=$member_usid"));

            $xoopsTpl->assign('show_user', $show_user);
            $xoopsTpl->assign('show_user_link', "members.php?usid=$member_usid");
        }
    }
}

######################################################

$pa = !isset($_GET['pa']) ? null : $_GET['pa'];

switch ($pa) {
    default:
        $GLOBALS['xoopsOption']['template_main'] = 'adslight_maps.tpl';
        adslightMaps();
        break;
}
include XOOPS_ROOT_PATH . '/footer.php';
